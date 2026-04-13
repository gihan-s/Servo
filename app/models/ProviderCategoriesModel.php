<?php

require_once __DIR__ . '/../core/Database.php';

class ProviderCategoriesModel extends Database
{
    public function getByProviderId(int $providerId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->conn->prepare(
            "SELECT pc.ID AS Provider_Categories_ID,
                    pc.Category_ID,
                    pc.Provider_ID,
                    pc.Title,
                    pc.Description,
                    pc.Default_Price,
                    pc.Price_Type,
                    pc.Portfolio_Link,
                    c.Name AS Category_Name,
                    c.Icon AS Category_Icon
             FROM provider_categories pc
             LEFT JOIN category c ON c.Category_ID = pc.Category_ID
             WHERE pc.Provider_ID = ?
             AND pc.Status = 'Active'
             ORDER BY pc.ID DESC
             LIMIT ? OFFSET ?"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getByProviderId prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('iii', $providerId, $limit, $offset);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getByProviderId exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows ?: [];
    }

    public function getCountByProviderId(int $providerId): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total
             FROM provider_categories
             WHERE Provider_ID = ?
             AND Status = 'Active'
             "
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getCountByProviderId prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param('i', $providerId);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getCountByProviderId exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }

    public function getSkillsByProviderCategoryIds(array $providerCategoryIds): array
    {
        if (empty($providerCategoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($providerCategoryIds), '?'));
        $types = str_repeat('i', count($providerCategoryIds));

        $stmt = $this->conn->prepare(
            "SELECT pcs.Provider_Categories_ID, s.Skill
             FROM provider_categories_has_skills pcs
             INNER JOIN skills s ON s.Skill_ID = pcs.Skills_Skill_ID
             WHERE pcs.Provider_Categories_ID IN ($placeholders)
             
             ORDER BY s.Skill"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getSkillsByProviderCategoryIds prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param($types, ...$providerCategoryIds);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getSkillsByProviderCategoryIds exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $skillsByCategory = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $categoryId = (int) ($row['Provider_Categories_ID'] ?? 0);
            if ($categoryId > 0) {
                $skillsByCategory[$categoryId][] = $row['Skill'];
            }
        }

        $stmt->close();
        return $skillsByCategory;
    }

    public function getLocationsByProviderCategoryIds(array $providerCategoryIds): array
    {
        if (empty($providerCategoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($providerCategoryIds), '?'));
        $types = str_repeat('i', count($providerCategoryIds));

        $stmt = $this->conn->prepare(
            "SELECT pcl.Provider_Categories_ID, l.District, l.City
             FROM provider_categories_has_location pcl
             INNER JOIN location l ON l.Location_ID = pcl.Location_Location_ID
             WHERE pcl.Provider_Categories_ID IN ($placeholders)
             ORDER BY l.District, l.City"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getLocationsByProviderCategoryIds prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param($types, ...$providerCategoryIds);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getLocationsByProviderCategoryIds exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $locationsByCategory = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $categoryId = (int) ($row['Provider_Categories_ID'] ?? 0);
            if ($categoryId > 0) {
                $locationsByCategory[$categoryId][] = [
                    'District' => $row['District'] ?? '',
                    'City' => $row['City'] ?? '',
                ];
            }
        }

        $stmt->close();
        return $locationsByCategory;
    }

    public function getOngoingRequestServiceIds(int $clientId, array $providerCategoryIds): array
    {
        if ($clientId <= 0 || empty($providerCategoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($providerCategoryIds), '?'));
        $types = 'i' . str_repeat('i', count($providerCategoryIds));

        $stmt = $this->conn->prepare(
            "SELECT DISTINCT Provider_Categories_ID
             FROM post
             WHERE Client_ID = ?
               AND Post_Type = 'direct'
               AND Request_Status = 'ongoing'
               AND Provider_Categories_ID IN ($placeholders)"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getOngoingRequestServiceIds prepare: ' . $this->conn->error);
            return [];
        }

        $params = array_merge([$clientId], $providerCategoryIds);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getOngoingRequestServiceIds exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $ids = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $serviceId = (int) ($row['Provider_Categories_ID'] ?? 0);
            if ($serviceId > 0) {
                $ids[] = $serviceId;
            }
        }

        $stmt->close();
        return $ids;
    }
}