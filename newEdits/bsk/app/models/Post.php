<?php
namespace App\Models;

use Core\Model;

class Post extends Model
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO Post (Created_At, Category_ID, Post_Type, Post_Status, Client_ID, Provider_ID, Title, Description, Requesting_Price)
                VALUES (NOW(), :category, :type, :status, :clientId, :providerId, :title, :description, :price)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':category' => $data['Category_ID'],
            ':type' => $data['Post_Type'] ?? 'public',
            ':status' => $data['Post_Status'] ?? 'draft',
            ':clientId' => $data['Client_ID'],
            ':providerId' => $data['Provider_ID'] ?? null,
            ':title' => $data['Title'] ?? null,
            ':description' => $data['Description'] ?? null,
            ':price' => $data['Requesting_Price'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $postId, array $data): bool
    {
        $sql = 'UPDATE Post SET Category_ID=:category, Post_Type=:type, Post_Status=:status, Provider_ID=:providerId, Title=:title, Description=:description, Requesting_Price=:price WHERE Post_ID=:id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':category' => $data['Category_ID'],
            ':type' => $data['Post_Type'],
            ':status' => $data['Post_Status'],
            ':providerId' => $data['Provider_ID'] ?? null,
            ':title' => $data['Title'],
            ':description' => $data['Description'],
            ':price' => $data['Requesting_Price'],
            ':id' => $postId,
        ]);
    }

    public function findById(int $postId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM Post WHERE Post_ID = ?');
        $stmt->execute([$postId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function delete(int $postId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Post WHERE Post_ID = ?');
        return $stmt->execute([$postId]);
    }

    public function listForClient(int $clientId, ?string $status = null): array
    {
        $sql = 'SELECT * FROM Post WHERE Client_ID = :clientId';
        $params = [':clientId' => $clientId];
        if ($status !== null) {
            $sql .= ' AND Post_Status = :status';
            $params[':status'] = $status;
        }
        $sql .= ' ORDER BY Created_At DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function browseActive(?int $categoryId = null): array
    {
        $sql = 'SELECT p.*, c.Name AS Category_Name FROM Post p JOIN Category c ON c.Category_ID = p.Category_ID WHERE p.Post_Status = "active"';
        $params = [];
        if ($categoryId !== null) {
            $sql .= ' AND p.Category_ID = :cat';
            $params[':cat'] = $categoryId;
        }
        $sql .= ' ORDER BY p.Created_At DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}


