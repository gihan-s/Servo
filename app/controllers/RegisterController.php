<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../models/SkillsModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/LocationModel.php';
require_once __DIR__ . '/../../helpers/upload.php';
require_once __DIR__ . '/../../helpers/email.php';

require_once __DIR__ . '/../services/provider.php';

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
        $_SESSION['register']['email'] = strtolower($_POST['email'] ?? '');
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

        $_SESSION['register']['facebook'] = $_POST['social_media_facebook'] ?? '';
        $_SESSION['register']['instagram'] = $_POST['social_media_instagram'] ?? '';
        $_SESSION['register']['tiktok'] = $_POST['social_media_tiktok'] ?? '';
        $_SESSION['register']['youtube'] = $_POST['social_media_youtube'] ?? '';
        $_SESSION['register']['github'] = $_POST['social_media_github'] ?? '';
        $_SESSION['register']['linkedin'] = $_POST['social_media_linkedin'] ?? '';

        if ($_FILES['profile_picture']['name'] != '') {
            $profile_picture = uploadFile('profile_picture', __DIR__ . '/../../uploads/temp/', 'image/*');
            if ($profile_picture) {
                $_SESSION['register']['profile_picture'] = $profile_picture; // just store the filename in session
            }
        }

        if ($_SESSION['register']['user_type'] == "provider") {
            header('Location: documents');
            exit;
        } else {
            header('Location: password');
            exit;
        }
    }


    public function checkEmail()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['email'])) {
            echo json_encode(['status' => 'error', 'message' => 'No email provided']);
            return;
        }

        $email = strtolower(trim($_POST['email']));
        $model = new ClientModel();

        if ($model->emailExists($email)) {
            echo json_encode(['status' => 'exists', 'message' => 'Email already exists']);
        } else {
            echo json_encode(['status' => 'ok', 'message' => 'Email available']);
        }
    }



    public function checkNIC()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['nic_no'])) {
            echo json_encode(['status' => 'error', 'message' => 'No NIC number provided']);
            return;
        }

        $email = trim($_POST['nic_no']);
        $model = new ProviderModel();

        if ($model->nicExists($email)) {
            echo json_encode(['status' => 'exists', 'message' => 'NIC already exists']);
        } else {
            echo json_encode(['status' => 'ok', 'message' => 'NIC available']);
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


        $targetDir = __DIR__ . '/../../uploads/temp/';
        $finalDir = __DIR__ . '/../../uploads/Users/';
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        if ($_SESSION['register']['profile_picture'] != '') {
            rename($targetDir . $_SESSION['register']['profile_picture'], $finalDir . $_SESSION['register']['profile_picture']);
        }

        

        if ($_SESSION['register']['user_type'] == 'client') {
            $model = new ClientModel();
            $userId = $model->insertClient($data);
            if ($userId) {
                $_SESSION["New_Register"] = "client";
                unset($_SESSION['register']);
                header('Location: ../login');
                exit;
            }
        } else if ($_SESSION['register']['user_type'] == 'provider') {

            if ($_SESSION['register']['nic_front'] != '') {
                rename($targetDir . $_SESSION['register']['nic_front'], $finalDir . $_SESSION['register']['nic_front']);
            }

            if ($_SESSION['register']['nic_back'] != '') {
                rename($targetDir . $_SESSION['register']['nic_back'], $finalDir . $_SESSION['register']['nic_back']);
            }

            if ($_SESSION['register']['resume'] != '') {
                rename($targetDir . $_SESSION['register']['resume'], $finalDir . $_SESSION['register']['resume']);
            }


            $model = new ProviderModel();
            $userId = $model->insertProvider($data);
            if ($userId) {

                $SocialMediaLinkElements = ["facebook", "instagram", "tiktok", "youtube", "github", "linkedin"];
                foreach ($SocialMediaLinkElements as $key => $value) {
                    $model->insertProviderSocialLinks($userId, $value, $data[$value]);
                }

                addServicesToProvider($data, $userId);

                $_SESSION['reg_pending_notice'] = "Provider";
                unset($_SESSION['register']);
                header('Location: ../login');
                exit;
            }
        }
    }



    public function documents()
    {
        include __DIR__ . '/../views/register/documents.php';
    }


    public function documentsubmit()
    {

        if ($_FILES['nic_front']['name'] != '') {
            $nic_front = uploadFile('nic_front', __DIR__ . '/../../uploads/temp/', 'image/*');
            if ($nic_front) {
                $_SESSION['register']['nic_front'] = $nic_front; // just store the filename in session
            }
        }

        if ($_FILES['nic_back']['name'] != '') {
            $nic_back = uploadFile('nic_back', __DIR__ . '/../../uploads/temp/', 'image/*');
            if ($nic_back) {
                $_SESSION['register']['nic_back'] = $nic_back; // just store the filename in session
            }
        }

        if ($_FILES['resume']['name'] != '') {
            $resume = uploadFile('resume', __DIR__ . '/../../uploads/temp/', ['application/pdf']);
            if ($resume) {
                $_SESSION['register']['resume'] = $resume; // just store the filename in session
            }
        }


        header('Location: services');
        exit;
    }


    public function services()
    {

        $model = new CategoryModel();
        $Categories = $model->getCategories();
        $model = new LocationModel();
        $Districts = $model->getDistricts();

        include __DIR__ . '/../views/register/services.php';
    }


    public function getCities()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['district'])) {
            echo json_encode(['status' => 'error', 'message' => 'No district provided']);
            return;
        }

        $District = trim($_POST['district']);
        $model = new LocationModel();
        echo json_encode(['status' => 'ok', 'result' => $model->getCities($District)]);
    }



    public function servicesubmit()
    {
        $_SESSION['register']['category_id'] = $_POST['category_id'] ?? [];
        $_SESSION['register']['category_name'] = $_POST['category_name'] ?? [];
        $_SESSION['register']['title'] = $_POST['title'] ?? [];
        $_SESSION['register']['description'] = $_POST['description'] ?? [];
        $_SESSION['register']['default_price'] = $_POST['default_price'] ?? [];
        $_SESSION['register']['skills'] = $_POST['skills'] ?? [];
        $_SESSION['register']['locations'] = $_POST['locations'] ?? [];

        $_SESSION['register']['portfolio_link'] = $_POST['portfolio_link'] ?? [];
        $_SESSION['register']['price_type'] = $_POST['price_type'] ?? [];
        $_SESSION['register']['price_negotiability'] = $_POST['price_negotiability'] ?? [];
        header('Location: password');
        exit;
    }


    public function getSkills()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['category_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'No category provided']);
            return;
        }

        $CategoryID = (int)trim($_POST['category_id']);
        $model = new SkillsModel();
        echo json_encode(['status' => 'ok', 'result' => $model->getByCategoryId($CategoryID)]);
    }


    public function sendEmailOTP()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['Email'])) {
            echo json_encode(['status' => 'error', 'message' => 'No email provided']);
            return;
        }

        $_SESSION['otp'] = random_int(100000, 999999);
        $_SESSION['otp_expire'] = time() + 300;

        $_SESSION['otp'] = 111111; // hardcode for testing, comment out in production


        $EmailTemplate = '
        <!DOCTYPE html>
        <html>
        <body style="font-family: Arial, sans-serif; color: #333333;">
            <p>Hello ' . $_POST["First_Name"] . ',</p>

            <p>Use the following One-Time Password (OTP) to complete your verification.</p>

            <p style="font-size: 24px; font-weight: bold; letter-spacing: 4px;">
            ' . $_SESSION['otp'] . '
            </p>

            <p>This OTP is valid for 5 minutes.</p>

            <p>If you did not request this, please ignore this email.</p>

            <p>Thanks,<br>Servo Team</p>
        </body>
        </html>
        ';

        $response = sendEmail(
            $_POST["Email"],
            $_POST["First_Name"] . " " . $_POST["Last_Name"],
            "Verify Your Email",
            $EmailTemplate
        );

        if (trim($response) == 'Success') {
            echo json_encode(['status' => 'success', 'message' => 'OTP Sent Success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $response]);
        }
    }

    public function verifyEmailOTP()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['OTP'])) {
            echo json_encode(['status' => 'error', 'message' => 'No OTP provided']);
            return;
        }

        if (time() > $_SESSION['otp_expire']) {
            echo json_encode(['status' => 'error', 'message' => 'OTP Expired']);
            return;
        }

        if ($_SESSION['otp'] == $_POST['OTP']) {
            echo json_encode(['status' => 'success', 'message' => 'OTP Verified']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
            return;
        }
    }
}
