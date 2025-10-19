<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MVC Demo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Home - User List</h1>

    <ul>
        <?php foreach ($users as $user): ?>
            <li><?= htmlspecialchars($user['First_Name']) ?></li>
        <?php endforeach; ?>
    </ul>

    <script src="js/script.js"></script>
</body>
</html>
