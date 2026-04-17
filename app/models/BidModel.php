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
                b.Est_Date,
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

    public function submitBid($providerId, $postId, $amount, $comment, $estDate)
    {
        // add entry to database, bids table with status 'Active' and current timestamp for created_at
        $sql = "INSERT INTO bids (Provider_ID, Post_ID, Amount, Comment, Est_Date, Status, Created_At) VALUES (?, ?, ?, ?, ?, 'Active', NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('BidModel::submitBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $stmt->bind_param('iiiis', $providerId, $postId, $amount, $comment, $estDate);
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
        // Business rule: edit is implemented as withdraw + create under the hood.
        return [
            'success' => true,
            'providerId' => $providerId,
            'oldBidRef' => $bidRef,
            'operation' => 'withdraw_and_recreate',
            'newBidData' => $newBidData,
        ];
    }

    public function withdrawBidForProvider($providerId, $bidRef)
    {
        // Withdrawn bids are removed from provider list completely.
        return [
            'success' => true,
            'providerId' => $providerId,
            'bidRef' => $bidRef,
            'removedFromList' => true,
        ];
    }

    public function getProviderBids($providerId)
    {
        // TODO: Uncomment when ready to use actual database
        /*
        $sql = "SELECT 
                    b.Bid_ID,
                    b.Comment,
                    b.Amount,
                    b.Created_At,
                    b.Est_Date,
                    b.Status,
                    b.Post_ID,
                    p.Title,
                    p.Description AS Post_Description,
                    p.Category_ID,
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
            $row['Timeline'] = formatDuration($row['Est_Date']);
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
        */

        // Dummy data for testing
        return [
            [
                'Bid_ID' => 1,
                'Comment' => 'Custom responsive portfolio with blog/case-study CMS and deployment support.',
                'Amount' => 550,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'Est_Date' => date('Y-m-d', strtotime('+8 days')),
                'Status' => 'Active',
                'Post_ID' => 1,
                'Title' => 'Portfolio Website + CMS',
                'Post_Description' => 'Need a professional portfolio website with CMS capabilities.',
                'Category_ID' => 1,
                'Client_First_Name' => 'Nadia',
                'Client_Last_Name' => 'Perera',
                'Category_Name' => 'Web Development',
                // data below is formatted will be formatted in the controller/view normally, but included here for testing purposes
                'Duration' => 8 * 24,
            ],
            [
                'Bid_ID' => 2,
                'Comment' => 'Technical audit, on-page optimization and speed improvements for better rankings.',
                'Amount' => 460,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'Est_Date' => date('Y-m-d', strtotime('+12 days')),
                'Status' => 'Active',
                'Post_ID' => 2,
                'Title' => 'WordPress SEO Optimization',
                'Post_Description' => 'Need SEO expert to optimize WordPress site.',
                'Category_ID' => 2,
                'Client_First_Name' => 'Tharushi',
                'Client_Last_Name' => 'De Silva',
                'Category_Name' => 'SEO',
                'Duration' => 12 * 24,
            ],
            [
                'Bid_ID' => 3,
                'Comment' => 'Logo, color system and typography package prepared for social and print use.',
                'Amount' => 340,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'Est_Date' => date('Y-m-d', strtotime('+5 days')),
                'Status' => 'Accepted',
                'Post_ID' => 3,
                'Title' => 'Brand Kit for Startup Launch',
                'Post_Description' => 'Creating brand identity for new startup.',
                'Category_ID' => 3,
                'Client_First_Name' => 'Isuru',
                'Client_Last_Name' => 'Fernando',
                'Category_Name' => 'Graphic Design',
                'Duration' => 5 * 24,
            ],
            [
                'Bid_ID' => 4,
                'Comment' => 'Conversion-focused rewrite for hero, services and CTA blocks.',
                'Amount' => 190,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'Est_Date' => date('Y-m-d', strtotime('+4 days')),
                'Status' => 'Rejected',
                'Post_ID' => 4,
                'Title' => 'Landing Page Copy Refresh',
                'Post_Description' => 'Need compelling copy for landing page.',
                'Category_ID' => 4,
                'Client_First_Name' => 'Kavindu',
                'Client_Last_Name' => 'Jayasekara',
                'Category_Name' => 'Content Writing',
                'Duration' => 4 * 24,
            ],
        ];
    }

    public function getActiveBidsForProvider($providerId) // Request_Status = 'open' AND Post_Provider_ID = null means bid is active and waiting for client action
    {}

    public function getAcceptedBidsForProvider($providerId) // Request_Status = 'pending' || 'accepted' AND Post_Provider_ID = providerId means bid is accepted and waiting for provider to accept or reject the job
    {}

    public function getClosedBidForProvider($providerId) // Request_Status = 'pending' || 'accepted' AND Post_Provider_ID != providerId means bid is no longer viable for the provider, either because client accepted another bid or the post got closed without accepting any bid
    {}
}
