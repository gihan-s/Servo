<?php
    include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Jobs - ServiceHub</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 
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

  <?php include '../navbar.php'; ?>
  <!-- Main Content -->
  <div class="main-content">
    <?php include 'servicePosts_client.php'; ?>
  </div>

  <!-- Footer -->
  <?php include '../footer.php'; ?>

  
</body>

</html>

