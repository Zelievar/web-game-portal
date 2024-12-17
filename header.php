<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <header>
        <div class="logo">
            <a>Warhammer 40,000: Space Marine 2</a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="media.php">Медиа</a></li>
                <li><a href="buy.php">Купить игру</a></li>
                <li><a href="forum.php">Форум</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    // Если пользователь авторизован
                    echo '<li><a href="profile.php">Личный кабинет</a></li>';
                    echo '<li><a href="logout.php">Выход</a></li>';
                } else {
                    // Если пользователь не авторизован
                    echo '<li><a href="login.php">Вход</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>