<?php

require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProviderModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';

class BaseController
{
    protected $clientModel;
    protected $providerModel;
    protected $notificationModel;

    protected $unreadNotificationCount = 2;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
        $this->notificationModel = new NotificationModel();

        $this->ensureAuth();
        $this->loadNotifs($_SESSION['user_id'], $_SESSION['role']);
    }

    public function loadNotifs($userId, $role)
    {
        $this->notifications = [];
        $this->unprocessedNotifications = [];
        $this->unreadNotificationCount = 0;
        if ($role === 'Client') {
            $this->unprocessedNotifications = $this->notificationModel->getNotificationsByClientId($userId, 10);
            $this->unreadNotificationCount = $this->notificationModel->getUnreadNotificationsCountByClientId($userId);
            foreach ($this->unprocessedNotifications as $notif) {
                $data = json_decode($notif['Data'], true);
                $this->notifications[] = array_merge($notif, $data);
            }
        } elseif ($role === 'Provider') {
            $this->unprocessedNotifications = $this->notificationModel->getNotificationsByProviderId($userId, 10);
            $this->unreadNotificationCount = $this->notificationModel->getUnreadNotificationsCountByProviderId($userId);
            foreach ($this->unprocessedNotifications as $notif) {
                $data = json_decode($notif['Data'], true);
                $this->notifications[] = array_merge($notif, $data);
            }
        }
        return;
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