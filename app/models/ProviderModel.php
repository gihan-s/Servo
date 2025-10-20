<?php

require_once __DIR__ . '/../core/Database.php';

class ProviderModel extends Database {

    // Get provider by ID
    public function getProviderById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM provider WHERE Provider_ID = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Update provider profile (example)
    public function updateProfile($id, $name, $email, $skills) {
        $id = $this->conn->real_escape_string($id);
        $name = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $skills = $this->conn->real_escape_string($skills);

        $sql = "UPDATE providers 
                SET name='$name', email='$email', skill_category='$skills' 
                WHERE id=$id";

        return $this->conn->query($sql);
    }
}

