<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/ProviderModel.php';
require_once __DIR__ . '/PostModel.php';
require_once __DIR__ . '/ReviewModel.php';

class BidModel extends Database
{
    private ProviderModel $providerModel;
    private PostModel $postModel;
    private ReviewModel $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->providerModel = new ProviderModel();
        $this->postModel = new PostModel(false);
        $this->reviewModel = new ReviewModel();
    }

    public function getBidsForPost(int $postId): array
    {
        $sql = "SELECT
                    Bid_ID,
                    Comment,
                    Amount,
                    Created_At,
                    Est_Date,
                    Status,
                    Post_ID,
                    Provider_ID
                FROM bids
                WHERE Post_ID = ?
                ORDER BY Created_At DESC";

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

        $providerCache = [];
        $ratingCache = [];
        $providerPostIdsCache = [];

        foreach ($rows as &$row) {
            $providerId = (int) ($row['Provider_ID'] ?? 0);

            if ($providerId > 0) {
                if (!array_key_exists($providerId, $providerCache)) {
                    $providerCache[$providerId] = $this->providerModel->getProviderById($providerId);
                }

                $provider = $providerCache[$providerId] ?? null;
                $row['First_Name'] = $provider['First_Name'] ?? null;
                $row['Last_Name'] = $provider['Last_Name'] ?? null;
                $row['Profile_Picture'] = $provider['Profile_Picture'] ?? null;

                if (!array_key_exists($providerId, $ratingCache)) {
                    if (!array_key_exists($providerId, $providerPostIdsCache)) {
                        $providerPostIdsCache[$providerId] = $this->postModel->getPostIdsByProviderId($providerId);
                    }

                    $ratingCache[$providerId] = $this->reviewModel->getAverageRatingByPostIds($providerPostIdsCache[$providerId]);
                }

                $row['Provider_Rating'] = $ratingCache[$providerId];
            } else {
                $row['First_Name'] = null;
                $row['Last_Name'] = null;
                $row['Profile_Picture'] = null;
                $row['Provider_Rating'] = null;
            }
        }

        unset($row);
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

class BidModel extends Database
{
    private const STATUS_ACTIVE = 'active';
    private const STATUS_ACCEPTED = 'accepted';
    private const STATUS_REJECTED = 'rejected';

    public function getProviderBidsByStatus($providerId, $status)
    {
        $bids = $this->getProviderBids($providerId);

        return array_values(array_filter($bids, function ($bid) use ($status) {
            return $bid['statusKey'] === $status;
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
            $key = $bid['statusKey'];
            if (array_key_exists($key, $grouped)) {
                $grouped[$key][] = $bid;
            }
        }

        return $grouped;
    }

    public function canEditBid(array $bid)
    {
        return ($bid['statusKey'] ?? '') === self::STATUS_ACTIVE;
    }

    public function canWithdrawBid(array $bid)
    {
        return ($bid['statusKey'] ?? '') === self::STATUS_ACTIVE;
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

    private function getProviderBids($providerId)
    {
        return [
            [
                'providerId' => $providerId, // Provider_ID in bids
                'client' => 'Nadia Perera', // First_Name + Last_Name from client <- Client_ID in post <- Post_ID in bids
                'title' => 'Portfolio Website + CMS', // Title in post
                'bidAmount' => '$550', // Amount in bids
                'timeline' => '8 days', // change to appropriate metric
                'bidDate' => '2h ago', // Created_At from bids
                'category' => 'Web Development', // Name from category table based on Category_ID in post
                'description' => 'Custom responsive portfolio with blog/case-study CMS and deployment support.',
                'statusKey' => self::STATUS_ACTIVE,
                'statusClass' => 'status-pending',
                'statusLabel' => 'Active (Pending Client Decision)',
                'projectRef' => 'BID-2026-001',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Tharushi De Silva',
                'title' => 'WordPress SEO Optimization',
                'bidAmount' => '$460',
                'timeline' => '12 days',
                'bidDate' => '1d ago',
                'category' => 'SEO',
                'description' => 'Technical audit, on-page optimization and speed improvements for better rankings.',
                'statusKey' => self::STATUS_ACTIVE,
                'statusClass' => 'status-pending',
                'statusLabel' => 'Active (Pending Client Decision)',
                'projectRef' => 'BID-2026-004',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Isuru Fernando',
                'title' => 'Brand Kit for Startup Launch',
                'bidAmount' => '$340',
                'timeline' => '5 days',
                'bidDate' => '3d ago',
                'category' => 'Graphic Design',
                'description' => 'Logo, color system and typography package prepared for social and print use.',
                'statusKey' => self::STATUS_ACCEPTED,
                'statusClass' => 'status-progress',
                'statusLabel' => 'Accepted',
                'projectRef' => 'BID-2026-009',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Kavindu Jayasekara',
                'title' => 'Landing Page Copy Refresh',
                'bidAmount' => '$190',
                'timeline' => '4 days',
                'bidDate' => '4d ago',
                'category' => 'Content Writing',
                'description' => 'Conversion-focused rewrite for hero, services and CTA blocks.',
                'statusKey' => self::STATUS_REJECTED,
                'statusClass' => 'status-complete',
                'statusLabel' => 'Rejected',
                'projectRef' => 'BID-2026-012',
            ],
        ];
    }
}
