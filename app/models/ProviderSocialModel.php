<?php

require_once __DIR__ . '/../core/Database.php';

class ProviderSocialModel extends Database
{
    /**
     * Get social media links by provider ID
     * 
     * @param int $provider_id The provider ID
     * @return array Array of social media records
     */
    public function getByProviderId($provider_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM provider_social WHERE Provider_ID = ?");
        $stmt->bind_param("i", $provider_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $socialLinks = [];
        while ($row = $result->fetch_assoc()) {
            $socialLinks[] = $row;
        }
        return $socialLinks;
    }

    /**
     * Get social media by type and provider ID
     * 
     * @param int $provider_id The provider ID
     * @param string $social_type The social media type
     * @return array|null The social media record or null if not found
     */
    public function getByType($provider_id, $social_type)
    {
        $social_type = strtolower($social_type);
        $stmt = $this->conn->prepare("SELECT * FROM provider_social WHERE Provider_ID = ? AND LOWER(Social_Type) = ?");
        $stmt->bind_param("is", $provider_id, $social_type);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    /**
     * Insert social media link
     * 
     * @param array $data Array containing social_type, social_link, provider_id
     * @return bool True on success, false on failure
     */
    public function insertSocialLink($data)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO provider_social (Social_Type, Social_Link, Provider_ID) VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $social_type = ucfirst(strtolower($data['social_type']));
        $social_link = $data['social_link'];
        $provider_id = $data['provider_id'];

        $stmt->bind_param("ssi", $social_type, $social_link, $provider_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Update social media link
     * 
     * @param array $data Array containing social_id, social_type, social_link, provider_id
     * @return bool True on success, false on failure
     */
    public function updateSocialLink($data)
    {
        $stmt = $this->conn->prepare(
            "UPDATE provider_social SET Social_Type = ?, Social_Link = ? WHERE Social_ID = ? AND Provider_ID = ?"
        );

        if (!$stmt) {
            return false;
        }

        $social_type = ucfirst(strtolower($data['social_type']));
        $social_link = $data['social_link'];
        $social_id = $data['social_id'];
        $provider_id = $data['provider_id'];

        $stmt->bind_param("ssii", $social_type, $social_link, $social_id, $provider_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Delete social media link
     * 
     * @param int $social_id The social media ID
     * @return bool True on success, false on failure
     */
    public function deleteSocialLink($social_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM provider_social WHERE Social_ID = ?");
        $stmt->bind_param("i", $social_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

?>
