<?php
session_start(); // Запуск сессии

// Подключение к базе данных
$host = 'localhost';
$db = 'game';
$user = 'root';
$password = '1111';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Обработка входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Поиск пользователя в базе
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Успешный вход
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['edition'] = 'Не указано'; // Инициализация по умолчанию

        // Получение данных о покупке
        $stmt = $conn->prepare("SELECT edition FROM purchases WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user['user_id']);
        $stmt->execute();
        $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['edition'] = $purchase ? $purchase['edition'] : 'Не указано';

        // Перенаправление на главную страницу
        header("Location: index.php?from=login");
        exit();
    } else {
        // Неверные данные
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
}
?>