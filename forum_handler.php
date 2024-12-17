<?php
session_start();

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

// Обработка создания новой записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $topic = $_POST['topic'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    // Вставка новой записи
    $stmt = $conn->prepare("INSERT INTO forum_posts (topic, content, author_id) VALUES (:topic, :content, :author_id)");
    $stmt->bindParam(':topic', $topic);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->execute();

    // Перенаправление на страницу форума
    header("Location: forum.php");
    exit();
} else {
    die("Ошибка: недостаточно прав для создания записи.");
}
?>