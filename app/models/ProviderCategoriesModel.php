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
                    c.Icon AS Category_Icon,
                                        p.First_Name,
                                        p.Last_Name,
                                        p.Profile_Picture,
                                        p.Rating AS Provider_Rating,
                                        pc.Rating AS Rating,
                                        pc.Total_Earning
             FROM provider_categories pc
                         INNER JOIN provider p ON p.Provider_ID = pc.Provider_ID
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

    public function getAllServices(int $limit = 10, int $offset = 0): array
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
                    p.First_Name,
                    p.Last_Name,
                    p.Profile_Picture,
                    p.Rating AS Provider_Rating,
                    pc.Rating AS Rating,
                    pc.Total_Earning
             FROM provider_categories pc
             INNER JOIN provider p ON p.Provider_ID = pc.Provider_ID
             LEFT JOIN category c ON c.Category_ID = pc.Category_ID
             WHERE p.Status <> 'Deleted'
             ORDER BY pc.ID DESC
             LIMIT ? OFFSET ?"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getAllServices prepare: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('ii', $limit, $offset);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getAllServices exec: ' . $stmt->error);
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

    public function getAllServicesCount(): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total
             FROM provider_categories pc
             INNER JOIN provider p ON p.Provider_ID = pc.Provider_ID
             WHERE p.Status <> 'Deleted'"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getAllServicesCount prepare: ' . $this->conn->error);
            return 0;
        }

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getAllServicesCount exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }

    public function getServicesForListing(int $providerId, int $limit, int $offset, array $filters = []): array
    {
        [$whereSql, $types, $params] = $this->buildServicesListingWhere($providerId, $filters);
        $orderSql = $this->getServiceSortClause((string) ($filters['sort'] ?? ''));

        $sql = "SELECT pc.ID AS Provider_Categories_ID,
                       pc.Category_ID,
                       pc.Provider_ID,
                       pc.Title,
                       pc.Description,
                       pc.Default_Price,
                       pc.Price_Type,
                       pc.Portfolio_Link,
                       c.Name AS Category_Name,
                       p.First_Name,
                       p.Last_Name,
                      p.Profile_Picture,
                      p.Rating AS Provider_Rating,
                      pc.Rating AS Rating,
                      pc.Total_Earning
                FROM provider_categories pc
                INNER JOIN provider p ON p.Provider_ID = pc.Provider_ID
                LEFT JOIN category c ON c.Category_ID = pc.Category_ID
                $whereSql
                ORDER BY $orderSql
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('ProviderCategoriesModel::getServicesForListing prepare: ' . $this->conn->error);
            return [];
        }

        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;
        $this->bindDynamicParams($stmt, $types, $params);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getServicesForListing exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows ?: [];
    }

    public function getServicesForListingCount(int $providerId, array $filters = []): int
    {
        [$whereSql, $types, $params] = $this->buildServicesListingWhere($providerId, $filters);

        $sql = "SELECT COUNT(*) AS total
                FROM provider_categories pc
                INNER JOIN provider p ON p.Provider_ID = pc.Provider_ID
                LEFT JOIN category c ON c.Category_ID = pc.Category_ID
                $whereSql";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('ProviderCategoriesModel::getServicesForListingCount prepare: ' . $this->conn->error);
            return 0;
        }

        if ($types !== '') {
            $this->bindDynamicParams($stmt, $types, $params);
        }

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getServicesForListingCount exec: ' . $stmt->error);
            $stmt->close();
            return 0;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }

    private function buildServicesListingWhere(int $providerId, array $filters): array
    {
        $where = ["p.Status <> 'Deleted'"];
        $types = '';
        $params = [];

        if ($providerId > 0) {
            $where[] = 'pc.Provider_ID = ?';
            $types .= 'i';
            $params[] = $providerId;
        }

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $where[] = '(pc.Title LIKE ? OR pc.Description LIKE ? OR c.Name LIKE ? OR p.First_Name LIKE ? OR p.Last_Name LIKE ? OR CONCAT(p.First_Name, " ", p.Last_Name) LIKE ?)';
            $like = '%' . $search . '%';
            $types .= 'ssssss';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $priceRange = (string) ($filters['price_range'] ?? '');
        switch ($priceRange) {
            case 'below_5000':
                $where[] = 'COALESCE(pc.Default_Price, 0) < 5000';
                break;
            case '5000_10000':
                $where[] = 'COALESCE(pc.Default_Price, 0) >= 5000 AND COALESCE(pc.Default_Price, 0) < 10000';
                break;
            case '10000_50000':
                $where[] = 'COALESCE(pc.Default_Price, 0) >= 10000 AND COALESCE(pc.Default_Price, 0) < 50000';
                break;
            case '50000_100000':
                $where[] = 'COALESCE(pc.Default_Price, 0) >= 50000 AND COALESCE(pc.Default_Price, 0) < 100000';
                break;
            case 'above_100000':
                $where[] = 'COALESCE(pc.Default_Price, 0) >= 100000';
                break;
        }

        $completionRange = (string) ($filters['completion_range'] ?? '');
        switch ($completionRange) {
            case 'below_25':
                $where[] = 'COALESCE(pc.Rating, 0) < 25';
                break;
            case '25_50':
                $where[] = 'COALESCE(pc.Rating, 0) >= 25 AND COALESCE(pc.Rating, 0) < 50';
                break;
            case '50_75':
                $where[] = 'COALESCE(pc.Rating, 0) >= 50 AND COALESCE(pc.Rating, 0) < 75';
                break;
            case 'above_75':
                $where[] = 'COALESCE(pc.Rating, 0) >= 75';
                break;
        }

        $priceTypes = $filters['price_types'] ?? [];
        if (is_string($priceTypes)) {
            $priceTypes = array_filter(array_map('trim', explode(',', $priceTypes)));
        }

        $normalizedTypes = [];
        foreach ((array) $priceTypes as $type) {
            $t = strtolower((string) $type);
            if ($t === 'hourly') {
                $normalizedTypes[] = 'Hourly';
            } elseif ($t === 'daily') {
                $normalizedTypes[] = 'Daily';
            } elseif ($t === 'fixed') {
                $normalizedTypes[] = 'Fixed';
            }
        }
        $normalizedTypes = array_values(array_unique($normalizedTypes));

        if (!empty($normalizedTypes)) {
            $in = implode(',', array_fill(0, count($normalizedTypes), '?'));
            $where[] = "pc.Price_Type IN ($in)";
            $types .= str_repeat('s', count($normalizedTypes));
            foreach ($normalizedTypes as $type) {
                $params[] = $type;
            }
        }

        $categoryIds = $filters['category_ids'] ?? [];
        if (is_string($categoryIds)) {
            $categoryIds = array_filter(array_map('trim', explode(',', $categoryIds)), static function ($value) {
                return $value !== '';
            });
        }

        $normalizedCategoryIds = [];
        foreach ((array) $categoryIds as $categoryId) {
            $id = (int) $categoryId;
            if ($id > 0) {
                $normalizedCategoryIds[] = $id;
            }
        }
        $normalizedCategoryIds = array_values(array_unique($normalizedCategoryIds));

        if (!empty($normalizedCategoryIds)) {
            $in = implode(',', array_fill(0, count($normalizedCategoryIds), '?'));
            $where[] = "pc.Category_ID IN ($in)";
            $types .= str_repeat('i', count($normalizedCategoryIds));
            foreach ($normalizedCategoryIds as $categoryId) {
                $params[] = $categoryId;
            }
        }

        return ['WHERE ' . implode(' AND ', $where), $types, $params];
    }

    private function getServiceSortClause(string $sort): string
    {
        $map = [
            'price_asc' => 'COALESCE(pc.Default_Price, 0) ASC, pc.ID DESC',
            'price_desc' => 'COALESCE(pc.Default_Price, 0) DESC, pc.ID DESC',
            'completion_asc' => 'COALESCE(pc.Rating, 0) ASC, pc.ID DESC',
            'completion_desc' => 'COALESCE(pc.Rating, 0) DESC, pc.ID DESC',
            'category_asc' => 'COALESCE(c.Name, "") ASC, pc.ID DESC',
            'category_desc' => 'COALESCE(c.Name, "") DESC, pc.ID DESC',
        ];

        return $map[$sort] ?? 'pc.ID DESC';
    }

    private function bindDynamicParams(mysqli_stmt $stmt, string $types, array $params): void
    {
        if ($types === '' || empty($params)) {
            return;
        }

        $bindArgs = [$types];
        foreach ($params as $key => $value) {
            $bindArgs[] = &$params[$key];
        }

        call_user_func_array([$stmt, 'bind_param'], $bindArgs);
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

    public function getLatestRequestStatusesByServiceIds(int $clientId, array $providerCategoryIds): array
    {
        if ($clientId <= 0 || empty($providerCategoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($providerCategoryIds), '?'));
        $types = 'i' . str_repeat('i', count($providerCategoryIds)) . 'i';

        $stmt = $this->conn->prepare(
            "SELECT p.Provider_Categories_ID, p.Request_Status
             FROM post p
             INNER JOIN (
                SELECT Provider_Categories_ID, MAX(Created_At) AS latest_created_at
                FROM post
                WHERE Client_ID = ?
                  AND Post_Type = 'direct'
                  AND Provider_Categories_ID IN ($placeholders)
                GROUP BY Provider_Categories_ID
             ) latest
                ON latest.Provider_Categories_ID = p.Provider_Categories_ID
               AND latest.latest_created_at = p.Created_At
             WHERE p.Client_ID = ?
               AND p.Post_Type = 'direct'"
        );

        if (!$stmt) {
            error_log('ProviderCategoriesModel::getLatestRequestStatusesByServiceIds prepare: ' . $this->conn->error);
            return [];
        }

        $params = array_merge([$clientId], $providerCategoryIds, [$clientId]);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            error_log('ProviderCategoriesModel::getLatestRequestStatusesByServiceIds exec: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $statuses = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $serviceId = (int) ($row['Provider_Categories_ID'] ?? 0);
            if ($serviceId > 0) {
                $statuses[$serviceId] = strtolower(trim((string) ($row['Request_Status'] ?? '')));
            }
        }

        $stmt->close();
        return $statuses;
    }
}