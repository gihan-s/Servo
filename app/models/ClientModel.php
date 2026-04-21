<?php
// CLIENT TABLE STRUCTURE
// +-----------------+---------------+------+-----+---------+----------------+
// | Field           | Type          | Null | Key | Default | Extra          |
// +-----------------+---------------+------+-----+---------+----------------+
// | Client_ID       | int           | NO   | PRI | NULL    | auto_increment |
// | Email           | varchar(256)  | YES  | UNI | NULL    |                |
// | Contact_No      | varchar(45)   | YES  |     | NULL    |                |
// | Password        | varchar(512)  | YES  |     | NULL    |                |
// | Created_At      | datetime      | YES  |     | NULL    |                |
// | First_Name      | varchar(100)  | YES  |     | NULL    |                |
// | Last_Name       | varchar(100)  | YES  |     | NULL    |                |
// | Gender          | varchar(45)   | YES  |     | NULL    |                |
// | Profile_Picture | varchar(512)  | YES  |     | NULL    |                |
// | Social_Link     | varchar(512)  | YES  |     | NULL    |                |
// | Bio             | varchar(1024) | YES  |     | NULL    |                |
// | Status          | varchar(45)   | YES  |     | NULL    |                |
// +-----------------+---------------+------+-----+---------+----------------+

require_once __DIR__ . '/../core/Database.php';

class ClientModel extends Database
{

    public function getByEmail($email)
    {
        $email = strtolower($email);
        $stmt = $this->conn->prepare("SELECT * FROM client WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Get client by ID
    public function getClientById($id)
    {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM client WHERE Client_ID = $id";
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

        $stmt = $this->conn->prepare("UPDATE client
                SET First_Name=?, Last_Name=?, Contact_No=?, Gender=?, Social_Link=?, Bio=?
                WHERE Client_ID=?");
        if (!$stmt)
            return false;
        $stmt->bind_param("ssssssi", $firstName, $lastName, $contact, $gender, $website, $bio, $id);
        return $stmt->execute();

    }


    public function updateProfilePicture($id, $profilePic)
    {
        $stmt = $this->conn->prepare("UPDATE client SET Profile_Picture = ? WHERE Client_ID = ?");
        if (!$stmt)
            return false;
        $stmt->bind_param("si", $profilePic, $id);
        return $stmt->execute();
    }


    public function getCurrentPassword($id)
    {
        $stmt = $this->conn->prepare("SELECT Password FROM client WHERE Client_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['Password'] ?? false;
    }


    public function updatePassword($id, $hashed)
    {
        $stmt = $this->conn->prepare("UPDATE client SET Password = ? WHERE Client_ID = ?");
        if (!$stmt)
            return false;
        $stmt->bind_param("si", $hashed, $id);

        return $stmt->execute();
    }

    public function insertClient($data)
    {
        // Prepare SQL with placeholders
        $stmt = $this->conn->prepare(
            "INSERT INTO client (`Email`, `Contact_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Social_Link`, `Bio`, `Status`) 
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
        
        // Convert email to lowercase
        $email = strtolower($data['email']);

        // Bind parameters: s = string
        $stmt->bind_param(
            "sssssssssss",
            $email,
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
        $email = strtolower($email);
        $stmt = $this->conn->prepare("SELECT Client_ID FROM client WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $status = $stmt->num_rows > 0;

        if (!$status) {

            $stmt = $this->conn->prepare("SELECT Provider_ID FROM provider WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $status = $stmt->num_rows > 0;
        }

        return $status;
    }

    public function deleteClient($id)
    {
        // mark status as 'Deleted' instead of hard-deleting the row
        $stmt = $this->conn->prepare("UPDATE client SET Status = ? WHERE Client_ID = ?");
        if (!$stmt)
            return false;
        $status = 'Deleted';
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function getAllClients($limit, $offset)
    {
        $stmt = $this->conn->prepare(
            "SELECT Client_ID, First_Name, Last_Name, Email, Contact_No, Status, Created_At
             FROM client
             WHERE Status <> 'Deleted'
             ORDER BY Client_ID DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getClientCount()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM client WHERE Status <> 'Deleted'");
        return (int)$result->fetch_assoc()['total'];
    }

    public function getCountByStatus($status)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as cnt FROM client WHERE Status = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$result['cnt'];
    }

    public function updateClientStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE client SET Status = ? WHERE Client_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function getNewClientsThisMonth()
    {
        $result = $this->conn->query(
            "SELECT COUNT(*) AS total FROM client
             WHERE MONTH(Created_At) = MONTH(CURDATE())
               AND YEAR(Created_At) = YEAR(CURDATE())"
        );
        return (int)$result->fetch_assoc()['total'];
    }

    public function getClientStats(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT
                (SELECT COUNT(*) FROM post WHERE Client_ID = ? AND Post_Type = 'post') AS total_posts,
                (SELECT COUNT(*) FROM project pr JOIN post p ON pr.Post_ID = p.Post_ID WHERE p.Client_ID = ?) AS total_projects,
                COALESCE(
                    (SELECT SUM(pay.Amount)
                     FROM payment pay
                     JOIN project pr ON pay.Project_ID = pr.Project_ID
                     JOIN post p ON pr.Post_ID = p.Post_ID
                     WHERE p.Client_ID = ? AND pay.Status = 'Paid'), 0
                ) AS total_spent"
        );
        $stmt->bind_param("iii", $id, $id, $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function getRecentClientPosts(int $id, int $limit = 10): array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.Post_ID, p.Title, p.Post_Status, p.Post_Type,
                    p.Requesting_Price, p.Price_Type, p.Created_At,
                    c.Name AS Category_Name
             FROM post p
             JOIN category c ON p.Category_ID = c.Category_ID
             WHERE p.Client_ID = ? AND p.Post_Type = 'post'
             ORDER BY p.Created_At DESC
             LIMIT ?"
        );
        $stmt->bind_param("ii", $id, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
