<?php

require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PostSkillsModel.php';
require_once __DIR__ . '/../models/SkillsModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class PostController extends BaseController
{
    private PostModel $postModel;
    private PostSkillsModel $postSkillsModel;
    private SkillsModel $skillsModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
        $this->postSkillsModel = new PostSkillsModel();
        $this->skillsModel = new SkillsModel();
        $this->categoryModel = new CategoryModel();
    }

    private function getSkillsForPost($post): array
    {
        $postId = (int) ($post['Post_ID'] ?? 0);
        if ($postId <= 0)
            return [];

        $rows = $this->skillsModel->getSkillsByPostId($postId);
        if (empty($rows))
            return [];

        $names = [];
        foreach ($rows as $id) {
            $sid = is_array($id) ? (int) ($id['Skill_ID'] ?? 0) : (int) $id;
            if ($sid <= 0)
                continue;

            $skill = $this->skillsModel->getAllSkills($sid); // may be row or list
            if (is_array($skill)) {
                if (isset($skill['Skill'])) {
                    $names[] = (string) $skill['Skill'];
                } elseif (isset($skill[0]) && is_array($skill[0]) && isset($skill[0]['Skill'])) {
                    $names[] = (string) $skill[0]['Skill'];
                }
            }
        }
        return $names;
    }

    public function getSkills(): void
    {
        header('Content-Type: application/json');
        $catId = (int) ($_POST['category_id'] ?? 0);
        if ($catId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid category']);
            return;
        }
        $rows = $this->skillsModel->getByCategoryId($catId);
        echo json_encode(['status' => 'ok', 'result' => $rows]);
    }

    // Combine posts with their skills into [{ post: {...}, skills: [...] }, ...]
    private function assemblePostsWithSkills(array $posts): array
    {
        $out = [];
        foreach ($posts as $p) {
            $out[] = [
                'post' => $p,
                'skills' => $this->getSkillsForPost($p),
            ];
        }
        return $out;
    }

    public function getPosts(): void
    {
        header('Content-Type: application/json');

        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'active'; // active, draft, or expired
        $sort = $_GET['sort'] ?? 'date_desc'; // date_desc, date_asc, price_desc, price_asc, views_desc, views_asc
        $search = $_GET['search'] ?? ''; // search query

        error_log("getPosts - User: $userId, Status: $status, Sort: $sort, Search: '$search'");

        try {
            $posts = $this->postModel->getPosts($userId, $status, $sort, $search);
            $postsWithSkills = $this->assemblePostsWithSkills($posts);

            echo json_encode([
                'success' => true,
                'posts' => $postsWithSkills
            ]);
        } catch (Exception $e) {
            error_log('Error fetching posts: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch posts'
            ]);
        }
    }

    public function index(): void
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role === 'Client') {
            // Don't load posts here - let JavaScript do it via AJAX
            $data = [
                'activePosts' => [],
                'draftPosts' => [],
                'expiredPosts' => [],
            ];
            $categories = $this->categoryModel->getCategories();

            $viewFile = __DIR__ . '/../views/client/Posts/index.php';
        } elseif ($role === 'Provider') {
            
            $viewFile = __DIR__ . '/../views/provider/Posts/index.php';
        } else {
            $this->notFound();
            return;
        }

        include $viewFile;
    }


    // public function draftPost(): void
    // {
    //     $this->ensureAuth();
    //     $Published_At = null;
    //     $this->create('draft', $Published_At);
    // }

    // public function publishPost(): void
    // {
    //     $this->ensureAuth();
    //     $Published_At = date('Y-m-d H:i:s');
    //     $this->create( $Published_At);
    // }

    public function create(): void
    {
        if (ob_get_level())
            ob_clean();

        header('Content-Type: application/json');

        // try {
            // Log received data
            error_log("Received POST data: " . print_r($_POST, true));

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $skills = $_POST['skills'] ?? '';
            $price = $_POST['price'] ?? '';
            $priceType = $_POST['price_type'] ?? '';
            $estDate = $_POST['est_date'] ?? '';
            $level = $_POST['level'] ?? '';
            $endAt = $_POST['end_at'] ?? '';
            $status = $_POST['status'] ?? 'draft';
            $publishedAt = $status === 'publish' ? date('Y-m-d H:i:s') : null;

            if (empty($title) || empty($description) || empty($categoryId)) {
                error_log("Validation failed - missing required fields");
                echo json_encode([
                    'success' => false,
                    'message' => 'Title, description, and category are required'
                ]);
                return;
            }

            $clientId = $_SESSION['user_id'] ?? null;
            error_log("Client ID from session: " . $clientId);

            if (!$clientId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
                return;
            }

            $postData = [
                'Client_ID' => $clientId,
                'Title' => $title,
                'Description' => $description,
                'Category_ID' => $categoryId,
                'Requesting_Price' => $price ?: '0',
                'Price_Type' => $priceType ?: 'Fixed',
                'Est_Date' => $estDate ?: date('Y-m-d', strtotime('+7 days')),
                'Level' => $level ?: 'Beginner',
                'End_At' => $endAt ?: date('Y-m-d', strtotime('+30 days')),
                'Status' => $status === 'publish' ? 'active' : 'draft',
                'Published_At' => $publishedAt,
                'Request_Status' => $status === 'publish' ? 'open' : 'draft'
            ];

            error_log("Creating post with data: " . print_r($postData, true));

            $postId = $this->postModel->createPost($postData);
            error_log("createPost returned: " . var_export($postId, true));

            if (!$postId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create post - check error log'
                ]);
                return;
            }

            // Add skills
            if (!empty($skills)) {
                $skillIds = explode(',', $skills);
                error_log("Adding skills: " . print_r($skillIds, true));
                foreach ($skillIds as $skillId) {
                    $skillId = trim($skillId);
                    if (!empty($skillId)) {
                        $this->postSkillsModel->addPostSkill($postId, $skillId);
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'message' => $status === 'publish' ? 'Post published successfully' : 'Draft saved successfully',
                'post_id' => $postId
            ]);

        // } catch (Exception $e) {
        //     error_log('Error creating post: ' . $e->getMessage());
        //     error_log('Stack trace: ' . $e->getTraceAsString());
        //     echo json_encode([
        //         'success' => false,
        //         'message' => 'An error occurred while creating the post'
        //     ]);
        // }
    }

    public function viewPost($id): void
    {
        header('Content-Type: application/json');

        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        // Implement this in PostModel to return ONE row (or rename to your actual method)
        $post = $this->postModel->getPostById($id);

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            return;
        }

        // Reuse existing helper to get skills for this post
        $skills = $this->postSkillsModel->getPostSkills($id);
        $bids = $this->postModel->getBidsForPost($id);

        $response = [
            'Post_ID' => $post['Post_ID'] ?? null,
            'Title' => $post['Title'] ?? '',
            'Description' => $post['Description'] ?? '',
            'Requesting_Price' => $post['Requesting_Price'] ?? '',
            'Price_Type' => $post['Price_Type'] ?? '',
            'Level' => $post['Level'] ?? '',
            'Est_Date' => $post['Est_Date'] ?? null,
            'Post_Status' => $post['Post_Status'] ?? null,
            'Request_Status' => $post['Request_Status'] ?? null,
            'Provider_ID' => $post['Provider_ID'] ?? null,
            'Proposal_Count' => $post['Proposal_Count'] ?? ($post['ProposalsCount'] ?? 0),
            'Published_At' => $post['Published_At'] ?? ($post['Created_At'] ?? null),
            'End_At' => $post['End_At'] ?? null,
            'Views' => $post['Views'] ?? ($post['View_Count'] ?? null),
            'Category_Name' => $post['CategoryName'] ?? '',
            'Category_ID' => $post['Category_ID'] ?? null,
            'Provider_Name' => $post['Provider_Name'] ?? null,
            'Request_Status' => $post['Request_Status'] ?? null,
            'skills' => array_column($skills, 'Skill'),
            'bids' => $bids,
        ];

        echo json_encode($response);
    }

    public function deletePost($id): void
    {
        header('Content-Type: application/json');

        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $success = $this->postModel->deletePost($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to delete post']);
        }
    }

    public function markAsExpired($id): void
    {
        if (ob_get_level())
            ob_clean();

        header('Content-Type: application/json');

        try {
            $id = (int) $id;
            if ($id <= 0) {
                error_log("Invalid post ID for expiring: $id");
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid post ID'
                ]);
                exit;
            }

            error_log("Attempting to mark post ID as expired: $id");

            $success = $this->postModel->markPostAsExpired($id);

            if ($success) {
                error_log("Post $id marked as expired successfully");
                echo json_encode([
                    'success' => true,
                    'message' => 'Post marked as expired successfully'
                ]);
            } else {
                error_log("Failed to mark post $id as expired");
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to mark post as expired. Post may not exist.'
                ]);
            }

        } catch (Exception $e) {
            error_log('Exception marking post as expired: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while marking as expired: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    public function updatePost($id): void
    {
        error_log("=== UPDATE POST CALLED ===");
        error_log("Post ID: $id");
        error_log("POST Data: " . print_r($_POST, true));

        if (ob_get_level())
            ob_clean();

        header('Content-Type: application/json');

        try {
            $id = (int) $id;
            if ($id <= 0) {
                error_log("ERROR: Invalid post ID");
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid post ID'
                ]);
                exit;
            }

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $skills = $_POST['skills'] ?? '';
            $price = $_POST['price'] ?? '';
            $priceType = $_POST['price_type'] ?? '';
            $estDate = $_POST['est_date'] ?? '';
            $level = $_POST['level'] ?? '';
            $endAt = $_POST['end_at'] ?? '';

            error_log("Raw end_at received: '$endAt'");

            // Validate and format the date
            if (empty($endAt) || $endAt === '0000-00-00') {
                error_log("Empty or zero date, using default");
                $endAt = date('Y-m-d', strtotime('+30 days'));
            } else {
                // Validate date format
                $dateObj = DateTime::createFromFormat('Y-m-d', $endAt);
                if ($dateObj && $dateObj->format('Y-m-d') === $endAt) {
                    // Date is valid
                    error_log("Valid date format: '$endAt'");
                } else {
                    error_log("Invalid date format: '$endAt', using default");
                    $endAt = date('Y-m-d', strtotime('+30 days'));
                }
            }

            error_log("Final end_at to save: '$endAt'");

            if (empty($title) || empty($description) || empty($categoryId)) {
                error_log("ERROR: Missing required fields");
                echo json_encode([
                    'success' => false,
                    'message' => 'Title, description, and category are required'
                ]);
                exit;
            }

            $postData = [
                'Title' => $title,
                'Description' => $description,
                'Category_ID' => $categoryId,
                'Requesting_Price' => $price ?: '0',
                'Price_Type' => $priceType ?: 'Fixed',
                'Est_Date' => $estDate ?: date('Y-m-d', strtotime('+7 days')),
                'Level' => $level ?: 'Beginner',
                'End_At' => $endAt
            ];

            error_log("Post data to update: " . json_encode($postData));

            $success = $this->postModel->updatePost($id, $postData);
            error_log("postModel->updatePost result: " . ($success ? 'TRUE' : 'FALSE'));

            if (!$success) {
                error_log("ERROR: Failed to update post in database");
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update post'
                ]);
                exit;
            }

            // Update skills
            error_log("=== STARTING SKILLS UPDATE ===");
            $this->postSkillsModel->deletePostSkills($id);

            if (!empty($skills)) {
                error_log("Raw skills received: '$skills'");

                $skillIds = [];
                if (strpos($skills, '[') === 0 || strpos($skills, '{') === 0) {
                    $decoded = json_decode($skills, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $item) {
                            if (isset($item['id'])) {
                                $skillIds[] = $item['id'];
                            }
                        }
                    }
                } else {
                    $skillIds = array_map('trim', explode(',', $skills));
                }

                foreach ($skillIds as $skillId) {
                    if (!empty($skillId) && is_numeric($skillId)) {
                        $this->postSkillsModel->addPostSkill($id, (int) $skillId);
                    }
                }
            }

            error_log("=== UPDATE POST COMPLETED SUCCESSFULLY ===");
            echo json_encode([
                'success' => true,
                'message' => 'Post updated successfully',
                'post_id' => $id
            ]);

        } catch (Exception $e) {
            error_log('=== UPDATE POST EXCEPTION ===');
            error_log('Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());

            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while updating the post'
            ]);
        }

        exit;
    }

    public function publishById($id): void
    {
        if (ob_get_level())
            ob_clean();

        header('Content-Type: application/json');

        try {
            $id = (int) $id;
            if ($id <= 0) {
                error_log("Invalid post ID for publishing: $id");
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid post ID'
                ]);
                exit;
            }

            error_log("Attempting to publish post ID: $id");

            $success = $this->postModel->publishPost($id);

            if ($success) {
                error_log("Post $id published successfully");
                echo json_encode([
                    'success' => true,
                    'message' => 'Draft published successfully'
                ]);
            } else {
                error_log("Failed to publish post $id");
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to publish draft. Post may not exist or is not a draft.'
                ]);
            }

        } catch (Exception $e) {
            error_log('Exception publishing post: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while publishing: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    public function sendRequestToProvider($id): void
    {
        if (ob_get_level())
            ob_clean();

        header('Content-Type: application/json');
        $this->ensureAuth();

        try {
            $postId = (int) $id;
            $providerId = (int) ($_POST['provider_id'] ?? 0);
            $clientId = (int) ($_SESSION['user_id'] ?? 0);

            if ($postId <= 0 || $providerId <= 0 || $clientId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid request data']);
                exit;
            }

            $result = $this->postModel->sendRequestToProvider($postId, $providerId, $clientId);
            echo json_encode($result);
        } catch (Exception $e) {
            error_log('sendRequestToProvider exception: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to send request']);
        }

        exit;
    }

    public function createDirectRequest(): void
    {
        if (ob_get_level()) {
            ob_clean();
        }

        header('Content-Type: application/json');
        $this->ensureAuth();

        try {
            $clientId = (int) ($_SESSION['user_id'] ?? 0);
            if ($clientId <= 0) {
                echo json_encode(['success' => false, 'message' => 'User not authenticated']);
                exit;
            }

            $payload = [
                'Client_ID' => $clientId,
                'Provider_Categories_ID' => (int) ($_POST['provider_categories_id'] ?? 0),
                'Title' => (string) ($_POST['title'] ?? ''),
                'Description' => (string) ($_POST['description'] ?? ''),
                'Requesting_Price' => (float) ($_POST['requesting_price'] ?? 0),
                'Price_Type' => (string) ($_POST['price_type'] ?? ''),
                'Est_Date' => (string) ($_POST['est_date'] ?? ''),
                'Level' => (string) ($_POST['level'] ?? 'Beginner'),
                'End_At' => (string) ($_POST['end_at'] ?? date('Y-m-d', strtotime('+30 days'))),
            ];

            $result = $this->postModel->createDirectServiceRequest($payload);
            echo json_encode($result);
        } catch (Exception $e) {
            error_log('createDirectRequest exception: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to create service request']);
        }

        exit;
    }
}