<?php

require_once __DIR__ . '/../models/FeedModel.php';

class FeedController extends BaseController
{
    private $feedModel;

    public function __construct()
    {
        parent::__construct();
        $this->feedModel = new FeedModel();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Provider') {
            $feedItems = $this->feedModel->getFeedItemsForProvider($userId);
            $viewFile = __DIR__ . '/../views/provider/Feed/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}