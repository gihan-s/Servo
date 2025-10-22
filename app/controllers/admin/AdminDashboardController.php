<?php

class AdminDashboardController {

    public function index() {

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }
        
        include __DIR__ . '/../../views/admin/dashboard.php';
    }


}