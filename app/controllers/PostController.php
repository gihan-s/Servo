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

    public function index(): void
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role === 'Client') {
            $actives = $this->postModel->getPosts($userId, 'active');
            $drafts = $this->postModel->getPosts($userId, 'draft');
            $expireds = $this->postModel->getPosts($userId, 'expired');

            // Each is an array of dictionaries with keys: post, skills
            $activePosts = $this->assemblePostsWithSkills($actives);
            $draftPosts = $this->assemblePostsWithSkills($drafts);
            $expiredPosts = $this->assemblePostsWithSkills($expireds);

            $data = [
                'activePosts' => $activePosts,
                'draftPosts' => $draftPosts,
                'expiredPosts' => $expiredPosts,
            ];
            $categories = $this->categoryModel->getCategories();

            $viewFile = __DIR__ . '/../views/client/Posts/index.php';
        } elseif ($role === 'Provider') {
            $viewFile = __DIR__ . '/../views/provider/Posts/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
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

        try {
            // Log received data
            error_log("Received POST data: " . print_r($_POST, true));

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $skills = $_POST['skills'] ?? '';
            $price = $_POST['price'] ?? '';
            $priceType = $_POST['price_type'] ?? '';
            $duration = $_POST['duration'] ?? '';
            $durationType = $_POST['duration_type'] ?? '';
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
                'Duration' => $duration ?: '0',
                'Duration_Type' => $durationType ?: 'Days',
                'Level' => $level ?: 'Beginner',
                'End_At' => $endAt ?: date('Y-m-d', strtotime('+30 days')),
                'Status' => $status === 'publish' ? 'active' : 'draft',
                'Published_At' => $publishedAt
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

        } catch (Exception $e) {
            error_log('Error creating post: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while creating the post'
            ]);
        }
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

        $response = [
            'Post_ID' => $post['Post_ID'] ?? null,
            'Title' => $post['Title'] ?? '',
            'Description' => $post['Description'] ?? '',
            'Requesting_Price' => $post['Requesting_Price'] ?? '',
            'Price_Type' => $post['Price_Type'] ?? '',
            'Level' => $post['Level'] ?? '',
            'Duration' => $post['Duration'] ?? '',
            'Duration_Type' => $post['Duration_Type'] ?? '',
            'Proposal_Count' => $post['Proposal_Count'] ?? ($post['ProposalsCount'] ?? 0),
            'Published_At' => $post['Published_At'] ?? ($post['Created_At'] ?? null),
            'Views' => $post['Views'] ?? ($post['View_Count'] ?? null),
            'Category_Name' => $post['CategoryName'] ?? '',
            'Category_ID' => $post['Category_ID'] ?? null,
            'skills' => array_column($skills, 'Skill'),
        ];

        echo json_encode($response);
    }

}