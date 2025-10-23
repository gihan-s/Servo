<?php

require_once __DIR__ . '/../core/Database.php';

class PostModel extends Database
{
    public function getPosts($clientId, $status)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Post WHERE Client_ID = ? AND Post_Status = ?");
        $stmt->bind_param("is", $clientId, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createPost($clientId, $title, $description, $price, $pricetype, $duration, $durationtype, $categoryId, $createdAt, $level, $endAt, $Published_At, $status, $type )
    {
        $stmt = $this->conn->prepare("INSERT INTO Post (Client_ID, Title, Description, Requesting_Price, Price_Type, Duration, Duration_Type, Created_At, Level, End_At, Post_Status, Category_ID, Published_At, Post_Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdsisssssiss", $clientId, $title, $description, $price, $pricetype, $duration, $durationtype, $createdAt, $level, $endAt, $status, $categoryId, $Published_At, $type);
        if ($stmt->execute()) {
            $postId = $stmt->insert_id;
            $stmt->close();
            return $postId;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getPostById(int $postId): ?array
    {
        $sql = "SELECT 
                    p.Post_ID, p.Client_ID, p.Title, p.Description,
                    p.Requesting_Price, p.Price_Type,
                    p.Duration, p.Duration_Type,
                    p.Category_ID, p.Created_At, p.Published_At,
                    p.End_At, p.Post_Status, p.Post_Type,
                    c.Name AS CategoryName
                FROM Post p
                LEFT JOIN Category c ON c.Category_ID = p.Category_ID
                WHERE p.Post_ID = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) { error_log('getPostById prepare: '.$this->conn->error); return null; }
        $stmt->bind_param('i', $postId);
        if (!$stmt->execute()) { error_log('getPostById exec: '.$stmt->error); return null; }
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }
}