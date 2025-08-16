<?php
include 'dummydata.php';
$ongoing = [];
$finished = [];
foreach ($projects as $p) {
  if ($p['status'] === 'ongoing') {
    $ongoing[] = $p;
  } elseif ($p['status'] === 'finished') {
    $finished[] = $p;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Provider Project Management</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  
  <h1>Projects</h1>

  <h2>Ongoing</h2>
  <?php if (count($ongoing) > 0): ?>
    <table>
      <tr>
        <th>Project Name</th>
        <th>Client</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($ongoing as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project['name']) ?></td>
          <td><?= htmlspecialchars($project['client']) ?></td>
          <td><?= htmlspecialchars($project['start_date']) ?></td>
          <td><?= htmlspecialchars($project['due_date']) ?></td>
          <td class="desc"><?= htmlspecialchars($project['description']) ?></td>
          <td class="actions">
            <a href="edit_project.php?id=<?= $project['id'] ?>">Edit</a>
            <a href="mark_complete.php?id=<?= $project['id'] ?>">Mark as Complete</a>
            <a href="view_updates.php?id=<?= $project['id'] ?>">View Updates</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No ongoing projects.</p>
  <?php endif; ?>

  <h2>Finished</h2>
  <?php if (count($finished) > 0): ?>
    <table>
      <tr>
        <th>Project Name</th>
        <th>Client</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($finished as $project): ?>
        <tr>
          <td><?= htmlspecialchars($project['name']) ?></td>
          <td><?= htmlspecialchars($project['client']) ?></td>
          <td><?= htmlspecialchars($project['start_date']) ?></td>
          <td><?= htmlspecialchars($project['due_date']) ?></td>
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