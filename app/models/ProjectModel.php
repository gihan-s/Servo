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
// TODO: Missing fields: Stage (project phase/sprint), Budget, Progress (percentage)
// Budget can be derived from Post.Requesting_Price
// Stage and Progress need to be added to schema or calculated differently

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/helpers.php';

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
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, prov.First_Name, prov.Last_Name
    //        FROM project pr 
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status = ?
    // TODO: Stage and Progress fields need to be added to project table or calculated
    return [];
  }

  public function countProjectsByClientId($clientId, $status = null) {
    // TODO: Implement actual database query
    return 0;
  }

  public function getPendingRequestsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, prov.First_Name, prov.Last_Name
    //        FROM post p
    //        INNER JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND p.Request_Status = 'Pending' AND p.Post_Type = 'Direct'
    return [];
  }

  public function getPendingReviewsByClientId($clientId) {
    // TODO: Implement actual database query
    // This might need a milestone/deliverable table that doesn't exist yet
    return [];
  }

  public function getCompletedProjectsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status = 'Completed'
    return [];
  }

  public function getApprovedRequestsByClientId($clientId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, prov.First_Name, prov.Last_Name
    //        FROM post p
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND p.Request_Status = 'Approved'
    return [];
  }

  public function getOngoingProjectsByClientId($clientId) {
    // TODO: Implement actual database query  
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, prov.First_Name, prov.Last_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        LEFT JOIN provider prov ON p.Provider_ID = prov.Provider_ID
    //        WHERE p.Client_ID = ? AND pr.Project_Status IN ('Active', 'In Progress')
    // TODO: Progress field needs implementation (not in current schema)
    return [];
  }

  // Provider-specific methods

  public function getIncomingRequestsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT p.*, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM post p
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND p.Request_Status = 'Pending' AND p.Post_Type = 'Direct'
    
    return [
      [
        'Client_Name' => 'Michael Chen',
        'Title' => 'E-commerce Website Development',
        'Category_Name' => 'Web Development',
        'Posted' => 'Proposed 12 Jul 2025',
        'Budget' => '$2,500',
        'Timeline' => '4 weeks',
        'Status' => 'Pending Response',
        'Description' => 'Full e-commerce site with product catalog, shopping cart, and payment integration.'
      ],
      [
        'Client_Name' => 'Sarah Martinez',
        'Title' => 'Corporate Logo Design',
        'Category_Name' => 'Graphic Design',
        'Posted' => 'Proposed 10 Jul 2025',
        'Budget' => '$800',
        'Timeline' => '2 weeks',
        'Status' => 'Pending Response',
        'Description' => 'Modern, professional logo for a consulting firm with brand guidelines.'
      ]
    ];
  }

  public function getOngoingProjectsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND pr.Project_Status IN ('Active', 'In Progress')
    // TODO: Progress field needs implementation
    
    return [
      [
        'Client_Name' => 'Emma Wilson',
        'Title' => 'Mobile App UI/UX Design',
        'Category_Name' => 'UI/UX Design',
        'Posted' => 'Started 10 Jul 2025',
        'Budget' => '$1,200',
        'Timeline' => 'ETA 12d',
        'Status' => 'In Progress',
        'Progress' => 65,
        'Progress_Detail' => '32h of 50h',
        'Hours_Logged' => '32h',
        'Description' => 'Designing user interface and experience for a fitness tracking mobile application.'
      ],
      [
        'Client_Name' => 'Robert Taylor',
        'Title' => 'SEO Strategy Implementation',
        'Category_Name' => 'SEO',
        'Posted' => 'Started 05 Jul 2025',
        'Budget' => '$950',
        'Timeline' => 'ETA 18d',
        'Status' => 'In Progress',
        'Progress' => 40,
        'Progress_Detail' => '20h of 50h',
        'Hours_Logged' => '20h',
        'Description' => 'Implementing comprehensive SEO strategy including keyword research and on-page optimization.'
      ]
    ];
  }

  public function getPendingReviewProjectsByProviderId($providerId) {
    // TODO: Implement actual database query
    // This might need a milestone/deliverable table that doesn't exist yet
    // Query would check for submitted deliverables awaiting client approval
    
    return [
      [
        'Client_Name' => 'David Rodriguez',
        'Title' => 'Website Content Writing',
        'Category_Name' => 'Content Writing',
        'Posted' => 'Submitted 08 Jul 2025',
        'Budget' => '$600',
        'Timeline' => 'Not specified',
        'Status' => 'Pending Review',
        'Description' => 'Wrote homepage, about us, and services page content for a digital marketing agency.'
      ],
      [
        'Client_Name' => 'Lisa Anderson',
        'Title' => 'Product Photography',
        'Category_Name' => 'Photography',
        'Posted' => 'Submitted 06 Jul 2025',
        'Budget' => '$1,100',
        'Timeline' => 'Not specified',
        'Status' => 'Pending Review',
        'Description' => 'Professional product photography for e-commerce catalog (50 items).'
      ]
    ];
  }

  public function getCompletedJobsByProviderId($providerId) {
    // TODO: Implement actual database query
    // Query: SELECT pr.*, p.Title, p.Requesting_Price, c.First_Name, c.Last_Name, cat.Name AS Category_Name
    //        FROM project pr
    //        INNER JOIN post p ON pr.Post_ID = p.Post_ID
    //        INNER JOIN client c ON p.Client_ID = c.Client_ID
    //        INNER JOIN category cat ON p.Category_ID = cat.Category_ID
    //        WHERE p.Provider_ID = ? AND pr.Project_Status = 'Completed'
    
    return [
      [
        'Client_Name' => 'Jennifer Lee',
        'Title' => 'Social Media Marketing Campaign',
        'Category_Name' => 'Digital Marketing',
        'Posted' => 'Completed 01 Jul 2025',
        'Budget' => '$1,500',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => '30-day social media campaign with content creation and community management across 3 platforms.'
      ],
      [
        'Client_Name' => 'Thomas Brown',
        'Title' => 'WordPress Plugin Development',
        'Category_Name' => 'Web Development',
        'Posted' => 'Completed 28 Jun 2025',
        'Budget' => '$2,200',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => 'Custom WordPress plugin for appointment booking with payment integration.'
      ],
      [
        'Client_Name' => 'Amanda White',
        'Title' => 'Brand Identity Package',
        'Category_Name' => 'Graphic Design',
        'Posted' => 'Completed 25 Jun 2025',
        'Budget' => '$1,800',
        'Timeline' => 'Not specified',
        'Status' => 'Completed',
        'Description' => 'Complete brand identity including logo, business cards, letterhead, and brand guidelines.'
      ]
    ];
  }
}
