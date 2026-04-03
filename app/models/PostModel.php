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
    public function getPosts($clientId, $status = null, $sort = 'date_desc', $search = '')
    {
        error_log("PostModel::getPosts - Client: $clientId, Status: $status, Sort: $sort, Search: '$search'");
        
        $query = "SELECT * FROM Post WHERE Client_ID = ? AND Post_Type = 'post'";
        $types = "i";
        $params = [$clientId];

        if ($status !== null) {
            $query .= " AND Post_Status = ?";
            $types .= "s";
            $params[] = $status;
        }

        // Add search filter if search term is provided
        if (!empty($search)) {
            $query .= " AND (Title LIKE ? OR Description LIKE ? OR Requesting_Price LIKE ? OR Level LIKE ?)";
            $types .= "ssss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            error_log("Search filter applied with term: '$searchTerm'");
        }

        // Build ORDER BY clause based on sort parameter and status
        // Different date fields for different statuses:
        // - active: Published_At
        // - draft: Created_At
        // - expired: End_At
        $orderBy = 'Created_At DESC'; // Default
        
        switch ($sort) {
            case 'date_asc':
                if ($status === 'active') {
                    $orderBy = 'Published_At ASC';
                } elseif ($status === 'Draft') {
                    $orderBy = 'Created_At ASC';
                } else {
                    $orderBy = 'End_At ASC'; // For expired posts
                }
                break;
            case 'date_desc':
                if ($status === 'active') {
                    $orderBy = 'Published_At DESC';
                } elseif ($status === 'Draft') {
                    $orderBy = 'Created_At DESC';
                } else {
                    $orderBy = 'End_At DESC'; // For expired posts
                }
                break;
            case 'price_asc':
                $orderBy = 'Requesting_Price ASC';
                break;
            case 'price_desc':
                $orderBy = 'Requesting_Price DESC';
                break;
            case 'views_asc':
                $orderBy = 'Views ASC';
                break;
            case 'views_desc':
                $orderBy = 'Views DESC';
                break;
        }
        
        error_log("Using ORDER BY: $orderBy");
        $query .= " ORDER BY $orderBy";

        error_log("Final SQL: $query");
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRequestPosts($clientId, $status = null, $sort = 'date_desc', $search = '')
    {
        error_log("PostModel::getPosts - Client: $clientId, Status: $status, Sort: $sort, Search: '$search'");
        
        $query = "SELECT p.*, CONCAT(pr.First_Name, ' ', pr.Last_Name) AS Provider_Name  FROM Post p  LEFT JOIN Provider pr ON p.Provider_ID = pr.Provider_ID  WHERE p.Client_ID = ? AND p.Post_Type = 'post'";
        $types = "i";
        $params = [$clientId];

        if ($status !== null) {
            $query .= " AND Request_Status = ?";
            $types .= "s";
            $params[] = $status;
        }

        // Add search filter if search term is provided
        if (!empty($search)) {
            $query .= " AND (Title LIKE ? OR Description LIKE ? OR Requesting_Price LIKE ? OR Level LIKE ?)";
            $types .= "ssss";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            error_log("Search filter applied with term: '$searchTerm'");
        }

        // Build ORDER BY clause based on sort parameter and status
        // Different date fields for different statuses:
        // - active: Published_At
        // - draft: Created_At
        // - expired: End_At
        $orderBy = 'Created_At DESC'; // Default
        
        switch ($sort) {
            case 'date_asc':
                if ($status === 'pending') {
                    $orderBy = 'Published_At ASC';
                } elseif ($status === 'accepted') {
                    $orderBy = 'Created_At ASC';
                } else {
                    $orderBy = 'End_At ASC'; // For expired posts
                }
                break;
            case 'date_desc':
                if ($status === 'pending') {
                    $orderBy = 'Published_At DESC';
                } elseif ($status === 'accepted') {
                    $orderBy = 'Created_At DESC';
                } else {
                    $orderBy = 'End_At DESC'; // For expired posts
                }
                break;
            case 'price_asc':
                $orderBy = 'Requesting_Price ASC';
                break;
            case 'price_desc':
                $orderBy = 'Requesting_Price DESC';
                break;
            case 'views_asc':
                $orderBy = 'Views ASC';
                break;
            case 'views_desc':
                $orderBy = 'Views DESC';
                break;
        }
        
        error_log("Using ORDER BY: $orderBy");
        $query .= " ORDER BY $orderBy";

        error_log("Final SQL: $query");
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createPost($data)
    {
        try {
            $query = "INSERT INTO Post (Client_ID, Title, Description, Category_ID, Requesting_Price, 
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

    public function publishPost($postId)
    {
        error_log("=== PUBLISH POST START ===");
        error_log("Publishing draft post ID: $postId");

        // First check if the post exists and is a draft
        $checkSql = "SELECT Post_ID, Post_Status FROM Post WHERE Post_ID = ?";
        $checkStmt = $this->conn->prepare($checkSql);

        if (!$checkStmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $checkStmt->bind_param('i', $postId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $post = $result->fetch_assoc();
        $checkStmt->close();

        if (!$post) {
            error_log("ERROR: Post $postId not found in database");
            return false;
        }

        error_log("Found post - Current status: '" . $post['Post_Status'] . "'");

        // Check all possible draft status values (case-insensitive)
        $currentStatus = strtolower(trim($post['Post_Status']));
        error_log("Normalized status: '$currentStatus'");

        if ($currentStatus !== 'draft') {
            error_log("ERROR: Post $postId is not a draft. Current status: '" . $post['Post_Status'] . "'");

            // Log what statuses exist in the database for debugging
            $statusCheckSql = "SELECT DISTINCT Post_Status FROM Post";
            $statusResult = $this->conn->query($statusCheckSql);
            if ($statusResult) {
                $statuses = [];
                while ($row = $statusResult->fetch_assoc()) {
                    $statuses[] = $row['Post_Status'];
                }
                error_log("Available statuses in database: " . implode(', ', $statuses));
            }

            return false;
        }

        // Update to active status
        $sql = "UPDATE Post SET Post_Status = 'active', Published_At = NOW() WHERE Post_ID = ?";
        error_log("Executing SQL: $sql with Post_ID = $postId");

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('Prepare update failed: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('Execute update failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }

        $affected = $stmt->affected_rows;
        error_log("Rows affected: $affected");

        $stmt->close();

        if ($affected === 0) {
            error_log("WARNING: No rows updated for post $postId");
            return false;
        }

        error_log("=== PUBLISH POST SUCCESS ===");
        return true;
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

    public function deletePost(int $postId): bool
    {
        $sql = "UPDATE Post SET Post_Status = 'deleted' WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('deletePost prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('deletePost exec: ' . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function cancelRequest(int $postId): bool
    {
        $sql = "UPDATE Post SET Request_Status = 'cancelled' WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('cancelRequest prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('cancelRequest exec: ' . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function updatePost(int $postId, array $data): bool
    {
        $sql = "UPDATE Post SET Title = ?, Description = ?, Category_ID = ?, Requesting_Price = ?, 
                Price_Type = ?, Duration = ?, Duration_Type = ?, Level = ?, End_At = ?
                WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('updatePost prepare: ' . $this->conn->error);
            return false;
        }

        error_log('Update data: ' . $data['End_At']);

        $stmt->bind_param(
            'ssissssssi',
            $data['Title'],
            $data['Description'],
            $data['Category_ID'],
            $data['Requesting_Price'],
            $data['Price_Type'],
            $data['Duration'],
            $data['Duration_Type'],
            $data['Level'],
            $data['End_At'],
            $postId
        );

        if (!$stmt->execute()) {
            error_log('updatePost exec: ' . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    public function markPostAsExpired(int $postId): bool
    {
        $sql = "UPDATE Post SET Post_Status = 'expired' WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('markPostAsExpired prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('markPostAsExpired exec: ' . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }
}