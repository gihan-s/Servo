<?php

require_once __DIR__ . '/../../helpers/upload.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/LocationModel.php';

require_once __DIR__ . '/../services/provider.php';


class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function view()
    {
        // Step 1: Check if user is logged in
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        // Step 2: Load the correct model based on role
        if ($role === 'Client') {
            $user = $this->clientModel->getClientById($userId);
            $viewFile = __DIR__ . '/../views/client/Profile/index.php';
        } elseif ($role === 'Provider') {
            $user = $this->providerModel->getProviderById($userId);


            $model = new CategoryModel();
            $Categories = $model->getCategories();
            $model = new LocationModel();
            $Districts = $model->getDistricts();


            $viewFile = __DIR__ . '/../views/provider/Profile/index.php';
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
        if ($role === 'Client') {
            $ok = $this->clientModel->updateProfile($userId, $firstName, $lastName, $contact, $gender, $website, $bio);

            $_SESSION['user_name'] = $firstName . ' ' . $lastName; // Update session name for immediate UI update
        } elseif ($role === 'Provider') {


            $Resume = uploadFile("Resume",  __DIR__ . '/../../uploads/Users/', "application/pdf");

            $ok = $this->providerModel->updateProfile($userId, $firstName, $lastName, $contact, $gender, $website, $bio, $Resume);

            $_SESSION['user_name'] = $firstName . ' ' . $lastName; // Update session name for immediate UI update
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid user role'];
            header("Location: /profile");
            exit;
        }


        $returnValue = $ok
            ? ['type' => 'success', 'message' => 'Profile saved']
            : ['type' => 'error',   'message' => 'Error updating profile'];

        header('Content-Type: application/json');
        echo json_encode($returnValue);

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
        $this->ensureAuth();

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
        $user = $role === 'Client'
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
        $ok = $role === 'Client'
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

    // POST /profile/delete-account
    public function deleteAccount()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        $ok = false;
        if ($role === 'Client') {
            $ok = $this->clientModel->deleteClient($userId);
        } elseif ($role === 'Provider') {
            $ok = $this->providerModel->deleteProvider($userId);
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid user role'];
            header("Location: /profile");
            exit;
        }

        if ($ok) {
            session_destroy();
            header("Location: /home");
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Error deleting account'];
            header("Location: /profile");
            exit;
        }
    }

    public function changeProfilePicture()
    {
        $this->ensureAuth();

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];

        if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'No file uploaded']);
            return;
        }

        $profilePic = uploadFile('profile_pic', __DIR__ . '/../../uploads/Users/', 'image/*');
        if (!$profilePic) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'File upload failed']);
            return;
        }

        $ok = false;
        if ($role === 'Client') {
            $ok = $this->clientModel->updateProfilePicture($userId, $profilePic);
            $_SESSION['user_image'] = $profilePic; // Update session image for immediate UI update
        } elseif ($role === 'Provider') {
            $ok = $this->providerModel->updateProfilePicture($userId, $profilePic);
            $_SESSION['user_image'] = $profilePic; // Update session image for immediate UI update
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid user role'];
            header("Location: /profile");
            exit;
        }

        $return = $ok
            ? ['type' => 'success', 'message' => 'Profile picture saved', 'Profile_Picture' =>  BASE_URL . '/file/user-files/' . $profilePic]
            : ['type' => 'error',   'message' => 'Error updating profile picture'];

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function changePassword()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Not authorized']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        if ($role === 'Client') {
            $model  = $this->clientModel;
        } elseif ($role === 'Provider') {
            $model  = $this->providerModel;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Invalid user role']);
            return;
        }

        if (!$model) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'User not found']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $CurrentPassword = $model->getCurrentPassword($userId);

        if (password_verify($data["Old_Password"], $CurrentPassword)) {
            $hashed = password_hash($data["New_Password"], PASSWORD_DEFAULT);
            if ($model->updatePassword($userId, $hashed)) {
                header('Content-Type: application/json');
                echo json_encode(['ok' => true, 'message' => 'Password Change Successful']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'message' => 'Error updating password']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Old password is incorrect']);
            return;
        }
    }


    public function addService()
    {
        $this->ensureAuth();
        if (addServicesToProvider($_POST, $_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'New service added successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error adding service']);
        }
    }

    public function removeService()
    {
        $this->ensureAuth();

        $serviceId = $_POST['provider_category_id'] ?? null;

        if (!$serviceId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Service ID is required']);
            return;
        }

        if ($this->providerModel->removeService($serviceId)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Service removed successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error removing service']);
        }
    }
}
