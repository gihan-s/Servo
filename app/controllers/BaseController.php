<?php

require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../models/MessageModel.php';

class BaseController
{
    protected $clientModel;
    protected $providerModel;
    protected $notificationModel;
    protected $messageModel;

    protected $notifications = [];
    protected $messages = [];

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
        $this->notificationModel = new NotificationModel();
        $this->messageModel = new MessageModel();

        $this->ensureAuth();
        $this->loadNotificationsAndMessages($_SESSION['user_id'], $_SESSION['role']);
    }

    public function loadNotificationsAndMessages($userId, $role)
    {
        if ($role === 'Client') {
            $notifications = $this->notificationModel->getNotificationsByClientId($userId, 10);
            $messages = $this->messageModel->getMessagesByClientId($userId, 10);
        } elseif ($role === 'Provider') {
            $notifications = $this->notificationModel->getNotificationsByProviderId($userId, 10);
            $messages = $this->messageModel->getMessagesByProviderId($userId, 10);
        }
        return [];
    }

    protected function ensureAuth(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }
    }
}