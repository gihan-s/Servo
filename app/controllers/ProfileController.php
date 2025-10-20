<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';

class ProfileController
{
    private $clientModel;
    private $providerModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
    }

    public function view()
    {
        $_SESSION['user_id'] = 3;
        $_SESSION['role'] = 'client';
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

    public function update()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $contact = $_POST['contact_no'];
        $gender = $_POST['gender'];
        $website = $_POST['website'];
        $bio = $_POST['bio'];



        
        $ok = false;
        if ($role === 'client') {
            $ok = $this->clientModel->updateProfile($userId, $firstName, $lastName, $contact, $gender, $website, $bio);
        } elseif ($role === 'provider') {
            $ok = $this->providerModel->updateProfile($userId, $firstName, $lastName, $contact, $gender, $website, $bio);
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid user role'];
            header("Location: /profile");
            exit;
        }

        $redirect = $_SERVER['HTTP_REFERER'] ?? "/profile";
        $_SESSION['flash'] = $ok
            ? ['type' => 'success', 'message' => 'Profile saved']
            : ['type' => 'error',   'message' => 'Error updating profile'];

        header("Location: " . $redirect);
        exit;
    }

    public function sendResetCode()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Not authorized']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        $user   = $role === 'client'
            ? $this->clientModel->getClientById($userId)
            : $this->providerModel->getProviderById($userId);

        if (!$user) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'User not found']);
            return;
        }

        $email = $_POST['email'] ?? $user['Email'] ?? '';
        if (!$email) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Email required']);
            return;
        }

        $code = random_int(100000, 999999);
        $_SESSION['pw_reset_code'] = $code;
        $_SESSION['pw_reset_expires'] = time() + 10 * 60; // 10 minutes

        // Simple mail (adjust from-address/headers to your SMTP setup)
        $subject = 'Your password reset code';
        $message = "Your password reset code is: {$code}\nThis code expires in 10 minutes.";
        @mail($email, $subject, $message, "From: no-reply@servo.com");

        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
    }

    // POST /profile/account
    public function account()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        $newPass = $_POST['New_Password'] ?? '';
        $conf    = $_POST['Confirm_Password'] ?? '';
        $codeIn  = trim($_POST['reset_code'] ?? '');

        $redirect = $_SERVER['HTTP_REFERER'] ?? "/profile";

        // If no password change requested
        if ($newPass === '' && $conf === '' && $codeIn === '') {
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'Nothing to update'];
            header("Location: " . $redirect);
            exit;
        }

        // Validate inputs
        if ($newPass === '' || $conf === '') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Enter new and confirm password'];
            header("Location: " . $redirect);
            exit;
        }
        if ($newPass !== $conf) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Passwords do not match'];
            header("Location: " . $redirect);
            exit;
        }
        if (empty($_SESSION['pw_reset_code']) || empty($_SESSION['pw_reset_expires'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Send the reset code first'];
            header("Location: " . $redirect);
            exit;
        }
        if (time() > (int)$_SESSION['pw_reset_expires']) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Reset code expired'];
            unset($_SESSION['pw_reset_code'], $_SESSION['pw_reset_expires']);
            header("Location: " . $redirect);
            exit;
        }
        if ((string)$_SESSION['pw_reset_code'] !== $codeIn) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid reset code'];
            header("Location: " . $redirect);
            exit;
        }

        // Fetch current user to compare password
        $user = $role === 'client'
            ? $this->clientModel->getClientById($userId)
            : $this->providerModel->getProviderById($userId);

        if (!$user || empty($user['Password'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found'];
            header("Location: " . $redirect);
            exit;
        }

        // Ensure new password is different
        if (password_verify($newPass, $user['Password'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'New password must be different from current'];
            header("Location: " . $redirect);
            exit;
        }

        // Update password
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $ok = $role === 'client'
            ? $this->clientModel->updatePassword($userId, $hash)
            : $this->providerModel->updatePassword($userId, $hash);

        if ($ok) {
            unset($_SESSION['pw_reset_code'], $_SESSION['pw_reset_expires']);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password updated'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error updating password'];
        }

        header("Location: " . $redirect);
        exit;
    }
}
