<?php

require_once __DIR__ . '/../../models/ProviderModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/SkillsModel.php';
require_once __DIR__ . '/../../models/LocationModel.php';
include_once '../helpers/email.php';

class AdminProviderController
{

    public function index()
    {

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }

        $model = new ProviderModel();

        // Pagination setup
        $limit = 10; // users per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        $users = $model->getAllProviders($limit, $offset);
        $totalUsers = $model->getUserCount();
        $totalPages = ceil($totalUsers / $limit);

        $totalProviders   = $totalUsers;
        $pendingProviders = $model->getCountByStatus('Pending');
        $activeProviders  = $model->getCountByStatus('Active');
        $bannedProviders  = $model->getCountByStatus('Banned');

        include __DIR__ . '/../../views/admin/providers.php';
    }


    public function view($id)
    {
        $ProviderModel = new ProviderModel();
        $user = $ProviderModel->getProviderById($id);
        if (!$user) {
            echo "<p style='color:red;'>Provider not found</p>";
            return;
        }

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getByProviderId($id);
        $categoryIds = array_column($categories, 'ID');


        $skillModel = new SkillsModel();
        $skills    = $skillModel->getSkillsByProviderID($id);
        
        // $skills = [];

        $locationModel = new LocationModel();
        $locations = $locationModel->getByProviderCategoryIds($categoryIds);

        foreach ($categories as &$category) {
            $categoryId = $category['ID'];
            $category['Skills']    = $skills[$categoryId] ?? [];
            $category['Locations'] = $locations[$categoryId] ?? [];
        }


        $user['Categories'] = $categories;

        include __DIR__ . '/../../views/admin/providerView.php';
    }


    public function review()
    {
        $model = new ProviderModel();

        $ProviderDetails = $model->getProviderById($_POST["provider_id"]);
        $ProviderName = $ProviderDetails['First_Name'] . " " . $ProviderDetails['Last_Name'];


        $Status = "";
        if (isset($_POST["accept"])) {
            $Status = "Active";

            $domain = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];

            $EmailTemplate = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Account Approved</title>
            </head>
            <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                <td align="center">
                    <table width="600" cellpadding="20" cellspacing="0" style="background-color: #ffffff; border-radius: 6px;">
                    <tr>
                        <td>
                        <h2 style="color: #333;">Your Account Has Been Approved</h2>
                        <p style="color: #555; font-size: 14px;">
                            Hello ' . $ProviderName . ',
                        </p>
                        <p style="color: #555; font-size: 14px;">
                            We are happy to let you know that your account has been approved. You can now log in and start using our platform.
                        </p>
                        <p style="margin: 20px 0;">
                            <a href="https://' . $domain . '/login" 
                            style="background-color: #008500; color: #ffffff; padding: 10px 16px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                            Log In
                            </a>
                        </p>
                        <p style="color: #777; font-size: 12px;">
                            If you have any questions, feel free to contact our support team.
                        </p>
                        <p style="color: #777; font-size: 12px;">
                            - The Servo Team
                        </p>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>
            ';

            sendEmail(
                $ProviderDetails['Email'],
                $ProviderName,
                "Your Registration Has Been Approved",
                $EmailTemplate
            );
            
            $_POST['reason_for_rejection'] = null;
        } else if (isset($_POST["reject"])) {
            $Status = "Rejected";

            $EmailTemplate = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Registration Request Rejected</title>
            </head>
            <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                <td align="center">
                    <table width="600" cellpadding="20" cellspacing="0" style="background-color: #ffffff; border-radius: 6px;">
                    <tr>
                        <td>
                        <h2 style="color: #333;">Registration Request Rejected</h2>

                        <p style="color: #555; font-size: 14px;">
                            Hello '. $ProviderName .',
                        </p>

                        <p style="color: #555; font-size: 14px;">
                            Thank you for your interest in Servo. After reviewing your registration request, we are unable to approve it at this time.
                        </p>

                        <p style="color: #555; font-size: 14px;">
                            <strong>Reason:</strong> '. $_POST['reason_for_rejection'] .'
                        </p>

                        <p style="color: #555; font-size: 14px;">
                            You may review your information and submit a new request if applicable. If you believe this decision was made in error, please contact our support team.
                        </p>

                        <p style="color: #777; font-size: 12px;">
                            Thank you for your understanding.
                        </p>

                        <p style="color: #777; font-size: 12px;">
                            - The Servo Team
                        </p>

                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>

            ';

            sendEmail(
                $ProviderDetails['Email'],
                $ProviderName,
                "Update on Your Registration Request",
                $EmailTemplate
            );
        } else {
            return;
        }

        $model->updateProviderStatus($_POST["provider_id"], $Status, $_POST['reason_for_rejection']);

        header("Location: ../Providers");
    }

    public function ban()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/providers");
            exit;
        }

        $providerId = isset($_POST['provider_id']) ? (int)$_POST['provider_id'] : 0;
        $reason     = isset($_POST['ban_reason']) ? trim($_POST['ban_reason']) : '';

        if (!$providerId || $reason === '') {
            header("Location: /admin/providers");
            exit;
        }

        $model           = new ProviderModel();
        $providerDetails = $model->getProviderById($providerId);

        if (!$providerDetails) {
            header("Location: /admin/providers");
            exit;
        }

        $model->updateProviderStatus($providerId, 'Banned', $reason);

        $providerName  = htmlspecialchars($providerDetails['First_Name'] . ' ' . $providerDetails['Last_Name']);
        $domain        = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];
        $safeReason    = htmlspecialchars($reason);

        $emailTemplate = '
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Account Banned</title></head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color:#ffffff; border-radius:6px;">
                    <tr><td>
                        <h2 style="color:#c0392b;">Your Account Has Been Banned</h2>
                        <p style="color:#555; font-size:14px;">Hello ' . $providerName . ',</p>
                        <p style="color:#555; font-size:14px;">
                            Your provider account on Servo has been banned by the administration.
                        </p>
                        <p style="color:#555; font-size:14px;"><strong>Reason:</strong> ' . $safeReason . '</p>
                        <p style="color:#555; font-size:14px;">
                            If you believe this is a mistake, please contact our support team.
                        </p>
                        <p style="color:#777; font-size:12px;">- The Servo Team</p>
                    </td></tr>
                </table>
            </td></tr>
        </table>
        </body>
        </html>';

        sendEmail(
            $providerDetails['Email'],
            $providerName,
            "Your Servo Account Has Been Banned",
            $emailTemplate
        );

        header("Location: /admin/providers");
        exit;
    }

    public function unban()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/providers");
            exit;
        }

        $providerId = isset($_POST['provider_id']) ? (int)$_POST['provider_id'] : 0;

        if (!$providerId) {
            header("Location: /admin/providers");
            exit;
        }

        $model           = new ProviderModel();
        $providerDetails = $model->getProviderById($providerId);

        if (!$providerDetails) {
            header("Location: /admin/providers");
            exit;
        }

        $model->updateProviderStatus($providerId, 'Active', null);

        $providerName  = htmlspecialchars($providerDetails['First_Name'] . ' ' . $providerDetails['Last_Name']);
        $domain        = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];

        $emailTemplate = '
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Account Unbanned</title></head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color:#ffffff; border-radius:6px;">
                    <tr><td>
                        <h2 style="color:#27ae60;">Your Account Has Been Reinstated</h2>
                        <p style="color:#555; font-size:14px;">Hello ' . $providerName . ',</p>
                        <p style="color:#555; font-size:14px;">
                            Your provider account on Servo has been reinstated. You can now log in and continue providing services.
                        </p>
                        <p style="margin:20px 0;">
                            <a href="https://' . $domain . '/login"
                            style="background-color:#27ae60; color:#fff; padding:10px 16px; text-decoration:none; border-radius:4px; font-size:14px;">
                            Log In
                            </a>
                        </p>
                        <p style="color:#777; font-size:12px;">If you have any questions, please contact our support team.</p>
                        <p style="color:#777; font-size:12px;">- The Servo Team</p>
                    </td></tr>
                </table>
            </td></tr>
        </table>
        </body>
        </html>';

        sendEmail(
            $providerDetails['Email'],
            $providerName,
            "Your Servo Account Has Been Reinstated",
            $emailTemplate
        );

        header("Location: /admin/providers");
        exit;
    }
}
