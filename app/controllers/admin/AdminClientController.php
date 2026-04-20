<?php

require_once __DIR__ . '/../../models/ClientModel.php';
include_once '../helpers/email.php';

class AdminClientController
{
    private function guardAdmin()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
            header("Location: /admin/login");
            exit;
        }
    }

    public function index()
    {
        $this->guardAdmin();

        $model = new ClientModel();

        $limit = 10;
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $users      = $model->getAllClients($limit, $offset);
        $totalUsers = $model->getClientCount();
        $totalPages = ceil($totalUsers / $limit);

        $totalClients  = $totalUsers;
        $activeClients = $model->getCountByStatus('Active');
        $bannedClients        = $model->getCountByStatus('Banned');
        $newClientsThisMonth   = $model->getNewClientsThisMonth();

        include __DIR__ . '/../../views/admin/clients.php';
    }

    public function view($id)
    {
        $this->guardAdmin();

        $id    = (int)$id;
        $model = new ClientModel();
        $user  = $model->getClientById($id);

        if (!$user) {
            header("Location: /admin/clients");
            exit;
        }

        $stats       = $model->getClientStats($id);
        $recentPosts = $model->getRecentClientPosts($id);

        include __DIR__ . '/../../views/admin/clientView.php';
    }

    public function api($id)
    {
        $this->guardAdmin();

        $id    = (int)$id;
        $model = new ClientModel();
        $user  = $model->getClientById($id);

        if (!$user) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Client not found']);
            exit;
        }

        // Remove sensitive field
        unset($user['Password']);

        $stats       = $model->getClientStats($id);
        $recentPosts = $model->getRecentClientPosts($id);

        header('Content-Type: application/json');
        echo json_encode([
            'user'  => $user,
            'stats' => $stats,
            'posts' => $recentPosts,
        ]);
        exit;
    }

    public function ban()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/clients");
            exit;
        }

        $clientId = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
        $reason   = isset($_POST['ban_reason']) ? trim($_POST['ban_reason']) : '';

        if (!$clientId || $reason === '') {
            header("Location: /admin/clients");
            exit;
        }

        $model         = new ClientModel();
        $clientDetails = $model->getClientById($clientId);

        if (!$clientDetails) {
            header("Location: /admin/clients");
            exit;
        }

        $model->updateClientStatus($clientId, 'Banned');

        $clientName  = htmlspecialchars($clientDetails['First_Name'] . ' ' . $clientDetails['Last_Name']);
        $domain      = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];
        $safeReason  = htmlspecialchars($reason);

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
                        <p style="color:#555; font-size:14px;">Hello ' . $clientName . ',</p>
                        <p style="color:#555; font-size:14px;">
                            Your client account on Servo has been banned by the administration.
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
            $clientDetails['Email'],
            $clientName,
            "Your Servo Account Has Been Banned",
            $emailTemplate
        );

        header("Location: /admin/clients");
        exit;
    }

    public function unban()
    {
        $this->guardAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/clients");
            exit;
        }

        $clientId = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;

        if (!$clientId) {
            header("Location: /admin/clients");
            exit;
        }

        $model         = new ClientModel();
        $clientDetails = $model->getClientById($clientId);

        if (!$clientDetails) {
            header("Location: /admin/clients");
            exit;
        }

        $model->updateClientStatus($clientId, 'Active');

        $clientName = htmlspecialchars($clientDetails['First_Name'] . ' ' . $clientDetails['Last_Name']);
        $domain     = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];

        $emailTemplate = '
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"><title>Account Reinstated</title></head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color:#ffffff; border-radius:6px;">
                    <tr><td>
                        <h2 style="color:#27ae60;">Your Account Has Been Reinstated</h2>
                        <p style="color:#555; font-size:14px;">Hello ' . $clientName . ',</p>
                        <p style="color:#555; font-size:14px;">
                            Your client account on Servo has been reinstated. You can now log in and continue using the platform.
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
            $clientDetails['Email'],
            $clientName,
            "Your Servo Account Has Been Reinstated",
            $emailTemplate
        );

        header("Location: /admin/clients");
        exit;
    }
}
