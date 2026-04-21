<?php
// CATEGORY TABLE STRUCTURE
// +-------------+--------------+------+-----+---------+-------+
// | Field       | Type         | Null | Key | Default | Extra |
// +-------------+--------------+------+-----+---------+-------+
// | Category_ID | int          | NO   | PRI | NULL    |       |
// | Name        | varchar(100) | YES  |     | NULL    |       |
// | Description | varchar(100) | YES  |     | NULL    |       |
// | Icon        | varchar(100) | YES  |     | NULL    |       |
// +-------------+--------------+------+-----+---------+-------+


require_once __DIR__ . '/../core/Database.php';

class CategoryModel extends Database
{

    public function getCategories()
    {
        $stmt = $this->conn->prepare("SELECT Category_ID, Name, Description, Icon FROM category ORDER BY Category_ID ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM category WHERE Category_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getNextId(): int
    {
        $result = $this->conn->query("SELECT COALESCE(MAX(Category_ID), 0) + 1 AS next_id FROM category");
        return (int)$result->fetch_assoc()['next_id'];
    }

    public function create(string $name, string $description, string $icon): bool
    {
        $id = $this->getNextId();
        $stmt = $this->conn->prepare("INSERT INTO category (Category_ID, Name, Description, Icon) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id, $name, $description, $icon);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update(int $id, string $name, string $description, string $icon): bool
    {
        $stmt = $this->conn->prepare("UPDATE category SET Name = ?, Description = ?, Icon = ? WHERE Category_ID = ?");
        $stmt->bind_param("sssi", $name, $description, $icon, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM category WHERE Category_ID = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getByProviderId(int $providerId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT provider_categories.*, category.Name AS Category_Type
            FROM provider_categories
            INNER JOIN category ON provider_categories.Category_ID = category.Category_ID
            WHERE Provider_ID = ?"
        );
        $stmt->bind_param("i", $providerId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
