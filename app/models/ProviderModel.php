<?php

require_once __DIR__ . '/../core/Database.php';

class ProviderModel extends Database {

    // Get provider by ID
    public function getProviderById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM Provider WHERE Provider_ID = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Update provider profile (example)
    public function updateProfile($id, $firstName, $lastName, $contact, $gender, $website, $bio) {
        $id = $this->conn->real_escape_string($id);
        $firstName = $this->conn->real_escape_string($firstName);
        $lastName = $this->conn->real_escape_string($lastName);
        $contact = $this->conn->real_escape_string($contact);
        $gender = $this->conn->real_escape_string($gender);
        $website = $this->conn->real_escape_string($website);
        $bio = $this->conn->real_escape_string($bio);

        $sql = "UPDATE Provider 
                SET first_name='$firstName', last_name='$lastName', contact='$contact', gender='$gender', website='$website', bio='$bio' 
                WHERE id=$id";

        return $this->conn->query($sql);
    }

    public function updatePassword($id, $hashed)
    {
        $stmt = $this->conn->prepare("UPDATE provider SET Password = ? WHERE Provider_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }
}

