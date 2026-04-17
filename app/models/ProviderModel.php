<?php

require_once __DIR__ . '/../core/Database.php';

class ProviderModel extends Database
{

    public function getByEmail($email)
    {
        $email = strtolower($email);
        $stmt = $this->conn->prepare("SELECT * FROM provider WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function insertProvider($data)
    {
        // Prepare SQL with placeholders
        $stmt = $this->conn->prepare(
            "INSERT INTO provider (`Email`, `Contact_No`, `NIC_No`, `Password`, `Created_At`, `First_Name`, `Last_Name`, `Gender`, `Profile_Picture`, `Bio`, `NIC_Front`, `NIC_Back`, `Resume`, `Website`, `Status`) 
             VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $CurrentDate = date("Y-m-d H:i:s");
        $Status = "Pending";

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $profileImage = $data['profile_picture'] ?? null;
        $nicf = $data['nic_front'] ?? null;
        $nicb = $data['nic_back'] ?? null;
        $resume = $data['resume'] ?? null;

        // Convert email to lowercase
        $email = strtolower($data['email']);

        // Bind parameters: s = string
        $stmt->bind_param(
            "sssssssssssssss",
            $email,
            $data['contact_no'],
            $data['nic_no'],
            $hashedPassword,
            $CurrentDate,
            $data['first_name'],
            $data['last_name'],
            $data['gender'],
            $profileImage,
            $data['bio'],
            $nicf,
            $nicb,
            $resume,
            $data['website'],
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


    public function insertProviderCategory($Provider_ID, $data, $key)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO provider_categories (`Category_ID`, `Provider_ID`, `Title`, `Description`, `Default_Price`, `Price_Type`, `Portfolio_Link`, `Price_Negotiability`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $PriceNegotiability = $data['price_negotiability'][$key] == 'true' ? 1 : 0;

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param(
            "sssssssi",
            $data['category_id'][$key],
            $Provider_ID,
            $data['title'][$key],
            $data['description'][$key],
            $data['default_price'][$key],
            $data['price_type'][$key],
            $data['portfolio_link'][$key],
            $PriceNegotiability
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function insertProviderSocialLinks($Provider_ID, $SocialType, $SocialLink)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO provider_social (`Social_Type`, `Social_Link`, `Provider_ID`) 
                VALUES (?, ?, ?)"
        );
        $stmt->bind_param(
            "ssi",
            $SocialType,
            $SocialLink,
            $Provider_ID,
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function insertSkill($Category_ID, $Skill)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO skills (`Skill`, `Category_ID`) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE 
            Skill_ID = LAST_INSERT_ID(Skill_ID)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("si", $Skill, $Category_ID);

        if ($stmt->execute()) {
            $id = $this->conn->insert_id; // <-- THIS is the key
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


        public function insertProviderSkills($ProviderCategoryID, $SkillID)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO provider_categories_has_skills (`Provider_Categories_ID`, `Skills_Skill_ID`) 
                VALUES (?, ?)"
        );

        $stmt->bind_param(
            "ii",
            $ProviderCategoryID,
            $SkillID
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function getLocationID($District, $City)
    {

        $DistrictFilter = "";
        if ($District != '') {
            $DistrictFilter = "AND District = '{$District}'";
        }
        $CityFilter = "";
        if ($City != '') {
            $CityFilter = "AND City = '{$City}'";
        }

        $stmt = $this->conn->prepare("SELECT Location_ID FROM location WHERE 1 {$DistrictFilter} {$CityFilter}");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function insertLocation($Provider_Category_ID, $Location_ID)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO provider_categories_has_location (`Provider_Categories_ID`, `Location_Location_ID`) 
                VALUES (?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param(
            "ss",
            $Provider_Category_ID,
            $Location_ID
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        } else {
            die("Insert failed: " . $stmt->error);
        }
    }


    public function getAllSkills($Category_ID)
    {
        $stmt = $this->conn->prepare(
            "SELECT DISTINCT Skill FROM skills, provider_categories
            WHERE skills.Provider_Categories_ID = provider_categories.ID 
            AND provider_categories.Category_ID = '{$Category_ID}'
            AND provider_categories.Status = 'Active'
            "
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // Get provider by ID
    public function getProviderById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM provider WHERE Provider_ID = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }


    // Update provider profile (example)
    public function updateProfile($id, $firstName, $lastName, $contact, $gender, $website, $bio, $Resume = null)
    {
        $id = $this->conn->real_escape_string($id);
        $firstName = $this->conn->real_escape_string($firstName);
        $lastName = $this->conn->real_escape_string($lastName);
        $contact = $this->conn->real_escape_string($contact);
        $gender = $this->conn->real_escape_string($gender);
        $website = $this->conn->real_escape_string($website);
        $bio = $this->conn->real_escape_string($bio);

        $ResumeUpdate = "";
        if ($Resume) {
            $ResumeUpdate = ", Resume = '". $this->conn->real_escape_string($Resume) ."'";
        }

        $sql = "UPDATE provider 
                SET First_Name='$firstName', Last_Name='$lastName', Contact_No='$contact', Gender='$gender', Website='$website', Bio='$bio' $ResumeUpdate
                WHERE Provider_ID=$id";

        $this->conn->query($sql);

        header('Location: ' . BASE_URL . '/profile');
    }

    public function updatePassword($id, $hashed)
    {
        $stmt = $this->conn->prepare("UPDATE provider SET Password = ? WHERE Provider_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }

    public function getCurrentPassword($id)
    {
        $stmt = $this->conn->prepare("SELECT Password FROM provider WHERE Provider_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['Password'] ?? false;
    }


     public function updateProfilePicture($id, $profilePic)
    {
        $stmt = $this->conn->prepare("UPDATE provider SET Profile_Picture = ? WHERE Provider_ID = ?");
        if (!$stmt)
            return false;
        $stmt->bind_param("si", $profilePic, $id);
        return $stmt->execute();
    }



    public function nicExists($nic_no)
    {
        $stmt = $this->conn->prepare("SELECT Provider_ID FROM provider WHERE NIC_No = ?");
        $stmt->bind_param("s", $nic_no);
        $stmt->execute();
        $stmt->store_result(); // store result to get num_rows
        return $stmt->num_rows > 0; // true if email found
    }

    public function deleteProvider($id)
    {
        // mark status as 'Deleted' instead of hard-deleting the row
        $stmt = $this->conn->prepare("UPDATE provider SET Status = ? WHERE Provider_ID = ?");
        if (!$stmt) return false;
        $status = 'Deleted';
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }


    public function getAllProviders($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT Provider_ID, First_Name, Last_Name, Contact_No, Email, NIC_No, Status FROM provider WHERE Status <> 'Deleted' ORDER BY Provider_ID DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getActiveProvidersForSearch($limit, $offset)
    {
        $providers = $this->fetchProvidersForSearchByStatus('active', $limit, $offset);
        if (!empty($providers)) {
            return $providers;
        }

        return $this->fetchProvidersForSearchByStatus('fallback', $limit, $offset);
    }


    public function getActiveProviderCount()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM provider WHERE Status = 'active'");
        $row = $result ? $result->fetch_assoc() : ['total' => 0];
        $activeCount = (int)($row['total'] ?? 0);

        if ($activeCount > 0) {
            return $activeCount;
        }

        $fallbackResult = $this->conn->query("SELECT COUNT(*) AS total FROM provider WHERE Status <> 'Deleted'");
        $fallbackRow = $fallbackResult ? $fallbackResult->fetch_assoc() : ['total' => 0];
        return (int)($fallbackRow['total'] ?? 0);
    }


    public function getSkillsByProviderId($providerId)
    {
        $stmt = $this->conn->prepare(
            "SELECT DISTINCT s.Skill FROM provider_categories_has_skills pcs
             JOIN skills s ON pcs.Skills_Skill_ID = s.Skill_ID
             JOIN provider_categories pc ON pcs.Provider_Categories_ID = pc.ID
             WHERE pc.Provider_ID = ?
             AND pc.Status = 'Active'
             LIMIT 3"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $skills = [];
        while ($row = $result->fetch_assoc()) {
            $skills[] = $row['Skill'];
        }
        $stmt->close();

        return $skills;
    }


    private function fetchProvidersForSearchByStatus($statusMode, $limit, $offset)
    {
        $whereClause = $statusMode === 'active' ? "WHERE p.Status = 'active'" : "WHERE p.Status <> 'Deleted'";

        $stmt = $this->conn->prepare(
            "SELECT p.*, 
                    COUNT(DISTINCT pc.ID) as category_count,
                    p.Rating as avg_rating
             FROM provider p
             LEFT JOIN provider_categories pc ON p.Provider_ID = pc.Provider_ID
             $whereClause
             AND pc.Status = 'Active'
             GROUP BY p.Provider_ID
             ORDER BY p.Provider_ID DESC
             LIMIT ? OFFSET ?"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $providers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $providers;
    }

    public function getUserCount()
    {
        $result = $this->conn->query("SELECT COUNT(Provider_ID) AS Total_Providers FROM provider WHERE Status <> 'Deleted'");
        return $result->fetch_assoc()['Total_Providers'];
    }


    public function updateProviderStatus($provider_id, $status, $reason_for_rejection = null)
    {
        // mark status as 'Deleted' instead of hard-deleting the row
        $stmt = $this->conn->prepare("UPDATE provider SET Status = ?, Reason_For_Rejection  = ? WHERE Provider_ID = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ssi", $status, $reason_for_rejection, $provider_id);
        return $stmt->execute();
    }

    /**
     * Get services (posts) for a provider
     * These are posts where the provider is assigned (Provider_ID is set)
     * Primarily completed/accepted projects
     */
    public function getProviderServices($providerId, $limit = 10, $offset = 0)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.Post_ID, p.Title, p.Description, p.Requesting_Price, 
                    p.Price_Type, p.Category_ID, c.Name as CategoryName,
                    p.Created_At
             FROM post p
             LEFT JOIN category c ON p.Category_ID = c.Category_ID
             WHERE p.Provider_ID = ? AND p.Post_Status IN ('active', 'completed')
             ORDER BY p.Created_At DESC
             LIMIT ? OFFSET ?"
        );

        if (!$stmt) {
            error_log('getProviderServices prepare error: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param("iii", $providerId, $limit, $offset);
        if (!$stmt->execute()) {
            error_log('getProviderServices execute error: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $services;
    }

    /**
     * Get total count of provider services
     */
    public function getProviderServiceCount($providerId)
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total FROM post 
             WHERE Provider_ID = ? AND Post_Status IN ('active', 'completed')"
        );

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $providerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (int)($row['total'] ?? 0);
    }

    public function removeService($serviceId)
    {
        $stmt = $this->conn->prepare("UPDATE provider_categories SET Status = 'Removed' WHERE ID = ?");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $serviceId);
        return $stmt->execute();
    }
}
