<?php

require_once __DIR__ . '/../models/ProjectModel.php';

class ProjectController extends BaseController
{
    private $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new ProjectModel();
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