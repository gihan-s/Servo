<?php

require_once __DIR__ . '/../core/Database.php';

class LocationModel extends Database
{
    public function getDistricts()
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT District FROM location ORDER BY District ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllLocations(): array
    {
        $stmt = $this->conn->prepare("SELECT Location_ID, District, City FROM location ORDER BY District ASC, City ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLocationById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM location WHERE Location_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getNextId(): int
    {
        $result = $this->conn->query("SELECT COALESCE(MAX(Location_ID), 0) + 1 AS next_id FROM location");
        return (int)$result->fetch_assoc()['next_id'];
    }

    public function getDistrictList(): array
    {
        $stmt = $this->conn->prepare("SELECT ID, District FROM districts ORDER BY District ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create(string $district, string $city): bool
    {
        $id = $this->getNextId();
        // Find matching District_ID
        $stmt = $this->conn->prepare("SELECT ID FROM districts WHERE District = ? LIMIT 1");
        $stmt->bind_param("s", $district);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $districtId = $row ? (int)$row['ID'] : null;

        $stmt = $this->conn->prepare("INSERT INTO location (Location_ID, District_ID, District, City) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id, $districtId, $district, $city);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update(int $id, string $district, string $city): bool
    {
        $stmt = $this->conn->prepare("SELECT ID FROM districts WHERE District = ? LIMIT 1");
        $stmt->bind_param("s", $district);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $districtId = $row ? (int)$row['ID'] : null;

        $stmt = $this->conn->prepare("UPDATE location SET District_ID = ?, District = ?, City = ? WHERE Location_ID = ?");
        $stmt->bind_param("issi", $districtId, $district, $city, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function deleteLocation(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM location WHERE Location_ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
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
