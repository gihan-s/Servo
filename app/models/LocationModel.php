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
}
