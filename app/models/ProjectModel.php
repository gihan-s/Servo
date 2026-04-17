<?php
// PROJECT Table Format:
// +----------------+-------------+------+-----+---------+-------+
// | Field          | Type        | Null | Key | Default | Extra |
// +----------------+-------------+------+-----+---------+-------+
// | Project_ID     | int         | NO   | PRI | NULL    |       |
// | Post_ID        | int         | NO   | MUL | NULL    |       |
// | Project_Status | varchar(45) | YES  |     | NULL    |       |
// | Started_At     | datetime    | YES  |     | NULL    |       |
// | Ended_At       | datetime    | YES  |     | NULL    |       |
// +----------------+-------------+------+-----+---------+-------+
// needed columns: Stage, Budget, Progress

require_once __DIR__ . '/../core/Database.php';

class ProjectModel extends Database {
  public function getByPostId(int $postId): ?array {
    $stmt = $this->conn->prepare("SELECT * FROM project WHERE Post_ID = ? LIMIT 1");
    if (!$stmt) {
      error_log('ProjectModel::getByPostId prepare: ' . $this->conn->error);
      return null;
    }

    $stmt->bind_param('i', $postId);
    if (!$stmt->execute()) {
      error_log('ProjectModel::getByPostId exec: ' . $stmt->error);
      $stmt->close();
      return null;
    }

    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
  }

  public function getProjectIdsByProviderId(int $providerId): array {
    $stmt = $this->conn->prepare(
      "SELECT pr.Project_ID
       FROM project pr
       INNER JOIN post p ON p.Post_ID = pr.Post_ID
       WHERE p.Provider_ID = ?"
    );

    if (!$stmt) {
      error_log('ProjectModel::getProjectIdsByProviderId prepare: ' . $this->conn->error);
      return [];
    }

    $stmt->bind_param('i', $providerId);
    if (!$stmt->execute()) {
      error_log('ProjectModel::getProjectIdsByProviderId exec: ' . $stmt->error);
      $stmt->close();
      return [];
    }

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return array_values(array_filter(array_map(static function ($row) {
      return (int) ($row['Project_ID'] ?? 0);
    }, $rows)));
  }

  public function getProjectsByClientId($clientId, $count = null, $status = 'Active') {
    // actual data fetching logic to be implemented
    return [
      [
          'Title' => 'E‑commerce Platform',
          'Stage' => 'Sprint 3',
          'Provider' => 'DevStudio Labs',
          'Budget' => 6500,
          'Progress' => 60
      ],
      [
          'Title' => 'Analytics Dashboard',
          'Stage' => 'QA',
          'Provider' => 'DataCraft',
          'Budget' => 4800,
          'Progress' => 82
      ],
      [
          'Title' => 'Mobile Fitness App',
          'Stage' => 'Design',
          'Provider' => 'UXPro Studio',
          'Budget' => 3200,
          'Progress' => 35
      ]
    ];
  }

  public function countProjectsByClientId($clientId, $status = null) {
    // actual data fetching logic to be implemented
    return 5;
  }

  public function getPendingRequestsByClientId($clientId) {
    // actual data fetching logic to be implemented
    return [   
        [
            'provider' => 'Chethiya Bandara',
            'title' => 'Social Media Post Series (8 graphics)',
            'sentDate' => '15 Jul 2025 | 17:55',
            'proposedRate' => '$40/hr',
            'description' => 'Awaiting provider confirmation for design of 8 event/class promotional posts using provided branding.',
            'status' => 'Direct Request'
        ],
        // Add more pending requests as needed
    ];
  }

  public function getPendingReviewsByClientId($clientId) {
    // actual data fetching logic to be implemented
    return [
        [
            'title' => 'API Integration Phase 1',
            'submittedDate' => '03 Aug 2025',
            'milestoneAmount' => '$1,200',
            'description' => 'OAuth, invoice and billing endpoints integrated. Validate callback handling before release.'
        ],
        [
            'title' => 'API Integration Phase 1',
            'submittedDate' => '03 Aug 2025',
            'milestoneAmount' => '$1,200',
            'description' => 'OAuth, invoice and billing endpoints integrated. Validate callback handling before release.'
        ],
    ];
  }

  public function getCompletedProjectsByClientId($clientId) {
    // actual data fetching logic to be implemented
    return [
        [
            'title' => 'Website Redesign Project',
            'completedDate' => '15 Sep 2025',
            'totalPaid' => '$2,500',
            'duration' => '14d',
            'description' => 'Redesigned the entire company website with a focus on user experience and mobile responsiveness.'
        ],
        [
            'title' => 'Mobile App Development',
            'completedDate' => '30 Jul 2025',
            'totalPaid' => '$5,000',
            'duration' => '30d',
            'description' => 'Developed a cross-platform mobile application for e-commerce with integrated payment solutions.'
        ],
        // Add more completed projects as needed
    ];
  }

  public function getApprovedRequestsByClientId($clientId) {
    // actual data fetching logic to be implemented
    return [
        [
            'title' => '3D Asset Pack Creation',
            'startedDate' => '15 Aug 2025',
            'hourlyRate' => '$90/hr',
            'description' => 'Creating 15 optimized low‑poly environment props for prototype.',
            'status' => 'bid request',
            'estimatedTime' => '6d',
            'progress' => 0
        ],
        // Add more approved requests as needed
    ];
  }

  public function getOngoingProjectsByClientId($clientId) {
    // actual data fetching logic to be implemented
    return [
        [
          'title' => 'Brand Identity Development',
          'startedDate' => '01 Sep 2025',
          'hourlyRate' => '$75/hr',
          'status' => 'bid request',
          'estimatedTime' => '10d',
          'progress' => 40,
          'description' => 'Developing a comprehensive brand identity including logo, color palette, and typography.',
        ],
        [
          'title' => '3D Asset Pack Creation',
          'startedDate' => '15 Aug 2025',
          'hourlyRate' => '$90/hr',
          'status' => 'direct request',
          'estimatedTime' => '6d',
          'progress' => 0,
          'description' => 'Creating 15 optimized low‑poly environment props for prototype.',
        ],
    ];
  }

  public function cancelProject(int $postId): bool
    {
        $sql = "UPDATE Project SET Project_Status = 'canceled' WHERE Post_ID = ?";
        $sql2 = "UPDATE Post SET Request_Status = 'canceled' WHERE Post_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt2 = $this->conn->prepare($sql2);
        if (!$stmt || !$stmt2) {
            error_log('cancelProject prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);
        $stmt2->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('cancelProject exec: ' . $stmt->error);
            return false;
        }

        if (!$stmt2->execute()) {
            error_log('cancelProject exec: ' . $stmt2->error);
            return false;
        }

        $stmt->close();
        $stmt2->close();
        return true;
    }

    public function updateProjectProgress(int $postId, int $progress): bool
    {
        $sql = "UPDATE Project SET Progress = ? WHERE Post_ID = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('updateProjectProgress prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('ii', $progress, $postId);

        if (!$stmt->execute()) {
            error_log('updateProjectProgress exec: ' . $stmt->error);
            $stmt->close();
            return false;
        }


        $stmt->close();
        return true;
    }

    public function getRequirementsByPostId(int $postId): array
    {
        $sql = "SELECT 
                pr.Requirement_Text, pr .Project_ID
            FROM project_requirements pr
            JOIN Project p ON p.Project_ID = pr.Project_ID
            WHERE p.Post_ID = ? AND (pr.Status = 'accepted' OR pr.Status = 'completed')
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getProjectRequirementsById prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('getProjectRequirementsById exec: ' . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();
        $requirements = [];
        while ($row = $result->fetch_assoc()) {
            $requirements[] = $row;
        }

        $stmt->close();

        return $requirements;
    }

    public function addRequirement($project_id, $text)
    {
        $sql = "INSERT INTO project_requirements (Project_ID, Requirement_Text, Status)
                VALUES (?, ?, 'pending')";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("is", $project_id, $text);

        return $stmt->execute();
    }

    public function getProviderByProject($project_id)
    {
        $sql = "SELECT p.Provider_ID
                FROM project pr
                JOIN post p ON p.Post_ID = pr.Post_ID
                WHERE pr.Project_ID = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $project_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

}
