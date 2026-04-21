<?php
// PROJECT Table Format:
// +----------------+-------------+------+-----+---------+-------+
// | Field          | Type        | Null | Key | Default | Extra |
// +----------------+-------------+------+-----+---------+-------+
// | Project_ID     | int         | NO   | PRI | NULL    |       |
// | Post_ID        | int         | NO   | MUL | NULL    |       |
// | Project_Status | varchar(45) | YES  |     | NULL    |       |
// | Started_At     | datetime    | YES  |     | NULL    |       |
// | Ended_At       | datetime    | YES  |     | NULL    |       |
// +----------------+-------------+------+-----+---------+-------+
// TODO: Missing fields: Stage (project phase/sprint), Budget, Progress (percentage)
// Budget can be derived from post.Requesting_Price
// Stage and Progress need to be added to schema or calculated differently

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/helpers.php';

class ProjectModel extends Database {
    private const PAYMENT_COMMISSION_RATE = 0.10;

    private function getRequirementFilesTableName(): ?string {
        static $resolvedTable = null;
        static $resolved = false;

        if ($resolved) {
            return $resolvedTable;
        }

        $resolved = true;
        $candidates = ['project_requirements_files', 'project_requirement_files'];

        foreach ($candidates as $tableName) {
            $escaped = $this->conn->real_escape_string($tableName);
            $check = $this->conn->query("SHOW TABLES LIKE '{$escaped}'");
            if ($check && $check->num_rows > 0) {
                $resolvedTable = $tableName;
                return $resolvedTable;
            }
        }

        return null;
    }

    private function hasColumn(string $tableName, string $columnName): bool
    {
        $tableName = $this->conn->real_escape_string($tableName);
        $columnName = $this->conn->real_escape_string($columnName);
        $result = $this->conn->query("SHOW COLUMNS FROM `{$tableName}` LIKE '{$columnName}'");
        return $result && $result->num_rows > 0;
    }

    private function hasTable(string $tableName): bool
    {
        $tableName = $this->conn->real_escape_string($tableName);
        $result = $this->conn->query("SHOW TABLES LIKE '{$tableName}'");
        return $result && $result->num_rows > 0;
    }

    private function getNextPaymentId(): int
    {
        $res = $this->conn->query('SELECT COALESCE(MAX(Payment_ID), 10500) + 1 AS next_id FROM payment');
        if (!$res) {
            error_log('ProjectModel::getNextPaymentId: ' . $this->conn->error);
            return 10501;
        }

        $row = $res->fetch_assoc();
        return (int) ($row['next_id'] ?? 10501);
    }

    private function splitProjectValue(float $projectValue): array
    {
        $projectValue = max(0.0, $projectValue);
        $commission = round($projectValue * self::PAYMENT_COMMISSION_RATE, 2);
        $netAmount = max(0.0, round($projectValue - $commission, 2));

        return [$commission, $netAmount];
    }

    private function getAcceptedBidTermsForPost(int $postId, int $providerId): ?array
    {
        if ($postId <= 0 || $providerId <= 0) {
            return null;
        }

        $stmt = $this->conn->prepare(
            "SELECT Amount, Duration
             FROM bids
             WHERE Post_ID = ? AND Provider_ID = ?
               AND LOWER(COALESCE(Status, '')) NOT IN ('cancelled', 'deleted')
             ORDER BY
               CASE
                 WHEN LOWER(COALESCE(Status, '')) IN ('active', 'accepted', 'pending', 'open') THEN 0
                 ELSE 1
               END,
               COALESCE(Created_At, NOW()) DESC,
               Bid_ID DESC
             LIMIT 1"
        );
        if (!$stmt) {
            error_log('ProjectModel::getAcceptedBidTermsForPost prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('ii', $postId, $providerId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::getAcceptedBidTermsForPost exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }

        return [
            'amount' => max(0.0, (float) ($row['Amount'] ?? 0)),
            'duration' => max(0, (int) ($row['Duration'] ?? 0)),
        ];
    }

    private function resolveProjectValueByPost(int $postId, int $providerId, string $postType, float $defaultPrice): float
    {
        if (strtolower(trim($postType)) !== 'post') {
            return max(0.0, $defaultPrice);
        }

        $bidTerms = $this->getAcceptedBidTermsForPost($postId, $providerId);
        if (!$bidTerms) {
            return max(0.0, $defaultPrice);
        }

        return max(0.0, (float) $bidTerms['amount']);
    }

    private function applyBidTermsToProjectRows(array $rows): array
    {
        foreach ($rows as &$row) {
            $postType = strtolower(trim((string) ($row['Post_Type'] ?? '')));
            if ($postType !== 'post') {
                continue;
            }

            $postId = (int) ($row['Post_ID'] ?? 0);
            $providerId = (int) ($row['Provider_ID'] ?? 0);
            if ($postId <= 0 || $providerId <= 0) {
                continue;
            }

            $bidTerms = $this->getAcceptedBidTermsForPost($postId, $providerId);
            if (!$bidTerms) {
                continue;
            }

            $row['Requesting_Price'] = $bidTerms['amount'];

            if ($bidTerms['duration'] > 0) {
                $baseDateRaw = $row['Started_At'] ?? $row['Published_At'] ?? $row['Created_At'] ?? null;
                $baseTimestamp = $baseDateRaw ? strtotime((string) $baseDateRaw) : false;
                if ($baseTimestamp === false) {
                    $baseTimestamp = time();
                }

                $etaTimestamp = strtotime('+' . $bidTerms['duration'] . ' days', $baseTimestamp);
                if ($etaTimestamp !== false) {
                    $row['Est_Date'] = date('Y-m-d', $etaTimestamp);
                }
            }
        }
        unset($row);

        return $rows;
    }

    private function resolveProviderCategoryId(int $providerCategoryId, int $providerId, int $categoryId): int
    {
        if ($providerCategoryId > 0) {
            return $providerCategoryId;
        }

        if ($providerId <= 0 || $categoryId <= 0) {
            return 0;
        }

        $stmt = $this->conn->prepare(
            'SELECT ID FROM provider_categories WHERE Provider_ID = ? AND Category_ID = ? AND Status = "Active" ORDER BY ID ASC LIMIT 1'
        );
        if (!$stmt) {
            error_log('ProjectModel::resolveProviderCategoryId prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param('ii', $providerId, $categoryId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::resolveProviderCategoryId exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['ID'] ?? 0);
    }

    private function refreshProviderCategoryRating(int $providerCategoryId): float
    {
        $stmt = $this->conn->prepare(
                        'SELECT COALESCE(AVG(r.Rating), 0) AS avg_rating
                         FROM reviews r
                         JOIN project proj ON proj.Project_ID = r.Project_ID
                                                 JOIN post p ON p.Post_ID = proj.Post_ID
                                                 JOIN provider_categories pc ON pc.Provider_ID = p.Provider_ID AND pc.Category_ID = p.Category_ID
                         WHERE pc.ID = ?
               AND proj.Project_Status = "completed"
                             AND r.Rating IS NOT NULL'
        );
        if (!$stmt) {
            error_log('ProjectModel::refreshProviderCategoryRating prepare: ' . $this->conn->error);
            return 0.0;
        }

        $stmt->bind_param('i', $providerCategoryId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::refreshProviderCategoryRating exec: ' . $stmt->error);
            $stmt->close();
            return 0.0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $percentage = round(max(0.0, min(100.0, (float) ($row['avg_rating'] ?? 0))), 1);

        $update = $this->conn->prepare('UPDATE provider_categories SET Rating = ? WHERE ID = ?');
        if (!$update) {
            error_log('ProjectModel::refreshProviderCategoryRating update prepare: ' . $this->conn->error);
            return $percentage;
        }

        $update->bind_param('di', $percentage, $providerCategoryId);
        if (!$update->execute()) {
            error_log('ProjectModel::refreshProviderCategoryRating update exec: ' . $update->error);
        }
        $update->close();

        return $percentage;
    }

    private function refreshProviderRatingFromCategories(int $providerId): float
    {
        $stmt = $this->conn->prepare(
                        'SELECT COALESCE(AVG(pc.Rating), 0) AS avg_rating
                         FROM provider_categories pc
                         WHERE pc.Provider_ID = ?
                             AND pc.Rating IS NOT NULL
                             AND EXISTS (
                                     SELECT 1
                                     FROM project proj
                                     INNER JOIN post p ON p.Post_ID = proj.Post_ID
                                     INNER JOIN reviews r ON r.Project_ID = proj.Project_ID
                                     WHERE p.Provider_ID = pc.Provider_ID
                                         AND p.Category_ID = pc.Category_ID
                                         AND proj.Project_Status = "completed"
                                         AND r.Rating IS NOT NULL
                                     LIMIT 1
                             )'
        );
        if (!$stmt) {
            error_log('ProjectModel::refreshProviderRatingFromCategories prepare: ' . $this->conn->error);
            return 0.0;
        }

        $stmt->bind_param('i', $providerId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::refreshProviderRatingFromCategories exec: ' . $stmt->error);
            $stmt->close();
            return 0.0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $rating = round(max(0.0, min(100.0, (float) ($row['avg_rating'] ?? 0))), 1);

        $update = $this->conn->prepare('UPDATE provider SET Rating = ? WHERE Provider_ID = ?');
        if (!$update) {
            error_log('ProjectModel::refreshProviderRatingFromCategories update prepare: ' . $this->conn->error);
            return $rating;
        }

        $update->bind_param('di', $rating, $providerId);
        if (!$update->execute()) {
            error_log('ProjectModel::refreshProviderRatingFromCategories update exec: ' . $update->error);
        }
        $update->close();

        return $rating;
    }

    private function releasePaymentForProject(int $projectId): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT pay.Payment_ID, pay.Status, po.Requesting_Price, po.Post_Type, po.Provider_ID, po.Post_ID
             FROM project proj
             JOIN post po ON po.Post_ID = proj.Post_ID
             LEFT JOIN payment pay ON pay.Project_ID = proj.Project_ID
             WHERE proj.Project_ID = ?
             LIMIT 1'
        );
        if (!$stmt) {
            error_log('ProjectModel::releasePaymentForProject prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $projectId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::releasePaymentForProject exec: ' . $stmt->error);
            $stmt->close();
            return false;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            error_log('ProjectModel::releasePaymentForProject: project not found for payment release');
            return false;
        }

        $paymentId = (int) ($row['Payment_ID'] ?? 0);
        $projectValue = $this->resolveProjectValueByPost(
            (int) ($row['Post_ID'] ?? 0),
            (int) ($row['Provider_ID'] ?? 0),
            (string) ($row['Post_Type'] ?? ''),
            (float) ($row['Requesting_Price'] ?? 0)
        );
        $status = strtolower(trim((string) ($row['Status'] ?? '')));
        [$commission] = $this->splitProjectValue($projectValue);
        $grossAmount = max(0.0, round($projectValue, 2));

        if ($paymentId > 0) {
            if ($status === 'paid') {
                return true;
            }

            if (!in_array($status, ['hold', 'pending'], true)) {
                error_log('ProjectModel::releasePaymentForProject: payment is not on hold/pending before release');
                return false;
            }

            $update = $this->conn->prepare(
                "UPDATE payment
                 SET Status = 'Paid',
                     Amount = ?,
                     Commission = ?,
                     Paid_Time = NOW(),
                     Hold_Time = COALESCE(Hold_Time, NOW())
                 WHERE Payment_ID = ?"
            );
            if (!$update) {
                error_log('ProjectModel::releasePaymentForProject update prepare: ' . $this->conn->error);
                return false;
            }

            $update->bind_param('ddi', $grossAmount, $commission, $paymentId);
            $ok = $update->execute();
            if (!$ok) {
                error_log('ProjectModel::releasePaymentForProject update exec: ' . $update->error);
            }
            $update->close();
            return (bool) $ok;
        }

        error_log('ProjectModel::releasePaymentForProject: missing payment row for project release');
        return false;
    }

    private function getPaidPaymentFinancialsForProject(int $projectId): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT Amount, Commission
             FROM payment
             WHERE Project_ID = ? AND Status = 'Paid'
             ORDER BY COALESCE(Paid_Time, Hold_Time) DESC, Payment_ID DESC
             LIMIT 1"
        );
        if (!$stmt) {
            error_log('ProjectModel::getPaidPaymentFinancialsForProject prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('i', $projectId);
        if (!$stmt->execute()) {
            error_log('ProjectModel::getPaidPaymentFinancialsForProject exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }

        $amount = max(0.0, (float) ($row['Amount'] ?? 0));
        $commission = max(0.0, (float) ($row['Commission'] ?? 0));

        return [
            'amount' => $amount,
            'commission' => $commission,
        ];
    }

    private function applyEarningsFromProjectPayment(int $projectId, int $providerCategoryId, int $providerId): bool
    {
        if ($providerCategoryId <= 0 || $providerId <= 0) {
            error_log('ProjectModel::applyEarningsFromProjectPayment invalid provider/category reference');
            return false;
        }

        if (!$this->hasColumn('provider_categories', 'Total_Earning') || !$this->hasColumn('provider', 'Total_Earning')) {
            error_log('ProjectModel::applyEarningsFromProjectPayment missing Total_Earning column');
            return false;
        }

        $payment = $this->getPaidPaymentFinancialsForProject($projectId);
        if ($payment === null) {
            error_log('ProjectModel::applyEarningsFromProjectPayment no paid payment financials found');
            return false;
        }

        $netEarning = max(0.0, round(((float) $payment['amount']) - ((float) $payment['commission']), 2));

        $updateCategory = $this->conn->prepare(
            'UPDATE provider_categories
             SET Total_Earning = COALESCE(Total_Earning, 0) + ?
             WHERE ID = ? AND Provider_ID = ?'
        );
        if (!$updateCategory) {
            error_log('ProjectModel::applyEarningsFromProjectPayment category prepare: ' . $this->conn->error);
            return false;
        }

        $updateCategory->bind_param('dii', $netEarning, $providerCategoryId, $providerId);
        if (!$updateCategory->execute()) {
            error_log('ProjectModel::applyEarningsFromProjectPayment category exec: ' . $updateCategory->error);
            $updateCategory->close();
            return false;
        }
        if ($updateCategory->affected_rows <= 0) {
            $updateCategory->close();
            error_log('ProjectModel::applyEarningsFromProjectPayment category row not updated');
            return false;
        }
        $updateCategory->close();

        $updateProvider = $this->conn->prepare(
            'UPDATE provider
             SET Total_Earning = COALESCE(Total_Earning, 0) + ?
             WHERE Provider_ID = ?'
        );
        if (!$updateProvider) {
            error_log('ProjectModel::applyEarningsFromProjectPayment provider prepare: ' . $this->conn->error);
            return false;
        }

        $updateProvider->bind_param('di', $netEarning, $providerId);
        if (!$updateProvider->execute()) {
            error_log('ProjectModel::applyEarningsFromProjectPayment provider exec: ' . $updateProvider->error);
            $updateProvider->close();
            return false;
        }
        if ($updateProvider->affected_rows <= 0) {
            $updateProvider->close();
            error_log('ProjectModel::applyEarningsFromProjectPayment provider row not updated');
            return false;
        }
        $updateProvider->close();

        return true;
    }

    public function createProjectAndHoldPayment(int $postId): array
    {
        $this->conn->begin_transaction();

        try {
            $priceStmt = $this->conn->prepare('SELECT Requesting_Price, Post_Type, Provider_ID FROM post WHERE Post_ID = ? LIMIT 1');
            if (!$priceStmt) {
                throw new Exception('createProjectAndHoldPayment prepare price: ' . $this->conn->error);
            }
            $priceStmt->bind_param('i', $postId);
            if (!$priceStmt->execute()) {
                throw new Exception('createProjectAndHoldPayment exec price: ' . $priceStmt->error);
            }
            $priceRow = $priceStmt->get_result()->fetch_assoc();
            $priceStmt->close();

            if (!$priceRow) {
                throw new Exception('createProjectAndHoldPayment post not found');
            }

            $projectValue = $this->resolveProjectValueByPost(
                $postId,
                (int) ($priceRow['Provider_ID'] ?? 0),
                (string) ($priceRow['Post_Type'] ?? ''),
                (float) ($priceRow['Requesting_Price'] ?? 0)
            );
            [$commission] = $this->splitProjectValue($projectValue);
            $grossAmount = max(0.0, round($projectValue, 2));

            $projectId = 0;
            $existingProjectStmt = $this->conn->prepare('SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1');
            if (!$existingProjectStmt) {
                throw new Exception('createProjectAndHoldPayment prepare existing project: ' . $this->conn->error);
            }
            $existingProjectStmt->bind_param('i', $postId);
            if (!$existingProjectStmt->execute()) {
                throw new Exception('createProjectAndHoldPayment exec existing project: ' . $existingProjectStmt->error);
            }
            $existingProject = $existingProjectStmt->get_result()->fetch_assoc();
            $existingProjectStmt->close();

            if ($existingProject) {
                $projectId = (int) ($existingProject['Project_ID'] ?? 0);

                $updateProjectStmt = $this->conn->prepare(
                    'UPDATE project SET Project_Status = "ongoing", Started_At = COALESCE(Started_At, NOW()), Ended_At = NULL WHERE Project_ID = ?'
                );
                if (!$updateProjectStmt) {
                    throw new Exception('createProjectAndHoldPayment prepare project update: ' . $this->conn->error);
                }
                $updateProjectStmt->bind_param('i', $projectId);
                if (!$updateProjectStmt->execute()) {
                    throw new Exception('createProjectAndHoldPayment exec project update: ' . $updateProjectStmt->error);
                }
                $updateProjectStmt->close();
            } else {
                $createProjectStmt = $this->conn->prepare(
                    "INSERT INTO project (Post_ID, Project_Status, Started_At) VALUES (?, 'ongoing', NOW())"
                );
                if (!$createProjectStmt) {
                    throw new Exception('createProjectAndHoldPayment prepare project insert: ' . $this->conn->error);
                }
                $createProjectStmt->bind_param('i', $postId);
                if (!$createProjectStmt->execute()) {
                    throw new Exception('createProjectAndHoldPayment exec project insert: ' . $createProjectStmt->error);
                }
                $projectId = (int) $createProjectStmt->insert_id;
                $createProjectStmt->close();
            }

            if ($projectId <= 0) {
                throw new Exception('createProjectAndHoldPayment invalid project id');
            }

            $existingPaymentStmt = $this->conn->prepare('SELECT Payment_ID FROM payment WHERE Project_ID = ? LIMIT 1');
            if (!$existingPaymentStmt) {
                throw new Exception('createProjectAndHoldPayment prepare existing payment: ' . $this->conn->error);
            }
            $existingPaymentStmt->bind_param('i', $projectId);
            if (!$existingPaymentStmt->execute()) {
                throw new Exception('createProjectAndHoldPayment exec existing payment: ' . $existingPaymentStmt->error);
            }
            $existingPayment = $existingPaymentStmt->get_result()->fetch_assoc();
            $existingPaymentStmt->close();

            if ($existingPayment) {
                $paymentId = (int) ($existingPayment['Payment_ID'] ?? 0);
                $updatePaymentStmt = $this->conn->prepare(
                    "UPDATE payment
                     SET Amount = ?, Commission = ?, Status = 'Hold', Hold_Time = COALESCE(Hold_Time, NOW()), Paid_Time = NULL
                     WHERE Payment_ID = ?"
                );
                if (!$updatePaymentStmt) {
                    throw new Exception('createProjectAndHoldPayment prepare payment update: ' . $this->conn->error);
                }
                $updatePaymentStmt->bind_param('ddi', $grossAmount, $commission, $paymentId);
                if (!$updatePaymentStmt->execute()) {
                    throw new Exception('createProjectAndHoldPayment exec payment update: ' . $updatePaymentStmt->error);
                }
                $updatePaymentStmt->close();
            } else {
                $paymentId = $this->getNextPaymentId();
                $insertPaymentStmt = $this->conn->prepare(
                    "INSERT INTO payment (Payment_ID, Amount, Status, Hold_Time, Paid_Time, Commission, Project_ID)
                     VALUES (?, ?, 'Hold', NOW(), NULL, ?, ?)"
                );
                if (!$insertPaymentStmt) {
                    throw new Exception('createProjectAndHoldPayment prepare payment insert: ' . $this->conn->error);
                }
                $insertPaymentStmt->bind_param('iddi', $paymentId, $grossAmount, $commission, $projectId);
                if (!$insertPaymentStmt->execute()) {
                    throw new Exception('createProjectAndHoldPayment exec payment insert: ' . $insertPaymentStmt->error);
                }
                $insertPaymentStmt->close();
            }

            $updatePostStmt = $this->conn->prepare("UPDATE post SET Request_Status = 'completed' WHERE Post_ID = ?");
            if (!$updatePostStmt) {
                throw new Exception('createProjectAndHoldPayment prepare post update: ' . $this->conn->error);
            }
            $updatePostStmt->bind_param('i', $postId);
            if (!$updatePostStmt->execute()) {
                throw new Exception('createProjectAndHoldPayment exec post update: ' . $updatePostStmt->error);
            }
            $updatePostStmt->close();

            $this->conn->commit();
            return ['success' => true, 'project_id' => $projectId];
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::createProjectAndHoldPayment error: ' . $e->getMessage());
            return ['success' => false, 'project_id' => 0];
        }
    }

  public function getByPostId(int $postId): ?array {
    $stmt = $this->conn->prepare("SELECT * FROM project WHERE Post_ID = ? LIMIT 1");
    if (!$stmt) {
      error_log('ProjectModel::getByPostId prepare: ' . $this->conn->error);
      return null;
    }

    $stmt->bind_param('i', $postId);
    if (!$stmt->execute()) {
      error_log('ProjectModel::getByPostId exec: ' . $stmt->error);
      $stmt->close();
      return null;
    }

    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
  }

  public function getProjectIdsByProviderId(int $providerId): array {
    $stmt = $this->conn->prepare(
      "SELECT pr.Project_ID
       FROM project pr
       INNER JOIN post p ON p.Post_ID = pr.Post_ID
       WHERE p.Provider_ID = ?"
    );

    if (!$stmt) {
      error_log('ProjectModel::getProjectIdsByProviderId prepare: ' . $this->conn->error);
      return [];
    }

    $stmt->bind_param('i', $providerId);
    if (!$stmt->execute()) {
      error_log('ProjectModel::getProjectIdsByProviderId exec: ' . $stmt->error);
      $stmt->close();
      return [];
    }

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return array_values(array_filter(array_map(static function ($row) {
      return (int) ($row['Project_ID'] ?? 0);
    }, $rows)));
  }

  public function getProjectsByClientId($clientId, $count = null, $status = 'Active') {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, prov.First_Name, prov.Last_Name
    //        FROM project pr 
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status = ?
    // TODO: Stage and Progress fields need to be added to project table or calculated
    return [];
  }

  public function countProjectsByClientId($clientId, $status = null) {
        $sql = "SELECT COUNT(*) AS total
                        FROM project pr
                        INNER JOIN post p ON p.Post_ID = pr.Post_ID
                        WHERE p.Client_ID = ?";

        $types = 'i';
        $params = [$clientId];

        if ($status !== null) {
            $normalizedStatus = strtolower(trim((string) $status));
            if ($normalizedStatus === 'active') {
                $sql .= " AND pr.Project_Status IN ('ongoing', 'pending-review')";
            } elseif ($normalizedStatus === 'completed') {
                $sql .= " AND pr.Project_Status = 'completed'";
            } elseif ($normalizedStatus === 'pending') {
                $sql .= " AND pr.Project_Status = 'pending'";
            } elseif ($normalizedStatus === 'cancelled') {
                $sql .= " AND pr.Project_Status = 'cancelled'";
            } else {
                $sql .= " AND pr.Project_Status = ?";
                $types .= 's';
                $params[] = $status;
            }
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('ProjectModel::countProjectsByClientId prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param($types, ...$params);
        if (!$stmt->execute()) {
            error_log('ProjectModel::countProjectsByClientId exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['total'] ?? 0);
  }

  public function getPendingRequestsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, prov.First_Name, prov.Last_Name
    //        FROM post p
    //        INNER JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND p.Request_Status = 'Pending' AND p.Post_Type = 'Direct'
    return [];
  }

  public function getPendingReviewsByClientId($clientId) {
    // TODO: Implement actual database query
    // This might need a milestone/deliverable table that doesn't exist yet
    return [];
  }

  public function getCompletedProjectsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status = 'Completed'
    return [];
  }

  public function getApprovedRequestsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, prov.First_Name, prov.Last_Name
    //        FROM post p
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND p.Request_Status = 'Approved'
    return [];
  }

  public function getOngoingProjectsByClientId($clientId) {
    // TODO: Implement actual database query  
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, prov.First_Name, prov.Last_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status IN ('Active', 'In Progress')
    // TODO: Progress field needs implementation (not in current schema)
    return [];
  }

  // Provider-specific methods

  public function getIncomingRequestsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM post p
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND p.Request_Status = 'Pending' AND p.Post_Type = 'Direct'
    
    return [
      [
        'Client_Name' => 'Michael Chen',
        'Title' => 'E-commerce Website Development',
        'Category_Name' => 'Web Development',
        'Posted' => 'Proposed 12 Jul 2025',
        'Budget' => '$2,500',
        'Timeline' => '4 weeks',
        'Status' => 'Pending Response',
        'Description' => 'Full e-commerce site with product catalog, shopping cart, and payment integration.'
      ],
      [
        'Client_Name' => 'Sarah Martinez',
        'Title' => 'Corporate Logo Design',
        'Category_Name' => 'Graphic Design',
        'Posted' => 'Proposed 10 Jul 2025',
        'Budget' => '$800',
        'Timeline' => '2 weeks',
        'Status' => 'Pending Response',
        'Description' => 'Modern, professional logo for a consulting firm with brand guidelines.'
      ]
    ];
  }

  public function getOngoingProjectsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND pr.Project_Status IN ('Active', 'In Progress')
    // TODO: Progress field needs implementation
    
    return [
      [
        'Client_Name' => 'Emma Wilson',
        'Title' => 'Mobile App UI/UX Design',
        'Category_Name' => 'UI/UX Design',
        'Posted' => 'Started 10 Jul 2025',
        'Budget' => '$1,200',
        'Timeline' => 'ETA 12d',
        'Status' => 'In Progress',
        'Progress' => 65,
        'Progress_Detail' => '32h of 50h',
        'Hours_Logged' => '32h',
        'Description' => 'Designing user interface and experience for a fitness tracking mobile application.'
      ],
      [
        'Client_Name' => 'Robert Taylor',
        'Title' => 'SEO Strategy Implementation',
        'Category_Name' => 'SEO',
        'Posted' => 'Started 05 Jul 2025',
        'Budget' => '$950',
        'Timeline' => 'ETA 18d',
        'Status' => 'In Progress',
        'Progress' => 40,
        'Progress_Detail' => '20h of 50h',
        'Hours_Logged' => '20h',
        'Description' => 'Implementing comprehensive SEO strategy including keyword research and on-page optimization.'
      ]
    ];
  }

  public function getPendingReviewProjectsByProviderId($providerId) {
    // TODO: Implement actual database query
    // This might need a milestone/deliverable table that doesn't exist yet
    // Query would check for submitted deliverables awaiting client approval
    
    return [
      [
        'Client_Name' => 'David Rodriguez',
        'Title' => 'Website Content Writing',
        'Category_Name' => 'Content Writing',
        'Posted' => 'Submitted 08 Jul 2025',
        'Budget' => '$600',
        'Timeline' => 'Not specified',
        'Status' => 'Pending Review',
        'Description' => 'Wrote homepage, about us, and services page content for a digital marketing agency.'
      ],
      [
        'Client_Name' => 'Lisa Anderson',
        'Title' => 'Product Photography',
        'Category_Name' => 'Photography',
        'Posted' => 'Submitted 06 Jul 2025',
        'Budget' => '$1,100',
        'Timeline' => 'Not specified',
        'Status' => 'Pending Review',
        'Description' => 'Professional product photography for e-commerce catalog (50 items).'
      ]
    ];
  }

  public function getCompletedJobsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND pr.Project_Status = 'Completed'
    
    return [
      [
        'Client_Name' => 'Jennifer Lee',
        'Title' => 'Social Media Marketing Campaign',
        'Category_Name' => 'Digital Marketing',
        'Posted' => 'Completed 01 Jul 2025',
        'Budget' => '$1,500',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => '30-day social media campaign with content creation and community management across 3 platforms.'
      ],
      [
        'Client_Name' => 'Thomas Brown',
        'Title' => 'WordPress Plugin Development',
        'Category_Name' => 'Web Development',
        'Posted' => 'Completed 28 Jun 2025',
        'Budget' => '$2,200',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => 'Custom WordPress plugin for appointment booking with payment integration.'
      ],
      [
        'Client_Name' => 'Amanda White',
        'Title' => 'Brand Identity Package',
        'Category_Name' => 'Graphic Design',
        'Posted' => 'Completed 25 Jun 2025',
        'Budget' => '$1,800',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => 'Complete brand identity including logo, business cards, letterhead, and brand guidelines.'
      ]
    ];
  }

  public function cancelProject(int $postId): bool
    {
        $sql = "UPDATE project SET Project_Status = 'canceled' WHERE Post_ID = ?";
        $sql2 = "UPDATE post SET Request_Status = 'canceled' WHERE Post_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt2 = $this->conn->prepare($sql2);
        if (!$stmt || !$stmt2) {
            error_log('cancelProject prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);
        $stmt2->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('cancelProject exec: ' . $stmt->error);
            return false;
        }

        if (!$stmt2->execute()) {
            error_log('cancelProject exec: ' . $stmt2->error);
            return false;
        }

        $stmt->close();
        $stmt2->close();
        return true;
    }

    public function updateProjectProgress(
        int    $postId,
        int    $progress,
        string $title,
        string $description,
        float  $workedHours,
        array  $fileNames = []
    ): bool {
        // Resolve Project_ID from Post_ID
        $idStmt = $this->conn->prepare(
            'SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1'
        );
        if (!$idStmt) {
            error_log('updateProjectProgress id prepare: ' . $this->conn->error);
            return false;
        }
        $idStmt->bind_param('i', $postId);
        $idStmt->execute();
        $projectId = (int) ($idStmt->get_result()->fetch_assoc()['Project_ID'] ?? 0);
        $idStmt->close();

        if ($projectId <= 0) {
            error_log('updateProjectProgress: project not found for post ' . $postId);
            return false;
        }

        $this->conn->begin_transaction();

        try {
            // 1. Update progress percentage on the project row
            $upStmt = $this->conn->prepare(
                'UPDATE project SET Progress = ? WHERE Post_ID = ?'
            );
            if (!$upStmt) throw new Exception('prepare project update: ' . $this->conn->error);
            $upStmt->bind_param('ii', $progress, $postId);
            if (!$upStmt->execute()) throw new Exception('exec project update: ' . $upStmt->error);
            $upStmt->close();

            // 2. Insert update log entry
            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES (?, ?, NOW(), ?, ?, ?, "ongoing")'
            );
            if (!$logStmt) throw new Exception('prepare log insert: ' . $this->conn->error);
            $logStmt->bind_param('ssidi', $title, $description, $projectId, $workedHours, $progress);
            if (!$logStmt->execute()) throw new Exception('exec log insert: ' . $logStmt->error);
            $logId = (int) $this->conn->insert_id;
            $logStmt->close();

            // 3. Insert file records (if any)
            if (!empty($fileNames)) {
                $fileStmt = $this->conn->prepare(
                    'INSERT INTO project_update_log_files (Update_Log_ID, File) VALUES (?, ?)'
                );
                if (!$fileStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                foreach ($fileNames as $fileName) {
                    $fileStmt->bind_param('is', $logId, $fileName);
                    if (!$fileStmt->execute()) throw new Exception('exec file insert: ' . $fileStmt->error);
                }
                $fileStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::updateProjectProgress error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Returns all requirements (all statuses) for a post, each with their attached files.
     * Response shape: { project_id: int, requirements: [ { ...row, files: [filename, ...] } ] }
     */
    public function getRequirementsByPostId(int $postId): array
    {
        // Resolve Project_ID
        $pidStmt = $this->conn->prepare(
            'SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1'
        );
        if (!$pidStmt) return ['project_id' => null, 'requirements' => []];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $projectId = (int) ($pidStmt->get_result()->fetch_assoc()['Project_ID'] ?? 0);
        $pidStmt->close();

        if ($projectId <= 0) {
            return ['project_id' => null, 'requirements' => []];
        }

        // Fetch all requirements
        $reqStmt = $this->conn->prepare(
            'SELECT Requirement_ID, Requirement_Title, Requirement_Description,
                    Status, Created_At, Approved_At, Rejected_At, Rejection_Reason
             FROM project_requirements
             WHERE Project_ID = ?
             ORDER BY Created_At DESC'
        );
        if (!$reqStmt) return ['project_id' => $projectId, 'requirements' => []];
        $reqStmt->bind_param('i', $projectId);
        $reqStmt->execute();
        $rows = $reqStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $reqStmt->close();

        if (empty($rows)) {
            return ['project_id' => $projectId, 'requirements' => []];
        }

        // Collect IDs and fetch files in one query
        $ids      = array_column($rows, 'Requirement_ID');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types    = str_repeat('i', count($ids));
        $fileRows = [];
        $filesTable = $this->getRequirementFilesTableName();
        if ($filesTable !== null) {
            $fileStmt = $this->conn->prepare(
                "SELECT Requirement_ID, File FROM {$filesTable} WHERE Requirement_ID IN ($placeholders)"
            );
            if ($fileStmt) {
                $bindArgs = [$types];
                foreach ($ids as $idx => $id) {
                    $ids[$idx] = (int) $id;
                    $bindArgs[] = &$ids[$idx];
                }
                call_user_func_array([$fileStmt, 'bind_param'], $bindArgs);
                $fileStmt->execute();
                $fileRows = $fileStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $fileStmt->close();
            }
        }

        // Group files by requirement
        $fileMap = [];
        foreach ($fileRows as $f) {
            $fileMap[$f['Requirement_ID']][] = $f['File'];
        }

        // Attach files to each requirement row
        foreach ($rows as &$row) {
            $row['files'] = $fileMap[$row['Requirement_ID']] ?? [];
        }
        unset($row);

        return ['project_id' => $projectId, 'requirements' => $rows];
    }

    /**
     * Insert a new requirement (status = 'pending') with optional file attachments.
     *
     * @param int    $projectId
     * @param string $title
     * @param string $description
     * @param array  $fileNames   Array of saved file name strings
     */
    public function addRequirement(int $projectId, string $title, string $description = '', array $fileNames = []): bool
    {
        $this->conn->begin_transaction();
        try {
            $insStmt = $this->conn->prepare(
                "INSERT INTO project_requirements (Project_ID, Requirement_Title, Requirement_Description, Status, Created_At)
                 VALUES (?, ?, ?, 'pending', NOW())"
            );
            if (!$insStmt) throw new Exception('prepare req insert: ' . $this->conn->error);
            $insStmt->bind_param('iss', $projectId, $title, $description);
            if (!$insStmt->execute()) throw new Exception('exec req insert: ' . $insStmt->error);
            $reqId = (int) $this->conn->insert_id;
            $insStmt->close();

            if (!empty($fileNames)) {
                $filesTable = $this->getRequirementFilesTableName();
                if ($filesTable !== null) {
                    $fileStmt = $this->conn->prepare(
                        "INSERT INTO {$filesTable} (Requirement_ID, File) VALUES (?, ?)"
                    );
                    if (!$fileStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                    foreach ($fileNames as $fileName) {
                        $fileStmt->bind_param('is', $reqId, $fileName);
                        if (!$fileStmt->execute()) throw new Exception('exec file insert: ' . $fileStmt->error);
                    }
                    $fileStmt->close();
                } else {
                    error_log('ProjectModel::addRequirement warning: requirement files table not found; skipping file records');
                }
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::addRequirement error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check that a requirement belongs to a project owned by the given provider.
     */
    public function requirementBelongsToProvider(int $requirementId, int $providerId): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT 1
             FROM project_requirements pr
             JOIN project proj ON proj.Project_ID = pr.Project_ID
             JOIN post p       ON p.Post_ID = proj.Post_ID
             WHERE pr.Requirement_ID = ? AND p.Provider_ID = ?
             LIMIT 1'
        );
        if (!$stmt) return false;
        $stmt->bind_param('ii', $requirementId, $providerId);
        $stmt->execute();
        $found = (bool) $stmt->get_result()->fetch_row();
        $stmt->close();
        return $found;
    }

    /**
     * Set a requirement status to 'approved' or 'rejected'.
     * For rejected status a non-empty rejection reason is required.
     */
    public function updateRequirementStatus(int $requirementId, string $status, string $rejectionReason = ''): bool
    {
        if (!in_array($status, ['approved', 'rejected'], true)) return false;

        if ($status === 'approved') {
            $stmt = $this->conn->prepare(
                "UPDATE project_requirements
                 SET Status = 'approved', Approved_At = NOW()
                 WHERE Requirement_ID = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param('i', $requirementId);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE project_requirements
                 SET Status = 'rejected', Rejected_At = NOW(), Rejection_Reason = ?
                 WHERE Requirement_ID = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param('si', $rejectionReason, $requirementId);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getProviderByProject($project_id)
    {
        $sql = "SELECT p.Provider_ID
                FROM project pr
                JOIN post p ON p.Post_ID = pr.Post_ID
                WHERE pr.Project_ID = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $project_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function cancelRequest(int $postId, int $clientId): array
    {
        // Fetch the post to verify ownership, current status, and type
        $checkSql = "SELECT Client_ID, Request_Status, Post_Type FROM post WHERE Post_ID = ? LIMIT 1";
        $checkStmt = $this->conn->prepare($checkSql);
        if (!$checkStmt) {
            error_log('cancelRequest prepare check: ' . $this->conn->error);
            return ['success' => false, 'message' => 'Server error', 'code' => 500];
        }
        $checkStmt->bind_param('i', $postId);
        $checkStmt->execute();
        $row = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if (!$row) {
            return ['success' => false, 'message' => 'Post not found', 'code' => 404];
        }

        if ((int) $row['Client_ID'] !== $clientId) {
            return ['success' => false, 'message' => 'Unauthorized', 'code' => 403];
        }

        $current = strtolower(trim((string) ($row['Request_Status'] ?? '')));
        $cancellable = ['', 'open', 'pending', 'accepted', 'declined'];
        if (!in_array($current, $cancellable, true)) {
            return ['success' => false, 'message' => 'Request cannot be cancelled in its current status', 'code' => 422];
        }

        // For public posts reset to 'open' so other providers can still bid;
        // for direct requests mark as 'cancelled'.
        $isPublicPost  = strtolower(trim((string) ($row['Post_Type'] ?? ''))) === 'post';
        $newStatus     = $isPublicPost ? 'open' : 'cancelled';
        $clearProvider = $isPublicPost ? ', Provider_ID = NULL' : '';

        $sql = "UPDATE post SET Request_Status = ? {$clearProvider} WHERE Post_ID = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('cancelRequest prepare update: ' . $this->conn->error);
            return ['success' => false, 'message' => 'Server error', 'code' => 500];
        }
        $stmt->bind_param('si', $newStatus, $postId);

        if (!$stmt->execute()) {
            error_log('cancelRequest exec: ' . $stmt->error);
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to cancel request', 'code' => 500];
        }
        $stmt->close();

        return ['success' => true, 'message' => 'Request cancelled successfully'];
    }

    /**
     * Fetch ongoing projects for a provider with pagination.
     * Joins project → post → category → client.
     * Filters: project.Project_Status = 'ongoing' AND post.Provider_ID = $providerId
     */
    public function getOngoingProjectsForProvider(int $providerId, int $page = 1, int $limit = 10): array
    {
        $page   = max(1, $page);
        $limit  = max(1, min($limit, 100));
        $offset = ($page - 1) * $limit;

        // Total count
        $countSql = "SELECT COUNT(*) AS total
                     FROM project proj
                     JOIN post p ON p.Post_ID = proj.Post_ID
                     WHERE proj.Project_Status = 'ongoing' AND p.Provider_ID = ?";

        $countStmt = $this->conn->prepare($countSql);
        if (!$countStmt) {
            error_log('getOngoingProjectsForProvider count prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $countStmt->bind_param('i', $providerId);
        $countStmt->execute();
        $totalRecords = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
        $countStmt->close();

        $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $limit) : 0;

        // Data query
        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    cat.Name AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl ON cl.Client_ID = p.Client_ID
                WHERE proj.Project_Status = 'ongoing' AND p.Provider_ID = ?
                ORDER BY proj.Started_At DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getOngoingProjectsForProvider prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $stmt->bind_param('iii', $providerId, $limit, $offset);
        if (!$stmt->execute()) {
            error_log('getOngoingProjectsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return [
            'data'         => $data,
            'total'        => $totalRecords,
            'pages'        => $totalPages,
            'current_page' => $page,
        ];
    }

    public function getPendingReviewProjectsForProvider(int $providerId, int $page = 1, int $limit = 10): array
    {
        $page   = max(1, $page);
        $limit  = max(1, min($limit, 100));
        $offset = ($page - 1) * $limit;

        $countSql = "SELECT COUNT(*) AS total
                     FROM project proj
                     JOIN post p ON p.Post_ID = proj.Post_ID
                     WHERE proj.Project_Status = 'pending-review' AND p.Provider_ID = ?";

        $countStmt = $this->conn->prepare($countSql);
        if (!$countStmt) {
            error_log('getPendingReviewProjectsForProvider count prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $countStmt->bind_param('i', $providerId);
        $countStmt->execute();
        $totalRecords = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
        $countStmt->close();

        $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $limit) : 0;

        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    cat.Name AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl ON cl.Client_ID = p.Client_ID
                WHERE proj.Project_Status = 'pending-review' AND p.Provider_ID = ?
                ORDER BY proj.Ended_At DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getPendingReviewProjectsForProvider prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $stmt->bind_param('iii', $providerId, $limit, $offset);
        if (!$stmt->execute()) {
            error_log('getPendingReviewProjectsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return [
            'data'         => $data,
            'total'        => $totalRecords,
            'pages'        => $totalPages,
            'current_page' => $page,
        ];
    }

    public function createProject(int $postId): int
    {
        $sql = "INSERT INTO project (Post_ID, Project_Status, Started_At) VALUES (?, 'ongoing', NOW())";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('createProject prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('createProject exec: ' . $stmt->error);
            return 0;
        }

        $projectId = $stmt->insert_id;

        $stmt->close();
        
        return $projectId;
    }

      public function createProjectAndCompleteRequest(int $postId): array
      {
        $this->conn->begin_transaction();

        try {
          $insertSql = "INSERT INTO project (Post_ID, Project_Status, Started_At) VALUES (?, 'ongoing', NOW())";
          $insertStmt = $this->conn->prepare($insertSql);
          if (!$insertStmt) {
            throw new Exception('createProjectAndCompleteRequest prepare project: ' . $this->conn->error);
          }

          $insertStmt->bind_param('i', $postId);
          if (!$insertStmt->execute()) {
            throw new Exception('createProjectAndCompleteRequest exec project: ' . $insertStmt->error);
          }

          $projectId = (int) $insertStmt->insert_id;
          $insertStmt->close();

          if ($projectId <= 0) {
            throw new Exception('createProjectAndCompleteRequest invalid project id');
          }

          $updateSql = "UPDATE post SET Request_Status = 'completed' WHERE Post_ID = ?";
          $updateStmt = $this->conn->prepare($updateSql);
          if (!$updateStmt) {
            throw new Exception('createProjectAndCompleteRequest prepare post: ' . $this->conn->error);
          }

          $updateStmt->bind_param('i', $postId);
          if (!$updateStmt->execute()) {
            throw new Exception('createProjectAndCompleteRequest exec post: ' . $updateStmt->error);
          }

          if ($updateStmt->affected_rows <= 0) {
            throw new Exception('createProjectAndCompleteRequest no post rows updated');
          }

          $updateStmt->close();

          $this->conn->commit();

          return [
            'success' => true,
            'project_id' => $projectId
          ];
        } catch (Throwable $e) {
          $this->conn->rollback();
          error_log('ProjectModel::createProjectAndCompleteRequest error: ' . $e->getMessage());
          return [
            'success' => false,
            'project_id' => 0
          ];
        }
      }

    /**
     * Get full project details for the detail view (project + post + provider/client + category).
     */
    public function getProjectFullDetails(int $postId): ?array
    {
        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    p.Provider_ID,
                    p.Created_At AS Post_Created_At,
                    cat.Name       AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture AS Client_Picture,
                    CONCAT(prov.First_Name, ' ', prov.Last_Name) AS Provider_Name,
                    prov.Profile_Picture AS Provider_Picture
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat  ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl     ON cl.Client_ID    = p.Client_ID
                LEFT JOIN provider prov ON prov.Provider_ID = p.Provider_ID
                WHERE proj.Post_ID = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param('i', $postId);
        if (!$stmt->execute()) { $stmt->close(); return null; }
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }

    /**
     * Get project timeline: all events (start, progress updates, requirement adds, requirement status changes).
     * Returns a flat array sorted by date DESC.
     */
    public function getProjectTimeline(int $postId): array
    {
        // Resolve Project_ID
        $pidStmt = $this->conn->prepare('SELECT Project_ID, Started_At FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$pidStmt) return [];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $projRow = $pidStmt->get_result()->fetch_assoc();
        $pidStmt->close();
        if (!$projRow) return [];

        $projectId = (int) $projRow['Project_ID'];
        $events = [];

        // 1. Project started event
        if (!empty($projRow['Started_At'])) {
            $events[] = [
                'type'  => 'project_started',
                'title' => 'Project Started',
                'description' => '',
                'date'  => $projRow['Started_At'],
                'icon'  => 'fa-rocket',
                'color' => 'green',
            ];
        }

        // 2. Progress update logs (ongoing only)
        $logSql = "SELECT ul.ID, ul.Title, ul.Description, ul.Date, ul.Worked_Hours,
                          COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                   FROM project_update_log ul
                   WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') = 'ongoing'
                   ORDER BY ul.Date DESC";
        $logStmt = $this->conn->prepare($logSql);
        if ($logStmt) {
            $logStmt->bind_param('i', $projectId);
            $logStmt->execute();
            $logs = $logStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $logStmt->close();

            // Fetch files for all update logs
            $logFileMap = [];
            if (!empty($logs)) {
                $logIds = array_column($logs, 'ID');
                $ph = implode(',', array_fill(0, count($logIds), '?'));
                $types = str_repeat('i', count($logIds));
                $fStmt = $this->conn->prepare("SELECT Update_Log_ID, File FROM project_update_log_files WHERE Update_Log_ID IN ($ph)");
                if ($fStmt) {
                    $fStmt->bind_param($types, ...$logIds);
                    $fStmt->execute();
                    foreach ($fStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                        $logFileMap[$f['Update_Log_ID']][] = $f['File'];
                    }
                    $fStmt->close();
                }
            }

            foreach ($logs as $log) {
                $events[] = [
                    'type'              => 'progress_update',
                    'title'             => $log['Title'] ?? 'Progress Update',
                    'description'       => $log['Description'] ?? '',
                    'date'              => $log['Date'],
                    'worked_hours'      => (float) ($log['Worked_Hours'] ?? 0),
                    'progress_completed'=> (int) ($log['Progress_Completed'] ?? 0),
                    'files'             => $logFileMap[$log['ID']] ?? [],
                    'icon'              => 'fa-arrow-up-right-dots',
                    'color'             => 'blue',
                ];
            }
        }

        // 3. Status-change logs (e.g. submitted for review)
        $statusSql = "SELECT ul.ID, ul.Title, ul.Description, ul.Date, ul.Project_Status_Update,
                             COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                      FROM project_update_log ul
                      WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') != 'ongoing'
                      ORDER BY ul.Date DESC";
        $statusStmt = $this->conn->prepare($statusSql);
        if ($statusStmt) {
            $statusStmt->bind_param('i', $projectId);
            $statusStmt->execute();
            $statusLogs = $statusStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $statusStmt->close();

            // Fetch files for status logs
            $statusFileMap = [];
            if (!empty($statusLogs)) {
                $sIds = array_column($statusLogs, 'ID');
                $sph  = implode(',', array_fill(0, count($sIds), '?'));
                $sTypes = str_repeat('i', count($sIds));
                $sfStmt = $this->conn->prepare("SELECT Update_Log_ID, File FROM project_update_log_files WHERE Update_Log_ID IN ($sph)");
                if ($sfStmt) {
                    $sfStmt->bind_param($sTypes, ...$sIds);
                    $sfStmt->execute();
                    foreach ($sfStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                        $statusFileMap[$f['Update_Log_ID']][] = $f['File'];
                    }
                    $sfStmt->close();
                }
            }

            foreach ($statusLogs as $sl) {
                $statusVal = $sl['Project_Status_Update'] ?? '';
                $icon  = 'fa-flag';
                $color = 'gray';
                if ($statusVal === 'pending-review') {
                    $icon  = 'fa-paper-plane';
                    $color = 'green';
                }
                $events[] = [
                    'type'              => 'status_change',
                    'title'             => $sl['Title'] ?? 'Status Changed',
                    'description'       => $sl['Description'] ?? '',
                    'date'              => $sl['Date'],
                    'status'            => $statusVal,
                    'progress_completed'=> (int) ($sl['Progress_Completed'] ?? 0),
                    'files'             => $statusFileMap[$sl['ID']] ?? [],
                    'icon'              => $icon,
                    'color'             => $color,
                ];
            }
        }

        // 4. Requirement events (created, approved, rejected)
        $reqSql = "SELECT Requirement_ID, Requirement_Title, Status, Created_At, Approved_At, Rejected_At, Rejection_Reason
                   FROM project_requirements
                   WHERE Project_ID = ?";
        $reqStmt = $this->conn->prepare($reqSql);
        if ($reqStmt) {
            $reqStmt->bind_param('i', $projectId);
            $reqStmt->execute();
            $reqs = $reqStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $reqStmt->close();

            foreach ($reqs as $req) {
                // Requirement added
                if (!empty($req['Created_At'])) {
                    $events[] = [
                        'type'  => 'requirement_added',
                        'title' => 'Requirement Added: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => '',
                        'date'  => $req['Created_At'],
                        'icon'  => 'fa-file-circle-plus',
                        'color' => 'amber',
                    ];
                }
                // Requirement approved
                if ($req['Status'] === 'approved' && !empty($req['Approved_At'])) {
                    $events[] = [
                        'type'  => 'requirement_approved',
                        'title' => 'Requirement Accepted: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => '',
                        'date'  => $req['Approved_At'],
                        'icon'  => 'fa-circle-check',
                        'color' => 'green',
                    ];
                }
                // Requirement rejected
                if ($req['Status'] === 'rejected' && !empty($req['Rejected_At'])) {
                    $events[] = [
                        'type'  => 'requirement_rejected',
                        'title' => 'Requirement Rejected: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => $req['Rejection_Reason'] ?? '',
                        'date'  => $req['Rejected_At'],
                        'icon'  => 'fa-circle-xmark',
                        'color' => 'red',
                    ];
                }
            }
        }

        // Sort by date DESC
        usort($events, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $events;
    }

    /**
     * Get progress history (for charting) — returns date+progress pairs from update logs.
     * Uses the real Progress_Completed column stored on each update log entry.
     */
    public function getProgressHistory(int $postId): array
    {
        $pidStmt = $this->conn->prepare('SELECT Project_ID, Started_At FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$pidStmt) return [];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $proj = $pidStmt->get_result()->fetch_assoc();
        $pidStmt->close();
        if (!$proj) return [];

        $projectId = (int) $proj['Project_ID'];
        $points = [];

        // Starting point: 0% at project start
        if (!empty($proj['Started_At'])) {
            $points[] = ['date' => $proj['Started_At'], 'progress' => 0];
        }

        // Use the actual Progress_Completed snapshot stored on each update log (ongoing entries only)
        $sql = "SELECT ul.Date, COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                FROM project_update_log ul
                WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') = 'ongoing'
                ORDER BY ul.Date ASC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $projectId);
            $stmt->execute();
            $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            foreach ($logs as $log) {
                $points[] = [
                    'date'     => $log['Date'],
                    'progress' => (int) $log['Progress_Completed'],
                ];
            }
        }

        return $points;
    }

    /**
     * Get the most recent "submitted for review" log entry (with files) for a project.
     * Returns null if no such entry exists or it has no files.
     */
    public function getLastSubmissionDeliverables(int $postId): ?array
    {
        $pidStmt = $this->conn->prepare('SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$pidStmt) return null;
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $proj = $pidStmt->get_result()->fetch_assoc();
        $pidStmt->close();
        if (!$proj) return null;

        $projectId = (int) $proj['Project_ID'];

        // Get most recent pending-review log entry
        $stmt = $this->conn->prepare(
            "SELECT ul.ID, ul.Title, ul.Description, ul.Date
             FROM project_update_log ul
             WHERE ul.Project_ID = ? AND ul.Project_Status_Update = 'pending-review'
             ORDER BY ul.Date DESC
             LIMIT 1"
        );
        if (!$stmt) return null;
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $log = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$log) return null;

        $logId = (int) $log['ID'];

        // Fetch associated files
        $fStmt = $this->conn->prepare(
            'SELECT File FROM project_update_log_files WHERE Update_Log_ID = ? ORDER BY id ASC'
        );
        if (!$fStmt) return null;
        $fStmt->bind_param('i', $logId);
        $fStmt->execute();
        $files = array_column($fStmt->get_result()->fetch_all(MYSQLI_ASSOC), 'File');
        $fStmt->close();

        // Only return if there are files (nothing to show without deliverable files)
        if (empty($files)) return null;

        return [
            'note'  => $log['Description'] ?? '',
            'date'  => $log['Date'],
            'files' => $files,
        ];
    }

    /**
     * Submit a project for review: update status to 'pending-review', set Ended_At, and log the event.
     */
    public function submitForReview(
        int    $postId,
        string $description,
        array  $fileNames = []
    ): bool {
        $idStmt = $this->conn->prepare('SELECT Project_ID, COALESCE(Progress,0) AS Progress FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$idStmt) return false;
        $idStmt->bind_param('i', $postId);
        $idStmt->execute();
        $row = $idStmt->get_result()->fetch_assoc();
        $idStmt->close();
        if (!$row) return false;

        $projectId = (int) $row['Project_ID'];
        $progress  = (int) $row['Progress'];

        $this->conn->begin_transaction();
        try {
            // 1. Update project status and end time
            $upStmt = $this->conn->prepare(
                'UPDATE project SET Project_Status = "pending-review", Ended_At = NOW() WHERE Post_ID = ?'
            );
            if (!$upStmt) throw new Exception('prepare status update: ' . $this->conn->error);
            $upStmt->bind_param('i', $postId);
            if (!$upStmt->execute()) throw new Exception('exec status update: ' . $upStmt->error);
            $upStmt->close();

            // 2. Insert log entry for the status change
            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES ("Submitted for Review", ?, NOW(), ?, 0, ?, "pending-review")'
            );
            if (!$logStmt) throw new Exception('prepare review log: ' . $this->conn->error);
            $logStmt->bind_param('sii', $description, $projectId, $progress);
            if (!$logStmt->execute()) throw new Exception('exec review log: ' . $logStmt->error);
            $logId = (int) $this->conn->insert_id;
            $logStmt->close();

            // 3. Insert file records
            if (!empty($fileNames)) {
                $fStmt = $this->conn->prepare(
                    'INSERT INTO project_update_log_files (Update_Log_ID, File) VALUES (?, ?)'
                );
                if (!$fStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                foreach ($fileNames as $fn) {
                    $fStmt->bind_param('is', $logId, $fn);
                    if (!$fStmt->execute()) throw new Exception('exec file insert: ' . $fStmt->error);
                }
                $fStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::submitForReview error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get projects for a client by project status from the project table (joined with post + provider + category).
     * Returns rows in the same shape as PostModel::getRequestPosts() so existing JS card renderers work.
     */
    public function getClientProjectsByStatus(
        int $clientId,
        string $projectStatus,
        string $sort = 'date_desc',
        string $search = ''
    ): array {
        $allowed = ['ongoing', 'pending-review', 'completed'];
        if (!in_array($projectStatus, $allowed, true)) {
            return [];
        }

        $query = "SELECT p.*,
                         CONCAT(pr.First_Name, ' ', pr.Last_Name) AS Provider_Name,
                         pr.Profile_Picture AS Provider_Picture,
                         pr.Rating AS Provider_Rating,
                         COALESCE(proj.Progress, 0) AS Progress,
                         proj.Started_At, proj.Ended_At,
                         cat.Name AS Category_Name,
                         proj.Project_ID,
                         proj.Project_Status
                  FROM project proj
                  JOIN post p ON p.Post_ID = proj.Post_ID
                  LEFT JOIN provider pr ON p.Provider_ID = pr.Provider_ID
                  LEFT JOIN category cat ON p.Category_ID = cat.Category_ID
                  WHERE p.Client_ID = ? AND proj.Project_Status = ?";
        $types  = 'is';
        $params = [$clientId, $projectStatus];

        if (!empty($search)) {
            $query .= " AND (p.Title LIKE ? OR p.Description LIKE ?)";
            $types .= 'ss';
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $orderBy = 'COALESCE(proj.Ended_At, proj.Started_At) DESC';
        switch ($sort) {
            case 'date_asc':
                $orderBy = 'COALESCE(proj.Ended_At, proj.Started_At) ASC';
                break;
            case 'date_desc':
                $orderBy = 'COALESCE(proj.Ended_At, proj.Started_At) DESC';
                break;
            case 'price_asc':
                $orderBy = 'p.Requesting_Price ASC';
                break;
            case 'price_desc':
                $orderBy = 'p.Requesting_Price DESC';
                break;
        }
        $query .= " ORDER BY $orderBy";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log('getClientProjectsByStatus prepare: ' . $this->conn->error);
            return [];
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $this->applyBidTermsToProjectRows($rows);
    }

    public function getOngoingProjectsForClient(int $clientId, string $sort = 'date_desc', string $search = ''): array
    {
        return $this->getClientProjectsByStatus($clientId, 'ongoing', $sort, $search);
    }

    public function getPendingReviewProjectsForClient(int $clientId, string $sort = 'date_desc', string $search = ''): array
    {
        return $this->getClientProjectsByStatus($clientId, 'pending-review', $sort, $search);
    }

    public function getCompletedProjectsForClient(int $clientId, string $sort = 'date_desc', string $search = ''): array
    {
        return $this->getClientProjectsByStatus($clientId, 'completed', $sort, $search);
    }

    /* ------------------------------------------------------------------
     *  Reviews
     * ------------------------------------------------------------------ */

    /**
     * Fetch all reviews for a given project (both client and provider).
     */
    public function getReviewsByProjectId(int $projectId): array
    {
        $hasRatedBy = $this->hasColumn('reviews', 'Rated_By');
        $ratedBySelect = $hasRatedBy ? 'r.Rated_By' : 'NULL AS Rated_By';

        $sql = "SELECT r.Review_ID, r.Title, r.Description, r.Rating, r.Left_At, {$ratedBySelect}
            FROM reviews r
            WHERE r.Project_ID = ?
            ORDER BY r.Left_At ASC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getReviewsByProjectId prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($reviews)) return [];

        // Attach files
        $fileMap = [];
        if ($this->hasTable('reviews_files')) {
            $reviewIds = array_column($reviews, 'Review_ID');
            $ph = implode(',', array_fill(0, count($reviewIds), '?'));
            $fStmt = $this->conn->prepare("SELECT Review_ID, File FROM reviews_files WHERE Review_ID IN ($ph)");
            if ($fStmt) {
                $fStmt->bind_param(str_repeat('i', count($reviewIds)), ...$reviewIds);
                $fStmt->execute();
                foreach ($fStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                    $fileMap[$f['Review_ID']][] = $f['File'];
                }
                $fStmt->close();
            }
        }
        foreach ($reviews as &$r) {
            $r['files'] = $fileMap[$r['Review_ID']] ?? [];
        }
        unset($r);
        return $reviews;
    }

    /**
     * Fetch all reviews for a project by Post_ID.
     */
    public function getReviewsByPostId(int $postId): array
    {
        $hasRatedBy = $this->hasColumn('reviews', 'Rated_By');
        $ratedBySelect = $hasRatedBy ? 'r.Rated_By' : 'NULL AS Rated_By';

        $sql = "SELECT r.Review_ID, r.Title, r.Description, r.Rating, r.Left_At, {$ratedBySelect}
            FROM reviews r
            JOIN project proj ON proj.Project_ID = r.Project_ID
            WHERE proj.Post_ID = ?
            ORDER BY r.Left_At ASC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getReviewsByPostId prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($reviews)) return [];

        // Attach files
        $fileMap = [];
        if ($this->hasTable('reviews_files')) {
            $reviewIds = array_column($reviews, 'Review_ID');
            $ph = implode(',', array_fill(0, count($reviewIds), '?'));
            $fStmt = $this->conn->prepare("SELECT Review_ID, File FROM reviews_files WHERE Review_ID IN ($ph)");
            if ($fStmt) {
                $fStmt->bind_param(str_repeat('i', count($reviewIds)), ...$reviewIds);
                $fStmt->execute();
                foreach ($fStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                    $fileMap[$f['Review_ID']][] = $f['File'];
                }
                $fStmt->close();
            }
        }
        foreach ($reviews as &$r) {
            $r['files'] = $fileMap[$r['Review_ID']] ?? [];
        }
        unset($r);
        return $reviews;
    }

    /**
     * Provider leaves a review for the client on a completed project.
     */
    public function addProviderReview(int $providerId, int $postId, int $rating, string $title, string $description): bool
    {
        $this->conn->begin_transaction();
        $hasRatedBy = $this->hasColumn('reviews', 'Rated_By');

        try {
            // Verify: project is completed AND belongs to this provider
            $chk = $this->conn->prepare(
                'SELECT proj.Project_ID FROM project proj
                 JOIN post p ON p.Post_ID = proj.Post_ID
                 WHERE proj.Post_ID = ? AND proj.Project_Status = "completed" AND p.Provider_ID = ?
                 LIMIT 1'
            );
            if (!$chk) throw new Exception('addProviderReview check prepare: ' . $this->conn->error);
            $chk->bind_param('ii', $postId, $providerId);
            $chk->execute();
            $row = $chk->get_result()->fetch_assoc();
            $chk->close();
            if (!$row) throw new Exception('Project not found or not yours.');

            $projectId = (int) $row['Project_ID'];

            // Ensure provider hasn't already reviewed
            $dupSql = $hasRatedBy
                ? 'SELECT Review_ID FROM reviews WHERE Project_ID = ? AND Rated_By = "Provider" LIMIT 1'
                : 'SELECT Review_ID FROM reviews WHERE Project_ID = ? LIMIT 1';
            $dupChk = $this->conn->prepare($dupSql);
            if (!$dupChk) throw new Exception('addProviderReview dup prepare: ' . $this->conn->error);
            $dupChk->bind_param('i', $projectId);
            $dupChk->execute();
            $existing = $dupChk->get_result()->fetch_assoc();
            $dupChk->close();
            if ($existing) throw new Exception('You have already reviewed this project.');

            // Get next Review_ID
            $idStmt = $this->conn->prepare('SELECT COALESCE(MAX(Review_ID), 0) + 1 AS next_id FROM reviews FOR UPDATE');
            if (!$idStmt) throw new Exception('addProviderReview id prepare: ' . $this->conn->error);
            $idStmt->execute();
            $reviewId = (int) $idStmt->get_result()->fetch_assoc()['next_id'];
            $idStmt->close();

            $titleVal = $title !== '' ? $title : null;
            $descVal  = $description !== '' ? $description : null;
            $ratedBy  = 'Provider';

            $insSql = $hasRatedBy
                ? 'INSERT INTO reviews (Review_ID, Title, Description, Rating, Left_At, Project_ID, Rated_By)
                   VALUES (?, ?, ?, ?, NOW(), ?, ?)'
                : 'INSERT INTO reviews (Review_ID, Title, Description, Rating, Left_At, Project_ID)
                   VALUES (?, ?, ?, ?, NOW(), ?)';
            $ins = $this->conn->prepare($insSql);
            if (!$ins) throw new Exception('addProviderReview ins prepare: ' . $this->conn->error);
            if ($hasRatedBy) {
                $ins->bind_param('ississ', $reviewId, $titleVal, $descVal, $rating, $projectId, $ratedBy);
            } else {
                $ins->bind_param('issii', $reviewId, $titleVal, $descVal, $rating, $projectId);
            }
            if (!$ins->execute()) throw new Exception('addProviderReview ins exec: ' . $ins->error);
            $ins->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            throw $e;
        }
    }

    /**
     * Completed projects for provider — includes whether provider has reviewed.
     */
    public function getCompletedProjectsForProvider(int $providerId, int $page = 1, int $limit = 10): array
    {
        $page   = max(1, $page);
        $limit  = max(1, min($limit, 100));
        $offset = ($page - 1) * $limit;

        $countSql = "SELECT COUNT(*) AS total
                     FROM project proj
                     JOIN post p ON p.Post_ID = proj.Post_ID
                     WHERE proj.Project_Status = 'completed' AND p.Provider_ID = ?";

        $countStmt = $this->conn->prepare($countSql);
        if (!$countStmt) {
            error_log('getCompletedProjectsForProvider count: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }
        $countStmt->bind_param('i', $providerId);
        $countStmt->execute();
        $totalRecords = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
        $countStmt->close();

        $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $limit) : 0;

        $hasRatedBy = $this->hasColumn('reviews', 'Rated_By');
        $providerReviewedSelect = $hasRatedBy
            ? "(SELECT COUNT(*) FROM reviews rv WHERE rv.Project_ID = proj.Project_ID AND rv.Rated_By = 'Provider') AS provider_has_reviewed"
            : '0 AS provider_has_reviewed';

        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    cat.Name AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture,
                    {$providerReviewedSelect}
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl ON cl.Client_ID = p.Client_ID
                WHERE proj.Project_Status = 'completed' AND p.Provider_ID = ?
                ORDER BY proj.Ended_At DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getCompletedProjectsForProvider prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }
        $stmt->bind_param('iii', $providerId, $limit, $offset);
        if (!$stmt->execute()) {
            error_log('getCompletedProjectsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return [
            'data'         => $data,
            'total'        => $totalRecords,
            'pages'        => $totalPages,
            'current_page' => $page,
        ];
    }

    /**
     * Client approves final submission, marks project as completed, and saves review.
     */
    public function completeProjectByClient(int $clientId, int $postId, float $rating, string $title, string $description, array $reviewFileNames = []): bool
    {
        $hasRatedBy = $this->hasColumn('reviews', 'Rated_By');
        $hasReviewFilesTable = $this->hasTable('reviews_files');

        $idStmt = $this->conn->prepare(
            'SELECT proj.Project_ID, COALESCE(proj.Progress, 0) AS Progress,
                    COALESCE(p.Provider_ID, 0) AS Provider_ID,
                    COALESCE(p.Category_ID, 0) AS Category_ID
             FROM project proj
             JOIN post p ON p.Post_ID = proj.Post_ID
             WHERE proj.Post_ID = ? AND proj.Project_Status = "pending-review" AND p.Client_ID = ?
             LIMIT 1'
        );
        if (!$idStmt) {
            return false;
        }
        $idStmt->bind_param('ii', $postId, $clientId);
        $idStmt->execute();
        $row = $idStmt->get_result()->fetch_assoc();
        $idStmt->close();
        if (!$row) {
            return false;
        }

        $projectId = (int) $row['Project_ID'];
        $progress  = (int) $row['Progress'];
        $providerId = (int) ($row['Provider_ID'] ?? 0);
        $providerCategoryId = $this->resolveProviderCategoryId(
            0,
            $providerId,
            (int) ($row['Category_ID'] ?? 0)
        );
        $projectRating = max(0.5, min(5.0, $rating));
        $projectRatingPercent = round($projectRating * 20, 1);

        $this->conn->begin_transaction();
        try {
            // Mark project completed
            $upStmt = $this->conn->prepare(
                'UPDATE project
                 SET Project_Status = "completed",
                     Ended_At = COALESCE(Ended_At, NOW())
                 WHERE Post_ID = ?'
            );
            if (!$upStmt) {
                throw new Exception('completeProjectByClient prepare status: ' . $this->conn->error);
            }
            $upStmt->bind_param('i', $postId);
            if (!$upStmt->execute()) {
                throw new Exception('completeProjectByClient exec status: ' . $upStmt->error);
            }
            $upStmt->close();

            if (!$this->releasePaymentForProject($projectId)) {
                throw new Exception('completeProjectByClient payment release failed');
            }

            if (!$this->applyEarningsFromProjectPayment($projectId, $providerCategoryId, $providerId)) {
                throw new Exception('completeProjectByClient earnings update failed');
            }

            // Log the status change
            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES (?, ?, NOW(), ?, 0, ?, "completed")'
            );
            if (!$logStmt) {
                throw new Exception('completeProjectByClient prepare log: ' . $this->conn->error);
            }
            $completionTitle = 'Payment Released & Project Completed';
            $completionDescription = sprintf(
                'Client approved the final submission, rated this project %.1f/5, and payment was released to the provider.',
                $projectRating
            );
            $logStmt->bind_param('ssii', $completionTitle, $completionDescription, $projectId, $progress);
            if (!$logStmt->execute()) {
                throw new Exception('completeProjectByClient exec log: ' . $logStmt->error);
            }
            $logStmt->close();

            // Get next Review_ID (table has no AUTO_INCREMENT)
            $idRow = $this->conn->query('SELECT COALESCE(MAX(Review_ID), 0) + 1 AS next_id FROM reviews FOR UPDATE');
            if (!$idRow) {
                throw new Exception('completeProjectByClient next review id: ' . $this->conn->error);
            }
            $reviewId = (int) ($idRow->fetch_assoc()['next_id'] ?? 1);

            // Insert review
            $ratedBy  = 'Client';
            $reviewInsertSql = $hasRatedBy
                ? 'INSERT INTO reviews (Review_ID, Title, Description, Rating, Left_At, Project_ID, Rated_By)
                   VALUES (?, ?, ?, ?, NOW(), ?, ?)'
                : 'INSERT INTO reviews (Review_ID, Title, Description, Rating, Left_At, Project_ID)
                   VALUES (?, ?, ?, ?, NOW(), ?)';
            $revStmt  = $this->conn->prepare($reviewInsertSql);
            if (!$revStmt) {
                throw new Exception('completeProjectByClient prepare review: ' . $this->conn->error);
            }
            $titleVal = $title !== '' ? $title : null;
            $descVal  = $description !== '' ? $description : null;
            if ($hasRatedBy) {
                $revStmt->bind_param('issdis', $reviewId, $titleVal, $descVal, $projectRatingPercent, $projectId, $ratedBy);
            } else {
                $revStmt->bind_param('issdi', $reviewId, $titleVal, $descVal, $projectRatingPercent, $projectId);
            }
            if (!$revStmt->execute()) {
                throw new Exception('completeProjectByClient exec review: ' . $revStmt->error);
            }
            $revStmt->close();

            $this->refreshProviderCategoryRating($providerCategoryId);
            if ($providerId > 0) {
                $this->refreshProviderRatingFromCategories($providerId);
            }

            // Insert review files
            if (!empty($reviewFileNames) && $hasReviewFilesTable) {
                $rfStmt = $this->conn->prepare('INSERT INTO reviews_files (Review_ID, File) VALUES (?, ?)');
                if (!$rfStmt) {
                    throw new Exception('completeProjectByClient prepare review_files: ' . $this->conn->error);
                }
                foreach ($reviewFileNames as $fileName) {
                    $rfStmt->bind_param('is', $reviewId, $fileName);
                    if (!$rfStmt->execute()) {
                        throw new Exception('completeProjectByClient exec review_files: ' . $rfStmt->error);
                    }
                }
                $rfStmt->close();
            } elseif (!empty($reviewFileNames) && !$hasReviewFilesTable) {
                error_log('ProjectModel::completeProjectByClient warning: reviews_files table not found; skipping review file records');
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::completeProjectByClient error: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Client requests changes and moves project back to ongoing with an optional file set.
     */
    public function moveProjectBackToOngoingByClient(int $clientId, int $postId, string $reason, array $fileNames = []): bool
    {
        $idStmt = $this->conn->prepare(
            'SELECT proj.Project_ID, COALESCE(proj.Progress, 0) AS Progress
             FROM project proj
             JOIN post p ON p.Post_ID = proj.Post_ID
             WHERE proj.Post_ID = ? AND proj.Project_Status = "pending-review" AND p.Client_ID = ?
             LIMIT 1'
        );
        if (!$idStmt) {
            return false;
        }
        $idStmt->bind_param('ii', $postId, $clientId);
        $idStmt->execute();
        $row = $idStmt->get_result()->fetch_assoc();
        $idStmt->close();
        if (!$row) {
            return false;
        }

        $projectId = (int) $row['Project_ID'];
        $progress  = (int) $row['Progress'];

        $this->conn->begin_transaction();
        try {
            $upStmt = $this->conn->prepare('UPDATE project SET Project_Status = "ongoing", Ended_At = NULL WHERE Post_ID = ?');
            if (!$upStmt) {
                throw new Exception('moveProjectBackToOngoingByClient prepare status: ' . $this->conn->error);
            }
            $upStmt->bind_param('i', $postId);
            if (!$upStmt->execute()) {
                throw new Exception('moveProjectBackToOngoingByClient exec status: ' . $upStmt->error);
            }
            $upStmt->close();

            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES ("Changes Requested by Client", ?, NOW(), ?, 0, ?, "ongoing")'
            );
            if (!$logStmt) {
                throw new Exception('moveProjectBackToOngoingByClient prepare log: ' . $this->conn->error);
            }
            $logStmt->bind_param('sii', $reason, $projectId, $progress);
            if (!$logStmt->execute()) {
                throw new Exception('moveProjectBackToOngoingByClient exec log: ' . $logStmt->error);
            }
            $logId = (int) $this->conn->insert_id;
            $logStmt->close();

            if (!empty($fileNames)) {
                $fStmt = $this->conn->prepare('INSERT INTO project_update_log_files (Update_Log_ID, File) VALUES (?, ?)');
                if (!$fStmt) {
                    throw new Exception('moveProjectBackToOngoingByClient prepare file insert: ' . $this->conn->error);
                }
                foreach ($fileNames as $fn) {
                    $fStmt->bind_param('is', $logId, $fn);
                    if (!$fStmt->execute()) {
                        throw new Exception('moveProjectBackToOngoingByClient exec file insert: ' . $fStmt->error);
                    }
                }
                $fStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::moveProjectBackToOngoingByClient error: ' . $e->getMessage());
            return false;
        }
    }

}
