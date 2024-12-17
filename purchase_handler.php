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
    die(json_encode(['success' => false, 'message' => 'Ошибка подключения: ' . $e->getMessage()]));
}

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Пользователь не авторизован']));
}

// Получаем данные из POST-запроса
$edition = $_POST['edition'];
$user_id = $_SESSION['user_id']; // ID текущего пользователя

// Проверяем, существует ли уже покупка для этого пользователя
$stmt = $conn->prepare("SELECT * FROM purchases WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$existingPurchase = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingPurchase) {
    // Если покупка уже существует, обновляем её
    $stmt = $conn->prepare("UPDATE purchases SET edition = :edition WHERE user_id = :user_id");
    $stmt->bindParam(':edition', $edition);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
} else {
    // Если покупки нет, создаём новую
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, edition) VALUES (:user_id, :edition)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':edition', $edition);
    $stmt->execute();
}

// Обновляем сессию пользователя
$_SESSION['edition'] = $edition;

// Возвращаем успешный ответ
echo json_encode(['success' => true, 'message' => 'Покупка успешно подтверждена!']);
?>