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

    public function getLatestNotificationsByClientId($clientId, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Client_ID = ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("ii", $clientId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return null; // Placeholder
    }

    public function getLatestNotificationsByProviderId($providerId, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Notification WHERE Provider_ID = ? ORDER BY Created_At DESC LIMIT ?");
        // $stmt->bind_param("ii", $providerId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return null; // Placeholder
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
}