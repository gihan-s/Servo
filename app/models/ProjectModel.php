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
}
