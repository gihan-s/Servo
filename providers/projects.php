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
  <title>Projects</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <h1>Projects</h1>

  <h2>Ongoing</h2>
  <?php if (count($ongoing) > 0): ?>
    <div class="project-cards">
      <?php foreach ($ongoing as $project): ?>
        <div class="project-card">
          <h3><?= htmlspecialchars($project['name']) ?></h3>
          <p><strong>Client:</strong> <?= htmlspecialchars($project['client']) ?></p>
          <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
          <p><strong>Due Date:</strong> <?= htmlspecialchars($project['due_date']) ?></p>
          <p class="desc"><strong>Description:</strong> <?= htmlspecialchars($project['description']) ?></p>
          <div class="actions">
            <a href="edit_project.php?id=<?= $project['id'] ?>">Edit</a>
            <a href="mark_complete.php?id=<?= $project['id'] ?>">Mark as Complete</a>
            <a href="view_updates.php?id=<?= $project['id'] ?>">View Updates</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No ongoing projects.</p>
  <?php endif; ?>

  <h2>Finished</h2>
  <?php if (count($finished) > 0): ?>
    <div class="project-cards">
      <?php foreach ($finished as $project): ?>
        <div class="project-card finished">
          <h3><?= htmlspecialchars($project['name']) ?></h3>
          <p><strong>Client:</strong> <?= htmlspecialchars($project['client']) ?></p>
          <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
          <p><strong>Due Date:</strong> <?= htmlspecialchars($project['due_date']) ?></p>
          <p class="desc"><strong>Description:</strong> <?= htmlspecialchars($project['description']) ?></p>
          <div class="actions">
            <a href="view_project.php?id=<?= $project['id'] ?>">View</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No past projects.</p>
  <?php endif; ?>

  <a href="#" class="add-btn">Add New Project</a>
</body>
</html>