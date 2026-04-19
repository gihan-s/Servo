<?php
// EARNINGS MODULE
// Calculates provider earnings from: payment → project → post (where post.Provider_ID = ?)
//
// Join chain:
//   payment pay
//   JOIN project proj ON pay.Project_ID = proj.Project_ID
//   JOIN post po      ON proj.Post_ID   = po.Post_ID
//   JOIN client c     ON po.Client_ID   = c.Client_ID
//   WHERE po.Provider_ID = ?

require_once __DIR__ . '/../core/Database.php';

class EarningsModel extends Database
{
    const COMMISSION_RATE = 0.10;

    public function getTotalEarnings($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT COALESCE(SUM(pay.Amount - pay.Commission), 0) AS total
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ? AND pay.Status = 'Paid'
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (float) ($row['total'] ?? 0);
    }

    public function getMonthlyEarningsChange($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT
                COALESCE(SUM(CASE
                    WHEN MONTH(pay.Paid_Time) = MONTH(CURDATE()) AND YEAR(pay.Paid_Time) = YEAR(CURDATE())
                    THEN pay.Amount - pay.Commission ELSE 0 END), 0) AS current_month,
                COALESCE(SUM(CASE
                    WHEN MONTH(pay.Paid_Time) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                     AND YEAR(pay.Paid_Time) = YEAR(CURDATE() - INTERVAL 1 MONTH)
                    THEN pay.Amount - pay.Commission ELSE 0 END), 0) AS last_month
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ? AND pay.Status = 'Paid'
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $current = (float) ($row['current_month'] ?? 0);
        $last    = (float) ($row['last_month'] ?? 0);
        if ($last == 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $last) / $last) * 100, 1);
    }

    public function getPendingPayout($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT COALESCE(SUM(pay.Amount - pay.Commission), 0) AS pending
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ? AND pay.Status IN ('Pending', 'Hold', 'Processing')
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (float) ($row['pending'] ?? 0);
    }

    public function getAvgProjectValue($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT COALESCE(AVG(pay.Amount - pay.Commission), 0) AS avg_value
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ? AND pay.Status = 'Paid'
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return round((float) ($row['avg_value'] ?? 0), 2);
    }

    public function getCompletedProjectsCount($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS cnt
            FROM project proj
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ? AND proj.Project_Status = 'Completed'
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['cnt'] ?? 0);
    }

    public function getCompletedThisMonth($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS cnt
            FROM project proj
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ?
              AND proj.Project_Status = 'Completed'
              AND MONTH(proj.Ended_At) = MONTH(CURDATE())
              AND YEAR(proj.Ended_At) = YEAR(CURDATE())
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['cnt'] ?? 0);
    }

    public function getRecentTransactions($providerId, $limit = 5)
    {
        return $this->fetchTransactions($providerId, $limit);
    }

    public function getAllTransactions($providerId)
    {
        return $this->fetchTransactions($providerId, null);
    }

    private function fetchTransactions($providerId, $limit)
    {
        $sql = "
            SELECT
                pay.Payment_ID,
                pay.Amount,
                pay.Commission,
                pay.Status,
                pay.Paid_Time,
                pay.Hold_Time,
                po.Title AS Project_Title,
                proj.Project_Status,
                TRIM(CONCAT(COALESCE(c.First_Name, ''), ' ', COALESCE(c.Last_Name, ''))) AS Client_Name
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            LEFT JOIN client c ON po.Client_ID = c.Client_ID
            WHERE po.Provider_ID = ?
            ORDER BY COALESCE(pay.Paid_Time, pay.Hold_Time, proj.Started_At) DESC
        ";
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $providerId, $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $providerId);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /**
     * Chart data for all periods (1M/3M/6M/1Y/All).
     * Returns an associative array keyed by period containing labels, earnings, and fees.
     */
    public function getMonthlyChartData($providerId)
    {
        return [
            '1M'  => $this->getWeeklyChartData($providerId, 4),
            '3M'  => $this->getPeriodMonthlyChart($providerId, 3),
            '6M'  => $this->getPeriodMonthlyChart($providerId, 6),
            '1Y'  => $this->getPeriodMonthlyChart($providerId, 12),
            'All' => $this->getAllQuarterlyChart($providerId),
        ];
    }

    private function getWeeklyChartData($providerId, $weeks)
    {
        $days = $weeks * 7;

        $stmt = $this->conn->prepare("
            SELECT
                FLOOR(DATEDIFF(CURDATE(), DATE(pay.Paid_Time)) / 7) AS weeks_ago,
                COALESCE(SUM(pay.Amount - pay.Commission), 0) AS earnings,
                COALESCE(SUM(pay.Commission), 0) AS fees
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ?
              AND pay.Status = 'Paid'
              AND pay.Paid_Time IS NOT NULL
              AND pay.Paid_Time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
              AND pay.Paid_Time <  DATE_ADD(CURDATE(), INTERVAL 1 DAY)
            GROUP BY weeks_ago
        ");
        $stmt->bind_param("ii", $providerId, $days);
        $stmt->execute();
        $result = $stmt->get_result();

        $bucket = [];
        while ($row = $result->fetch_assoc()) {
            $bucket[(int) $row['weeks_ago']] = [
                'earnings' => (float) $row['earnings'],
                'fees'     => (float) $row['fees'],
            ];
        }
        $stmt->close();

        $labels   = [];
        $earnings = [];
        $fees     = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $end   = date('M j', strtotime("-{$i} week -0 day"));
            $start = date('M j', strtotime("-" . ($i + 1) . " week +1 day"));
            $labels[]   = $start . '–' . $end;
            $earnings[] = $bucket[$i]['earnings'] ?? 0.0;
            $fees[]     = $bucket[$i]['fees']     ?? 0.0;
        }

        return ['labels' => $labels, 'earnings' => $earnings, 'fees' => $fees];
    }

    private function getPeriodMonthlyChart($providerId, $months)
    {
        $stmt = $this->conn->prepare("
            SELECT
                DATE_FORMAT(pay.Paid_Time, '%Y-%m') AS ym,
                COALESCE(SUM(pay.Amount - pay.Commission), 0) AS earnings,
                COALESCE(SUM(pay.Commission), 0) AS fees
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ?
              AND pay.Status = 'Paid'
              AND pay.Paid_Time IS NOT NULL
              AND pay.Paid_Time >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL ? MONTH)
            GROUP BY ym
        ");
        $monthsBack = $months - 1;
        $stmt->bind_param("ii", $providerId, $monthsBack);
        $stmt->execute();
        $result = $stmt->get_result();

        $bucket = [];
        while ($row = $result->fetch_assoc()) {
            $bucket[$row['ym']] = [
                'earnings' => (float) $row['earnings'],
                'fees'     => (float) $row['fees'],
            ];
        }
        $stmt->close();

        $labels   = [];
        $earnings = [];
        $fees     = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $ts    = strtotime("first day of -{$i} month");
            $key   = date('Y-m', $ts);
            $label = ($months >= 12) ? date("M 'y", $ts) : date('M', $ts);
            $labels[]   = $label;
            $earnings[] = $bucket[$key]['earnings'] ?? 0.0;
            $fees[]     = $bucket[$key]['fees']     ?? 0.0;
        }

        return ['labels' => $labels, 'earnings' => $earnings, 'fees' => $fees];
    }

    private function getAllQuarterlyChart($providerId)
    {
        $stmt = $this->conn->prepare("
            SELECT
                YEAR(pay.Paid_Time)    AS yr,
                QUARTER(pay.Paid_Time) AS qr,
                COALESCE(SUM(pay.Amount - pay.Commission), 0) AS earnings,
                COALESCE(SUM(pay.Commission), 0) AS fees
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ?
              AND pay.Status = 'Paid'
              AND pay.Paid_Time IS NOT NULL
            GROUP BY yr, qr
            ORDER BY yr ASC, qr ASC
        ");
        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $bucket = [];
        $minYr = null; $minQr = null; $maxYr = null; $maxQr = null;
        while ($row = $result->fetch_assoc()) {
            $yr = (int) $row['yr'];
            $qr = (int) $row['qr'];
            $bucket[$yr . '-' . $qr] = [
                'earnings' => (float) $row['earnings'],
                'fees'     => (float) $row['fees'],
            ];
            if ($minYr === null || $yr < $minYr || ($yr === $minYr && $qr < $minQr)) {
                $minYr = $yr; $minQr = $qr;
            }
            if ($maxYr === null || $yr > $maxYr || ($yr === $maxYr && $qr > $maxQr)) {
                $maxYr = $yr; $maxQr = $qr;
            }
        }
        $stmt->close();

        if ($minYr === null) {
            return ['labels' => ['—'], 'earnings' => [0], 'fees' => [0]];
        }

        $labels   = [];
        $earnings = [];
        $fees     = [];
        $yr = $minYr; $qr = $minQr;
        while ($yr < $maxYr || ($yr === $maxYr && $qr <= $maxQr)) {
            $labels[]   = 'Q' . $qr . " '" . substr((string) $yr, -2);
            $earnings[] = $bucket[$yr . '-' . $qr]['earnings'] ?? 0.0;
            $fees[]     = $bucket[$yr . '-' . $qr]['fees']     ?? 0.0;
            $qr++;
            if ($qr > 4) { $qr = 1; $yr++; }
        }

        return ['labels' => $labels, 'earnings' => $earnings, 'fees' => $fees];
    }

    /**
     * Report summary for a date range: total gross, commission, net, pending, count.
     */
    public function getReportSummary($providerId, $from, $to)
    {
        $stmt = $this->conn->prepare("
            SELECT
                COALESCE(SUM(CASE WHEN pay.Status = 'Paid' THEN pay.Amount END), 0) AS total_gross,
                COALESCE(SUM(CASE WHEN pay.Status = 'Paid' THEN pay.Commission END), 0) AS total_commission,
                COALESCE(SUM(CASE WHEN pay.Status = 'Paid' THEN pay.Amount - pay.Commission END), 0) AS total_net,
                COALESCE(SUM(CASE WHEN pay.Status IN ('Pending','Hold','Processing') THEN pay.Amount - pay.Commission END), 0) AS total_pending,
                COUNT(CASE WHEN pay.Status = 'Paid' THEN 1 END) AS txn_count
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE po.Provider_ID = ?
              AND DATE(COALESCE(pay.Paid_Time, pay.Hold_Time, proj.Started_At)) BETWEEN ? AND ?
        ");
        $stmt->bind_param("iss", $providerId, $from, $to);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return [
            'total_gross'      => (float) ($row['total_gross'] ?? 0),
            'total_commission' => (float) ($row['total_commission'] ?? 0),
            'total_net'        => (float) ($row['total_net'] ?? 0),
            'total_pending'    => (float) ($row['total_pending'] ?? 0),
            'txn_count'        => (int)   ($row['txn_count'] ?? 0),
        ];
    }

    /**
     * Full receipt details for a single payment (provider perspective).
     * Returns null if not found.
     */
    public function getReceiptById($paymentId)
    {
        $stmt = $this->conn->prepare("
            SELECT
                pay.Payment_ID, pay.Amount, pay.Status, pay.Hold_Time, pay.Paid_Time,
                pay.Commission, pay.Project_ID,
                po.Title AS Project_Title, po.Description,
                po.End_At AS Est_Delivery, po.Requesting_Price,
                po.Price_Type, po.Post_Type, po.Client_ID,
                proj.Started_At, proj.Project_Status,
                prov.Provider_ID,
                TRIM(CONCAT(COALESCE(prov.First_Name, ''), ' ', COALESCE(prov.Last_Name, ''))) AS Provider_Name,
                prov.Email AS Provider_Email,
                TRIM(CONCAT(COALESCE(c.First_Name, ''), ' ', COALESCE(c.Last_Name, ''))) AS Client_Name,
                c.Email AS Client_Email
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            LEFT JOIN provider prov ON po.Provider_ID = prov.Provider_ID
            LEFT JOIN client c ON po.Client_ID = c.Client_ID
            WHERE pay.Payment_ID = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $paymentId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Returns the Provider_ID who owns a given payment, or null if not found.
     */
    public function getReceiptOwnerProviderId($paymentId)
    {
        $stmt = $this->conn->prepare("
            SELECT po.Provider_ID
            FROM payment pay
            JOIN project proj ON pay.Project_ID = proj.Project_ID
            JOIN post po ON proj.Post_ID = po.Post_ID
            WHERE pay.Payment_ID = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $paymentId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row || $row['Provider_ID'] === null) return null;
        return (int) $row['Provider_ID'];
    }
}
