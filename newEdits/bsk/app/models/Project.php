<?php
namespace App\Models;

use Core\Model;

class Project extends Model
{
    public function createFromPost(int $postId, string $status = 'pending'): int
    {
        $stmt = $this->db->prepare('INSERT INTO Project (Post_ID, Project_Status, Started_At) VALUES (?, ?, NOW())');
        $stmt->execute([$postId, $status]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $projectId, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE Project SET Project_Status = ? WHERE Project_ID = ?');
        return $stmt->execute([$status, $projectId]);
    }

    public function findById(int $projectId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM Project WHERE Project_ID = ?');
        $stmt->execute([$projectId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}


