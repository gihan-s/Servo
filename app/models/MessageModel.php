<?php

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
                        last_message_time
                    FROM Client c
                    INNER JOIN Messages m
                        ON m.Client_ID = c.Client_ID
                    INNER JOIN (
                        -- latest message per client
                        SELECT Client_ID, MAX(Sent_At) AS last_message_time
                        FROM Messages
                        WHERE Provider_ID = ?
                        GROUP BY Client_ID
                    ) lm
                        ON lm.Client_ID = m.Client_ID
                    AND lm.last_message_time = m.Sent_At
                    LEFT JOIN (
                        -- unread count per client
                        SELECT Client_ID, COUNT(*) AS unread_count
                        FROM Messages
                        WHERE Provider_ID = ?
                        AND (Status = 'Sent' OR Status = 'Delivered')
                        AND Is_Client_To_Provider = 1
                        GROUP BY Client_ID
                    ) u
                        ON u.Client_ID = c.Client_ID
                    WHERE m.Provider_ID = ?
                    ORDER BY m.Sent_At DESC;
            ";
        } else if ($Role === 'Client') {

            $sql = "SELECT 
                        p.Provider_ID as id,
                        p.First_Name,
                        p.Last_Name,
                        p.Profile_Picture,
                        m.Content AS last_message,
                        COALESCE(u.unread_count, 0) AS unread_count,
                        last_message_time
                    FROM Provider p
                    INNER JOIN Messages m
                        ON m.Provider_ID = p.Provider_ID
                    INNER JOIN (
                        -- latest message per client
                        SELECT Provider_ID, MAX(Sent_At) AS last_message_time
                        FROM Messages
                        WHERE Client_ID = ?
                        GROUP BY Provider_ID
                    ) lm
                        ON lm.Provider_ID = m.Provider_ID
                    AND lm.last_message_time = m.Sent_At
                    LEFT JOIN (
                        -- unread count per client
                        SELECT Provider_ID, COUNT(*) AS unread_count
                        FROM Messages
                        WHERE Client_ID = ?
                        AND (Status = 'Sent' OR Status = 'Delivered')
                        AND Is_Client_To_Provider = 0
                        GROUP BY Provider_ID
                    ) u
                        ON u.Provider_ID = p.Provider_ID
                    WHERE m.Client_ID = ?
                    ORDER BY m.Sent_At DESC;
            ";
        } else {
            return;
        }





        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $User_ID, $User_ID, $User_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getMessagesByID($User_ID, $Role, $Conversation_ID)
    {

        if ($Role === 'Provider') {
            $sql = "SELECT Message_ID as id, Content as text, NOT(Is_Client_To_Provider) AS self, Sent_At, Messages.Status
            FROM Messages
            WHERE Provider_ID = ?
            AND Client_ID = ?
            ORDER BY Sent_At
            ";
        } else if ($Role === 'Client') {
            $sql = "SELECT Message_ID as id, Content as text, Is_Client_To_Provider AS self, Sent_At, Messages.Status
                FROM Messages
                WHERE Client_ID = ?
                AND Provider_ID = ?
                ORDER BY Sent_At
            ";
        } else {
            return;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $User_ID, $Conversation_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function insertMessage($Content, $ClientToProvider, $Provider_ID, $Client_ID)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO Messages (`Content`, `Is_Client_To_Provider`, `Sent_At`, `Status`, `Provider_ID`, `Client_ID`) 
                VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $date = date("Y-m-d H:i:s");
        $Status = "Sent";
        $stmt->bind_param(
            "sissii",
            $Content,
            $ClientToProvider,
            $date,
            $Status,
            $Provider_ID,
            $Client_ID
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }

    public function updateMessageStatus($id, $status)
    {
        $stmt = $this->conn->prepare(
            "UPDATE Messages SET Status = ? WHERE Message_ID = ?"
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
            $UserFilter = 'AND Provider_ID = ? AND Is_Client_To_Provider = 1';
        } else if ($Role === 'Client') {
            $UserFilter = 'AND Client_ID = ? AND Is_Client_To_Provider = 0';
        } else {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE Messages SET Status = ? WHERE Status = 'Sent' {$UserFilter}"
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
            $UserFilter = 'AND Provider_ID = ? AND Client_ID = ? AND Is_Client_To_Provider = 1';
        } else if ($Role === 'Client') {
            $UserFilter = 'AND Client_ID = ? AND Provider_ID = ? AND Is_Client_To_Provider = 0';
        } else {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE Messages SET Status = ? WHERE Status = 'Delivered' {$UserFilter}"
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
            die("Insert failed: " . $stmt->error);
        }
    }
}
