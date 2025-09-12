<?php
namespace App\Models;

use Core\Model;

class Category extends Model
{
    public function all(): array { return $this->db->query('SELECT * FROM Category ORDER BY Name')->fetchAll(); }

    public function create(string $name, ?string $description = null, ?string $icon = null): int
    {
        $stmt = $this->db->prepare('INSERT INTO Category (Name, Description, Icon) VALUES (?,?,?)');
        $stmt->execute([$name, $description, $icon]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE Category SET Name=?, Description=?, Icon=? WHERE Category_ID=?');
        return $stmt->execute([$data['Name'], $data['Description'] ?? null, $data['Icon'] ?? null, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Category WHERE Category_ID=?');
        return $stmt->execute([$id]);
    }
}


