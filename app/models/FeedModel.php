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
        // Feed Items fetched should be under categories that the provider has selected in their profile.
        // TODO: Uncomment when ready to use actual database
        /*
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
                AND p.Post_Status = 'published'
                AND p.Request_Status = 'open' -- NOTE: the status could change
                AND p.Est_Date > NOW()
                AND p.Category_ID IN (
                    SELECT Category_ID FROM provider_category WHERE Provider_ID = ?
                )
                ORDER BY p.Created_At DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('FeedModel::getFeedItemsForProvider prepare: ' . $this->conn->error);
            return [];
        }

        if ($limit !== null) {
            $stmt->bind_param('i', $limit);
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
            $row['Deadline'] = formatDuration($row['Est_Date']);
            $row['Posted'] = timeAgo($row['Created_At']);
            $row['Request_Status'] = $row['Request_Status'] ?? 'Pending';
        }
        unset($row);

        return $rows;
        */

        // Dummy data for testing
        return [
            [
                'Post_ID' => 1,
                'Title' => 'Build a Portfolio Website',
                'Description' => 'Need a modern, responsive personal portfolio with project showcase and contact form.',
                'Requesting_Price' => 600,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'Deadline' => date('Y-m-d', strtotime('+10 days')),
                'Post_Status' => 'Published',
                'Client_ID' => 1,
                'Client_First_Name' => 'Nadia', // from database join with client table
                'Client_Last_Name' => 'Perera', // from database join with client table
                'Category_ID' => 1,
                'Category_Name' => 'Web Development', // from database join with category table
                'Request_Status' => 'Pending',
            ],
            [
                'Post_ID' => 2,
                'Title' => 'Logo + Brand Kit',
                'Description' => 'Client is looking for a clean logo, color palette and typography suggestions for a new startup.',
                'Requesting_Price' => 350,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'Deadline' => date('Y-m-d', strtotime('+5 days')),
                'Post_Status' => 'Published',
                'Request_Status' => 'open',
                'Client_ID' => 2,
                'Client_First_Name' => 'Isuru',
                'Client_Last_Name' => 'Fernando',
                'Category_Name' => 'Graphic Design',
                'Category_ID' => 2,
            ],
            [
                'Post_ID' => 3,
                'Title' => 'WordPress SEO Optimization',
                'Description' => 'On-page + technical SEO improvements for an e-commerce WordPress site to increase search visibility.',
                'Requesting_Price' => 480,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'Deadline' => date('Y-m-d', strtotime('+14 days')),
                'Post_Status' => 'Published',
                'Request_Status' => 'open',
                'Client_ID' => 3,
                'Client_First_Name' => 'Tharushi',
                'Client_Last_Name' => 'De Silva',
                'Category_Name' => 'SEO',
                'Category_ID' => 3,
            ],
        ];
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

    public function providerHasBidonPost($providerId, $postId)
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
            error_log('BidModel::submitBid prepare: ' . $this->conn->error);
            return ['success' => false, 'error' => 'Database error'];
        }
        $stmt->bind_param('iidsi', $providerId, $postId, $amount, $comment, $duration);
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

}
