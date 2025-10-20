<?php

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
            $filename = uniqid() . '_' . $_FILES['profile_picture']['name'];
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $_SESSION['register']['profile_picture'] = $filename; // just store the filename in session
            }
        }
        
    }
}
