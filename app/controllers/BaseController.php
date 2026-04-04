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

    protected $unreadNotificationCount = 0;
    protected $notifications = [];
    protected $lastNotifTimestamp = null; // latest loaded notification timestamp
    protected $firstNotifTimestamp = null; // oldest loaded notification timestamp

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
        $this->notificationModel = new NotificationModel();
        $this->ensureAuth();
        $this->loadNotifs($_SESSION['user_id'], $_SESSION['role']);
        return;
    }

    // method to process and append notifications to controller
    protected function appendNotifications($unprocessedNotifications, &$notifications)
    {
        foreach ($unprocessedNotifications as $notif) {
            $data = json_decode($notif['Data'], true);
            $notifications[] = array_merge($notif, $data);
        }
        return;
    } 

    public function loadNotifs($userId, $role)
    {
        $notifications = [];
        $unprocessedNotifications = [];
        $unreadNotificationCount = 0;

        if ($role === 'Client') {
            $unprocessedNotifications = $this->notificationModel->getNotificationsByClientId($userId, 10);
            $unreadNotificationCount = $this->notificationModel->getUnreadNotificationsCountByClientId($userId);
            $this->appendNotifications($unprocessedNotifications, $notifications);
        } elseif ($role === 'Provider') {
            $unprocessedNotifications = $this->notificationModel->getNotificationsByProviderId($userId, 10);
            // $unreadNotificationCount = $this->notificationModel->getUnreadNotificationsCountByProviderId($userId);
            $this->appendNotifications($unprocessedNotifications, $notifications);
        }

        $this->unreadNotificationCount = $unreadNotificationCount;
        $this->notifications = $notifications;
        // update notification timestamps
        $this->lastNotifTimestamp = !empty($notifications) ? $notifications[0]['Created_At'] : null;
        $this->firstNotifTimestamp = !empty($notifications) ? $notifications[count($notifications) - 1]['Created_At'] : null;

        return;
    }

    protected function ensureAuth(): void
    {
        if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in first'];
            header("Location: /login");
            exit;
        }
        return;
    }
}