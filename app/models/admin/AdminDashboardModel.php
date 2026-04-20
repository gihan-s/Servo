<?php

require_once __DIR__ . '/../../core/Database.php';

class AdminDashboardModel extends Database
{
    public function getActiveClientCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM client WHERE Status = 'Active'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$result['count'];
    }

    public function getActiveProviderCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM provider WHERE Status = 'Active'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$result['count'];
    }

    public function getActivePostCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM post WHERE Post_Status = 'active' AND Request_Status = 'open'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$result['count'];
    }

    public function getTotalPaymentReceived()
    {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(Amount), 0) as total FROM payment WHERE Status = 'Paid'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (float)$result['total'];
    }

    public function getProjectStatusCounts()
    {
        $stmt = $this->conn->prepare(
            "SELECT
                SUM(CASE WHEN Project_Status = 'Pending'   THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN Project_Status = 'Ongoing'   THEN 1 ELSE 0 END) as ongoing,
                SUM(CASE WHEN Project_Status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN Project_Status = 'Rejected'  THEN 1 ELSE 0 END) as rejected,
                COUNT(*) as total
             FROM project"
        );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getTopProvidersByBids($limit = 5)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.Provider_ID, p.First_Name, p.Last_Name, p.Profile_Picture,
                    COUNT(b.Bid_ID) as bid_count
             FROM provider p
             JOIN bids b ON p.Provider_ID = b.Provider_ID
             GROUP BY p.Provider_ID, p.First_Name, p.Last_Name, p.Profile_Picture
             ORDER BY bid_count DESC
             LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTopProvidersByEarning($limit = 5)
    {
        $stmt = $this->conn->prepare(
            "SELECT p.Provider_ID, p.First_Name, p.Last_Name, p.Profile_Picture,
                    COALESCE(SUM(py.Amount), 0) as Total_Earning
             FROM provider p
             JOIN post po ON p.Provider_ID = po.Provider_ID
             JOIN project pr ON po.Post_ID = pr.Post_ID
             JOIN payment py ON pr.Project_ID = py.Project_ID
             WHERE py.Status = 'Paid'
             GROUP BY p.Provider_ID, p.First_Name, p.Last_Name, p.Profile_Picture
             ORDER BY Total_Earning DESC
             LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getTopClientsBySpending($limit = 5)
    {
        $stmt = $this->conn->prepare(
            "SELECT c.Client_ID, c.First_Name, c.Last_Name, c.Profile_Picture,
                    COALESCE(SUM(py.Amount), 0) as total_spent
             FROM client c
             JOIN post po ON c.Client_ID = po.Client_ID
             JOIN project pr ON po.Post_ID = pr.Post_ID
             JOIN payment py ON pr.Project_ID = py.Project_ID
             WHERE py.Status = 'Paid'
             GROUP BY c.Client_ID, c.First_Name, c.Last_Name, c.Profile_Picture
             ORDER BY total_spent DESC
             LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getPostCumulativeLast14Days()
    {
        $stmt = $this->conn->prepare(
            "SELECT d.day,
                    (SELECT COUNT(*) FROM post
                     WHERE DATE(Created_At) <= d.day) AS total_count
             FROM (
                 SELECT DATE(DATE_SUB(CURDATE(), INTERVAL n DAY)) AS day
                 FROM (
                     SELECT 0 n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                     UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                     UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13
                 ) nums
             ) d
             ORDER BY d.day ASC"
        );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
}
