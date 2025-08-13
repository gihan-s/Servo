<?php
// Dummy data for demonstration
$usertype = 'client';

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
    'name' => 'SEO Optimization #Test',
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
    body { font-family: Arial, sans-serif; margin: 30px; background: #f8f9fa; }
    h1 { color: #2c3e50; margin-bottom: 30px; }
    h2 { color: #34495e; margin-top: 40px; }
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-bottom: 40px; 
      background: #fff; 
      box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
      border-radius: 8px; 
      overflow: hidden;
    }
    th, td { 
      border: none; 
      padding: 14px 12px; 
      text-align: left; 
    }
    th { 
      background: #007bff; 
      color: #fff; 
      font-weight: 600; 
      letter-spacing: 0.5px;
    }
    tr:nth-child(even) { background: #f4f8fb; }
    tr:hover { background: #e9f5ff; transition: background 0.2s; }
    .actions a { 
      margin-right: 10px; 
      color: #007bff; 
      text-decoration: none; 
      font-weight: 500;
      padding: 6px 12px;
      border-radius: 4px;
      background: #e3f0ff;
      transition: background 0.2s, color 0.2s;
      border: 1px solid #b6d4fe;
    }
    .actions a:hover { 
      background: #007bff; 
      color: #fff; 
      border-color: #007bff;
      text-decoration: none;
    }
    .desc { color: #555; font-size: 0.97em; }
    a.add-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 22px;
      background: #28a745;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(40,167,69,0.08);
      transition: background 0.2s;
    }
    a.add-btn:hover {
      background: #218838;
      color: #fff;
    }
    @media (max-width: 700px) {
      table, thead, tbody, th, td, tr { display: block; }
      th { position: absolute; left: -9999px; top: -9999px; }
      tr { margin-bottom: 20px; }
      td { 
        border: none; 
        position: relative; 
        padding-left: 50%; 
        min-height: 40px;
        background: #fff;
      }
      td:before {
        position: absolute;
        top: 14px;
        left: 12px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        color: #007bff;
      }
      td:nth-of-type(1):before { content: "Project Name"; }
      td:nth-of-type(2):before { content: "Client"; }
      td:nth-of-type(3):before { content: "Start Date"; }
      td:nth-of-type(4):before { content: "Due Date"; }
      td:nth-of-type(5):before { content: "Status"; }
      td:nth-of-type(6):before { content: "Description"; }
      td:nth-of-type(7):before { content: "Actions"; }
    }
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

  <a href="add_project.php" class="add-btn">Add New Project</a>
</body>
</html>