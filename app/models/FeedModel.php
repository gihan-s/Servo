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
                    p.Est_Date,
                    p.Post_Status,
                    p.Client_ID,
                    c.First_Name AS Client_First_Name,
                    c.Last_Name AS Client_Last_Name,
                    cat.Name AS Category_Name,
                    cat.Category_ID
                FROM post p
                INNER JOIN client c ON p.Client_ID = c.Client_ID
                INNER JOIN category cat ON p.Category_ID = cat.Category_ID
                WHERE p.Post_Status = 'Open' 
                AND p.Post_Type = 'Bid'
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
            $row['Timeline'] = formatDuration($row['Est_Date']);
            $row['Posted'] = timeAgo($row['Created_At']);
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
                'Est_Date' => date('Y-m-d', strtotime('+10 days')),
                'Post_Status' => 'Open',
                'Client_ID' => 1,
                'Client_First_Name' => 'Nadia',
                'Client_Last_Name' => 'Perera',
                'Category_Name' => 'Web Development',
                'Category_ID' => 1,
                'Client_Name' => 'Nadia Perera',
                'Budget' => '$600',
                'Timeline' => '10 days',
                'Posted' => '2 hours ago'
            ],
            [
                'Post_ID' => 2,
                'Title' => 'Logo + Brand Kit',
                'Description' => 'Client is looking for a clean logo, color palette and typography suggestions for a new startup.',
                'Requesting_Price' => 350,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'Est_Date' => date('Y-m-d', strtotime('+5 days')),
                'Post_Status' => 'Open',
                'Client_ID' => 2,
                'Client_First_Name' => 'Isuru',
                'Client_Last_Name' => 'Fernando',
                'Category_Name' => 'Graphic Design',
                'Category_ID' => 2,
                'Client_Name' => 'Isuru Fernando',
                'Budget' => '$350',
                'Timeline' => '5 days',
                'Posted' => '5 hours ago'
            ],
            [
                'Post_ID' => 3,
                'Title' => 'WordPress SEO Optimization',
                'Description' => 'On-page + technical SEO improvements for an e-commerce WordPress site to increase search visibility.',
                'Requesting_Price' => 480,
                'Created_At' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'Est_Date' => date('Y-m-d', strtotime('+14 days')),
                'Post_Status' => 'Open',
                'Client_ID' => 3,
                'Client_First_Name' => 'Tharushi',
                'Client_Last_Name' => 'De Silva',
                'Category_Name' => 'SEO',
                'Category_ID' => 3,
                'Client_Name' => 'Tharushi De Silva',
                'Budget' => '$480',
                'Timeline' => '14 days',
                'Posted' => '1 day ago'
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
                    p.Est_Date,
                    p.Post_Status,
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
            $row['Timeline'] = formatDuration($row['Est_Date']);
            $row['Posted'] = timeAgo($row['Created_At']);
        }

        return $row;
    }
}
