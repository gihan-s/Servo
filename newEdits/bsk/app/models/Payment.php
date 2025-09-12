<?php
namespace App\Models;

use Core\Model;

class Payment extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Payment (Amount, Status, Hold_Time, Paid_Time, Commission, Project_ID) VALUES (:amount, :status, :hold, :paid, :commission, :project)');
        $stmt->execute([
            ':amount' => $data['Amount'],
            ':status' => $data['Status'] ?? 'pending',
            ':hold' => $data['Hold_Time'] ?? null,
            ':paid' => $data['Paid_Time'] ?? null,
            ':commission' => $data['Commission'] ?? null,
            ':project' => $data['Project_ID'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function listForProject(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Payment WHERE Project_ID = ? ORDER BY Payment_ID DESC');
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }
}


