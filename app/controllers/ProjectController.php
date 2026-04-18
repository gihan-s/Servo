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

    public function initiatePayment($PostID): void
    {
        error_log("Initiating payment for Post ID: $PostID");
        header('Content-Type: application/json');
        $PostID = (int) $PostID;
        if ($PostID <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $projectId = $this->projectModel->createProject($PostID);
        $success = $this->postModel->changePostRequestStatus($PostID, 'completed');

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

        $progress    = max(0, min(100, (int)   ($_POST['progress']    ?? 0)));
        $title       = trim((string)           ($_POST['title']       ?? ''));
        $description = trim((string)           ($_POST['description'] ?? ''));
        $workedHours = max(0.0, (float)        ($_POST['worked_hours'] ?? 0));

        if ($title === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Title is required']);
            return;
        }

        // Handle optional file uploads
        $fileNames = [];
        if (!empty($_FILES['update_files']['name'][0])) {
            require_once __DIR__ . '/../../helpers/upload.php';
            $uploadDir = __DIR__ . '/../../uploads/Projects/updates/';
            $allowed   = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/zip', 'application/x-zip-compressed',
                'text/plain',
            ];
            try {
                $fileNames = uploadFiles('update_files', $uploadDir, $allowed, 10 * 1024 * 1024);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
                return;
            }
        }

        $success = $this->projectModel->updateProjectProgress(
            $postId, $progress, $title, $description, $workedHours, $fileNames
        );

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Progress updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to update project progress']);
        }
    }

    public function submitForReview(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $postId = (int) $postId;
        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid post id']);
            return;
        }

        $description = trim((string) ($_POST['description'] ?? ''));

        // Handle optional file uploads
        $fileNames = [];
        if (!empty($_FILES['deliverable_files']['name'][0])) {
            require_once __DIR__ . '/../../helpers/upload.php';
            $uploadDir = __DIR__ . '/../../uploads/Projects/updates/';
            $allowed   = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/zip', 'application/x-zip-compressed',
                'text/plain',
            ];
            try {
                $fileNames = uploadFiles('deliverable_files', $uploadDir, $allowed, 10 * 1024 * 1024);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                return;
            }
        }

        $success = $this->projectModel->submitForReview($postId, $description, $fileNames);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Project submitted for review']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to submit for review']);
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
            $incomingRequests = $this->projectModel->getIncomingRequestsByProviderId($userId);
            $ongoingProjects = $this->projectModel->getOngoingProjectsByProviderId($userId);
            $pendingReviewProjects = $this->projectModel->getPendingReviewProjectsByProviderId($userId);
            $completedJobs = $this->projectModel->getCompletedJobsByProviderId($userId);
            $viewFile = __DIR__ . '/../views/provider/Projects/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    /**
     * GET /project/details/{postId}
     * Returns full project info, timeline events, and progress history for the detail view.
     */
    public function getProjectDetails(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $postId = (int) $postId;
        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid post id']);
            return;
        }

        $details = $this->projectModel->getProjectFullDetails($postId);
        if (!$details) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Project not found']);
            return;
        }

        $priceType     = trim($details['Price_Type'] ?: 'Fixed');
        $budgetDisplay = 'Rs. ' . number_format((float) $details['Requesting_Price'], 2) . ' (' . $priceType . ')';

        $timeline        = $this->projectModel->getProjectTimeline($postId);
        $progressHistory = $this->projectModel->getProgressHistory($postId);

        echo json_encode([
            'success' => true,
            'data'    => [
                'project_id'     => (int) $details['Project_ID'],
                'post_id'        => (int) $details['Post_ID'],
                'title'          => $details['Title'],
                'description'    => $details['Description'],
                'status'         => $details['Project_Status'],
                'progress'       => (int) $details['Progress'],
                'started_at'     => $details['Started_At'],
                'ended_at'       => $details['Ended_At'],
                'est_date'       => $details['Est_Date'] ?? null,
                'level'          => $details['Level'] ?? '—',
                'post_type'      => ucfirst(strtolower($details['Post_Type'] ?? 'direct')),
                'budget'         => (float) $details['Requesting_Price'],
                'price_type'     => $priceType,
                'budget_display' => $budgetDisplay,
                'category'       => $details['Category_Name'] ?? '—',
                'client_id'      => (int) $details['Client_ID'],
                'client_name'    => $details['Client_Name'] ?? 'Unknown',
                'client_picture' => $details['Client_Picture'] ?? null,
                'provider_id'    => (int) $details['Provider_ID'],
                'provider_name'  => $details['Provider_Name'] ?? 'Unknown',
                'provider_picture' => $details['Provider_Picture'] ?? null,
                'timeline'       => $timeline,
                'progress_history' => $progressHistory,
            ],
        ]);
    }

    public function getRequirementsByPost($id): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $data = $this->projectModel->getRequirementsByPostId($id);
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function updateRequirementStatusAction(): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $requirementId   = (int)   ($_POST['requirement_id']   ?? 0);
        $status          = trim((string) ($_POST['status']          ?? ''));
        $rejectionReason = trim((string) ($_POST['rejection_reason'] ?? ''));

        if ($requirementId <= 0 || !in_array($status, ['approved', 'rejected'], true)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
            return;
        }

        if ($status === 'rejected' && $rejectionReason === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Rejection reason is required']);
            return;
        }

        // Verify the requirement belongs to a project owned by the current provider
        $providerId = (int) ($_SESSION['user_id'] ?? 0);
        if (!$this->projectModel->requirementBelongsToProvider($requirementId, $providerId)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            return;
        }

        $ok = $this->projectModel->updateRequirementStatus($requirementId, $status, $rejectionReason);

        if ($ok) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to update requirement status']);
        }
    }

    public function addRequirementAction(): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $projectId   = (int) ($_POST['project_id']   ?? 0);
        $title       = trim((string) ($_POST['title']       ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        if ($projectId <= 0 || $title === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'project_id and title are required']);
            return;
        }

        $fileNames = [];
        if (!empty($_FILES['req_files']['name'][0])) {
            require_once __DIR__ . '/../../helpers/upload.php';
            $uploadDir = __DIR__ . '/../../uploads/Projects/requirements/';
            $allowed   = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/zip', 'application/x-zip-compressed',
                'text/plain',
            ];
            try {
                $fileNames = uploadFiles('req_files', $uploadDir, $allowed, 10 * 1024 * 1024);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                return;
            }
        }

        $ok = $this->projectModel->addRequirement($projectId, $title, $description, $fileNames);

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Requirement added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to add requirement']);
        }
    }
}