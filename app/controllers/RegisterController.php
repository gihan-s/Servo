<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/LocationModel.php';

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
                mkdir($targetDir, 0777, true);
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
            header('Location: documents');
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

        $email = trim($_POST['email']);
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

                foreach ($data["category_id"] as $key => $value) {
                    $ProviderCategoryID = $model->insertProviderCategory($userId, $data, $key);

                    $Skills = json_decode($data["skills"][$key]);
                    foreach ($Skills as $key1 => $value1) {
                        $SkillID = $model->insertSkill($ProviderCategoryID, $value1);
                    }

                    $Locations = json_decode($data["locations"][$key]);
                    foreach ($Locations as $key2 => $value2) {
                        echo $value2;
                        $District = "";
                        $City = "";

                        if ($value2 == 'All Districts') {
                            $District = "All";
                            $City = "All";
                        } else if (strpos($value2, "District") > -1){
                            $District = str_replace(" District", "", $value2);
                        } else {
                            $City = $value2;
                        }

                        $LocationID = $model->getLocationID($District, $City)[0]["Location_ID"];

                        $model->insertLocation($ProviderCategoryID, $LocationID);
                        
                    }
                }

                $_SESSION["New_Register"] = "provider";
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
            $targetDir = __DIR__ . '/../../uploads/temp/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filename = uniqid() . '_' . $_FILES['nic_front']['name'];
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES['nic_front']['tmp_name'], $targetFile)) {
                $_SESSION['register']['nic_front'] = $filename; // just store the filename in session
            }
        }


        if ($_FILES['nic_back']['name'] != '') {
            $targetDir = __DIR__ . '/../../uploads/temp/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filename = uniqid() . '_' . $_FILES['nic_back']['name'];
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES['nic_back']['tmp_name'], $targetFile)) {
                $_SESSION['register']['nic_back'] = $filename; // just store the filename in session
            }
        }


        if ($_FILES['resume']['name'] != '') {
            $targetDir = __DIR__ . '/../../uploads/temp/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filename = uniqid() . '_' . $_FILES['resume']['name'];
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetFile)) {
                $_SESSION['register']['resume'] = $filename; // just store the filename in session
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

        $CategoryID = trim($_POST['category_id']);
        $model = new ProviderModel();
        echo json_encode(['status' => 'ok', 'result' => $model->getAllSkills($CategoryID)]);
    }

}
