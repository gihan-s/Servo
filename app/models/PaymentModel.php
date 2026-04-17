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
//
// Join chain for client payments:
//   payment pay
//   JOIN project proj ON pay.Project_ID = proj.Project_ID
//   JOIN post po      ON proj.Post_ID   = po.Post_ID
//   LEFT JOIN provider pv ON po.Provider_ID = pv.Provider_ID
//   WHERE po.Client_ID = ?

require_once __DIR__ . '/../core/Database.php';

class PaymentModel extends Database
{
    // =========================================================================
    // CLIENT: Awaiting Payments (accepted requests that haven't been paid yet)
    // These are posts where a provider has been assigned but no payment exists
    // =========================================================================

    public function getAwaitingPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         po.Post_ID,
        //         po.Title,
        //         po.Requesting_Price,
        //         po.Price_Type,
        //         po.Duration,
        //         po.Duration_Type,
        //         po.Post_Type,
        //         po.Description,
        //         proj.Project_ID,
        //         proj.Started_At,
        //         CONCAT(pv.First_Name, ' ', pv.Last_Name) AS Provider_Name
        //     FROM post po
        //     JOIN project proj ON proj.Post_ID = po.Post_ID
        //     LEFT JOIN provider pv ON po.Provider_ID = pv.Provider_ID
        //     LEFT JOIN payment pay ON pay.Project_ID = proj.Project_ID
        //     WHERE po.Client_ID = ?
        //       AND proj.Project_Status = 'Pending'
        //       AND pay.Payment_ID IS NULL
        //     ORDER BY proj.Started_At DESC
        // ");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

        return [
            [
                'Post_ID'         => 1,
                'Title'           => '3D Asset Pack Creation',
                'Requesting_Price'=> 90.00,
                'Price_Type'      => 'Hourly',
                'Duration'        => '6',
                'Duration_Type'   => 'Days',
                'Post_Type'       => 'Bid Request',
                'Description'     => 'Creating 15 optimized low-poly environment props for prototype. Payment required before work begins.',
                'Project_ID'      => 1,
                'Started_At'      => '2025-08-15 00:00:00',
                'Provider_Name'   => 'DevStudio Labs',
                'Estimated_Total' => 4320.00,
                'Project_Title'   => 'Game Assets'
            ],
            [
                'Post_ID'         => 2,
                'Title'           => 'Brand Identity Development',
                'Requesting_Price'=> 75.00,
                'Price_Type'      => 'Hourly',
                'Duration'        => '10',
                'Duration_Type'   => 'Days',
                'Post_Type'       => 'Direct Request',
                'Description'     => 'Developing a comprehensive brand identity including logo, color palette, and typography. Milestone payment needed to proceed.',
                'Project_ID'      => 2,
                'Started_At'      => '2025-09-01 00:00:00',
                'Provider_Name'   => 'UXPro Studio',
                'Estimated_Total' => 6000.00,
                'Project_Title'   => 'Brand Suite'
            ]
        ]; // Placeholder
    }

    // =========================================================================
    // CLIENT: Pending Payments (invoiced but not yet paid)
    // =========================================================================

    public function getPendingPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         pay.Payment_ID,
        //         pay.Amount,
        //         pay.Commission,
        //         pay.Status,
        //         pay.Hold_Time,
        //         po.Title AS Project_Title,
        //         po.Description,
        //         proj.Project_Status,
        //         CONCAT(pv.First_Name, ' ', pv.Last_Name) AS Provider_Name
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     LEFT JOIN provider pv ON po.Provider_ID = pv.Provider_ID
        //     WHERE po.Client_ID = ? AND pay.Status = 'Pending'
        //     ORDER BY pay.Hold_Time DESC
        // ");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

        return [
            [
                'Payment_ID'     => 10452,
                'Amount'         => 1200.00,
                'Commission'     => 120.00,
                'Status'         => 'Pending',
                'Hold_Time'      => '2025-09-02 10:30:00',
                'Project_Title'  => 'Development Sprint 3',
                'Description'    => 'Payment for sprint 3 covering implementation of authentication module, profile settings page, and database optimization tasks as agreed in the project milestone plan.',
                'Project_Status' => 'Completed',
                'Provider_Name'  => 'DevStudio Labs',
                'Method'         => 'Card (Visa)',
                'Due_Date'       => '2025-09-15',
                'Project_Name'   => 'E-Commerce App'
            ],
            [
                'Payment_ID'     => 10463,
                'Amount'         => 680.00,
                'Commission'     => 68.00,
                'Status'         => 'Pending',
                'Hold_Time'      => '2025-09-05 14:00:00',
                'Project_Title'  => 'UI Design Phase',
                'Description'    => 'Cancellation penalty for UI/UX design project that was terminated after initial milestone. Penalty as per service agreement terms.',
                'Project_Status' => 'Cancelled',
                'Provider_Name'  => 'UXPro Studio',
                'Method'         => 'PayPal',
                'Due_Date'       => '2025-09-18',
                'Project_Name'   => 'Mobile Fitness App'
            ]
        ]; // Placeholder
    }

    // =========================================================================
    // CLIENT: Completed Payments (paid successfully)
    // =========================================================================

    public function getCompletedPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         pay.Payment_ID,
        //         pay.Amount,
        //         pay.Commission,
        //         pay.Status,
        //         pay.Paid_Time,
        //         po.Title AS Project_Title,
        //         po.Description,
        //         proj.Project_Status,
        //         CONCAT(pv.First_Name, ' ', pv.Last_Name) AS Provider_Name
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     LEFT JOIN provider pv ON po.Provider_ID = pv.Provider_ID
        //     WHERE po.Client_ID = ? AND pay.Status = 'Paid'
        //     ORDER BY pay.Paid_Time DESC
        // ");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

        return [
            [
                'Payment_ID'     => 10398,
                'Amount'         => 950.00,
                'Commission'     => 95.00,
                'Status'         => 'Paid',
                'Paid_Time'      => '2025-08-28 14:15:00',
                'Project_Title'  => 'Logo & Brand Pack',
                'Description'    => 'Final payment for brand identity delivery including vector logo assets, color guide and typography scale for marketing usage.',
                'Project_Status' => 'Completed',
                'Provider_Name'  => 'UXPro Studio',
                'Method'         => 'Stripe',
                'Txn_ID'         => 'TXN78C92',
                'Project_Name'   => 'Brand Suite'
            ],
            [
                'Payment_ID'     => 10374,
                'Amount'         => 1480.00,
                'Commission'     => 148.00,
                'Status'         => 'Paid',
                'Paid_Time'      => '2025-08-22 09:00:00',
                'Project_Title'  => 'Analytics Dashboard',
                'Description'    => 'Cancellation penalty payment for analytics dashboard module per contract terms after early termination.',
                'Project_Status' => 'Cancelled',
                'Provider_Name'  => 'DataCraft',
                'Method'         => 'Card (Mastercard)',
                'Txn_ID'         => 'TXN65B11',
                'Project_Name'   => 'BI Platform'
            ]
        ]; // Placeholder
    }

    // =========================================================================
    // CLIENT: Refunded Payments
    // =========================================================================

    public function getRefundedPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         pay.Payment_ID,
        //         pay.Amount,
        //         pay.Commission,
        //         pay.Status,
        //         pay.Paid_Time,
        //         po.Title AS Project_Title,
        //         po.Description,
        //         proj.Project_Status,
        //         CONCAT(pv.First_Name, ' ', pv.Last_Name) AS Provider_Name
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     LEFT JOIN provider pv ON po.Provider_ID = pv.Provider_ID
        //     WHERE po.Client_ID = ? AND pay.Status = 'Refunded'
        //     ORDER BY pay.Paid_Time DESC
        // ");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

        return [
            [
                'Payment_ID'     => 10321,
                'Amount'         => 420.00,
                'Commission'     => 42.00,
                'Status'         => 'Refunded',
                'Paid_Time'      => '2025-08-30 11:00:00',
                'Project_Title'  => 'QA Testing Cycle',
                'Description'    => 'Refund issued due to scope change after partial QA cycle execution. Remaining tasks were descoped and credited back.',
                'Project_Status' => 'Completed',
                'Provider_Name'  => 'DevStudio Labs',
                'Method'         => 'Stripe',
                'Refund_ID'      => 'RFN9021',
                'Project_Name'   => 'Platform QA'
            ],
            [
                'Payment_ID'     => 10294,
                'Amount'         => 300.00,
                'Commission'     => 30.00,
                'Status'         => 'Refunded',
                'Paid_Time'      => '2025-08-12 16:30:00',
                'Project_Title'  => 'Initial Wireframes',
                'Description'    => 'Original design direction changed after stakeholder review. Early milestone payment was reversed and credited to account balance.',
                'Project_Status' => 'Completed',
                'Provider_Name'  => 'UXPro Studio',
                'Method'         => 'Card (Visa)',
                'Refund_ID'      => 'RFN8810',
                'Project_Name'   => 'Design System'
            ]
        ]; // Placeholder
    }

    // =========================================================================
    // CLIENT: Dashboard helpers (existing methods, kept as-is)
    // =========================================================================

    public function getPaymentsByClientId($clientId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT pay.*
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Client_ID = ?
        //     ORDER BY COALESCE(pay.Paid_Time, pay.Hold_Time) DESC
        // ");
        // $stmt->bind_param("i", $clientId);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

        return null; // Placeholder
    }

    public function getPaymentsCountByClientId($clientId, $status = null)
    {
        // $query = "
        //     SELECT COUNT(*) as count
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Client_ID = ?
        // ";
        // if ($status) {
        //     $query .= " AND pay.Status = ?";
        //     $stmt = $this->conn->prepare($query);
        //     $stmt->bind_param("is", $clientId, $status);
        // } else {
        //     $stmt = $this->conn->prepare($query);
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
        // $stmt = $this->conn->prepare("
        //     SELECT COALESCE(SUM(pay.Amount), 0) as total
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Client_ID = ? AND pay.Status = 'Paid'
        // ");
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
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         pay.Payment_ID,
        //         pay.Amount,
        //         pay.Status,
        //         pay.Paid_Time,
        //         pay.Hold_Time,
        //         po.Title AS Description,
        //         po.Title AS Project_Title
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Client_ID = ?
        //     ORDER BY COALESCE(pay.Paid_Time, pay.Hold_Time) DESC
        //     LIMIT ?
        // ");
        // $stmt->bind_param("ii", $clientId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $data;

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
