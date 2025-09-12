<?php
namespace App\Models;

use Core\Model;

class ReviewReportLog extends Model
{
    public function addReview(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Reviews (Title, Description, Rating, Left_At, Edited_At, Project_ID) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$data['Title'], $data['Description'] ?? null, $data['Rating'] ?? null, date('Y-m-d H:i:s'), null, $data['Project_ID']]);
        return (int)$this->db->lastInsertId();
    }

    public function addReport(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Reports (Reason, Description, Reported_At, Status, Project_ID) VALUES (?,?,?,?,?)');
        $stmt->execute([$data['Reason'], $data['Description'] ?? null, date('Y-m-d H:i:s'), $data['Status'] ?? 'open', $data['Project_ID']]);
        return (int)$this->db->lastInsertId();
    }

    public function logProjectUpdate(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Project_Update_Log (Title, Description, Date, Project_ID) VALUES (?,?,NOW(),?)');
        $stmt->execute([$data['Title'], $data['Description'] ?? null, $data['Project_ID']]);
        return (int)$this->db->lastInsertId();
    }
}


