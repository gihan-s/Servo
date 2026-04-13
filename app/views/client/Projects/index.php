

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servo | Projects</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
 
</head>

<body>

  <?php
  require_once __DIR__ . '/../../includes/navbar.php';
  ?>

  <!-- Main Content -->
  <div class="main-content">
  <?php include __DIR__ . '/serviceProjects.php'; ?>
  </div>

  <!-- Footer -->
  <?php
  require_once __DIR__ . '/../../includes/footer.php';
  ?>

  
</body>

</html>