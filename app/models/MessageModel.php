<?php
// MESSAGE Table Format:
// +-----------------------+---------------------------+------+-----+---------+-------+
// | Field                 | Type                      | Null | Key | Default | Extra |
// +-----------------------+---------------------------+------+-----+---------+-------+
// | Message_ID            | int                       | NO   | PRI | NULL    |       |
// | Content               | text                      | YES  |     | NULL    |       |
// | Sender_Type           | enum('client','provider') | YES  |     | NULL    |       |
// | Receiver_Type         | enum('client','provider') | YES  |     | NULL    |       |
// | Sent_At               | datetime                  | YES  |     | NULL    |       |
// | Delivered_At          | datetime                  | YES  |     | NULL    |       |
// | Read_At               | datetime                  | YES  |     | NULL    |       |
// | Status                | varchar(45)               | YES  |     | NULL    |       |
// | Provider_ID           | int                       | NO   | MUL | NULL    |       |
// | Client_ID             | int                       | NO   | MUL | NULL    |       |
// | Is_Read               | boolean                   | YES  |     | NULL    |       |
// +-----------------------+---------------------------+------+-----+---------+-------+
// Note: Is_Client_To_Provider = 1 means message sent from Client to Provider
// Todo: add Is_Read, Sender_Type, Receiver_Type fields to the table and remove Is_Client_To_Provider field. change date types to timestamp.

require_once __DIR__ . '/../core/Database.php';

class MessageModel extends Database {
    public function getUnreadMessageCountByClientId($clientId) {
        // $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM Message WHERE Client_ID = ? AND Is_Read = 0");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $count = $result->fetch_assoc()['count'];
        // $stmt->close();
        // return $count;
        return 5; // Placeholder
    } 

    public function getMessagesByClientId($clientId, $limit = 5, $offset = 0) {
        // $stmt = $this->conn->prepare("SELECT * FROM Message WHERE Client_ID = ? ORDER BY Sent_At DESC LIMIT ? OFFSET ?");
        // $stmt->bind_param("iii", $clientId, $limit, $offset);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    public function getMessagesByProviderId($providerId, $limit = 5, $offset = 0) {
        // $stmt = $this->conn->prepare("SELECT * FROM Message WHERE Provider_ID = ? ORDER BY Sent_At DESC LIMIT ? OFFSET ?");
        // $stmt->bind_param("iii", $providerId, $limit, $offset);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    public function getLatestMessagesByClientId($clientId, $sinceTimestamp, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Message WHERE Client_ID = ? AND Sent_At > ? ORDER BY Sent_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $clientId, $sinceTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    public function getLatestMessagesByProviderId($providerId, $sinceTimestamp, $limit = 5) {
        // $stmt = $this->conn->prepare("SELECT * FROM Message WHERE Provider_ID = ? AND Sent_At > ? ORDER BY Sent_At DESC LIMIT ?");
        // $stmt->bind_param("isi", $providerId, $sinceTimestamp, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return []; // Placeholder
    }

    public function markMessageAsRead($messageId) {
        // $stmt = $this->conn->prepare("UPDATE Message SET Is_Read = 1, Read_At = NOW() WHERE Message_ID = ?");
        // $stmt->bind_param("i", $messageId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }

    public function markAllMessagesAsReadByClientId($clientId) {
        // $stmt = $this->conn->prepare("UPDATE Message SET Is_Read = 1, Read_At = NOW() WHERE Client_ID = ?");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }

    public function markAllMessagesAsReadByProviderId($providerId) {
        // $stmt = $this->conn->prepare("UPDATE Message SET Is_Read = 1, Read_At = NOW() WHERE Provider_ID = ?");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $stmt->close();
        return true; // Placeholder
    }
}