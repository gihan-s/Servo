<?php

require_once __DIR__ . '/../core/Database.php';

class CategoryModel extends Database
{

    public function getCategories()
    {
        $stmt = $this->conn->prepare("SELECT Category_ID, Name FROM Category");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByProviderId(int $providerId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT Provider_Categories.*, Category.Name AS Category_Type
            FROM Provider_Categories
            INNER JOIN Category ON Provider_Categories.Category_ID = Category.Category_ID
            WHERE Provider_ID = ?"
        );
        $stmt->bind_param("i", $providerId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
