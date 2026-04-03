<?php

require_once __DIR__ . '/../models/ProjectModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/PostSkillsModel.php';
require_once __DIR__ . '/../models/SkillsModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class ProjectController extends BaseController
{
    private ProjectModel $projectModel;
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
        $this->projectModel = new ProjectModel();
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
        $status = $_GET['status'] ?? 'pending'; // pending, accepted, ongoing
        $sort = $_GET['sort'] ?? 'date_desc'; // date_desc, date_asc, price_desc, price_asc, views_desc, views_asc
        $search = $_GET['search'] ?? ''; // search query

        error_log("getPosts - User: $userId, Status: $status, Sort: $sort, Search: '$search'");

        try {
            $posts = $this->postModel->getRequestPosts($userId, $status, $sort, $search);
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

    public function cancelRequest($id): void
    {
        header('Content-Type: application/json');
        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $success = $this->postModel->cancelRequest($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Request cancelled successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to cancel Request']);
        }
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Client') {
            $pendingRequestProjects = $this->projectModel->getPendingRequestsByClientId($userId);
            $pendingReviewProjects = $this->projectModel->getPendingReviewsByClientId($userId);
            $completedProjects = $this->projectModel->getCompletedProjectsByClientId($userId);
            $approvedRequestProjects = $this->projectModel->getApprovedRequestsByClientId($userId);
            $ongoingProjects = $this->projectModel->getOngoingProjectsByClientId($userId);
            $viewFile = __DIR__ . '/../views/client/Projects/index.php';


        } elseif ($role === 'Provider') {
            $viewFile = __DIR__ . '/../views/provider/Projects/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}