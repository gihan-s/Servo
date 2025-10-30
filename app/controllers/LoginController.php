<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

class LoginController {
    public function view() {
        include __DIR__ . '/../views/login/index.php';
    }


    public function authenticate() {

        $type = $_POST['type'] ?? '';
        $email = strtolower($_POST['email'] ?? '');
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
        if ($user && !empty($user['Password']) && password_verify($password, $user['Password'])) {
            $status = $user['Status'];

            $status = strtolower($status);

            switch ($status) {
                case 'pending':
                case 'active':
                    $_SESSION['user_id'] = $user['Client_ID'] ?? $user['Provider_ID'];
                    $_SESSION['role'] = $type;
                    $_SESSION['user_name'] = $user['First_Name'] . ' ' . $user['Last_Name'];
                    $_SESSION['user_image'] = $user['Profile_Picture'] ?? null;
                    header("Location: " . BASE_URL . "/../dashboard");
                    return;

                case 'rejected':
                    $_SESSION['login_error'] = 'Account has been rejected.';
                    header("Location: " . BASE_URL . "/../login");
                    return;

                case 'deleted':
                    $_SESSION['login_error'] = 'Account has been deleted.';
                    header("Location: " . BASE_URL . "/../login");
                    return;

                default:
                    $_SESSION['login_error'] = 'There was an issue with your account status: ' . htmlspecialchars($status);
                    header("Location: " . BASE_URL . "/../login");
                    return;
            }

        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
            header("Location: " . BASE_URL . "/../login");
            return;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "/home");
        exit;
    }

}