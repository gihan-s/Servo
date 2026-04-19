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
        // Fetch posts that are open for bidding
        // Join with Client and Category tables to get full details
        $sql = "SELECT 
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Created_At,
                    p.Est_Date AS Deadline,
                    p.Post_Status,
                    p.Request_Status,
                    p.Client_ID,
                    c.First_Name AS Client_First_Name,
                    c.Last_Name AS Client_Last_Name,
                    cat.Name AS Category_Name,
                    cat.Category_ID
                FROM post p
                INNER JOIN client c ON p.Client_ID = c.Client_ID
                INNER JOIN category cat ON p.Category_ID = cat.Category_ID
                WHERE p.Post_Status = 'active'
                AND p.Post_Type = 'post'
                AND p.Request_Status = 'open' -- NOTE: the status could change
                AND p.Est_Date > NOW()
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
            $stmt->bind_param('ii', $providerId, $limit);
        } else {
            $stmt->bind_param('i', $providerId);
        }

        if (!$stmt->execute()) {
            error_log('FeedModel::getFeedItemsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Format the data with helper functions
        foreach ($rows as &$row) {
            $row['Client_Name'] = formatFullName($row['Client_First_Name'], $row['Client_Last_Name']);
            $row['Budget'] = formatCurrency($row['Requesting_Price']);
            $row['Deadline'] = formatDuration($row['Deadline']);
            $row['Posted'] = timeAgo($row['Created_At']);
            $row['Request_Status'] = $row['Request_Status'] ?? 'Pending';
        }
        unset($row);

        return $rows;
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
        $sql = "SELECT COUNT(*) FROM bids WHERE Provider_ID = ? AND Post_ID = ?";
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
        // return success response with new bid details (including generated bid ID and reference)
        return ['success' => true, 'bidId' => $newBidId];
    }

}
