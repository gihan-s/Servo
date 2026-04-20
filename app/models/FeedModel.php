<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/helpers.php';

class FeedModel extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFeedItemsForProvider($providerId = null, $limit = null)
    {
        // Fetch posts open for bidding, LEFT JOIN provider's own bid
        $sql = "SELECT 
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Level,
                    p.Created_At,
                    p.Est_Date AS Deadline,
                    p.Post_Status,
                    p.Request_Status,
                    p.Client_ID,
                    c.First_Name AS Client_First_Name,
                    c.Last_Name AS Client_Last_Name,
                    c.Profile_Picture AS Client_Avatar,
                    cat.Name AS Category_Name,
                    cat.Category_ID,
                    (SELECT COUNT(*) FROM bids WHERE Post_ID = p.Post_ID AND Status = 'active') AS Total_Bids,
                    b.Bid_ID       AS My_Bid_ID,
                    b.Amount       AS My_Bid_Amount,
                    b.Comment      AS My_Bid_Comment,
                    b.Duration     AS My_Bid_Duration,
                    b.Status       AS My_Bid_Status,
                    b.Created_At   AS My_Bid_Created_At
                FROM post p
                INNER JOIN client c ON p.Client_ID = c.Client_ID
                INNER JOIN category cat ON p.Category_ID = cat.Category_ID
                LEFT JOIN bids b ON b.Post_ID = p.Post_ID AND b.Provider_ID = ? AND b.Status = 'active'
                WHERE p.Post_Status = 'active'
                AND p.Post_Type = 'post'
                AND p.Request_Status = 'open'
                AND p.Est_Date > CURDATE()
                AND p.Category_ID IN (
                    SELECT Category_ID FROM provider_categories WHERE Provider_ID = ?
                )
                ORDER BY p.Created_At DESC
                ";

        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::getFeedItemsForProvider prepare: ' . $this->conn->error);
            return [];
        }

        if ($limit !== null) {
            $stmt->bind_param('iii', $providerId, $providerId, $limit);
        } else {
            $stmt->bind_param('ii', $providerId, $providerId);
        }

        if (!$stmt->execute()) {
            error_log('FeedModel::getFeedItemsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($rows as &$row) {
            $row['Client_Name'] = formatFullName($row['Client_First_Name'], $row['Client_Last_Name']);
            $row['Budget']      = formatCurrency($row['Requesting_Price']);
            $row['Deadline']    = formatDuration($row['Deadline']);
            $row['Posted']      = timeAgo($row['Created_At']);
        }
        unset($row);

        return $rows;
    }

    public function updateBid($bidId, $providerId, $amount, $comment, $duration)
    {
        $sql = "UPDATE bids SET Amount = ?, Comment = ?, Duration = ? WHERE Bid_ID = ? AND Provider_ID = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::updateBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $stmt->bind_param('dsiii', $amount, $comment, $duration, $bidId, $providerId);
        if (!$stmt->execute()) {
            error_log('FeedModel::updateBid exec: ' . $stmt->error);
            $stmt->close();
            return ['success' => false, 'error' => 'Database error'];
        }
        $affected = $stmt->affected_rows;
        $stmt->close();
        if ($affected === 0) {
            return ['success' => false, 'error' => 'Bid not found or not authorized'];
        }
        return ['success' => true];
    }

    public function getPostById($postId)
    {
        $sql = "SELECT 
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Created_At,
                    p.Est_Date AS Deadline,
                    p.Post_Status,
                    p.Request_Status,
                    p.Price_Type,
                    p.Level,
                    p.Client_ID,
                    c.First_Name AS Client_First_Name,
                    c.Last_Name AS Client_Last_Name,
                    cat.Name AS Category_Name,
                    cat.Category_ID
                FROM post p
                INNER JOIN client c ON p.Client_ID = c.Client_ID
                INNER JOIN category cat ON p.Category_ID = cat.Category_ID
                WHERE p.Post_ID = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::getPostById prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('i', $postId);
        if (!$stmt->execute()) {
            error_log('FeedModel::getPostById exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }

        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        if ($row) {
            $row['Client_Name'] = formatFullName($row['Client_First_Name'], $row['Client_Last_Name']);
            $row['Budget'] = formatCurrency($row['Requesting_Price']);
            $row['Posted'] = timeAgo($row['Created_At']);
        }

        return $row;
    }

    public function providerHasBidOnPost($providerId, $postId)
    {
        $sql = "SELECT COUNT(*) FROM bids WHERE Provider_ID = ? AND Post_ID = ? AND Status = 'active'";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::providerHasBidOnPost prepare: ' . $this->conn->error);
            return false;
        }
        $stmt->bind_param('ii', $providerId, $postId);
        if (!$stmt->execute()) {
            error_log('FeedModel::providerHasBidOnPost exec: ' . $stmt->error);
            $stmt->close();
            return false;
        }
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function submitBid($providerId, $postId, $amount, $comment, $duration)
    {
        // add entry to database, bids table with status 'active' and current timestamp for created_at
        $sql = "INSERT INTO bids (Provider_ID, Post_ID, Amount, Comment, Duration, Status, Created_At) VALUES (?, ?, ?, ?, ?, 'active', NOW())";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::submitBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $stmt->bind_param('iidsi', $providerId, $postId, $amount, $comment, $duration);
        if (!$stmt->execute()) {
            error_log('FeedModel::submitBid exec: ' . $stmt->error);
            $stmt->close();
            return ['success' => false, 'error' => 'Database error'];
        }
        $newBidId = $stmt->insert_id;
        $stmt->close();
        return ['success' => true, 'bidId' => $newBidId];
    }

    /**
     * Fetch all active bids for the given post IDs, including provider name.
     * Returns array keyed by Post_ID, each value is an array of bid rows.
     */
    public function getBidsForPosts(array $postIds)
    {
        if (empty($postIds)) return [];

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $types        = str_repeat('i', count($postIds));

        $sql = "SELECT
                    b.Bid_ID,
                    b.Post_ID,
                    b.Provider_ID,
                    b.Amount,
                    b.Duration,
                    b.Comment,
                    b.Status,
                    p.First_Name,
                    p.Last_Name
                FROM bids b
                INNER JOIN provider p ON b.Provider_ID = p.Provider_ID
                WHERE b.Post_ID IN ($placeholders)
                AND b.Status = 'active'
                ORDER BY b.Amount ASC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::getBidsForPosts prepare: ' . $this->conn->error);
            return [];
        }
        $stmt->bind_param($types, ...$postIds);
        if (!$stmt->execute()) {
            error_log('FeedModel::getBidsForPosts exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $indexed = [];
        foreach ($rows as $row) {
            $indexed[$row['Post_ID']][] = $row;
        }
        return $indexed;
    }

    public function cancelBid($bidId, $providerId)
    {
        // Only allow cancelling own active bids
        $sql = "UPDATE bids SET Status = 'cancelled' WHERE Bid_ID = ? AND Provider_ID = ? AND Status = 'active'";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::cancelBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $stmt->bind_param('ii', $bidId, $providerId);
        if (!$stmt->execute()) {
            error_log('FeedModel::cancelBid exec: ' . $stmt->error);
            $stmt->close();
            return ['success' => false, 'error' => 'Database error'];
        }
        $affected = $stmt->affected_rows;
        $stmt->close();
        if ($affected === 0) {
            return ['success' => false, 'error' => 'Bid not found or already cancelled'];
        }
        return ['success' => true];
    }

}
