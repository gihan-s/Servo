<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
// Optional: uncomment if you have these models
// require_once __DIR__ . '/../models/NotificationModel.php';
// require_once __DIR__ . '/../models/OrderModel.php';
// require_once __DIR__ . '/../models/MessageModel.php';

class DashboardController
{
    private $clientModel;
    private $providerModel;
    // private $notificationModel;
    // private $orderModel;
    // private $messageModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
        // $this->notificationModel = new NotificationModel();
        // $this->orderModel = new OrderModel();
        // $this->messageModel = new MessageModel();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Gather data for widgets/cards
        // $stats = $this->getDashboardStats($userId, $role);
        // $recent = $this->getRecentActivity($userId, $role);
        // $notifications = $this->getNotifications($userId, $role);

        // Make variables available to the view
        // $viewData = compact('stats', 'recent', 'notifications', 'role');
        // extract($viewData, EXTR_SKIP);

        // Choose view by role
        if ($role === 'client') {
            $viewFile = "../app/views/client/dashboard/index.php";
        } elseif ($role === 'provider') {
            $viewFile = "../app/views/provider/dashboard/index.php";
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    // // GET /dashboard/stats (AJAX for charts)
    // public function stats()
    // {
    //     $this->ensureAuth();
    //     $data = $this->getDashboardStats($_SESSION['user_id'], $_SESSION['role']);
    //     $this->json($data);
    // }

    // // GET /dashboard/notifications (AJAX list)
    // public function notifications()
    // {
    //     $this->ensureAuth();
    //     $data = $this->getNotifications($_SESSION['user_id'], $_SESSION['role']);
    //     $this->json(['items' => $data]);
    // }

    // // POST /dashboard/notifications/read
    // public function markNotificationRead()
    // {
    //     $this->ensureAuth();
    //     $id = (int)($_POST['id'] ?? 0);
    //     if ($id <= 0) return $this->json(['ok' => false, 'message' => 'Invalid id'], 400);

    //     // TODO: update notification as read in DB
    //     // $ok = $this->notificationModel->markRead($_SESSION['user_id'], $id);
    //     $ok = true; // placeholder

    //     $this->json(['ok' => (bool)$ok]);
    // }

    // ---- Helpers ----

    private function ensureAuth(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }
    }

    private function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Aggregate KPI counts, chart data, etc.
    // private function getDashboardStats(int $userId, string $role): array
    // {
    //     // TODO: replace with real queries (orders/messages/earnings)
    //     return [
    //         'cards' => [
    //             ['label' => 'Open Orders', 'value' => 0],
    //             ['label' => 'Messages',    'value' => 0],
    //             ['label' => 'Earnings',    'value' => 0],
    //         ],
    //         'chart' => [
    //             'labels' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
    //             'values' => [0, 0, 0, 0, 0, 0, 0],
    //         ],
    //     ];
    // }

    // // Recent activity list
    // private function getRecentActivity(int $userId, string $role): array
    // {
    //     // TODO: replace with recent orders/requests/messages
    //     return [
    //         // ['type' => 'order', 'id' => 123, 'title' => 'Logo Design', 'at' => '2025-10-20 10:00:00'],
    //     ];
    // }

    // // Notifications list
    // private function getNotifications(int $userId, string $role): array
    // {
    //     // TODO: fetch notifications from DB
    //     return [
    //         // ['id' => 1, 'text' => 'Your order #123 was updated', 'read' => false, 'at' => '2025-10-20'],
    //     ];
    // }
}