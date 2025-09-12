<?php
namespace App\Models;

use Core\Model;

class Message extends Model
{
    public function send(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Messages (Content, Is_Client_To_Provider, Sent_At, Status, Provider_ID, Client_ID) VALUES (:content, :c2p, NOW(), :status, :provider, :client)');
        $stmt->execute([
            ':content' => $data['Content'],
            ':c2p' => $data['Is_Client_To_Provider'] ? 1 : 0,
            ':status' => $data['Status'] ?? 'sent',
            ':provider' => $data['Provider_ID'],
            ':client' => $data['Client_ID'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function conversation(int $clientId, int $providerId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Messages WHERE Client_ID = ? AND Provider_ID = ? ORDER BY Sent_At ASC');
        $stmt->execute([$clientId, $providerId]);
        return $stmt->fetchAll();
    }
}


