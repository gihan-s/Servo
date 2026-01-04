<?php

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/NotificationModel.php';

class BaseController
{
    protected $clientModel;
    protected $providerModel;
    protected $notificationModel;

    protected $notifications = [];
    protected $messages = [];

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
        $this->notificationModel = new NotificationModel();
        $this->loadNotificationsAndMessages();
    }

    public function getNotificationsAndMessages($userId, $role)
    {
        if ($role === 'Client') {
           $notifications =$this->notificationModel->getLatestNotificationsByClientId($userId);
           $messages = []; // Placeholder for messages
        } elseif ($role === 'Provider') {
            $notifications = $this->notificationModel->getLatestNotificationsByProviderId($userId);
            $messages = []; // Placeholder for messages
        }
        return [];
    }

    // protected function ensureAuth(): void
    // {
    //     if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    //         $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
    //         header("Location: /login");
    //         exit;
    //     }
    // }
}