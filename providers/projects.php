<?php
include 'dummydata.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Provider Project Management</title>
  <link rel="stylesheet" href="../styles.css">
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