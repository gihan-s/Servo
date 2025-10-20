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
}
