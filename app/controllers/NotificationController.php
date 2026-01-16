<?php

class NotificationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'Client') {
            $viewFile = __DIR__ . '/../views/client/Notification/index.php';
        } elseif ($role === 'Provider') {
            $viewFile = __DIR__ . '/../views/provider/Notification/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

}