<?php

require_once __DIR__ . '/../core/Database.php';

class PaymentModel extends Database
{
    public function getPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("SELECT * FROM Payment WHERE Client_ID = ?");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return null; // Placeholder
    }

    public function getPaymentsCountByClientId($clientId, $status = null)
    {
        // $query = "SELECT COUNT(*) as count FROM Payment WHERE Client_ID = ?";
        // if ($status) {
        //     $query .= " AND Status = ?";
        // }
        // $stmt = $this->conn->prepare($query);
        // if ($status) {
        //     $stmt->bind_param("is", $clientId, $status);
        // } else {
        //     $stmt->bind_param("i", $clientId);
        // }
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $count = $result->fetch_assoc()['count'];
        // $stmt->close();
        // return $count;
        return 4; // Placeholder
    } 

    public function getTotalSpentByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("SELECT SUM(Amount) as total FROM Payment WHERE Client_ID = ? AND Status = 'Paid'");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $total = $result->fetch_assoc()['total'];
        // $stmt->close();
        // return $total ?: 0;
        return 3450; // Placeholder
    }

    public function getRecentPaymentsByClientId($clientId, $limit = 3)
    {
        // $stmt = $this->conn->prepare("SELECT * FROM Payment WHERE Client_ID = ? ORDER BY Date DESC LIMIT ?");
        // $stmt->bind_param("ii", $clientId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return [
          [
              'invoice' => 'INV-10452',
              'description' => 'Sprint 3 Development',
              'status' => 'Pending',
              'date' => 'Sep 02, 2025',
              'amount' => 1200.00,
              'method' => 'Visa'
          ],
          [
              'invoice' => 'INV-10398',
              'description' => 'Brand Pack Delivery',
              'status' => 'Paid',
              'date' => 'Aug 28, 2025',
              'amount' => 950.00,
              'method' => 'Stripe'
          ],
          [
              'invoice' => 'INV-10321',
              'description' => 'QA Cycle Refund',
              'status' => 'Refunded',
              'date' => 'Aug 30, 2025',
              'amount' => 420.00,
              'method' => 'Stripe'
          ]
        ]; // Placeholder
    }
}