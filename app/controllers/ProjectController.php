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

    /**
     * GET /projects/ongoing - Fetches ongoing projects for the client from the project table.
     */
    public function getOngoingProjects(): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $userId = (int) $_SESSION['user_id'];
        $sort   = $_GET['sort']   ?? 'date_desc';
        $search = $_GET['search'] ?? '';

        try {
            $posts = $this->projectModel->getOngoingProjectsForClient($userId, $sort, $search);
            $postsWithSkills = $this->assemblePostsWithSkills($posts);

            echo json_encode([
                'success' => true,
                'posts'   => $postsWithSkills,
            ]);
        } catch (Exception $e) {
            error_log('Error fetching ongoing projects: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch ongoing projects',
            ]);
        }
    }

    /**
     * GET /projects/pending-review - Fetches pending-review projects for the client from the project table.
     */
    public function getPendingReviewProjects(): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $userId = (int) $_SESSION['user_id'];
        $sort   = $_GET['sort']   ?? 'date_desc';
        $search = $_GET['search'] ?? '';

        try {
            $posts = $this->projectModel->getPendingReviewProjectsForClient($userId, $sort, $search);
            $postsWithSkills = $this->assemblePostsWithSkills($posts);

            echo json_encode([
                'success' => true,
                'posts'   => $postsWithSkills,
            ]);
        } catch (Exception $e) {
            error_log('Error fetching pending review projects: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch pending review projects',
            ]);
        }
    }

    /**
     * GET /projects/completed - Fetches completed projects for the client from the project table.
     */
    public function getCompletedProjects(): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $userId = (int) $_SESSION['user_id'];
        $sort   = $_GET['sort']   ?? 'date_desc';
        $search = $_GET['search'] ?? '';

        try {
            $posts = $this->projectModel->getCompletedProjectsForClient($userId, $sort, $search);
            $postsWithSkills = $this->assemblePostsWithSkills($posts);

            // Attach reviews for each project
            foreach ($postsWithSkills as &$item) {
                if (!empty($item['post']['Project_ID'])) {
                    $item['post']['reviews'] = $this->projectModel->getReviewsByProjectId((int) $item['post']['Project_ID']);
                } else {
                    $item['post']['reviews'] = [];
                }
            }
            unset($item);

            echo json_encode([
                'success' => true,
                'posts'   => $postsWithSkills,
            ]);
        } catch (Exception $e) {
            error_log('Error fetching completed projects: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to fetch completed projects',
            ]);
        }
    }

    /**
     * GET /project/reviews/{postId} - Fetches reviews for a specific project.
     */
    public function getProjectReviews(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        try {
            $reviews = $this->projectModel->getReviewsByPostId($postId);
            echo json_encode(['success' => true, 'reviews' => $reviews]);
        } catch (Exception $e) {
            error_log('Error fetching reviews: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to fetch reviews']);
        }
    }

    /**
     * POST /project/provider-review/{postId} - Provider submits a review for the client.
     */
    public function submitProviderReview(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $providerId = (int) $_SESSION['user_id'];
        $rating     = (int) ($_POST['rating'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $desc       = trim($_POST['description'] ?? '');

        if ($rating < 1 || $rating > 5) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
            return;
        }

        try {
            $this->projectModel->addProviderReview($providerId, $postId, $rating, $title, $desc);
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully.']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cancelRequest($id): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $id       = (int) $id;
        $clientId = (int) ($_SESSION['user_id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid post id']);
            return;
        }

        $result = $this->projectModel->cancelRequest($id, $clientId);

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
        } else {
            http_response_code($result['code'] ?? 500);
            echo json_encode(['success' => false, 'error' => $result['message']]);
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

        $result = $this->projectModel->createProjectAndHoldPayment($PostID);

        if (!empty($result['success'])) {
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

    public function completeProjects(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $postId = (int) $postId;
        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid post id']);
            return;
        }

        $rating = (float) ($_POST['rating'] ?? 0);
        $ratingStep = round($rating * 2) / 2;
        if ($ratingStep < 0.5 || $ratingStep > 5) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'A rating between 0.5 and 5 is required']);
            return;
        }

        if (abs($rating - $ratingStep) > 0.01) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Rating must be in 0.5 increments']);
            return;
        }

        $title       = trim((string) ($_POST['title']       ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        $reviewFileNames = [];
        if (!empty($_FILES['review_files']['name'][0])) {
            require_once __DIR__ . '/../../helpers/upload.php';
            $uploadDir = __DIR__ . '/../../uploads/Projects/reviews/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $allowed = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/zip', 'application/x-zip-compressed',
                'text/plain',
            ];
            try {
                $reviewFileNames = uploadFiles('review_files', $uploadDir, $allowed, 10 * 1024 * 1024);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                return;
            }
        }

        $clientId = (int) ($_SESSION['user_id'] ?? 0);
        $success = $this->projectModel->completeProjectByClient($clientId, $postId, $ratingStep, $title, $description, $reviewFileNames);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Project marked as completed']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to complete project']);
        }
    }

    public function reopenProject(int $postId): void
    {
        header('Content-Type: application/json');
        $this->ensureAuth();

        $postId = (int) $postId;
        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid post id']);
            return;
        }

        $reason = trim((string) ($_POST['reason'] ?? ''));
        if ($reason === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Reason is required']);
            return;
        }

        $fileNames = [];
        if (!empty($_FILES['reopen_files']['name'][0])) {
            require_once __DIR__ . '/../../helpers/upload.php';
            $uploadDir = __DIR__ . '/../../uploads/Projects/updates/';
            $allowed   = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/zip', 'application/x-zip-compressed',
                'text/plain',
            ];
            try {
                $fileNames = uploadFiles('reopen_files', $uploadDir, $allowed, 10 * 1024 * 1024);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                return;
            }
        }

        $clientId = (int) ($_SESSION['user_id'] ?? 0);
        $success = $this->projectModel->moveProjectBackToOngoingByClient($clientId, $postId, $reason, $fileNames);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Project moved back to ongoing']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to move project back to ongoing']);
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
            $this->notFound();
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
        $deliverables    = $this->projectModel->getLastSubmissionDeliverables($postId);

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
                'deliverables'   => $deliverables,
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
        $postId      = (int) ($_POST['post_id']      ?? 0);
        $title       = trim((string) ($_POST['title']       ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        // Fallback for cases where client-side project_id is not ready yet.
        if ($projectId <= 0 && $postId > 0) {
            $project = $this->projectModel->getByPostId($postId);
            $projectId = (int) ($project['Project_ID'] ?? 0);
        }

        if ($projectId <= 0 || $title === '') {
            http_response_code(400);
            $error = $title === ''
                ? 'title is required'
                : 'project_id could not be resolved (missing/invalid post_id or project row)';
            echo json_encode(['success' => false, 'error' => $error]);
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


