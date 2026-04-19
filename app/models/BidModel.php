<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/ProviderModel.php';
require_once __DIR__ . '/PostModel.php';
require_once __DIR__ . '/ReviewModel.php';

class BidModel extends Database
{
    private const STATUS_ACTIVE = 'active';
    private const STATUS_ACCEPTED = 'accepted';
    private const STATUS_REJECTED = 'rejected';
    private const STATUS_DELETED = 'deleted';

    public function __construct()
    {
        parent::__construct();
    }

    public function getBidsForPost(int $postId): array
    {
        $sql = "
            SELECT
                b.Bid_ID,
                b.Comment,
                b.Amount,
                b.Created_At,
                b.Duration,
                b.Status,
                b.Post_ID,
                b.Provider_ID,
                p.First_Name,
                p.Last_Name,
                p.Profile_Picture,
                pr.Provider_Rating
            FROM bids b
            LEFT JOIN provider p
                ON p.Provider_ID = b.Provider_ID
            LEFT JOIN (
                SELECT
                    po.Provider_ID,
                    ROUND(AVG(r.Rating), 1) AS Provider_Rating
                FROM reviews r
                INNER JOIN project pj
                    ON pj.Project_ID = r.Project_ID
                INNER JOIN post po
                    ON po.Post_ID = pj.Post_ID
                WHERE po.Provider_ID IS NOT NULL
                GROUP BY po.Provider_ID
            ) pr
                ON pr.Provider_ID = b.Provider_ID
            WHERE b.Post_ID = ?
            ORDER BY b.Created_At DESC
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::getBidsForPost prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('BidModel::getBidsForPost exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function providerHasBidForPost(int $postId, int $providerId): bool
    {
        $sql = "SELECT 1 FROM bids WHERE Post_ID = ? AND Provider_ID = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::providerHasBidForPost prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('ii', $postId, $providerId);

        if (!$stmt->execute()) {
            error_log('BidModel::providerHasBidForPost exec: ' . $stmt->error);
            $stmt->close();
            return false;
        }

        $exists = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $exists;
    }

    public function countByPostId(int $postId): int
    {
        $sql = "SELECT COUNT(*) AS bid_count FROM bids WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::countByPostId prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('BidModel::countByPostId exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['bid_count'] ?? 0);
    }

    public function getCountsByPostIds(array $postIds): array
    {
        if (empty($postIds)) {
            return [];
        }

        $postIds = array_values(array_filter(array_map('intval', $postIds), function ($id) {
            return $id > 0;
        }));

        if (empty($postIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $types = str_repeat('i', count($postIds));
        $sql = "SELECT Post_ID, COUNT(*) AS bid_count FROM bids WHERE Post_ID IN ($placeholders) GROUP BY Post_ID";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::getCountsByPostIds prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param($types, ...$postIds);

        if (!$stmt->execute()) {
            error_log('BidModel::getCountsByPostIds exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $counts = [];
        foreach ($rows as $row) {
            $counts[(int) $row['Post_ID']] = (int) $row['bid_count'];
        }

        return $counts;
    }

    public function getProviderBidsByStatus($providerId, $status)
    {
        $bids = $this->getProviderBids($providerId);

        return array_values(array_filter($bids, function ($bid) use ($status) {
            return $bid['Status_Key'] === $status;
        }));
    }

    public function getProviderBidsGrouped($providerId)
    {
        $all = $this->getProviderBids($providerId);

        $grouped = [
            self::STATUS_ACTIVE => [],
            self::STATUS_ACCEPTED => [],
            self::STATUS_REJECTED => [],
        ];

        foreach ($all as $bid) {
            $key = $bid['Status_Key'];
            if (array_key_exists($key, $grouped)) {
                $grouped[$key][] = $bid;
            }
        }

        return $grouped;
    }

    public function canEditBid(array $bid)
    {
        return ($bid['Status_Key'] ?? '') === self::STATUS_ACTIVE;
    }

    public function canWithdrawBid(array $bid)
    {
        return ($bid['Status_Key'] ?? '') === self::STATUS_ACTIVE;
    }

    public function submitBid($providerId, $postId, $amount, $comment, $durationDays)
    {
        // add entry to database, bids table with status 'active' and current timestamp for created_at
        $sql = "INSERT INTO bids (Provider_ID, Post_ID, Amount, Comment, Duration, Status, Created_At) VALUES (?, ?, ?, ?, ?, 'active', NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::submitBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $durationDays = (int) $durationDays;
        $stmt->bind_param('iidsi', $providerId, $postId, $amount, $comment, $durationDays);
        if (!$stmt->execute()) {
            error_log('BidModel::submitBid exec: ' . $stmt->error);
            $stmt->close();
            return ['success' => false, 'error' => 'Database error'];
        }
        $newBidId = $stmt->insert_id;
        $stmt->close();
        // return success response with new bid details (including generated bid ID and reference)
        return ['success' => true, 'bidId' => $newBidId];
    }

    public function editBidForProvider($providerId, $bidRef, array $newBidData)
    {
        $amount = (float) ($newBidData['amount'] ?? 0);
        $comment = (string) ($newBidData['comment'] ?? '');
        $durationDays = (int) ($newBidData['durationDays'] ?? 0);

        return $this->updateBidForProvider((int) $providerId, (int) $bidRef, $amount, $comment, $durationDays);
    }

    public function updateBidForProvider(int $providerId, int $bidId, float $amount, string $comment, int $durationDays): array
    {
        if ($providerId <= 0 || $bidId <= 0) {
            return [
                'success' => false,
                'errorCode' => 'invalid_input',
                'message' => 'Invalid provider or bid id',
            ];
        }

        if ($amount <= 0 || $durationDays <= 0) {
            return [
                'success' => false,
                'errorCode' => 'invalid_input',
                'message' => 'Invalid amount or duration',
            ];
        }

        $comment = trim($comment);

        $editable = $this->getEditableBidRecord($providerId, $bidId);
        if ($editable !== null) {
            if (!$editable['canEdit']) {
                return [
                    'success' => false,
                    'errorCode' => 'not_editable',
                    'message' => 'Only active bids can be edited',
                ];
            }

            $sql = 'UPDATE bids SET Amount = ?, Comment = ?, Duration = ? WHERE Bid_ID = ? AND Provider_ID = ?';
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log('BidModel::updateBidForProvider prepare: ' . $this->conn->error);
                return [
                    'success' => false,
                    'errorCode' => 'db_prepare_error',
                    'message' => 'Database error',
                ];
            }

            $stmt->bind_param('dsiii', $amount, $comment, $durationDays, $bidId, $providerId);
            if (!$stmt->execute()) {
                error_log('BidModel::updateBidForProvider exec: ' . $stmt->error);
                $stmt->close();
                return [
                    'success' => false,
                    'errorCode' => 'db_exec_error',
                    'message' => 'Database error',
                ];
            }

            $stmt->close();
            return [
                'success' => true,
                'bidId' => $bidId,
                'amount' => $amount,
                'comment' => $comment,
                'durationDays' => $durationDays,
                'updatedAt' => date('Y-m-d H:i:s'),
            ];
        }

        // Fallback for current dummy-data mode so UI flow can be validated end-to-end.
        $providerBids = $this->getProviderBids($providerId);
        $target = null;
        foreach ($providerBids as $bid) {
            if ((int) ($bid['Bid_ID'] ?? 0) === $bidId) {
                $target = $bid;
                break;
            }
        }

        if ($target === null) {
            return [
                'success' => false,
                'errorCode' => 'not_found',
                'message' => 'Bid not found',
            ];
        }

        $statusLower = strtolower((string) ($target['Status'] ?? ''));
        if (!in_array($statusLower, ['active', 'pending', 'open'], true)) {
            return [
                'success' => false,
                'errorCode' => 'not_editable',
                'message' => 'Only active bids can be edited',
            ];
        }

        return [
            'success' => true,
            'bidId' => $bidId,
            'amount' => $amount,
            'comment' => $comment,
            'durationDays' => $durationDays,
            'updatedAt' => date('Y-m-d H:i:s'),
            'isSimulated' => true,
        ];
    }

    public function withdrawBidForProvider($providerId, $bidRef)
    {
        $providerId = (int) $providerId;
        $bidId = (int) $bidRef;

        if ($providerId <= 0 || $bidId <= 0) {
            return [
                'success' => false,
                'errorCode' => 'invalid_input',
                'message' => 'Invalid provider or bid id',
            ];
        }

        $ownedBid = $this->getProviderBidRecord($providerId, $bidId);
        if ($ownedBid !== null) {
            $sql = 'UPDATE bids SET Status = ? WHERE Bid_ID = ? AND Provider_ID = ?';
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log('BidModel::withdrawBidForProvider prepare: ' . $this->conn->error);
                return [
                    'success' => false,
                    'errorCode' => 'db_prepare_error',
                    'message' => 'Database error',
                ];
            }

            $deletedStatus = self::STATUS_DELETED;
            $stmt->bind_param('sii', $deletedStatus, $bidId, $providerId);
            if (!$stmt->execute()) {
                error_log('BidModel::withdrawBidForProvider exec: ' . $stmt->error);
                $stmt->close();
                return [
                    'success' => false,
                    'errorCode' => 'db_exec_error',
                    'message' => 'Database error',
                ];
            }

            $stmt->close();
            return [
                'success' => true,
                'bidId' => $bidId,
                'status' => self::STATUS_DELETED,
            ];
        }

        // Fallback for dummy-data mode.
        $providerBids = $this->getProviderBids($providerId);
        $target = null;
        foreach ($providerBids as $bid) {
            if ((int) ($bid['Bid_ID'] ?? 0) === $bidId) {
                $target = $bid;
                break;
            }
        }

        if ($target === null) {
            return [
                'success' => false,
                'errorCode' => 'not_found',
                'message' => 'Bid not found',
            ];
        }

        return [
            'success' => true,
            'bidId' => $bidId,
            'status' => self::STATUS_DELETED,
            'isSimulated' => true,
        ];
    }

    public function getProviderBids($providerId)
    {
        // TODO: Uncomment when ready to use actual database
        $sql = "SELECT 
                    b.Bid_ID,
                    b.Comment,
                    b.Amount,
                    b.Created_At,
                    b.Duration,
                    b.Status,
                    b.Post_ID,
                    p.Title,
                    p.Description AS Post_Description,
                    p.Category_ID,
                    p.Post_Status,
                    p.Request_Status AS Post_Request_Status,
                    p.Provider_ID AS Post_Provider_ID,
                    c.First_Name AS Client_First_Name,
                    c.Last_Name AS Client_Last_Name,
                    cat.Name AS Category_Name
                FROM bids b
                INNER JOIN post p ON b.Post_ID = p.Post_ID
                INNER JOIN client c ON p.Client_ID = c.Client_ID
                INNER JOIN category cat ON p.Category_ID = cat.Category_ID
                WHERE b.Provider_ID = ?
                ORDER BY b.Created_At DESC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::getProviderBids prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $providerId);
        if (!$stmt->execute()) {
            error_log('BidModel::getProviderBids exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Format data with helper functions
        foreach ($rows as &$row) {
            $row['Client_Name'] = formatFullName($row['Client_First_Name'], $row['Client_Last_Name']);
            $row['Bid_Amount'] = formatCurrency($row['Amount']);
            $durationDays = (int) ($row['Duration'] ?? 0);
            $row['Timeline'] = $durationDays . ' day' . ($durationDays === 1 ? '' : 's');
            $row['Bid_Date'] = timeAgo($row['Created_At']);
            $row['Bid_Ref'] = 'BID-' . str_pad($row['Bid_ID'], 6, '0', STR_PAD_LEFT);
            
            // Normalize status for filtering (lowercase for consistency)
            $statusLower = strtolower($row['Status'] ?? '');
            if (in_array($statusLower, ['active', 'pending', 'open'])) {
                $row['Status_Key'] = self::STATUS_ACTIVE;
            } elseif (in_array($statusLower, ['accepted', 'approved'])) {
                $row['Status_Key'] = self::STATUS_ACCEPTED;
            } elseif (in_array($statusLower, ['rejected', 'declined'])) {
                $row['Status_Key'] = self::STATUS_REJECTED;
            } else {
                $row['Status_Key'] = $statusLower;
            }
        }
        unset($row);

        return $rows;

        // Dummy data for testing
        // return [
        //     [
        //         'Bid_ID' => 1,
        //         'Comment' => 'Custom responsive portfolio with blog/case-study CMS and deployment support.',
        //         'Amount' => 550,
        //         'Created_At' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        //         'Est_Date' => date('Y-m-d', strtotime('+8 days')),
        //         'Status' => 'active',
        //         'Post_ID' => 1,
        //         'Title' => 'Portfolio Website + CMS',
        //         'Post_Description' => 'Need a professional portfolio website with CMS capabilities.',
        //         'Category_ID' => 1,
        //         'Post_Status' => 'published',
        //         'Post_Request_Status' => 'pending',
        //         'Post_Provider_ID' => null,
        //         'Client_First_Name' => 'Nadia',
        //         'Client_Last_Name' => 'Perera',
        //         'Category_Name' => 'Web Development',
        //         // data below is formatted will be formatted in the controller/view normally, but included here for testing purposes
        //         'Duration' => 8,
        //     ],
        //     [
        //         'Bid_ID' => 2,
        //         'Comment' => 'Technical audit, on-page optimization and speed improvements for better rankings.',
        //         'Amount' => 460,
        //         'Created_At' => date('Y-m-d H:i:s', strtotime('-1 day')),
        //         'Est_Date' => date('Y-m-d', strtotime('+12 days')),
        //         'Status' => 'active',
        //         'Post_ID' => 2,
        //         'Title' => 'WordPress SEO Optimization',
        //         'Post_Description' => 'Need SEO expert to optimize WordPress site.',
        //         'Category_ID' => 2,
        //         'Post_Status' => 'published',
        //         'Post_Request_Status' => 'pending',
        //         'Post_Provider_ID' => null,
        //         'Client_First_Name' => 'Tharushi',
        //         'Client_Last_Name' => 'De Silva',
        //         'Category_Name' => 'SEO',
        //         'Duration' => 12,
        //     ],
        //     [
        //         'Bid_ID' => 3,
        //         'Comment' => 'Logo, color system and typography package prepared for social and print use.',
        //         'Amount' => 340,
        //         'Created_At' => date('Y-m-d H:i:s', strtotime('-3 days')),
        //         'Est_Date' => date('Y-m-d', strtotime('+5 days')),
        //         'Status' => 'accepted',
        //         'Post_ID' => 3,
        //         'Title' => 'Brand Kit for Startup Launch',
        //         'Post_Description' => 'Creating brand identity for new startup.',
        //         'Category_ID' => 3,
        //         'Post_Status' => 'published',
        //         'Post_Request_Status' => 'accepted',
        //         'Post_Provider_ID' => (int) $providerId,
        //         'Client_First_Name' => 'Isuru',
        //         'Client_Last_Name' => 'Fernando',
        //         'Category_Name' => 'Graphic Design',
        //         'Duration' => 5,
        //     ],
        //     [
        //         'Bid_ID' => 4,
        //         'Comment' => 'Conversion-focused rewrite for hero, services and CTA blocks.',
        //         'Amount' => 190,
        //         'Created_At' => date('Y-m-d H:i:s', strtotime('-4 days')),
        //         'Est_Date' => date('Y-m-d', strtotime('+4 days')),
        //         'Status' => 'closed',
        //         'Post_ID' => 4,
        //         'Title' => 'Landing Page Copy Refresh',
        //         'Post_Description' => 'Need compelling copy for landing page.',
        //         'Category_ID' => 4,
        //         'Post_Status' => 'expired',
        //         'Post_Request_Status' => 'accepted',
        //         'Post_Provider_ID' => 9999,
        //         'Client_First_Name' => 'Kavindu',
        //         'Client_Last_Name' => 'Jayasekara',
        //         'Category_Name' => 'Content Writing',
        //         'Duration' => 4,
        //     ],
        // ];
    }

    private function getEditableBidRecord(int $providerId, int $bidId): ?array
    {
        $row = $this->getProviderBidRecord($providerId, $bidId);
        if (!$row) {
            return null;
        }

        $postStatus = strtolower(trim((string) ($row['Post_Status'] ?? '')));
        $requestStatus = strtolower(trim((string) ($row['Post_Request_Status'] ?? '')));
        $postProviderRaw = $row['Post_Provider_ID'] ?? null;
        $postProviderId = ($postProviderRaw === null || $postProviderRaw === '') ? null : (int) $postProviderRaw;
        $bidStatus = strtolower(trim((string) ($row['Bid_Status'] ?? '')));

        $isOpenPost = in_array($postStatus, ['published', 'active'], true);
        $isPendingRequest = $requestStatus === 'pending';
        $isUnassignedPost = $postProviderId === null;
        $isActiveBid = in_array($bidStatus, ['active', 'pending', 'open'], true);

        $row['canEdit'] = $isOpenPost && $isPendingRequest && $isUnassignedPost && $isActiveBid;
        return $row;
    }

    private function getProviderBidRecord(int $providerId, int $bidId): ?array
    {
        $sql = "
            SELECT
                b.Bid_ID,
                b.Provider_ID,
                b.Status AS Bid_Status,
                p.Post_Status,
                p.Request_Status AS Post_Request_Status,
                p.Provider_ID AS Post_Provider_ID
            FROM bids b
            INNER JOIN post p ON p.Post_ID = b.Post_ID
            WHERE b.Bid_ID = ? AND b.Provider_ID = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::getEditableBidRecord prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('ii', $bidId, $providerId);
        if (!$stmt->execute()) {
            error_log('BidModel::getEditableBidRecord exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }
}
