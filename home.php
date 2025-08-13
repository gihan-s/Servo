<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home - My Website</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { 
      font-family: Arial, sans-serif; 
      margin: 0; 
      padding: 0; 
      background: #f4f4f4; 
    }
    header { 
      background: #333; 
      color: #fff; 
      padding: 20px 0; 
      text-align: center; 
    }
    nav {
      background: #444;
      padding: 10px 0;
      text-align: center;
    }
    nav a {
      color: #fff;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
    }
    main {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 15px 0;
      position: fixed;
      width: 100%;
      bottom: 0;
    }
  </style>
</head>
<body>
  <nav>
    <a href="#">Find providers</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
  </nav>
  <header>
    <h1>Welcome to Servo</h1>
  </header>
  <main>
    <h2>Himath pcya</h2>
    <p>
      This is a generic home page. Use this space to introduce your website, share your mission, or highlight important information.
    </p>
    <p>
      Explore the navigation links above to learn more about what we offer.
    </p>
  </main>
  <footer>
    &copy; <?php echo date("Y"); ?> My Website. All rights reserved.
  </footer>
</body>
</html>