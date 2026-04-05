<?php
// PAYMENT Table Format:
// +------------+-------------+------+-----+---------+-------+
// | Field      | Type        | Null | Key | Default | Extra |
// +------------+-------------+------+-----+---------+-------+
// | Payment_ID | int         | NO   | PRI | NULL    |       |
// | Amount     | double      | YES  |     | NULL    |       |
// | Status     | varchar(45) | YES  |     | NULL    |       |
// | Hold_Time  | datetime    | YES  |     | NULL    |       |
// | Paid_Time  | datetime    | YES  |     | NULL    |       |
// | Commission | double      | YES  |     | NULL    |       |
// | Project_ID | int         | NO   | MUL | NULL    |       |
// +------------+-------------+------+-----+---------+-------+
// needed columns: Method, Description, Due_Date

require_once __DIR__ . '/../core/Database.php';

class PaymentModel extends Database {
    public function getPaymentsByClientId($clientId) {
        // $stmt = $this->conn->prepare("SELECT * FROM payment WHERE Client_ID = ?");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return null; // Placeholder
    }

    public function getPaymentsCountByClientId($clientId, $status = null) {
        // $query = "SELECT COUNT(*) as count FROM payment WHERE Client_ID = ?";
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

    public function getTotalSpentByClientId($clientId) {
        // $stmt = $this->conn->prepare("SELECT SUM(Amount) as total FROM payment WHERE Client_ID = ? AND Status = 'Paid'");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $total = $result->fetch_assoc()['total'];
        // $stmt->close();
        // return $total ?: 0;
        return 3450; // Placeholder
    }

    public function getRecentPaymentsByClientId($clientId, $limit = 3) {
        // $stmt = $this->conn->prepare("SELECT * FROM payment WHERE Client_ID = ? ORDER BY Date DESC LIMIT ?");
        // $stmt->bind_param("ii", $clientId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $stmt->close();
        // return $result->fetch_all(MYSQLI_ASSOC);
        return [
          [
              'Payment_ID' => 'INV-10452',
              'Description' => 'Sprint 3 Development',
              'Status' => 'Pending',
              'Due_Date' => 'Sep 02, 2025',
              'Amount' => 1200.00,
              'Method' => 'Visa'
          ],
          [
              'Payment_ID' => 'INV-10398',
              'Description' => 'Brand Pack Delivery',
              'Status' => 'Paid',
              'Due_Date' => 'Aug 28, 2025',
              'Amount' => 950.00,
              'Method' => 'Stripe'
          ],
          [
              'Payment_ID' => 'INV-10321',
              'Description' => 'QA Cycle Refund',
              'Status' => 'Refunded',
              'Due_Date' => 'Aug 30, 2025',
              'Amount' => 420.00,
              'Method' => 'Stripe'
          ]
        ]; // Placeholder
    }
}