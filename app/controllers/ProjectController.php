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

        $success = $this->projectModel->cancelRequest($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Request cancelled successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to cancel Request']);
        }
    }

    public function initiatePayment($id): void
    {
        error_log("Initiating payment for Post ID: $id");
        header('Content-Type: application/json');
        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $success = $this->projectModel->initiatePayment($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Payment initiated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to initiate payment']);
        }
    }

    public function cancelProject($id): void
    {
        header('Content-Type: application/json');
        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $success = $this->projectModel->cancelProject($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Project canceled successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to cancel project']);
        }
    }

    public function updateProgress($postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $postId = (int) $postId;
        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        // Get JSON data from request body
        $input = json_decode(file_get_contents('php://input'), true);
        $progress = (int) ($input['progress'] ?? 0);

        // Validate progress value
        if ($progress < 0 || $progress > 100) {
            http_response_code(400);
            echo json_encode(['error' => 'Progress must be between 0 and 100']);
            return;
        }

        $success = $this->projectModel->updateProjectProgress($postId, $progress);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Project progress updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to update project progress']);
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

    public function getRequirementsByPost($id)
    {
        header('Content-Type: application/json');

            $id = (int) $id;
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid post id']);
                return;
            }

            // Implement this in PostModel to return ONE row (or rename to your actual method)
            $requirements = $this->projectModel->getRequirementsByPostId($id);

            // if (empty($requirements)) {
            //     http_response_code(404);
            //     echo json_encode(['error' => 'Project requirements not found']);
            //     return;
            // }

            // $response = [
            //     'Requirement_Text' => $requirements[0]['Requirement_Text'] ?? null
            // ];

            echo json_encode($requirements);
        }

    public function submitRequirementsUpdate()
    {
        header('Content-Type: application/json');
        ob_clean();
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
                return;
            }

            $project_id = $data['project_id'] ?? null;
            $new_req = $data['new_requirement'] ?? null;

            if ($project_id <= 0 || !$new_req) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Missing project_id or new_requirement']);
                return;
            }

            // 1. add requirement
            $ok = $this->projectModel->addRequirement($project_id, $new_req);

            if (!$ok) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'DB insert failed'
                ]);
                return;
            }
            // 2. get provider
            $provider = $this->projectModel->getProviderByProject($project_id);

            if (!$provider) {
                echo json_encode(['success' => true, 'message' => 'Added but provider not found']);
                return;
            }

            // 3. send notification / request
            // $this->notificationModel->create([
            //     'User_ID' => $provider['Provider_ID'],
            //     'Message' => "New requirement added for your project #$project_id",
            //     'Type' => 'project_update'
            // ]);

            echo json_encode(['success' => true, 'message' => 'Requirement added successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function submitReviewComments()
    {
        header('Content-Type: application/json');
        ob_clean();
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
                return;
            }

            $project_id = $data['project_id'] ?? null;
            $comments = $data['review_comments'] ?? null;

            if ($project_id <= 0 || !$comments) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Missing project_id or comments']);
                return;
            }

            // 1. add review comments
            $ok = $this->projectModel->addReviewComments($project_id, $comments);

            if (!$ok) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'DB insert failed'
                ]);
                return;
            }
            
            // 2. get provider
            $provider = $this->projectModel->getProviderByProject($project_id);

            if (!$provider) {
                echo json_encode(['success' => true, 'message' => 'Added but provider not found']);
                return;
            }

            // 3. send notification / request
            // $this->notificationModel->create([
            //     'User_ID' => $provider['Provider_ID'],
            //     'Message' => "New review comments added for your project #$project_id",
            //     'Type' => 'project_update'
            // ]);

            echo json_encode(['success' => true, 'message' => 'Review comments added successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function completeProject($id)
    {   
        header('Content-Type: application/json');
        ob_clean();
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            error_log("Received data: " . print_r($data, true));

            if (!$data) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
                return;
            }


            $ok = $this->projectModel->markCompleteProject($id);
            error_log("markCompleteProject result: " . ($ok ? "success" : "failure"));

            if (!$ok) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to mark project as complete'
                ]);
                return;
            }

            echo json_encode(['success' => true, 'message' => 'Project marked as complete', 'ok' => $ok, 'id' => $id]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

}


