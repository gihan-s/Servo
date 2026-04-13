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
// | Conversation_ID       | int                       | YES  | MUL | NULL    |       |
// +-----------------------+---------------------------+------+-----+---------+-------+

// CONVERSATION Table Format:
// +-----------------------+---------------------------+------+-----+---------+-------+
// | Field                 | Type                      | Null | Key | Default | Extra |
// +-----------------------+---------------------------+------+-----+---------+-------+
// | Conversation_ID       | int                       | NO   | PRI | NULL    |       |
// | Project_ID            | int                       | YES  | MUL | NULL    |       |
// | Title                 | varchar(100)              | YES  |     | NULL    |       |
// | Last_Message_At       | datetime                  | YES  |     | NULL    |       |
// | Unread_Count_Client   | int                       | YES  |     | NULL    |       |
// | Unread_Count_Provider | int                       | YES  |     | NULL    |       |
// | Starred_By_Client     | boolean                   | YES  |     | NULL    |       |
// | Starred_By_Provider   | boolean                   | YES  |     | NULL    |       |
// +-----------------------+---------------------------+------+-----+---------+-------+

// Note: Is_Client_To_Provider = 1 means message sent from Client to Provider
// Todo: add Is_Read, Sender_Type, Receiver_Type, Conversation_ID fields to the table and remove Is_Client_To_Provider field. change date types to timestamp.
// Todo: add a Conversation table to group messages between a client and provider. Title set by Project title if applicable.

require_once __DIR__ . '/../core/Database.php';

class MessageModel extends Database
{
    public function getAllConversations($User_ID, $Role)
    {

        if ($Role === 'Provider') {
            $sql = "SELECT 
                c.Client_ID as id,
                c.First_Name,
                c.Last_Name,
                c.Profile_Picture,
                m.Content AS last_message,
                COALESCE(u.unread_count, 0) AS unread_count,
                lm.last_message_time
            FROM conversation conv
            INNER JOIN client c
                ON conv.Client_ID = c.Client_ID
            INNER JOIN messages m
                ON m.Conversation_ID = conv.ID
            INNER JOIN (
                -- latest message per conversation
                SELECT Conversation_ID, MAX(Sent_At) AS last_message_time
                FROM messages
                GROUP BY Conversation_ID
            ) lm
                ON lm.Conversation_ID = m.Conversation_ID
                AND lm.last_message_time = m.Sent_At
            LEFT JOIN (
                -- unread count per conversation
                SELECT Conversation_ID, COUNT(*) AS unread_count
                FROM messages
                WHERE (Status = 'Sent' OR Status = 'Delivered')
                AND Is_Client_To_Provider = 1
                GROUP BY Conversation_ID
            ) u
                ON u.Conversation_ID = conv.ID
            WHERE conv.Provider_ID = ?
            ORDER BY lm.last_message_time DESC;
            ";
        } else if ($Role === 'Client') {

            $sql = "SELECT 
                p.Provider_ID as id,
                p.First_Name,
                p.Last_Name,
                p.Profile_Picture,
                m.Content AS last_message,
                COALESCE(u.unread_count, 0) AS unread_count,
                lm.last_message_time
            FROM conversation conv
            INNER JOIN provider p
                ON conv.Provider_ID = p.Provider_ID
            INNER JOIN messages m
                ON m.Conversation_ID = conv.ID
            INNER JOIN (
                -- latest message per conversation
                SELECT Conversation_ID, MAX(Sent_At) AS last_message_time
                FROM messages
                GROUP BY Conversation_ID
            ) lm
                ON lm.Conversation_ID = m.Conversation_ID
                AND lm.last_message_time = m.Sent_At
            LEFT JOIN (
                -- unread count per conversation
                SELECT Conversation_ID, COUNT(*) AS unread_count
                FROM messages
                WHERE (Status = 'Sent' OR Status = 'Delivered')
                AND Is_Client_To_Provider = 0
                GROUP BY Conversation_ID
            ) u
                ON u.Conversation_ID = conv.ID
            WHERE conv.Client_ID = ?
            ORDER BY lm.last_message_time DESC;
            ";
        } else {
            return;
        }


        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $User_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getMessagesByID($User_ID, $Role, $OtherUser_ID)
    {
        // Determine provider and client based on role
        if ($Role === 'Provider') {
            $Provider_ID = $User_ID;
            $Client_ID = $OtherUser_ID;
        } else if ($Role === 'Client') {
            $Provider_ID = $OtherUser_ID;
            $Client_ID = $User_ID;
        } else {
            return [];
        }

        // 1️⃣ Get the conversation ID
        $stmt = $this->conn->prepare(
            "SELECT ID FROM conversation WHERE Provider_ID = ? AND Client_ID = ? LIMIT 1"
        );
        $stmt->bind_param("ii", $Provider_ID, $Client_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $conversation = $result->fetch_assoc();
        $stmt->close();

        if (!$conversation) {
            // No conversation exists yet
            return [];
        }

        $Conversation_ID = $conversation['ID'];

        // 2️⃣ Get all messages for that conversation
        $sql = "SELECT 
                m.Message_ID as id, 
                m.Content as text, 
                " . ($Role === 'Provider' ? "NOT(m.Is_Client_To_Provider)" : "m.Is_Client_To_Provider") . " AS self, 
                m.Sent_At, 
                m.Status
            FROM messages m
            WHERE m.Conversation_ID = ?
            ORDER BY m.Sent_At";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $Conversation_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function insertMessage($Content, $ClientToProvider, $Provider_ID, $Client_ID)
    {
        // 1. Create or get existing conversation (race-condition safe)
        $stmt = $this->conn->prepare(
            "INSERT INTO conversation (Provider_ID, Client_ID)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE ID = LAST_INSERT_ID(ID)"
        );

        if (!$stmt) {
            die("Prepare failed (conversation): " . $this->conn->error);
        }

        $stmt->bind_param("ii", $Provider_ID, $Client_ID);
        $stmt->execute();

        // This works for BOTH insert and existing row
        $Conversation_ID = $stmt->insert_id;
        $stmt->close();

        // 2. Insert message
        $stmt = $this->conn->prepare(
            "INSERT INTO messages 
        (Content, Is_Client_To_Provider, Sent_At, Status, Conversation_ID) 
        VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed (message): " . $this->conn->error);
        }

        $date = date("Y-m-d H:i:s");
        $Status = "Sent";

        $stmt->bind_param(
            "sissi",
            $Content,
            $ClientToProvider,
            $date,
            $Status,
            $Conversation_ID
        );

        if ($stmt->execute()) {
            $message_id = $stmt->insert_id;
            $stmt->close();
            return $message_id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }

    public function updateMessageStatus($id, $status)
    {
        $stmt = $this->conn->prepare(
            "UPDATE messages SET Status = ? WHERE Message_ID = ?"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "si",
            $status,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }

    public function updateMessageStatusByReceiver($User_ID, $Role, $status)
    {
        $UserFilter = '';
        if ($Role === 'Provider') {
            $UserFilter = 'AND conv.Provider_ID = ? AND m.Is_Client_To_Provider = 1';
        } else if ($Role === 'Client') {
            $UserFilter = 'AND conv.Client_ID = ? AND m.Is_Client_To_Provider = 0';
        } else {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE messages m
            INNER JOIN conversation conv
                ON m.Conversation_ID = conv.ID
            SET m.Status = ?
            WHERE m.Status = 'Sent' {$UserFilter}"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "si",
            $status,
            $User_ID
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function updateMessageStatusBySenderAndReceiver($Sender_ID, $Receiver_ID, $Role, $status)
    {
        $UserFilter = '';

        if ($Role === 'Provider') {
            // provider is receiver, client is sender
            $UserFilter = 'AND c.Provider_ID = ? AND c.Client_ID = ? AND m.Is_Client_To_Provider = 1';
        } else if ($Role === 'Client') {
            // client is receiver, provider is sender
            $UserFilter = 'AND c.Client_ID = ? AND c.Provider_ID = ? AND m.Is_Client_To_Provider = 0';
        } else {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE messages m
         INNER JOIN conversation c
            ON m.Conversation_ID = c.ID
         SET m.Status = ?
         WHERE m.Status = 'Delivered' {$UserFilter}"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "sii",
            $status,
            $Receiver_ID,
            $Sender_ID
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            die("Update failed: " . $stmt->error);
        }
    }




    public function getConversationUserIds($userId, $role)
    {
        if ($role === 'Provider') {
            $sql = "SELECT Client_ID as user_id FROM conversation WHERE Provider_ID = ?";
        } else {
            $sql = "SELECT Provider_ID as user_id FROM conversation WHERE Client_ID = ?";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        return array_column($result->fetch_all(MYSQLI_ASSOC), 'user_id');
    }


    public function setUserOnline($userId, $role)
    {
        $table = $role === 'Provider' ? 'provider' : 'client';
        $idField = $role === 'Provider' ? 'Provider_ID' : 'Client_ID';

        $stmt = $this->conn->prepare(
            "UPDATE {$table} SET Is_Online = 1, Last_Seen = NULL WHERE {$idField} = ?"
        );

        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public function setUserOffline($userId, $role)
    {
        $table = $role === 'Provider' ? 'provider' : 'client';
        $idField = $role === 'Provider' ? 'Provider_ID' : 'Client_ID';

        $now = date("Y-m-d H:i:s");

        $stmt = $this->conn->prepare(
            "UPDATE {$table} SET Is_Online = 0, Last_Seen = ? WHERE {$idField} = ?"
        );

        $stmt->bind_param("si", $now, $userId);
        $stmt->execute();
    }


    public function createOrGetConversation($Provider_ID, $Client_ID)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO conversation (Provider_ID, Client_ID)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE ID = LAST_INSERT_ID(ID)"
        );

        $stmt->bind_param("ii", $Provider_ID, $Client_ID);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function getUnreadMessageCount($User_ID, $Role)
    {
        if ($Role === 'Provider') {

            $sql = "SELECT COUNT(*) AS count 
            FROM messages m
            INNER JOIN conversation conv
                ON m.Conversation_ID = conv.ID
            WHERE (Status = 'Sent' OR Status = 'Delivered') 
            AND m.Is_Client_To_Provider = 1 AND conv.Provider_ID = ?";

        } else if ($Role === 'Client') {

            $sql = "SELECT COUNT(*) AS count 
            FROM messages m
            INNER JOIN conversation conv
                ON m.Conversation_ID = conv.ID  
            WHERE (Status = 'Sent' OR Status = 'Delivered') 
            AND m.Is_Client_To_Provider = 0 AND conv.Client_ID = ?";

        } else {
            return 0;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $User_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (int) ($row['count'] ?? 0);
    }
}
