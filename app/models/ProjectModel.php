<?php

require_once __DIR__ . '/../core/Database.php';

class ProjectModel extends Database
{
  public function getProjectsByClientId($clientId, $count = null, $status = 'Active') {
    // actual data fetching logic to be implemented
    return [
      [
          'name' => 'E‑commerce Platform',
          'stage' => 'Sprint 3',
          'provider' => 'DevStudio Labs',
          'budget' => 6500,
          'progress' => 60
      ],
      [
          'name' => 'Analytics Dashboard',
          'stage' => 'QA',
          'provider' => 'DataCraft',
          'budget' => 4800,
          'progress' => 82
      ],
      [
          'name' => 'Mobile Fitness App',
          'stage' => 'Design',
          'provider' => 'UXPro Studio',
          'budget' => 3200,
          'progress' => 35
      ]
    ];
  }

  public function countProjectsByClientId($clientId, $status = null) {
    // actual data fetching logic to be implemented
    return 5;
  }
}