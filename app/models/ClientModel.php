<?php

require_once __DIR__ . '/UserModel.php';

class ClientModel extends UserModel {

    // Get client by ID
    public function getClientById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM clients WHERE id = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Update client profile (example)
    public function updateProfile($id, $name, $email, $company) {
        $id = $this->conn->real_escape_string($id);
        $name = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $company = $this->conn->real_escape_string($company);

        $sql = "UPDATE clients 
                SET name='$name', email='$email', company_name='$company' 
                WHERE id=$id";

        return $this->conn->query($sql);
    }
}


