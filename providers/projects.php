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

  <!-- CSS Files -->
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/projects.css">
  <!-- JS Files -->
  <script src="../assets/js/projects.js"></script>
</head>

<body>
  <div class="search-bar">
  <input type="text" placeholder="Search projects...">
  </div>

  <div class="main-content">
    <h1>PROJECTS</h1>

    <h2>Ongoing Projects</h2>
    <?php if (count($ongoing) > 0): ?>

      <div class="project-list">
        <?php foreach ($ongoing as $project): ?>
          <div class="project-card">
            <h3 class="project-card-title"><?= htmlspecialchars($project['name']) ?></h3>
            <p class="project-card-description"><?= htmlspecialchars($project['description']) ?></p>
            <br>
            <p><strong>Client:</strong> <?= htmlspecialchars($project['client']) ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
            <p><strong>Due Date:</strong> <?= htmlspecialchars($project['due_date']) ?></p>
            <div class="buttons">
              <a class="button" href="#">View Updates</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>
      <p>No ongoing projects.</p>

    <?php endif; ?>

    <h2>Finished Projects</h2>
    <?php if (count($finished) > 0): ?>
      
      <div class="project-list">
        <?php foreach ($finished as $project): ?>
          <div class="project-card finished">
            <h3 class="project-card-title"><?= htmlspecialchars($project['name']) ?></h3>
            <p class="project-card-description"><?= htmlspecialchars($project['description']) ?></p>
            <br>
            <p><strong>Client:</strong> <?= htmlspecialchars($project['client']) ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
            <p><strong>Due Date:</strong> <?= htmlspecialchars($project['due_date']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>
      <p>No past projects.</p>

    <?php endif; ?>
  </div>
</body>
</html>