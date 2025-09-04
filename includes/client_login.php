<?php
// include database connection and session start if needed
require_once 'connection.php';
session_start();

// Set to true to use dummy data for testing
$useDummyData = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (empty($email) || empty($password)) {
    $error = "Please enter both email and password.";
  } else if ($useDummyData) {
    $clients = require __DIR__ . '/../dummyData/clients.php';
    $found = false;
    foreach ($clients as $client) {
      if (strtolower($client['Email']) === strtolower($email)) {
        $found = true;
        if (password_verify($password, $client['Password'])) {
          $_SESSION['client_id'] = $client['Client_ID'];
          $_SESSION['email'] = $client['Email'];
          header("Location: ../index.php");
          exit();
        } else {
          $error = "Invalid email or password.";
        }
        break;
      }
    }
    if (!$found) {
      $error = "Invalid email or password.";
    }
  } else {
    // prepare SQL to prevent SQL injection
    // select Client_ID and Password Hash from Client table where email matches
    $stmt = $conn->prepare("SELECT Client_ID, Password FROM Client WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
      $stmt->bind_result($client_id, $hashed_password);
      $stmt->fetch();

      if (password_verify($password, $hashed_password)) {
        // successful login
        $_SESSION['client_id'] = $client_id;
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
      } else {
        $error = "Invalid email or password.";
      }
    } else {
      $error = "Invalid email or password.";
    }
    $stmt->close();
  }
}

// display $error in your HTML form
if (isset($error)) {
  header("Location: login.php?error=" . urlencode($error));
  exit();
}
?>