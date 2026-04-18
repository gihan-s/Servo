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

    public function updateProjectProgress(
        int    $postId,
        int    $progress,
        string $title,
        string $description,
        float  $workedHours,
        array  $fileNames = []
    ): bool {
        // Resolve Project_ID from Post_ID
        $idStmt = $this->conn->prepare(
            'SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1'
        );
        if (!$idStmt) {
            error_log('updateProjectProgress id prepare: ' . $this->conn->error);
            return false;
        }
        $idStmt->bind_param('i', $postId);
        $idStmt->execute();
        $projectId = (int) ($idStmt->get_result()->fetch_assoc()['Project_ID'] ?? 0);
        $idStmt->close();

        if ($projectId <= 0) {
            error_log('updateProjectProgress: project not found for post ' . $postId);
            return false;
        }

        $this->conn->begin_transaction();

        try {
            // 1. Update progress percentage on the project row
            $upStmt = $this->conn->prepare(
                'UPDATE project SET Progress = ? WHERE Post_ID = ?'
            );
            if (!$upStmt) throw new Exception('prepare project update: ' . $this->conn->error);
            $upStmt->bind_param('ii', $progress, $postId);
            if (!$upStmt->execute()) throw new Exception('exec project update: ' . $upStmt->error);
            $upStmt->close();

            // 2. Insert update log entry
            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES (?, ?, NOW(), ?, ?, ?, "ongoing")'
            );
            if (!$logStmt) throw new Exception('prepare log insert: ' . $this->conn->error);
            $logStmt->bind_param('ssidi', $title, $description, $projectId, $workedHours, $progress);
            if (!$logStmt->execute()) throw new Exception('exec log insert: ' . $logStmt->error);
            $logId = (int) $this->conn->insert_id;
            $logStmt->close();

            // 3. Insert file records (if any)
            if (!empty($fileNames)) {
                $fileStmt = $this->conn->prepare(
                    'INSERT INTO project_update_log_files (Update_Log_ID, File) VALUES (?, ?)'
                );
                if (!$fileStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                foreach ($fileNames as $fileName) {
                    $fileStmt->bind_param('is', $logId, $fileName);
                    if (!$fileStmt->execute()) throw new Exception('exec file insert: ' . $fileStmt->error);
                }
                $fileStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::updateProjectProgress error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Returns all requirements (all statuses) for a post, each with their attached files.
     * Response shape: { project_id: int, requirements: [ { ...row, files: [filename, ...] } ] }
     */
    public function getRequirementsByPostId(int $postId): array
    {
        // Resolve Project_ID
        $pidStmt = $this->conn->prepare(
            'SELECT Project_ID FROM project WHERE Post_ID = ? LIMIT 1'
        );
        if (!$pidStmt) return ['project_id' => null, 'requirements' => []];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $projectId = (int) ($pidStmt->get_result()->fetch_assoc()['Project_ID'] ?? 0);
        $pidStmt->close();

        if ($projectId <= 0) {
            return ['project_id' => null, 'requirements' => []];
        }

        // Fetch all requirements
        $reqStmt = $this->conn->prepare(
            'SELECT Requirement_ID, Requirement_Title, Requirement_Description,
                    Status, Created_At, Approved_At, Rejected_At, Rejection_Reason
             FROM project_requirements
             WHERE Project_ID = ?
             ORDER BY Created_At DESC'
        );
        if (!$reqStmt) return ['project_id' => $projectId, 'requirements' => []];
        $reqStmt->bind_param('i', $projectId);
        $reqStmt->execute();
        $rows = $reqStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $reqStmt->close();

        if (empty($rows)) {
            return ['project_id' => $projectId, 'requirements' => []];
        }

        // Collect IDs and fetch files in one query
        $ids      = array_column($rows, 'Requirement_ID');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types    = str_repeat('i', count($ids));
        $fileStmt = $this->conn->prepare(
            "SELECT Requirement_ID, File FROM project_requirements_files WHERE Requirement_ID IN ($placeholders)"
        );
        if ($fileStmt) {
            $fileStmt->bind_param($types, ...$ids);
            $fileStmt->execute();
            $fileRows = $fileStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $fileStmt->close();
        } else {
            $fileRows = [];
        }

        // Group files by requirement
        $fileMap = [];
        foreach ($fileRows as $f) {
            $fileMap[$f['Requirement_ID']][] = $f['File'];
        }

        // Attach files to each requirement row
        foreach ($rows as &$row) {
            $row['files'] = $fileMap[$row['Requirement_ID']] ?? [];
        }
        unset($row);

        return ['project_id' => $projectId, 'requirements' => $rows];
    }

    /**
     * Insert a new requirement (status = 'pending') with optional file attachments.
     *
     * @param int    $projectId
     * @param string $title
     * @param string $description
     * @param array  $fileNames   Array of saved file name strings
     */
    public function addRequirement(int $projectId, string $title, string $description = '', array $fileNames = []): bool
    {
        $this->conn->begin_transaction();
        try {
            $insStmt = $this->conn->prepare(
                "INSERT INTO project_requirements (Project_ID, Requirement_Title, Requirement_Description, Status, Created_At)
                 VALUES (?, ?, ?, 'pending', NOW())"
            );
            if (!$insStmt) throw new Exception('prepare req insert: ' . $this->conn->error);
            $insStmt->bind_param('iss', $projectId, $title, $description);
            if (!$insStmt->execute()) throw new Exception('exec req insert: ' . $insStmt->error);
            $reqId = (int) $this->conn->insert_id;
            $insStmt->close();

            if (!empty($fileNames)) {
                $fileStmt = $this->conn->prepare(
                    'INSERT INTO project_requirements_files (Requirement_ID, File) VALUES (?, ?)'
                );
                if (!$fileStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                foreach ($fileNames as $fileName) {
                    $fileStmt->bind_param('is', $reqId, $fileName);
                    if (!$fileStmt->execute()) throw new Exception('exec file insert: ' . $fileStmt->error);
                }
                $fileStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::addRequirement error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check that a requirement belongs to a project owned by the given provider.
     */
    public function requirementBelongsToProvider(int $requirementId, int $providerId): bool
    {
        $stmt = $this->conn->prepare(
            'SELECT 1
             FROM project_requirements pr
             JOIN project proj ON proj.Project_ID = pr.Project_ID
             JOIN post p       ON p.Post_ID = proj.Post_ID
             WHERE pr.Requirement_ID = ? AND p.Provider_ID = ?
             LIMIT 1'
        );
        if (!$stmt) return false;
        $stmt->bind_param('ii', $requirementId, $providerId);
        $stmt->execute();
        $found = (bool) $stmt->get_result()->fetch_row();
        $stmt->close();
        return $found;
    }

    /**
     * Set a requirement status to 'approved' or 'rejected'.
     * For rejected status a non-empty rejection reason is required.
     */
    public function updateRequirementStatus(int $requirementId, string $status, string $rejectionReason = ''): bool
    {
        if (!in_array($status, ['approved', 'rejected'], true)) return false;

        if ($status === 'approved') {
            $stmt = $this->conn->prepare(
                "UPDATE project_requirements
                 SET Status = 'approved', Approved_At = NOW()
                 WHERE Requirement_ID = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param('i', $requirementId);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE project_requirements
                 SET Status = 'rejected', Rejected_At = NOW(), Rejection_Reason = ?
                 WHERE Requirement_ID = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param('si', $rejectionReason, $requirementId);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
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

    public function cancelRequest(int $postId): bool
    {
        $sql = "UPDATE Post SET Request_Status = 'cancelled' WHERE Post_ID = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('cancelRequest prepare: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('cancelRequest exec: ' . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }

    /**
     * Fetch ongoing projects for a provider with pagination.
     * Joins project → post → category → client.
     * Filters: project.Project_Status = 'ongoing' AND post.Provider_ID = $providerId
     */
    public function getOngoingProjectsForProvider(int $providerId, int $page = 1, int $limit = 10): array
    {
        $page   = max(1, $page);
        $limit  = max(1, min($limit, 100));
        $offset = ($page - 1) * $limit;

        // Total count
        $countSql = "SELECT COUNT(*) AS total
                     FROM project proj
                     JOIN post p ON p.Post_ID = proj.Post_ID
                     WHERE proj.Project_Status = 'ongoing' AND p.Provider_ID = ?";

        $countStmt = $this->conn->prepare($countSql);
        if (!$countStmt) {
            error_log('getOngoingProjectsForProvider count prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $countStmt->bind_param('i', $providerId);
        $countStmt->execute();
        $totalRecords = (int) ($countStmt->get_result()->fetch_assoc()['total'] ?? 0);
        $countStmt->close();

        $totalPages = $totalRecords > 0 ? (int) ceil($totalRecords / $limit) : 0;

        // Data query
        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    cat.Name AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl ON cl.Client_ID = p.Client_ID
                WHERE proj.Project_Status = 'ongoing' AND p.Provider_ID = ?
                ORDER BY proj.Started_At DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('getOngoingProjectsForProvider prepare: ' . $this->conn->error);
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $stmt->bind_param('iii', $providerId, $limit, $offset);
        if (!$stmt->execute()) {
            error_log('getOngoingProjectsForProvider exec: ' . $stmt->error);
            $stmt->close();
            return ['data' => [], 'total' => 0, 'pages' => 0, 'current_page' => $page];
        }

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return [
            'data'         => $data,
            'total'        => $totalRecords,
            'pages'        => $totalPages,
            'current_page' => $page,
        ];
    }

    public function createProject(int $postId): int
    {
        $sql = "INSERT INTO project (Post_ID, Project_Status, Started_At) VALUES (?, 'ongoing', NOW())";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('createProject prepare: ' . $this->conn->error);
            return 0;
        }

        $stmt->bind_param('i', $postId);

        if (!$stmt->execute()) {
            error_log('createProject exec: ' . $stmt->error);
            return 0;
        }

        $projectId = $stmt->insert_id;

        $stmt->close();
        
        return $projectId;
    }

      public function createProjectAndCompleteRequest(int $postId): array
      {
        $this->conn->begin_transaction();

        try {
          $insertSql = "INSERT INTO project (Post_ID, Project_Status, Started_At) VALUES (?, 'ongoing', NOW())";
          $insertStmt = $this->conn->prepare($insertSql);
          if (!$insertStmt) {
            throw new Exception('createProjectAndCompleteRequest prepare project: ' . $this->conn->error);
          }

          $insertStmt->bind_param('i', $postId);
          if (!$insertStmt->execute()) {
            throw new Exception('createProjectAndCompleteRequest exec project: ' . $insertStmt->error);
          }

          $projectId = (int) $insertStmt->insert_id;
          $insertStmt->close();

          if ($projectId <= 0) {
            throw new Exception('createProjectAndCompleteRequest invalid project id');
          }

          $updateSql = "UPDATE post SET Request_Status = 'completed' WHERE Post_ID = ?";
          $updateStmt = $this->conn->prepare($updateSql);
          if (!$updateStmt) {
            throw new Exception('createProjectAndCompleteRequest prepare post: ' . $this->conn->error);
          }

          $updateStmt->bind_param('i', $postId);
          if (!$updateStmt->execute()) {
            throw new Exception('createProjectAndCompleteRequest exec post: ' . $updateStmt->error);
          }

          if ($updateStmt->affected_rows <= 0) {
            throw new Exception('createProjectAndCompleteRequest no post rows updated');
          }

          $updateStmt->close();

          $this->conn->commit();

          return [
            'success' => true,
            'project_id' => $projectId
          ];
        } catch (Throwable $e) {
          $this->conn->rollback();
          error_log('ProjectModel::createProjectAndCompleteRequest error: ' . $e->getMessage());
          return [
            'success' => false,
            'project_id' => 0
          ];
        }
      }

    /**
     * Get full project details for the detail view (project + post + provider/client + category).
     */
    public function getProjectFullDetails(int $postId): ?array
    {
        $sql = "SELECT
                    proj.Project_ID,
                    proj.Project_Status,
                    proj.Started_At,
                    proj.Ended_At,
                    COALESCE(proj.Progress, 0) AS Progress,
                    p.Post_ID,
                    p.Title,
                    p.Description,
                    p.Requesting_Price,
                    p.Price_Type,
                    p.Est_Date,
                    p.Level,
                    p.Post_Type,
                    p.Client_ID,
                    p.Provider_ID,
                    p.Created_At AS Post_Created_At,
                    cat.Name       AS Category_Name,
                    CONCAT(cl.First_Name, ' ', cl.Last_Name) AS Client_Name,
                    cl.Profile_Picture AS Client_Picture,
                    CONCAT(prov.First_Name, ' ', prov.Last_Name) AS Provider_Name,
                    prov.Profile_Picture AS Provider_Picture
                FROM project proj
                JOIN post p ON p.Post_ID = proj.Post_ID
                LEFT JOIN category cat  ON cat.Category_ID = p.Category_ID
                LEFT JOIN client cl     ON cl.Client_ID    = p.Client_ID
                LEFT JOIN provider prov ON prov.Provider_ID = p.Provider_ID
                WHERE proj.Post_ID = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param('i', $postId);
        if (!$stmt->execute()) { $stmt->close(); return null; }
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }

    /**
     * Get project timeline: all events (start, progress updates, requirement adds, requirement status changes).
     * Returns a flat array sorted by date DESC.
     */
    public function getProjectTimeline(int $postId): array
    {
        // Resolve Project_ID
        $pidStmt = $this->conn->prepare('SELECT Project_ID, Started_At FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$pidStmt) return [];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $projRow = $pidStmt->get_result()->fetch_assoc();
        $pidStmt->close();
        if (!$projRow) return [];

        $projectId = (int) $projRow['Project_ID'];
        $events = [];

        // 1. Project started event
        if (!empty($projRow['Started_At'])) {
            $events[] = [
                'type'  => 'project_started',
                'title' => 'Project Started',
                'description' => '',
                'date'  => $projRow['Started_At'],
                'icon'  => 'fa-rocket',
                'color' => 'green',
            ];
        }

        // 2. Progress update logs (ongoing only)
        $logSql = "SELECT ul.ID, ul.Title, ul.Description, ul.Date, ul.Worked_Hours,
                          COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                   FROM project_update_log ul
                   WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') = 'ongoing'
                   ORDER BY ul.Date DESC";
        $logStmt = $this->conn->prepare($logSql);
        if ($logStmt) {
            $logStmt->bind_param('i', $projectId);
            $logStmt->execute();
            $logs = $logStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $logStmt->close();

            // Fetch files for all update logs
            $logFileMap = [];
            if (!empty($logs)) {
                $logIds = array_column($logs, 'ID');
                $ph = implode(',', array_fill(0, count($logIds), '?'));
                $types = str_repeat('i', count($logIds));
                $fStmt = $this->conn->prepare("SELECT Update_Log_ID, File FROM project_update_log_files WHERE Update_Log_ID IN ($ph)");
                if ($fStmt) {
                    $fStmt->bind_param($types, ...$logIds);
                    $fStmt->execute();
                    foreach ($fStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                        $logFileMap[$f['Update_Log_ID']][] = $f['File'];
                    }
                    $fStmt->close();
                }
            }

            foreach ($logs as $log) {
                $events[] = [
                    'type'              => 'progress_update',
                    'title'             => $log['Title'] ?? 'Progress Update',
                    'description'       => $log['Description'] ?? '',
                    'date'              => $log['Date'],
                    'worked_hours'      => (float) ($log['Worked_Hours'] ?? 0),
                    'progress_completed'=> (int) ($log['Progress_Completed'] ?? 0),
                    'files'             => $logFileMap[$log['ID']] ?? [],
                    'icon'              => 'fa-arrow-up-right-dots',
                    'color'             => 'blue',
                ];
            }
        }

        // 3. Status-change logs (e.g. submitted for review)
        $statusSql = "SELECT ul.ID, ul.Title, ul.Description, ul.Date, ul.Project_Status_Update,
                             COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                      FROM project_update_log ul
                      WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') != 'ongoing'
                      ORDER BY ul.Date DESC";
        $statusStmt = $this->conn->prepare($statusSql);
        if ($statusStmt) {
            $statusStmt->bind_param('i', $projectId);
            $statusStmt->execute();
            $statusLogs = $statusStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $statusStmt->close();

            // Fetch files for status logs
            $statusFileMap = [];
            if (!empty($statusLogs)) {
                $sIds = array_column($statusLogs, 'ID');
                $sph  = implode(',', array_fill(0, count($sIds), '?'));
                $sTypes = str_repeat('i', count($sIds));
                $sfStmt = $this->conn->prepare("SELECT Update_Log_ID, File FROM project_update_log_files WHERE Update_Log_ID IN ($sph)");
                if ($sfStmt) {
                    $sfStmt->bind_param($sTypes, ...$sIds);
                    $sfStmt->execute();
                    foreach ($sfStmt->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
                        $statusFileMap[$f['Update_Log_ID']][] = $f['File'];
                    }
                    $sfStmt->close();
                }
            }

            foreach ($statusLogs as $sl) {
                $statusVal = $sl['Project_Status_Update'] ?? '';
                $icon  = 'fa-flag';
                $color = 'gray';
                if ($statusVal === 'pending-review') {
                    $icon  = 'fa-paper-plane';
                    $color = 'green';
                }
                $events[] = [
                    'type'              => 'status_change',
                    'title'             => $sl['Title'] ?? 'Status Changed',
                    'description'       => $sl['Description'] ?? '',
                    'date'              => $sl['Date'],
                    'status'            => $statusVal,
                    'progress_completed'=> (int) ($sl['Progress_Completed'] ?? 0),
                    'files'             => $statusFileMap[$sl['ID']] ?? [],
                    'icon'              => $icon,
                    'color'             => $color,
                ];
            }
        }

        // 4. Requirement events (created, approved, rejected)
        $reqSql = "SELECT Requirement_ID, Requirement_Title, Status, Created_At, Approved_At, Rejected_At, Rejection_Reason
                   FROM project_requirements
                   WHERE Project_ID = ?";
        $reqStmt = $this->conn->prepare($reqSql);
        if ($reqStmt) {
            $reqStmt->bind_param('i', $projectId);
            $reqStmt->execute();
            $reqs = $reqStmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $reqStmt->close();

            foreach ($reqs as $req) {
                // Requirement added
                if (!empty($req['Created_At'])) {
                    $events[] = [
                        'type'  => 'requirement_added',
                        'title' => 'Requirement Added: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => '',
                        'date'  => $req['Created_At'],
                        'icon'  => 'fa-file-circle-plus',
                        'color' => 'amber',
                    ];
                }
                // Requirement approved
                if ($req['Status'] === 'approved' && !empty($req['Approved_At'])) {
                    $events[] = [
                        'type'  => 'requirement_approved',
                        'title' => 'Requirement Accepted: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => '',
                        'date'  => $req['Approved_At'],
                        'icon'  => 'fa-circle-check',
                        'color' => 'green',
                    ];
                }
                // Requirement rejected
                if ($req['Status'] === 'rejected' && !empty($req['Rejected_At'])) {
                    $events[] = [
                        'type'  => 'requirement_rejected',
                        'title' => 'Requirement Rejected: ' . ($req['Requirement_Title'] ?? 'Untitled'),
                        'description' => $req['Rejection_Reason'] ?? '',
                        'date'  => $req['Rejected_At'],
                        'icon'  => 'fa-circle-xmark',
                        'color' => 'red',
                    ];
                }
            }
        }

        // Sort by date DESC
        usort($events, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $events;
    }

    /**
     * Get progress history (for charting) — returns date+progress pairs from update logs.
     * Uses the real Progress_Completed column stored on each update log entry.
     */
    public function getProgressHistory(int $postId): array
    {
        $pidStmt = $this->conn->prepare('SELECT Project_ID, Started_At FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$pidStmt) return [];
        $pidStmt->bind_param('i', $postId);
        $pidStmt->execute();
        $proj = $pidStmt->get_result()->fetch_assoc();
        $pidStmt->close();
        if (!$proj) return [];

        $projectId = (int) $proj['Project_ID'];
        $points = [];

        // Starting point: 0% at project start
        if (!empty($proj['Started_At'])) {
            $points[] = ['date' => $proj['Started_At'], 'progress' => 0];
        }

        // Use the actual Progress_Completed snapshot stored on each update log (ongoing entries only)
        $sql = "SELECT ul.Date, COALESCE(ul.Progress_Completed, 0) AS Progress_Completed
                FROM project_update_log ul
                WHERE ul.Project_ID = ? AND COALESCE(ul.Project_Status_Update, 'ongoing') = 'ongoing'
                ORDER BY ul.Date ASC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $projectId);
            $stmt->execute();
            $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            foreach ($logs as $log) {
                $points[] = [
                    'date'     => $log['Date'],
                    'progress' => (int) $log['Progress_Completed'],
                ];
            }
        }

        return $points;
    }

    /**
     * Submit a project for review: update status to 'pending-review', set Ended_At, and log the event.
     */
    public function submitForReview(
        int    $postId,
        string $description,
        array  $fileNames = []
    ): bool {
        $idStmt = $this->conn->prepare('SELECT Project_ID, COALESCE(Progress,0) AS Progress FROM project WHERE Post_ID = ? LIMIT 1');
        if (!$idStmt) return false;
        $idStmt->bind_param('i', $postId);
        $idStmt->execute();
        $row = $idStmt->get_result()->fetch_assoc();
        $idStmt->close();
        if (!$row) return false;

        $projectId = (int) $row['Project_ID'];
        $progress  = (int) $row['Progress'];

        $this->conn->begin_transaction();
        try {
            // 1. Update project status and end time
            $upStmt = $this->conn->prepare(
                'UPDATE project SET Project_Status = "pending-review", Ended_At = NOW() WHERE Post_ID = ?'
            );
            if (!$upStmt) throw new Exception('prepare status update: ' . $this->conn->error);
            $upStmt->bind_param('i', $postId);
            if (!$upStmt->execute()) throw new Exception('exec status update: ' . $upStmt->error);
            $upStmt->close();

            // 2. Insert log entry for the status change
            $logStmt = $this->conn->prepare(
                'INSERT INTO project_update_log (Title, Description, Date, Project_ID, Worked_Hours, Progress_Completed, Project_Status_Update)
                 VALUES ("Submitted for Review", ?, NOW(), ?, 0, ?, "pending-review")'
            );
            if (!$logStmt) throw new Exception('prepare review log: ' . $this->conn->error);
            $logStmt->bind_param('sii', $description, $projectId, $progress);
            if (!$logStmt->execute()) throw new Exception('exec review log: ' . $logStmt->error);
            $logId = (int) $this->conn->insert_id;
            $logStmt->close();

            // 3. Insert file records
            if (!empty($fileNames)) {
                $fStmt = $this->conn->prepare(
                    'INSERT INTO project_update_log_files (Update_Log_ID, File) VALUES (?, ?)'
                );
                if (!$fStmt) throw new Exception('prepare file insert: ' . $this->conn->error);
                foreach ($fileNames as $fn) {
                    $fStmt->bind_param('is', $logId, $fn);
                    if (!$fStmt->execute()) throw new Exception('exec file insert: ' . $fStmt->error);
                }
                $fStmt->close();
            }

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('ProjectModel::submitForReview error: ' . $e->getMessage());
            return false;
        }
    }

}
