

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Projects - BSK</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
 
</head>

<body>

  <!-- Breadcrumb -->
  <!--<div class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="client-dashboard.html">Dashboard</a>
            <span>›</span>
            <span>My Jobs</span>
        </div>
    </div>-->

  <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
  <!-- Main Content -->
  <div class="main-content">
  <?php include __DIR__ . '/serviceProjects.php'; ?>
  </div>

  <!-- Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

  
</body>

</html>