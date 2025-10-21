<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

class DashboardController
{
    private $clientModel;
    private $providerModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
    }

    // GET /dashboard
    public function index()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        // Choose view by role
        if ($role === 'client') {
            $viewFile = __DIR__ . '/../views/client/Dashboard/index.php';
        } elseif ($role === 'provider') {
            $viewFile = __DIR__ . '/../views/provider/Dashboard/index.php';
        } else {
            http_response_code(403);
            echo "Invalid role";
            return;
        }

        include $viewFile;
    }

    private function ensureAuth(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }
    }


}