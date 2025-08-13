<?php
// projects.php

// Dummy data for demonstration
$ongoingProjects = [
  [
    'id' => 1,
    'name' => 'Website Redesign',
    'client' => 'Acme Corp',
    'start_date' => '2024-05-01',
    'due_date' => '2024-07-15',
    'status' => 'In Progress',
    'description' => 'Redesigning the corporate website for Acme Corp.'
  ],
  [
    'id' => 2,
    'name' => 'Mobile App Development',
    'client' => 'Beta Ltd',
    'start_date' => '2024-06-10',
    'due_date' => '2024-09-01',
    'status' => 'In Progress',
    'description' => 'Developing a cross-platform mobile app.'
  ]
];

$pastProjects = [
  [
    'id' => 3,
    'name' => 'SEO Optimization',
    'client' => 'Gamma Inc',
    'start_date' => '2024-02-01',
    'due_date' => '2024-04-15',
    'status' => 'Completed',
    'description' => 'Improved SEO for Gamma Inc\'s e-commerce site.'
  ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Provider Project Management</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; }
    h2 { color: #333; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #f4f4f4; }
    .actions a { margin-right: 10px; color: #007bff; text-decoration: none; }
    .actions a:hover { text-decoration: underline; }
    .desc { color: #555; font-size: 0.95em; }
  </style>
</head>
<body>
  <h1>Project Management</h1>

  <h2>Ongoing Projects</h2>
  <?php if (count($ongoingProjects) > 0): ?>
    <table>
      <tr>
        <th>Project Name</th>
        <th>Client</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($ongoingProjects as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project['name']) ?></td>
          <td><?= htmlspecialchars($project['client']) ?></td>
          <td><?= htmlspecialchars($project['start_date']) ?></td>
          <td><?= htmlspecialchars($project['due_date']) ?></td>
          <td><?= htmlspecialchars($project['status']) ?></td>
          <td class="desc"><?= htmlspecialchars($project['description']) ?></td>
          <td class="actions">
            <a href="edit_project.php?id=<?= $project['id'] ?>">Edit</a>
            <a href="mark_complete.php?id=<?= $project['id'] ?>">Mark as Complete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No ongoing projects.</p>
  <?php endif; ?>

  <h2>Past Projects</h2>
  <?php if (count($pastProjects) > 0): ?>
    <table>
      <tr>
        <th>Project Name</th>
        <th>Client</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($pastProjects as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project['name']) ?></td>
          <td><?= htmlspecialchars($project['client']) ?></td>
          <td><?= htmlspecialchars($project['start_date']) ?></td>
          <td><?= htmlspecialchars($project['due_date']) ?></td>
          <td><?= htmlspecialchars($project['status']) ?></td>
          <td class="desc"><?= htmlspecialchars($project['description']) ?></td>
          <td class="actions">
            <a href="view_project.php?id=<?= $project['id'] ?>">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No past projects.</p>
  <?php endif; ?>

  <a href="add_project.php">Add New Project</a>
</body>
</html>