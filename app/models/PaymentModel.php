<?php

require_once __DIR__ . '/../core/Database.php';

class PaymentModel extends Database
{
    private const COMMISSION_RATE = 0.10;
    public const PAGE_SIZE = 5;

    public function ensureAwaitingRowsForClient(int $clientId): void
    {
        $sql = "SELECT pr.Project_ID, po.Requesting_Price
                FROM project pr
                JOIN post po ON pr.Post_ID = po.Post_ID
                LEFT JOIN payment pay ON pay.Project_ID = pr.Project_ID
                WHERE po.Client_ID = ?
                  AND pay.Payment_ID IS NULL
                  AND (pr.Project_Status IS NULL OR pr.Project_Status <> 'Cancelled')";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::ensureAwaitingRowsForClient prepare: ' . $this->conn->error);
            return;
        }
        $stmt->bind_param('i', $clientId);
        if (!$stmt->execute()) {
            error_log('PaymentModel::ensureAwaitingRowsForClient exec: ' . $stmt->error);
            $stmt->close();
            return;
        }
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($rows)) return;

        $nextId = $this->nextPaymentId();

        $ins = $this->conn->prepare(
            "INSERT INTO payment (Payment_ID, Amount, Status, Hold_Time, Paid_Time, Commission, Project_ID)
             VALUES (?, ?, 'Awaiting', NULL, NULL, ?, ?)"
        );
        if (!$ins) {
            error_log('PaymentModel::ensureAwaitingRowsForClient insert prepare: ' . $this->conn->error);
            return;
        }

        foreach ($rows as $row) {
            $projectValue = (float) ($row['Requesting_Price'] ?? 0);
            $commission = round($projectValue * self::COMMISSION_RATE, 2);
            $amount = max(0.0, round($projectValue - $commission, 2));
            $projectId  = (int) $row['Project_ID'];
            $paymentId  = $nextId++;
            $ins->bind_param('iddi', $paymentId, $amount, $commission, $projectId);
            if (!$ins->execute()) {
                error_log('PaymentModel::ensureAwaitingRowsForClient insert exec: ' . $ins->error);
            }
        }
        $ins->close();
    }

    // Payment_ID has no AUTO_INCREMENT in the current schema.
    private function nextPaymentId(): int
    {
        $res = $this->conn->query("SELECT COALESCE(MAX(Payment_ID), 10500) + 1 AS next_id FROM payment");
        if (!$res) {
            error_log('PaymentModel::nextPaymentId: ' . $this->conn->error);
            return 10501;
        }
        $row = $res->fetch_assoc();
        return (int) ($row['next_id'] ?? 10501);
    }

    public function getAwaitingPaymentsByClientId(int $clientId, string $search = '', string $sort = 'recent', int $page = 1): array
    {
        return $this->fetchPaymentsByStatus($clientId, ['Awaiting'], $search, $sort, $page);
    }

    public function getPendingPaymentsByClientId(int $clientId, string $search = '', string $sort = 'recent', int $page = 1): array
    {
        return $this->fetchPaymentsByStatus($clientId, ['Pending', 'Hold'], $search, $sort, $page);
    }

    public function getCompletedPaymentsByClientId(int $clientId, string $search = '', string $sort = 'recent', int $page = 1): array
    {
        return $this->fetchPaymentsByStatus($clientId, ['Paid'], $search, $sort, $page);
    }

    public function getRefundedPaymentsByClientId(int $clientId, string $search = '', string $sort = 'recent', int $page = 1): array
    {
        return $this->fetchPaymentsByStatus($clientId, ['Refunded', 'Refund Requested'], $search, $sort, $page);
    }

    public function countAwaitingByClientId(int $clientId, string $search = ''): int
    {
        return $this->countPaymentsByStatus($clientId, ['Awaiting'], $search);
    }

    public function countPendingByClientId(int $clientId, string $search = ''): int
    {
        return $this->countPaymentsByStatus($clientId, ['Pending', 'Hold'], $search);
    }

    public function countCompletedByClientId(int $clientId, string $search = ''): int
    {
        return $this->countPaymentsByStatus($clientId, ['Paid'], $search);
    }

    public function countRefundedByClientId(int $clientId, string $search = ''): int
    {
        return $this->countPaymentsByStatus($clientId, ['Refunded', 'Refund Requested'], $search);
    }

    private function orderByClause(string $sort): string
    {
        return match ($sort) {
            'oldest'      => 'COALESCE(pay.Paid_Time, pay.Hold_Time, pr.Started_At) ASC',
            'amount-desc' => 'pay.Amount DESC',
            'amount-asc'  => 'pay.Amount ASC',
            default       => 'COALESCE(pay.Paid_Time, pay.Hold_Time, pr.Started_At) DESC',
        };
    }

    private function fetchPaymentsByStatus(int $clientId, array $statuses, string $search, string $sort, int $page): array
    {
        if (empty($statuses)) return [];

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $orderBy = $this->orderByClause($sort);
        $page = max(1, $page);
        $offset = ($page - 1) * self::PAGE_SIZE;
        $limit = self::PAGE_SIZE;

        $searchClause = '';
        $types  = 'i' . str_repeat('s', count($statuses));
        $params = array_merge([$clientId], $statuses);

        if ($search !== '') {
            $searchClause = ' AND (po.Title LIKE ? OR po.Description LIKE ? OR CAST(pay.Payment_ID AS CHAR) LIKE ?) ';
            $like = '%' . $search . '%';
            $types .= 'sss';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $types .= 'ii';
        $params[] = $offset;
        $params[] = $limit;

        $sql = "SELECT
                    pay.Payment_ID, pay.Amount, pay.Status, pay.Hold_Time, pay.Paid_Time,
                    pay.Commission, pay.Project_ID,
                    po.Title        AS Project_Title,
                    po.Title        AS Project_Name,
                    po.Description,
                    po.End_At       AS Est_Delivery,
                    po.End_At       AS Due_Date,
                    po.Requesting_Price,
                    po.Price_Type,
                    po.Post_Type,
                    pr.Started_At,
                    pr.Project_Status,
                    prov.Provider_ID,
                    TRIM(CONCAT(COALESCE(prov.First_Name, ''), ' ', COALESCE(prov.Last_Name, ''))) AS Provider_Name
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                LEFT JOIN provider prov ON po.Provider_ID = prov.Provider_ID
                WHERE po.Client_ID = ?
                  AND pay.Status IN ($placeholders)
                  $searchClause
                ORDER BY $orderBy
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::fetchPaymentsByStatus prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            error_log('PaymentModel::fetchPaymentsByStatus exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getReportSummary(int $clientId, string $from, string $to): array
    {
        $sql = "SELECT
                    COALESCE(SUM(CASE WHEN pay.Status = 'Paid'                                 THEN pay.Amount END), 0) AS total_paid,
                    COALESCE(SUM(CASE WHEN pay.Status IN ('Pending','Hold','Awaiting')         THEN pay.Amount END), 0) AS total_pending,
                    COALESCE(SUM(CASE WHEN pay.Status IN ('Refunded','Refund Requested')       THEN pay.Amount END), 0) AS total_refunded,
                    COUNT(*) AS txn_count
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                WHERE po.Client_ID = ?
                  AND DATE(COALESCE(pay.Paid_Time, pay.Hold_Time, pr.Started_At)) BETWEEN ? AND ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getReportSummary prepare: ' . $this->conn->error);
            return ['total_paid' => 0, 'total_pending' => 0, 'total_refunded' => 0, 'txn_count' => 0];
        }
        $stmt->bind_param('iss', $clientId, $from, $to);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getReportSummary exec: ' . $stmt->error);
            $stmt->close();
            return ['total_paid' => 0, 'total_pending' => 0, 'total_refunded' => 0, 'txn_count' => 0];
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return [
            'total_paid'     => (float) ($row['total_paid']     ?? 0),
            'total_pending'  => (float) ($row['total_pending']  ?? 0),
            'total_refunded' => (float) ($row['total_refunded'] ?? 0),
            'txn_count'      => (int)   ($row['txn_count']      ?? 0),
        ];
    }

    /**
     * Fetches post + client + provider details needed for PayHere checkout.
     * Queries directly from the post table — no project/payment needed yet.
     */
    public function getPaymentDetailsForPayHere(int $postId, int $clientId): ?array
    {
        $sql = "SELECT
                    po.Post_ID,
                    po.Title          AS Project_Title,
                    po.Description,
                    po.Price_Type,
                    po.Requesting_Price,
                    po.Post_Type,
                    po.Provider_ID,
                    cl.First_Name,
                    cl.Last_Name,
                    cl.Email,
                    cl.Contact_No,
                    pr.First_Name  AS Provider_First,
                    pr.Last_Name   AS Provider_Last
                FROM post po
                JOIN client cl  ON po.Client_ID = cl.Client_ID
                LEFT JOIN provider pr ON po.Provider_ID = pr.Provider_ID
                WHERE po.Post_ID   = ?
                  AND po.Client_ID = ?
                  AND po.Request_Status IN ('Accepted', 'ongoing')
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getPaymentDetailsForPayHere prepare: ' . $this->conn->error);
            return null;
        }
        $stmt->bind_param('ii', $postId, $clientId);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getPaymentDetailsForPayHere exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Creates project + payment records after a successful PayHere payment.
     * Called from the IPN notify handler.
     */
    public function createProjectAndPayment(int $postId, float $amount): bool
    {
        // Guard: skip if a project already exists for this post
        $check = $this->conn->prepare("SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1");
        $check->bind_param('i', $postId);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        $check->close();
        if ($existing) return true; // already created

        // $this->conn->begin_transaction();
        // try {
            // Create project
            $sql1 = "INSERT INTO project (Post_ID, Project_Status, Started_At)
                     VALUES (?, 'Ongoing', NOW())";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->bind_param('i', $postId);
            $stmt1->execute();
            $projectId = $this->conn->insert_id;
            $stmt1->close();

            
            $sql2 = "INSERT INTO payment (Amount, Status, Paid_Time, Commission, Project_ID)
                     VALUES (?, 'Paid', NOW(), 0, ?)";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bind_param('di', $amount, $projectId);
            $stmt2->execute();
            $stmt2->close();

            $sql3 = "UPDATE post SET Request_Status = 'Ongoing' WHERE Post_ID = ?";
            $stmt3 = $this->conn->prepare($sql3);
            $stmt3->bind_param('i', $postId);
            $stmt3->execute();
            $stmt3->close();

            // $this->conn->commit();
            return true;
        // } catch (\Exception $e) {
        //     $this->conn->rollback();
        //     error_log('PaymentModel::createProjectAndPayment failed: ' . $e->getMessage());
        //     return false;
        // }
    }

    /**
     * Marks a payment as Paid after successful PayHere IPN verification.
     * Uses the order_id format SERVO-{payment_id} to identify the record.
     */
    public function confirmPayHerePayment(int $paymentId): bool
    {
        $sql = "UPDATE payment
                SET Status = 'Paid', Paid_Time = NOW(),
                    Hold_Time = COALESCE(Hold_Time, NOW())
                WHERE Payment_ID = ? AND Status IN ('Awaiting', 'Pending', 'Hold')";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::confirmPayHerePayment prepare: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('i', $paymentId);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        if (!$ok) error_log('PaymentModel::confirmPayHerePayment: ' . $stmt->error);
        $stmt->close();
        return $ok;
    }

    public function getPaymentOwnerClientId(int $paymentId): ?int
    {
        $sql = "SELECT po.Client_ID
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                WHERE pay.Payment_ID = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getPaymentOwnerClientId prepare: ' . $this->conn->error);
            return null;
        }
        $stmt->bind_param('i', $paymentId);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getPaymentOwnerClientId exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['Client_ID'] : null;
    }

    public function capturePayment(int $paymentId): bool
    {
        // Capture at client payment time: place funds on hold and store net provider amount.
        // Paid_Time is intentionally not set here; it will be set on final release.
        $sql = "UPDATE payment pay
                JOIN project pr ON pr.Project_ID = pay.Project_ID
                JOIN post po ON po.Post_ID = pr.Post_ID
                SET pay.Commission = ROUND(COALESCE(po.Requesting_Price, 0) * ?, 2),
                    pay.Amount = GREATEST(0, ROUND(COALESCE(po.Requesting_Price, 0)
                        - ROUND(COALESCE(po.Requesting_Price, 0) * ?, 2), 2)),
                    pay.Status = 'Hold',
                    pay.Hold_Time = COALESCE(pay.Hold_Time, NOW()),
                    pay.Paid_Time = NULL
                WHERE pay.Payment_ID = ? AND pay.Status IN ('Awaiting', 'Pending', 'Hold')";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::capturePayment prepare: ' . $this->conn->error);
            return false;
        }
        $commissionRate = self::COMMISSION_RATE;
        $stmt->bind_param('ddi', $commissionRate, $commissionRate, $paymentId);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        if (!$ok) error_log('PaymentModel::capturePayment exec: ' . $stmt->error);
        $stmt->close();
        return $ok;
    }

    public function cancelPaymentAndProject(int $paymentId): bool
    {
        $this->conn->begin_transaction();
        try {
            $projectIdStmt = $this->conn->prepare("SELECT Project_ID FROM payment WHERE Payment_ID = ? LIMIT 1");
            $projectIdStmt->bind_param('i', $paymentId);
            $projectIdStmt->execute();
            $res = $projectIdStmt->get_result()->fetch_assoc();
            $projectIdStmt->close();
            if (!$res) throw new RuntimeException('Payment not found');
            $projectId = (int) $res['Project_ID'];

            $payStmt = $this->conn->prepare(
                "UPDATE payment SET Status = 'Cancelled' WHERE Payment_ID = ? AND Status NOT IN ('Paid', 'Refunded')"
            );
            $payStmt->bind_param('i', $paymentId);
            $payStmt->execute();
            $payStmt->close();

            $prjStmt = $this->conn->prepare(
                "UPDATE project SET Project_Status = 'Cancelled' WHERE Project_ID = ?"
            );
            $prjStmt->bind_param('i', $projectId);
            $prjStmt->execute();
            $prjStmt->close();

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('PaymentModel::cancelPaymentAndProject: ' . $e->getMessage());
            return false;
        }
    }

    public function requestRefund(int $paymentId): bool
    {
        // TODO: AdminPaymentController::approveRefund will flip this to 'Refunded'
        //       once admin-side approval is implemented.
        $sql = "UPDATE payment SET Status = 'Refund Requested' WHERE Payment_ID = ? AND Status = 'Paid'";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::requestRefund prepare: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('i', $paymentId);
        $ok = $stmt->execute() && $stmt->affected_rows > 0;
        if (!$ok) error_log('PaymentModel::requestRefund exec: ' . $stmt->error);
        $stmt->close();
        return $ok;
    }

    public function getInvoiceById(int $paymentId): ?array
    {
        $sql = "SELECT
                    pay.Payment_ID, pay.Amount, pay.Status, pay.Hold_Time, pay.Paid_Time,
                    pay.Commission, pay.Project_ID,
                    po.Title        AS Project_Title,
                    po.Description,
                    po.End_At       AS Est_Delivery,
                    po.Requesting_Price,
                    po.Price_Type,
                    po.Post_Type,
                    po.Client_ID,
                    pr.Started_At,
                    pr.Project_Status,
                    prov.Provider_ID,
                    TRIM(CONCAT(COALESCE(prov.First_Name, ''), ' ', COALESCE(prov.Last_Name, ''))) AS Provider_Name,
                    prov.Email       AS Provider_Email,
                    TRIM(CONCAT(COALESCE(c.First_Name, ''), ' ', COALESCE(c.Last_Name, ''))) AS Client_Name,
                    c.Email          AS Client_Email
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                LEFT JOIN provider prov ON po.Provider_ID = prov.Provider_ID
                LEFT JOIN client   c    ON po.Client_ID   = c.Client_ID
                WHERE pay.Payment_ID = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getInvoiceById prepare: ' . $this->conn->error);
            return null;
        }
        $stmt->bind_param('i', $paymentId);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getInvoiceById exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    private function countPaymentsByStatus(int $clientId, array $statuses, string $search): int
    {
        if (empty($statuses)) return 0;

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $types  = 'i' . str_repeat('s', count($statuses));
        $params = array_merge([$clientId], $statuses);
        $searchClause = '';

        if ($search !== '') {
            $searchClause = ' AND (po.Title LIKE ? OR po.Description LIKE ? OR CAST(pay.Payment_ID AS CHAR) LIKE ?) ';
            $like = '%' . $search . '%';
            $types .= 'sss';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql = "SELECT COUNT(*) AS total
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                WHERE po.Client_ID = ?
                  AND pay.Status IN ($placeholders)
                  $searchClause";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::countPaymentsByStatus prepare: ' . $this->conn->error);
            return 0;
        }
        $stmt->bind_param($types, ...$params);
        if (!$stmt->execute()) {
            error_log('PaymentModel::countPaymentsByStatus exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['total'] ?? 0);
    }

    public function getPaymentsCountByClientId(int $clientId, string $status): int
    {
        $statuses = match ($status) {
            'Pending'   => ['Pending', 'Hold'],
            'Completed' => ['Paid'],
            'Refunded'  => ['Refunded', 'Refund Requested'],
            'Awaiting'  => ['Awaiting'],
            default     => [$status],
        };
        return $this->countPaymentsByStatus($clientId, $statuses, '');
    }

    public function getTotalSpentByClientId(int $clientId): float
    {
        $sql = "SELECT COALESCE(SUM(pay.Amount), 0) AS total
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                WHERE po.Client_ID = ?
                  AND pay.Status = 'Paid'";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getTotalSpentByClientId prepare: ' . $this->conn->error);
            return 0.0;
        }
        $stmt->bind_param('i', $clientId);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getTotalSpentByClientId exec: ' . $stmt->error);
            $stmt->close();
            return 0.0;
        }
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (float) ($row['total'] ?? 0);
    }

    public function getRecentPaymentsByClientId(int $clientId, int $limit = 3): array
    {
        $limit = max(1, $limit);
        $sql = "SELECT
                    pay.Payment_ID,
                    pay.Amount,
                    pay.Status,
                    pay.Hold_Time,
                    pay.Paid_Time,
                    po.Title        AS Project_Title,
                    po.Description,
                    po.End_At       AS Due_Date,
                    'Card'          AS Method
                FROM payment pay
                JOIN project pr ON pay.Project_ID = pr.Project_ID
                JOIN post po    ON pr.Post_ID     = po.Post_ID
                WHERE po.Client_ID = ?
                ORDER BY COALESCE(pay.Paid_Time, pay.Hold_Time, pr.Started_At) DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('PaymentModel::getRecentPaymentsByClientId prepare: ' . $this->conn->error);
            return [];
        }
        $stmt->bind_param('ii', $clientId, $limit);
        if (!$stmt->execute()) {
            error_log('PaymentModel::getRecentPaymentsByClientId exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
