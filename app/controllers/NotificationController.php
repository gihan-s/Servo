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
            $this->notFound();
            return;
        }

        include $viewFile;
    }

    public function loadMoreNotifs()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Load more notifications based on role
        if ($role === 'Client') {
            $unprocessedNotifications = $this->notificationModel->getNotificationsByClientId($userId, 10, isset($_GET['offset']) ? intval($_GET['offset']) : 0);
        } elseif ($role === 'Provider') {
            $unprocessedNotifications = $this->notificationModel->getNotificationsByProviderId($userId, 10, isset($_GET['offset']) ? intval($_GET['offset']) : 0);
        } else {
            $this->jsonError('Forbidden', 403, 'forbidden');
            return;
        }

        $notifications = [];
        $this->appendNotifications($unprocessedNotifications, $notifications);

        // Return JSON response
        $this->jsonResponse(['notifications' => $notifications]);

        return;
    }

    // public function markAsRead($notificationId)
    // {
    //     $this->ensureAuth();

    //     $userId = $_SESSION['user_id'];
    //     $role   = $_SESSION['role'];

    //     // Mark notification as read based on role
    //     if ($role === 'Client') {
    //         $this->notificationModel->markAsReadByClient($notificationId, $userId);
    //     } elseif ($role === 'Provider') {
    //         $this->notificationModel->markAsReadByProvider($notificationId, $userId);
    //     } else {
    //         http_response_code(403);
    //         echo "Invalid role";
    //         return;
    //     }

    //     // Redirect back to notifications page
    //     header("Location: /notifications");
    //     exit;
    // }

}