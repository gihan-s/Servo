<?php
// POST Table Format:
// +------------------+---------------+------+-----+---------+----------------+
// | Field            | Type          | Null | Key | Default | Extra          |
// +------------------+---------------+------+-----+---------+----------------+
// | Post_ID          | int           | NO   | PRI | NULL    | auto_increment |
// | Created_At       | datetime      | YES  |     | NULL    |                |
// | Category_ID      | int           | NO   | MUL | NULL    |                |
// | Post_Type        | varchar(45)   | YES  |     | NULL    |                |
// | Post_Status      | varchar(45)   | YES  |     | NULL    |                |
// | Client_ID        | int           | NO   | MUL | NULL    |                |
// | Provider_ID      | int           | YES  | MUL | NULL    |                |
// | Title            | varchar(100)  | YES  |     | NULL    |                |
// | Description      | varchar(2048) | YES  |     | NULL    |                |
// | Requesting_Price | double        | YES  |     | NULL    |                |
// | Price_Type       | varchar(25)   | NO   |     | NULL    |                |
// | Duration         | varchar(50)   | NO   |     | NULL    |                |
// | Level            | varchar(30)   | NO   |     | NULL    |                |
// | End_At           | date          | NO   |     | NULL    |                |
// | Published_At     | datetime      | YES  |     | NULL    |                |
// | Duration_Type    | varchar(25)   | NO   |     | NULL    |                |
// +------------------+---------------+------+-----+---------+----------------+

require_once __DIR__ . '/../core/Database.php';

class PostModel extends Database
{
    public function getPosts($clientId, $status = null, $limit = null)
    {
        $query = "SELECT * FROM Post WHERE Client_ID = ?";
        $types = "i";
        $params = [$clientId];

        if ($status !== null) {
            $query .= " AND Post_Status = ?";
            $types .= "s";
            $params[] = $status;
        }

        $query .= " ORDER BY Created_At DESC";

        if ($limit !== null) {
            $query .= " LIMIT ?";
            $types .= "i";
            $params[] = $limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
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

    public function countActiveRequests($clientId)
    {
        $count = 3; // Replace with actual data fetching logic
        // $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM Post WHERE Client_ID = ? AND Post_Status = 'Published'");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $count = $result->fetch_assoc()['count'];
        // $stmt->close();
        return $count;
    }

    public function getRecentRequests($clientId, $limit = 3)
    {
        // $stmt = $this->conn->prepare("SELECT * FROM Post WHERE Client_ID = ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("ii", $clientId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return [
            [
                'Title' => 'Full-Stack E‑commerce Platform',
                'Post_Status' => 'Open',
                'Created_At' => '2025-12-12',
                'Proposals' => 23,
                'End_At' => '2025-12-31'
            ],
            [
                'Title' => 'Mobile App UI/UX Design',
                'Post_Status' => 'Open',
                'Created_At' => '2025-11-30',
                'Proposals' => 47,
                'End_At' => '2025-12-30'
            ],
            [
                'Title' => 'Digital Marketing Campaign Plan',
                'Post_Status' => 'Draft',
                'Created_At' => '2025-11-28',
                'Proposals' => NULL,
                'End_At' => NULL
            ]
        ]; // fetch actual data from model
    }
}