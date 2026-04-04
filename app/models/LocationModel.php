<?php

require_once __DIR__ . '/../core/Database.php';

class LocationModel extends Database
{
    public function getDistricts()
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT District FROM location");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCities($District)
    {
        $stmt = $this->conn->prepare("SELECT City FROM location WHERE District = ?");
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
            "SELECT `provider_categories_has_location`.Provider_Categories_ID, District, City
            FROM `provider_categories_has_location`
            INNER JOIN `location` ON  `provider_categories_has_location`.Location_Location_ID = `location`.Location_ID
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
