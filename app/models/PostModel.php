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
// | Est_Date         | date          | YES  |     | NULL    |                |
// | Level            | varchar(30)   | NO   |     | NULL    |                |
// | End_At           | date          | NO   |     | NULL    |                |
// | Published_At     | datetime      | YES  |     | NULL    |                |
// | Duration_Type    | varchar(25)   | YES  |     | NULL    | legacy         |
// +------------------+---------------+------+-----+---------+----------------+

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/BidModel.php';

class PostModel extends Database
{
    private ?BidModel $bidModel = null;

    public function __construct(bool $loadBidModel = true)
    {
        parent::__construct();
        if ($loadBidModel) {
            $this->bidModel = new BidModel();
        }
    }

    public function getPostIdsByProviderId(int $providerId): array
    {
        $stmt = $this->conn->prepare("SELECT Post_ID FROM Post WHERE Provider_ID = ?");
        if (!$stmt) {
            error_log('PostModel::getPostIdsByProviderId prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $providerId);
        if (!$stmt->execute()) {
            error_log('PostModel::getPostIdsByProviderId exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_values(array_filter(array_map(static function ($row) {
            return (int) ($row['Post_ID'] ?? 0);
        }, $rows)));
    }

    public function getPosts($clientId, $status = null, $sort = 'date_desc', $search = '')
    {
        error_log("PostModel::getPosts - Client: $clientId, Status: $status, Sort: $sort, Search: '$search'");
        
        $query = "SELECT p.*
              FROM Post p
              WHERE p.Client_ID = ? AND p.Post_Type = 'post'";
        $types = "i";
        $params = [$clientId];

        if ($status !== null) {
            $query .= " AND p.Post_Status = ?";
            $types .= "s";
            $params[] = $status;
        }

        // Add search filter if search term is provided
        if (!empty($search)) {
            $query .= " AND (p.Title LIKE ? OR p.Description LIKE ? OR p.Requesting_Price LIKE ? OR p.Level LIKE ?)";
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
                    $orderBy = 'p.Published_At ASC';
                } elseif ($status === 'Draft') {
                        $orderBy = 'p.Created_At ASC';
                } else {
                        $orderBy = 'p.End_At ASC'; // For expired posts
                }
                break;
            case 'date_desc':
                if ($status === 'active') {
                        $orderBy = 'p.Published_At DESC';
                } elseif ($status === 'Draft') {
                        $orderBy = 'p.Created_At DESC';
                } else {
                        $orderBy = 'p.End_At DESC'; // For expired posts
                }
                break;
            case 'price_asc':
                    $orderBy = 'p.Requesting_Price ASC';
                break;
            case 'price_desc':
                    $orderBy = 'p.Requesting_Price DESC';
                break;
            case 'views_asc':
                    $orderBy = 'p.Views ASC';
                break;
            case 'views_desc':
                    $orderBy = 'p.Views DESC';
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
        $posts = $result->fetch_all(MYSQLI_ASSOC);

        $postIds = array_map(static fn($row) => (int) ($row['Post_ID'] ?? 0), $posts);
        $bidCounts = $this->bidModel ? $this->bidModel->getCountsByPostIds($postIds) : [];

        foreach ($posts as &$post) {
            $postId = (int) ($post['Post_ID'] ?? 0);
            $post['Proposal_Count'] = $bidCounts[$postId] ?? 0;
        }

        return $posts;
    }

    public function createPost($data)
    {
        try {
            $query = "INSERT INTO Post (Client_ID, Title, Description, Category_ID, Requesting_Price, 
                Price_Type, Est_Date, Level, End_At, Post_Status, Created_At, Published_At, Post_Type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, 'post')";

            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param(
                'issidssssss',  // i=integer, s=string, d=double
                $data['Client_ID'],
                $data['Title'],
                $data['Description'],
                $data['Category_ID'],
                $data['Requesting_Price'],
                $data['Price_Type'],
                $data['Est_Date'],
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
                p.Est_Date,
                p.Level,
                p.Category_ID, 
                p.Created_At, 
                p.Published_At,
                p.End_At, 
                p.Post_Status, 
                p.Request_Status,
                p.Provider_ID,
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

        if ($row) {
            $post['Proposal_Count'] = $this->bidModel ? $this->bidModel->countByPostId((int) $postId) : 0;
        }

        return $row ?: null;
    }

    public function getBidsForPost(int $postId): array
    {
        return $this->bidModel ? $this->bidModel->getBidsForPost($postId) : [];
    }

    public function sendRequestToProvider(int $postId, int $providerId, int $clientId): array
    {
        if (!$this->bidModel || !$this->bidModel->providerHasBidForPost($postId, $providerId)) {
            return ['success' => false, 'message' => 'Selected provider has not bid on this post'];
        }

        $checkSql = "SELECT Post_ID, Client_ID, Post_Status, Request_Status FROM Post WHERE Post_ID = ? LIMIT 1";
        $checkStmt = $this->conn->prepare($checkSql);
        if (!$checkStmt) {
            return ['success' => false, 'message' => 'Failed to prepare status check'];
        }

        $checkStmt->bind_param('i', $postId);
        $checkStmt->execute();
        $row = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if (!$row) {
            return ['success' => false, 'message' => 'Post not found'];
        }

        if ((int) ($row['Client_ID'] ?? 0) !== $clientId) {
            return ['success' => false, 'message' => 'Unauthorized post access'];
        }

        if (strtolower((string) ($row['Post_Status'] ?? '')) !== 'active') {
            return ['success' => false, 'message' => 'Requests can only be sent for active posts'];
        }

        $current = strtolower(trim((string) ($row['Request_Status'] ?? '')));
        $canSend = ($current === '' || $current === 'declined');
        if (!$canSend) {
            return ['success' => false, 'message' => 'Cannot send request when status is ongoing or accepted'];
        }

        $updateSql = "UPDATE Post
            SET Provider_ID = ?, Request_Status = 'ongoing'
            WHERE Post_ID = ?
              AND Client_ID = ?
              AND (Request_Status IS NULL OR LOWER(Request_Status) = 'declined' OR Request_Status = '')";

        $updateStmt = $this->conn->prepare($updateSql);
        if (!$updateStmt) {
            return ['success' => false, 'message' => 'Failed to prepare request update'];
        }

        $updateStmt->bind_param('iii', $providerId, $postId, $clientId);
        $updateStmt->execute();
        $affected = $updateStmt->affected_rows;
        $updateStmt->close();

        if ($affected <= 0) {
            return ['success' => false, 'message' => 'Request status did not change'];
        }

        return ['success' => true, 'message' => 'Request sent successfully', 'request_status' => 'ongoing'];
    }

    public function createDirectServiceRequest(array $data): array
    {
        $clientId = (int) ($data['Client_ID'] ?? 0);
        $providerCategoryId = (int) ($data['Provider_Categories_ID'] ?? 0);
        $title = trim((string) ($data['Title'] ?? ''));
        $description = trim((string) ($data['Description'] ?? ''));
        $requestingPrice = (float) ($data['Requesting_Price'] ?? 0);
        $priceType = trim((string) ($data['Price_Type'] ?? '')) ?: 'Fixed';
        $estDate = trim((string) ($data['Est_Date'] ?? ''));
        $level = trim((string) ($data['Level'] ?? '')) ?: 'Beginner';
        $endAt = trim((string) ($data['End_At'] ?? '')) ?: date('Y-m-d', strtotime('+30 days'));

        if ($clientId <= 0 || $providerCategoryId <= 0 || $title === '' || $description === '' || $estDate === '') {
            return ['success' => false, 'message' => 'Missing required request data'];
        }

        $checkStmt = $this->conn->prepare(
            "SELECT Post_ID
             FROM post
             WHERE Client_ID = ?
               AND Provider_Categories_ID = ?
               AND Post_Type = 'direct'
               AND Request_Status = 'ongoing'
             LIMIT 1"
        );

        if (!$checkStmt) {
            return ['success' => false, 'message' => 'Failed to prepare duplicate check'];
        }

        $checkStmt->bind_param('ii', $clientId, $providerCategoryId);
        $checkStmt->execute();
        $existing = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($existing) {
            return ['success' => false, 'message' => 'You already have an ongoing request for this service'];
        }

        $serviceStmt = $this->conn->prepare(
            "SELECT pc.Provider_ID, pc.Category_ID
             FROM provider_categories pc
             WHERE pc.ID = ?
             LIMIT 1"
        );

        if (!$serviceStmt) {
            return ['success' => false, 'message' => 'Failed to prepare service lookup'];
        }

        $serviceStmt->bind_param('i', $providerCategoryId);
        $serviceStmt->execute();
        $serviceRow = $serviceStmt->get_result()->fetch_assoc();
        $serviceStmt->close();

        if (!$serviceRow) {
            return ['success' => false, 'message' => 'Service not found'];
        }

        $providerId = (int) ($serviceRow['Provider_ID'] ?? 0);
        $categoryId = (int) ($serviceRow['Category_ID'] ?? 0);

        $sql = "INSERT INTO post (
                    Created_At,
                    Category_ID,
                    Post_Type,
                    Post_Status,
                    Client_ID,
                    Provider_ID,
                    Provider_Categories_ID,
                    Title,
                    Description,
                    Requesting_Price,
                    Price_Type,
                    Est_Date,
                    Level,
                    End_At,
                    Published_At,
                    Request_Status
                ) VALUES (NOW(), ?, 'direct', 'active', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'ongoing')";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Failed to prepare direct request insert'];
        }

        $stmt->bind_param(
            'iiiissdssss',
            $categoryId,
            $clientId,
            $providerId,
            $providerCategoryId,
            $title,
            $description,
            $requestingPrice,
            $priceType,
            $estDate,
            $level,
            $endAt
        );

        if (!$stmt->execute()) {
            $message = $stmt->error ?: 'Failed to create direct request';
            $stmt->close();
            return ['success' => false, 'message' => $message];
        }

        $newPostId = $this->conn->insert_id;
        $stmt->close();

        return [
            'success' => true,
            'message' => 'Service request sent successfully',
            'post_id' => $newPostId,
            'request_status' => 'ongoing'
        ];
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

    public function updatePost(int $postId, array $data): bool
    {
        $sql = "UPDATE Post SET Title = ?, Description = ?, Category_ID = ?, Requesting_Price = ?, 
            Price_Type = ?, Est_Date = ?, Level = ?, End_At = ?
                WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('updatePost prepare: ' . $this->conn->error);
            return false;
        }

        error_log('Update data: ' . $data['End_At']);

        $stmt->bind_param(
            'ssisdsssi',
            $data['Title'],
            $data['Description'],
            $data['Category_ID'],
            $data['Requesting_Price'],
            $data['Price_Type'],
            $data['Est_Date'],
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