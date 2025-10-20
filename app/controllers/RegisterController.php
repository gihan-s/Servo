<?php

require_once __DIR__ . '/../models/ClientModel.php';

class RegisterController
{
    public function step1()
    {
        include __DIR__ . '/../views/register/step1.php';
    }

    public function step1submit()
    {
        $_SESSION['register']['user_type'] = $_POST['user_type'] ?? '';
        $_SESSION['register']['first_name'] = $_POST['first_name'] ?? '';
        $_SESSION['register']['last_name'] = $_POST['last_name'] ?? '';
        $_SESSION['register']['gender'] = $_POST['gender'] ?? '';
        $_SESSION['register']['email'] = $_POST['email'] ?? '';
        $_SESSION['register']['contact_no'] = $_POST['contact_no'] ?? '';
        $_SESSION['register']['nic_no'] = $_POST['nic_no'] ?? '';
        header('Location: profile');
        exit;
    }

    public function step2()
    {
        include __DIR__ . '/../views/register/step2.php';
    }

    public function step2submit()
    {
        $_SESSION['register']['bio'] = $_POST['bio'] ?? '';
        $_SESSION['register']['website'] = $_POST['website'] ?? '';

        if ($_FILES['profile_picture']['name'] != '') {
            $targetDir = __DIR__ . '/../../uploads/temp/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $filename = uniqid() . '_' . $_FILES['profile_picture']['name'];
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $_SESSION['register']['profile_picture'] = $filename; // just store the filename in session
            }
        }

        if ($_SESSION['register']['user_type'] == "client") {

            header('Location: password');
            exit;
        } else {
        }
    }


    public function password()
    {
        include __DIR__ . '/../views/register/password.php';
    }

    public function passwordsubmit()
    {
        $_SESSION['register']['password'] = $_POST['password'] ?? '';
        $data = $_SESSION['register'] ?? [];

        if ($_SESSION['register']['user_type'] == 'client') {
            $model = new UserModel();
            $userId = $model->insertUser($data);
            if ($userId) {
                $_SESSION["New_Register"] = true;
                header('Location: ../login');
                exit;
            }
        }
    }
}
