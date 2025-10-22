<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

class LoginController {
    public function view() {
        include __DIR__ . '/../views/login/index.php';
    }


    public function authenticate() {

        $type = $_POST['type'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($type === 'Client') {
            $model = new ClientModel();
        } elseif ($type === 'Provider') {
            $model = new ProviderModel();
        } else {
            $_SESSION['login_error'] = 'Invalid user type selected.';
            header("Location: " . BASE_URL . "/../login");
            exit;
        }

        $user = $model->getByEmail($email);
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['Client_ID'] ?? $user['Provider_ID'];
            $_SESSION['role'] = $type;
            header("Location: " . BASE_URL . "/../dashboard");
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
            header("Location: " . BASE_URL . "/../login");
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit;
    }

}