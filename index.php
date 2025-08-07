<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servo</title>
</head>
<body>
  <h1>Servo</h1>
  <h3>All in One Service Platform</h3>


  <ul>
    <?php 

    include 'connection.php';

    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);
    while ($data = mysqli_fetch_array($result)) {
      echo "<li>{$data["Tables_in_servo"]}</li>";
    }
    
    ?>
  </ul>

  <div class="footer">
    Last Update by Gihan
  </div>
</body>
</html>

<style>
  *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;

    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body{
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: black;

    flex-direction: column;
    min-height: 100vh;

    color: white;
    /* gap: 10px; */
  }

  h1, h3{
    color: white;
    text-align: center;
    text-shadow: 0px 0px 10px rgb(69, 255, 69);
  }

  h1{
    font-size: 5em;
  }


  .footer{
    position: fixed;
    width: 100%;
    text-align: center;
    font-size: 0.8em;
    background-color: #1c1c1cff;
    left: 0;
    bottom: 0;
    padding: 5px;
    font-weight: bold;
  }
</style>