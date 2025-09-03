<?php
  $passhash1 = password_hash('Test123@', PASSWORD_DEFAULT);
  $passhash2 = password_hash('Test456@', PASSWORD_DEFAULT);
  echo "Password Hash 1: " . $passhash1 . "<br>";
  echo "Password Hash 2: " . $passhash2 . "<br>";
?>