<?php

require_once __DIR__ . '/../../core/Database.php';

class AdminUserModel extends Database
{

    public function getByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Admin WHERE Username = ? AND Status = 'Active'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

}