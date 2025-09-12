<?php
namespace App\Models;

use Core\Model;

class Location extends Model
{
    public function all(): array { return $this->db->query('SELECT * FROM Location ORDER BY District, City')->fetchAll(); }
    public function create(string $district, string $city): int
    {
        $stmt = $this->db->prepare('INSERT INTO Location (District, City) VALUES (?,?)');
        $stmt->execute([$district, $city]);
        return (int)$this->db->lastInsertId();
    }
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Location WHERE Location_ID=?');
        return $stmt->execute([$id]);
    }
}


