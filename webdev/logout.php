<?php
    session_start();

    $name = isset($_SESSION['name']) ? $_SESSION['name'] : null;
    
    $_SESSION = [];

    session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv='refresh' content='3; url=login.html' />
    <title>Выход из системы</title>
</head>
<body>
    <?php if ($name): ?>
        <h2>До свидания, <?= htmlspecialchars($name) ?></h2>
    <?php endif; ?> 
    
</body>
</html>