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
                FROM Bids
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
        $sql = "SELECT 1 FROM Bids WHERE Post_ID = ? AND Provider_ID = ? LIMIT 1";

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
        $sql = "SELECT COUNT(*) AS bid_count FROM Bids WHERE Post_ID = ?";

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
        $sql = "SELECT Post_ID, COUNT(*) AS bid_count FROM Bids WHERE Post_ID IN ($placeholders) GROUP BY Post_ID";

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
}
