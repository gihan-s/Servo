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
        $stmt = $this->conn->prepare("SELECT Category_ID, Name FROM Category");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
