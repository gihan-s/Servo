<?php
namespace App\Models;

use Core\Model;

class Bid extends Model
{
    public function create(array $data): int
    {
        $sql = 'INSERT INTO Bids (Comment, Amount, Created_At, Est_Date, Status, Post_ID) VALUES (:comment, :amount, NOW(), :est, :status, :post)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':comment' => $data['Comment'] ?? null,
            ':amount' => $data['Amount'],
            ':est' => $data['Est_Date'] ?? null,
            ':status' => $data['Status'] ?? 'pending',
            ':post' => $data['Post_ID'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function listForPost(int $postId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Bids WHERE Post_ID = ? ORDER BY Created_At DESC');
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
}


