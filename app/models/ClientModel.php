<?php

require_once __DIR__ . '/../core/Database.php';

class ClientModel extends Database
{
    // Get client by ID
    public function getClientById($id)
    {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM Client WHERE Client_ID = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Update client profile (example)
    public function updateProfile($id, $firstName, $lastName, $contact, $gender, $website, $bio)
    {
        $id = $this->conn->real_escape_string($id);
        $firstName = $this->conn->real_escape_string($firstName);
        $lastName = $this->conn->real_escape_string($lastName);
        $contact = $this->conn->real_escape_string($contact);
        $gender = $this->conn->real_escape_string($gender);
        $website = $this->conn->real_escape_string($website);
        $bio = $this->conn->real_escape_string($bio);

        $sql = "UPDATE Client
                SET First_Name='$firstName', Last_Name='$lastName', Contact_No='$contact', Gender='$gender', Social_Link='$website', Bio='$bio'
                WHERE Client_ID=$id";

        return $this->conn->query($sql);
    }

    public function updatePassword($id, $hashed)
    {
        $stmt = $this->conn->prepare("UPDATE client SET Password = ? WHERE Client_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }

    public function insertClient($data)
    {
        // Prepare SQL with placeholders
        $stmt = $this->conn->prepare(
            "INSERT INTO Client (`Email`, `Contact_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Social_Link`, `Bio`, `Status`) 
             VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $CurrentDate = date("Y-m-d H:i:s");
        $Status = "Active";

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $profileImage = $data['profile_picture'] ?? null;

        // Bind parameters: s = string
        $stmt->bind_param(
            "sssssssssss",
            $data['email'],
            $data['contact_no'],
            $hashedPassword,
            $CurrentDate,
            $data['first_name'],
            $data['last_name'],
            $data['gender'],
            $profileImage,
            $data['website'],
            $data['bio'],
            $Status
        );

        // Execute
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT Client_ID FROM Client WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $status = $stmt->num_rows > 0;

        if ($status) {

            $stmt = $this->conn->prepare("SELECT Provider_ID FROM Provider WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $status = $stmt->num_rows > 0;
        }

        return $status;
    }
}
