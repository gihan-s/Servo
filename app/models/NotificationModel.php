<?php
// NOTIFICATION Table Format:
// +-----------------+-------------+------+-----+---------+-------+
// | Field           | Type        | Null | Key | Default | Extra |
// +-----------------+-------------+------+-----+---------+-------+
// | Notification_ID | int         | NO   | PRI | NULL    |       |
// | Client_ID       | int         | YES  | MUL | NULL    |       |
// | Provider_ID     | int         | YES  | MUL | NULL    |       |
// | Type            | string      | YES  |     | NULL    |       |
// | Data            | json        | YES  |     | NULL    |       |
// | Is_Read         | boolean     | YES  |     | NULL    |       |
// | Created_At      | timestamp   | NO   |     | NULL    |       |
// | Read_At         | timestamp   | YES  |     | NULL    |       |
// | Action_URL      | varchar(255)| YES  |     | NULL    |       |
// +-----------------+-------------+------+-----+---------+-------+

// Notification Data JSON Structure Examples:
// {
//     "Title": "Your appointment is confirmed.",
//     "Content": 12345
// }

require_once __DIR__ . '/../core/Database.php';

class NotificationModel extends Database {
    public function getUnreadNotificationsCountByClientId($clientId) {
        // $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM Notification WHERE Client_ID = ? AND Is_Read = 0");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $count = $result->fetch_assoc()['count'];
        // $stmt->close();
        // return $count;
        return 5; // Placeholder
    } 

    // direct method to get notifications for a client
    public function getNotificationsByClientId($clientId, $limit = 5, $beforeTimestamp = null) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Client_ID = ? AND Created_At < ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $clientId, $beforeTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return
            [
                ['Notification_ID' => 1, 'Data' => '{"Title": "Payment of $250 released for Project Alpha."}', 'Created_At' => '2025-12-30 11:39:00', 'Type' => 'payment', 'Is_Read' => true],
                ['Notification_ID' => 2, 'Data' => '{"Title": "New bid received on your post: UI Revamp"}', 'Created_At' => '2025-12-30 11:30:00', 'Type' => 'bid', 'Is_Read' => true],
                ['Notification_ID' => 3, 'Data' => '{"Title": "DevStudio Labs sent you a message."}', 'Created_At' => '2025-12-30 11:22:00', 'Type' => 'message', 'Is_Read' => false],
                ['Notification_ID' => 4, 'Data' => '{"Title": "Contract milestone approved."}', 'Created_At' => '2025-12-30 10:00:00', 'Type' => 'milestone', 'Is_Read' => false],
                ['Notification_ID' => 5, 'Data' => '{"Title": "New bid received on your post: Mobile App Development"}', 'Created_At' => '2025-12-29 16:45:00', 'Type' => 'bid', 'Is_Read' => true],
            ]; // Placeholder
    }

    public function getUnreadNotificationsCountByProviderId($providerId) {
        // $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM Notification WHERE Provider_ID = ? AND Is_Read = 0");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $count = $result->fetch_assoc()['count'];
        // $stmt->close();
        // return $count;
        return 5; // Placeholder
    } 

    // direct method to get notifications for a provider
    public function getNotificationsByProviderId($providerId, $limit = 5, $beforeTimestamp = null) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Provider_ID = ? AND Created_At < ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $providerId, $beforeTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    // method used for polling latest notifications since a given timestamp
    public function getLatestNotificationsByClientId($clientId, $sinceTimestamp, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Client_ID = ? AND Created_At > ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $clientId, $sinceTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    // method used for polling latest notifications since a given timestamp
    public function getLatestNotificationsByProviderId($providerId, $sinceTimestamp, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Provider_ID = ? AND Created_At > ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $providerId, $sinceTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    public function markNotificationAsRead($notificationId) {
        // $stmt = $this->conn->prepare("UPDATE Notification SET Is_Read = 1, Read_At = NOW() WHERE Notification_ID = ?");
        // $stmt->bind_param("i", $notificationId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }

    public function markAllNotificationsAsReadByClientId($clientId) {
        // $stmt = $this->conn->prepare("UPDATE Notification SET Is_Read = 1, Read_At = NOW() WHERE Client_ID = ?");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }

    public function markAllNotificationsAsReadByProviderId($providerId) {
        // $stmt = $this->conn->prepare("UPDATE Notification SET Is_Read = 1, Read_At = NOW() WHERE Provider_ID = ?");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }

    public function createNotification($data) {
        // $stmt = $this->conn->prepare("INSERT INTO Notification (Client_ID, Provider_ID, Type, Data, Is_Read, Created_At, Action_URL) VALUES (?, ?, ?, ?, 0, NOW(), ?)");
        // $stmt->bind_param("iisss", $data['client_id'], $data['provider_id'], $data['type'], json_encode($data['data']), $data['action_url']);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }
}