<?php

require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/SkillsModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class PostController extends BaseController
{
    private PostModel $postModel;
    private SkillsModel $skillsModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
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


    public function draftPost(): void
    {
        $this->ensureAuth();
        $Published_At = null;
        $this->create('draft', $Published_At);
    }

    public function publishPost(): void
    {
        $this->ensureAuth();
        $Published_At = date('Y-m-d H:i:s');
        $this->create('active', $Published_At);
    }

    public function create($status, $Published_At): void
    {
        $this->ensureAuth();

        $userId = (int) $_SESSION['user_id'];
        $role = $_SESSION['role'];

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $categoryId = (int) ($_POST['categoryid'] ?? 0);
        $price = (float) ($_POST['price'] ?? 0);
        $pricetype = $_POST['pricetype'] ?? 'fixed';
        $duration = (int) ($_POST['duration'] ?? 0);
        $durationtype = $_POST['durationtype'] ?? 'days';
        $createdAt = date('Y-m-d H:i:s');
        $level = $_POST['level'] ?? 'beginner';
        $endAt = $_POST['endat'];

        if ($categoryId <= 0) {
            $_SESSION['form_error'] = 'Please select a category.';
            header('Location: ' . '/requests');
            exit;
        }

        // skills can arrive as JSON string or array
        $skills = $_POST['skills'] ?? '[]';

        $postId = $this->postModel->createPost(
            $userId,
            $title,
            $description,
            $price,
            $pricetype,
            $duration,
            $durationtype,
            $categoryId,
            $createdAt,
            $level,
            $endAt,
            $Published_At,
            $status,
            "post"
        );

        if ($postId > 0) {
            // $skills is array of IDs (preferred) or names
            $this->skillsModel->insertPostSkill($postId, $skills);
            header('Location: ' . '/requests');
        } else {
            error_log('createPost failed, got PostID=0');
            $_SESSION['form_error'] = 'Could not create post. Please try again.';
            header('Location: ' . '/requests');
            exit;
        }
    }

    public function viewPost($postId): void
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

            $viewFile = __DIR__ . '/../views/client/Posts/show.php';
        }
        // elseif ($role === 'Provider') {
        //     $viewFile = __DIR__ . '/../views/provider/Posts/show.php';
        // }
        else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}