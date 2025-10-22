<?php

require_once __DIR__ . '/../../models/admin/AdminUserModel.php';

class AdminLoginController {

    public function index() {
        include __DIR__ . '/../../views/admin/login.php';
    }

    public function authenticate() {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $model = new AdminUserModel();
        $user = $model->getByUsername($username);

        if ($user && password_verify($password, $user['Password'])) {

            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['role'] = 'Admin';
            $_SESSION['access_level'] = $user['Access_Level'];
            $_SESSION['name'] = $user['Name'];

            header("Location: " . BASE_URL . "/../../admin/dashboard");
            exit;

        } else {
            $_SESSION['login_error'] = 'Invalid Username or Password.';
            header("Location: " . BASE_URL . "/../../admin/login");
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /admin/login");
        exit;
    }

}