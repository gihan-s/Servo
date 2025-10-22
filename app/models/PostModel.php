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

}