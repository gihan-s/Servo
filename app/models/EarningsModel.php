<?php
// EARNINGS MODULE
// Calculates provider earnings from: payment → project → post (where post.Provider_ID = ?)
//
// Join chain:
//   payment pay
//   JOIN project proj ON pay.Project_ID = proj.Project_ID
//   JOIN post po      ON proj.Post_ID   = po.Post_ID
//   JOIN client c     ON po.Client_ID   = c.Client_ID
//   WHERE po.Provider_ID = ?

require_once __DIR__ . '/../core/Database.php';

class EarningsModel extends Database
{
    /**
     * Total earnings (Amount - Commission) for all paid payments.
     */
    public function getTotalEarnings($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT COALESCE(SUM(pay.Amount - pay.Commission), 0) AS total
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ? AND pay.Status = 'Paid'
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return $result['total'];

        return 42850.00; // Placeholder
    }

    /**
     * Percentage change in earnings between current month and last month.
     * Returns a float like 18.5 meaning +18.5%.
     */
    public function getMonthlyEarningsChange($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         COALESCE(SUM(CASE
        //             WHEN MONTH(pay.Paid_Time) = MONTH(CURDATE()) AND YEAR(pay.Paid_Time) = YEAR(CURDATE())
        //             THEN pay.Amount - pay.Commission ELSE 0 END), 0) AS current_month,
        //         COALESCE(SUM(CASE
        //             WHEN MONTH(pay.Paid_Time) = MONTH(CURDATE() - INTERVAL 1 MONTH)
        //              AND YEAR(pay.Paid_Time) = YEAR(CURDATE() - INTERVAL 1 MONTH)
        //             THEN pay.Amount - pay.Commission ELSE 0 END), 0) AS last_month
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ? AND pay.Status = 'Paid'
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $row = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // if ($row['last_month'] == 0) return 0;
        // return round((($row['current_month'] - $row['last_month']) / $row['last_month']) * 100, 1);

        return 18.0; // Placeholder
    }

    /**
     * Sum of payments that are not yet paid out (Status = 'Pending' or 'Processing').
     */
    public function getPendingPayout($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT COALESCE(SUM(pay.Amount - pay.Commission), 0) AS pending
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ? AND pay.Status IN ('Pending', 'Processing')
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return $result['pending'];

        return 8250.00; // Placeholder
    }

    /**
     * Average payment amount across all of this provider's projects.
     */
    public function getAvgProjectValue($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT COALESCE(AVG(pay.Amount), 0) AS avg_value
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ?
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return round($result['avg_value'], 2);

        return 3570.00; // Placeholder
    }

    /**
     * Count of completed projects for this provider.
     */
    public function getCompletedProjectsCount($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT COUNT(*) AS count
        //     FROM project proj
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ? AND proj.Project_Status = 'Completed'
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return $result['count'];

        return 12; // Placeholder
    }

    /**
     * Count of projects completed in the current month.
     */
    public function getCompletedThisMonth($providerId)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT COUNT(*) AS count
        //     FROM project proj
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ?
        //       AND proj.Project_Status = 'Completed'
        //       AND MONTH(proj.Ended_At) = MONTH(CURDATE())
        //       AND YEAR(proj.Ended_At) = YEAR(CURDATE())
        // ");
        // $stmt->bind_param("i", $providerId);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return $result['count'];

        return 3; // Placeholder
    }

    /**
     * Recent transactions (payments) for the provider, joined with project/post/client info.
     */
    public function getRecentTransactions($providerId, $limit = 5)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         pay.Payment_ID,
        //         pay.Amount,
        //         pay.Commission,
        //         pay.Status,
        //         pay.Paid_Time,
        //         pay.Hold_Time,
        //         po.Title AS Project_Title,
        //         proj.Project_Status,
        //         CONCAT(c.First_Name, ' ', c.Last_Name) AS Client_Name
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     JOIN client c ON po.Client_ID = c.Client_ID
        //     WHERE po.Provider_ID = ?
        //     ORDER BY COALESCE(pay.Paid_Time, pay.Hold_Time) DESC
        //     LIMIT ?
        // ");
        // $stmt->bind_param("ii", $providerId, $limit);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $transactions = $result->fetch_all(MYSQLI_ASSOC);
        // $stmt->close();
        // return $transactions;

        return [
            [
                'Payment_ID'     => 10452,
                'Amount'         => 4200.00,
                'Commission'     => 420.00,
                'Status'         => 'Completed',
                'Paid_Time'      => '2025-09-02 10:30:00',
                'Hold_Time'      => null,
                'Project_Title'  => 'E-commerce Platform',
                'Project_Status' => 'Completed',
                'Client_Name'    => 'TechCorp Inc'
            ],
            [
                'Payment_ID'     => 10398,
                'Amount'         => 3500.00,
                'Commission'     => 350.00,
                'Status'         => 'Completed',
                'Paid_Time'      => '2025-08-28 14:15:00',
                'Hold_Time'      => null,
                'Project_Title'  => 'Analytics Dashboard',
                'Project_Status' => 'Completed',
                'Client_Name'    => 'DataSolutions LLC'
            ],
            [
                'Payment_ID'     => 10375,
                'Amount'         => 2800.00,
                'Commission'     => 280.00,
                'Status'         => 'Pending',
                'Paid_Time'      => null,
                'Hold_Time'      => '2025-08-22 09:00:00',
                'Project_Title'  => 'Mobile App UI/UX',
                'Project_Status' => 'Cancelled',
                'Client_Name'    => 'FitnessPlus'
            ],
            [
                'Payment_ID'     => 10321,
                'Amount'         => 5100.00,
                'Commission'     => 510.00,
                'Status'         => 'Processing',
                'Paid_Time'      => null,
                'Hold_Time'      => '2025-08-15 11:45:00',
                'Project_Title'  => 'CRM Integration',
                'Project_Status' => 'Completed',
                'Client_Name'    => 'SalesForce Pro'
            ],
            [
                'Payment_ID'     => 10294,
                'Amount'         => 2400.00,
                'Commission'     => 240.00,
                'Status'         => 'Completed',
                'Paid_Time'      => '2025-08-08 16:20:00',
                'Hold_Time'      => null,
                'Project_Title'  => 'WordPress E-commerce',
                'Project_Status' => 'Completed',
                'Client_Name'    => 'RetailTech'
            ]
        ]; // Placeholder
    }

    /**
     * Monthly earnings & fees grouped by month for the chart.
     * Returns arrays of labels, earnings, and fees for the given number of months.
     */
    public function getMonthlyChartData($providerId, $months = 12)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         DATE_FORMAT(COALESCE(pay.Paid_Time, pay.Hold_Time), '%Y-%m') AS month_key,
        //         DATE_FORMAT(COALESCE(pay.Paid_Time, pay.Hold_Time), '%b') AS month_label,
        //         COALESCE(SUM(pay.Amount - pay.Commission), 0) AS earnings,
        //         COALESCE(SUM(pay.Commission), 0) AS fees
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ?
        //       AND COALESCE(pay.Paid_Time, pay.Hold_Time) >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        //     GROUP BY month_key, month_label
        //     ORDER BY month_key ASC
        // ");
        // $stmt->bind_param("ii", $providerId, $months);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $data = ['labels' => [], 'earnings' => [], 'fees' => []];
        // while ($row = $result->fetch_assoc()) {
        //     $data['labels'][]   = $row['month_label'];
        //     $data['earnings'][] = (float) $row['earnings'];
        //     $data['fees'][]     = (float) $row['fees'];
        // }
        // $stmt->close();
        // return $data;

        return [
            '1M' => [
                'labels'   => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'earnings' => [3200, 4800, 2100, 4200],
                'fees'     => [320, 480, 210, 420]
            ],
            '3M' => [
                'labels'   => ['Jul', 'Aug', 'Sep'],
                'earnings' => [9800, 12400, 14300],
                'fees'     => [980, 1240, 1430]
            ],
            '6M' => [
                'labels'   => ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                'earnings' => [6200, 7800, 8500, 9800, 12400, 14300],
                'fees'     => [620, 780, 850, 980, 1240, 1430]
            ],
            '1Y' => [
                'labels'   => ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                'earnings' => [3100, 4200, 5500, 4800, 5200, 6100, 6200, 7800, 8500, 9800, 12400, 14300],
                'fees'     => [310, 420, 550, 480, 520, 610, 620, 780, 850, 980, 1240, 1430]
            ],
            'All' => [
                'labels'   => ['Q1 24', 'Q2 24', 'Q3 24', 'Q4 24', 'Q1 25', 'Q2 25', 'Q3 25'],
                'earnings' => [8200, 11500, 14200, 13700, 16100, 22500, 36500],
                'fees'     => [820, 1150, 1420, 1370, 1610, 2250, 3650]
            ]
        ]; // Placeholder
    }

    /**
     * Report summary for a date range: total earnings, fees, net, and transaction count.
     */
    public function getReportData($providerId, $startDate, $endDate)
    {
        // $stmt = $this->conn->prepare("
        //     SELECT
        //         COALESCE(SUM(pay.Amount), 0) AS total,
        //         COALESCE(SUM(pay.Commission), 0) AS fees,
        //         COALESCE(SUM(pay.Amount - pay.Commission), 0) AS net,
        //         COUNT(*) AS transaction_count
        //     FROM payment pay
        //     JOIN project proj ON pay.Project_ID = proj.Project_ID
        //     JOIN post po ON proj.Post_ID = po.Post_ID
        //     WHERE po.Provider_ID = ?
        //       AND COALESCE(pay.Paid_Time, pay.Hold_Time) BETWEEN ? AND ?
        // ");
        // $stmt->bind_param("iss", $providerId, $startDate, $endDate);
        // $stmt->execute();
        // $result = $stmt->get_result()->fetch_assoc();
        // $stmt->close();
        // return $result;

        return [
            'total'             => 18000.00,
            'fees'              => 1800.00,
            'net'               => 16200.00,
            'transaction_count' => 5
        ]; // Placeholder
    }
}
