<?php

require_once __DIR__ . '/../core/Database.php';

class LocationModel extends Database
{
    public function getDistricts()
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT District FROM Location");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCities($District)
    {
        $stmt = $this->conn->prepare("SELECT City FROM Location WHERE District = ?");
        $stmt->bind_param("s", $District);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getByProviderCategoryIds(array $categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $types = str_repeat('i', count($categoryIds));

        $stmt = $this->conn->prepare(
            "SELECT `Provider_Categories_has_Location`.Provider_Categories_ID, District, City
            FROM `Provider_Categories_has_Location`
            INNER JOIN `Location` ON  `Provider_Categories_has_Location`.Location_Location_ID = `Location`.Location_ID
            WHERE Provider_Categories_ID IN ($placeholders)"
        );

        $stmt->bind_param($types, ...$categoryIds);
        $stmt->execute();

        $locations = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $locations[$row['Provider_Categories_ID']][] = [
                "District" => $row["District"],
                "City" => $row["City"],
            ];
        }

        return $locations;
    }
}
