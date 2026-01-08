<?php

require_once __DIR__ . '/../core/Database.php';

class PostModel extends Database
{
    public function getPosts($clientId, $status)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Post WHERE Client_ID = ? AND Post_Status = ?  AND Post_Type = 'post'");
        $stmt->bind_param("is", $clientId, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createPost($data)
{
    try {
        $query = "INSERT INTO post (Client_ID, Title, Description, Category_ID, Requesting_Price, 
                  Price_Type, Duration, Duration_Type, Level, End_At, Post_Status, Created_At, Published_At, Post_Type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, 'post')";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param(
            'issidsssssss',  // i=integer, s=string, d=double
            $data['Client_ID'],
            $data['Title'],
            $data['Description'],
            $data['Category_ID'],
            $data['Requesting_Price'],
            $data['Price_Type'],
            $data['Duration'],
            $data['Duration_Type'],
            $data['Level'],
            $data['End_At'],
            $data['Status'],
            $data['Published_At']
        );
        
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
        
        $postId = $this->conn->insert_id;
        error_log("Post created successfully with ID: " . $postId);
        
        return $postId;
        
    } catch (Exception $e) {
        error_log('Error in createPost: ' . $e->getMessage());
        return false;
    }
}

    public function getPostById(int $postId): ?array
    {
        $sql = "SELECT 
                p.Post_ID, 
                p.Client_ID, 
                p.Title, 
                p.Description,
                p.Requesting_Price, 
                p.Price_Type,
                p.Duration, 
                p.Duration_Type, 
                p.Level,
                p.Category_ID, 
                p.Created_At, 
                p.Published_At,
                p.End_At, 
                p.Post_Status, 
                p.Post_Type,
                c.Name AS CategoryName
            FROM Post p
            LEFT JOIN Category c ON c.Category_ID = p.Category_ID
            LEFT JOIN Post_Need_Skills sk ON sk.Post_ID = p.Post_ID
            WHERE p.Post_ID = ?
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getPostById prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('getPostById exec: ' . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }
}