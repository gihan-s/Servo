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
            $_SESSION['user_name'] = $user['First_Name'] . ' ' . $user['Last_Name'];
            $_SESSION['user_image'] = $user['Profile_Picture'] ?? null;
            header("Location: " . BASE_URL . "/../dashboard");
            exit;
        } else {
            // Check if user exists but with different status
            $userAnyStatus = $model->getByEmailAnyStatus($email);
            if ($userAnyStatus && password_verify($password, $userAnyStatus['Password'])) {
                // Password is correct but account status is not active
                $status = $userAnyStatus['Status'];
                if (strcasecmp($status, 'Inactive') === 0 || strcasecmp($status, 'Deactivated') === 0) {
                    $_SESSION['login_error'] = 'Account is deactivated for this email.';
                } elseif (strcasecmp($status, 'Pending') === 0) {
                    $_SESSION['login_error'] = 'Account is pending approval.';
                } elseif (strcasecmp($status, 'Rejected') === 0) {
                    $_SESSION['login_error'] = 'Account has been rejected.';
                } elseif (strcasecmp($status, 'Deleted') === 0) {
                    $_SESSION['login_error'] = 'Account has been deleted.';
                } else {
                    $_SESSION['login_error'] = 'Account is not active.';
                }
            } else {
                $_SESSION['login_error'] = 'Invalid email or password.';
            }
            header("Location: " . BASE_URL . "/../login");
            exit;
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