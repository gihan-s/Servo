<?php 

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

class ProfileController {
    private $clientModel;
    private $providerModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
    }

    public function view(){
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'provider';
        // Step 1: Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        // Step 2: Load the correct model based on role
        if ($role === 'client') {
            $user = $this->clientModel->getClientById($userId);
            $viewFile = "../app/views/client/profile/index.php";
        } elseif ($role === 'provider') {
            $user = $this->providerModel->getProviderById($userId);
            $viewFile = "../app/views/provider/profile/index.php";
        } else {
            die("Invalid user role!");
        }

        // Step 3: Pass data to the correct view
        include $viewFile;
    }
}
