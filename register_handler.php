<?php
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

// Обработка регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка имени пользователя на соответствие требованиям
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        header("Location: register.php?error=invalid_username");
        exit();
    }

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Начинаем транзакцию
    $conn->beginTransaction();

    try {
        // Проверяем, существует ли пользователь с таким именем или email
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Пользователь с таким именем или email уже существует
            throw new Exception("Пользователь с таким именем или email уже существует.");
        }

        // Вставка данных в базу
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Фиксируем транзакцию
        $conn->commit();

        // Успешная регистрация
        // Убираем автоматическую авторизацию
        // session_start();
        // $_SESSION['user_id'] = $conn->lastInsertId();
        // $_SESSION['username'] = $username;
        // $_SESSION['email'] = $email;

        // Перенаправление на страницу входа
        header("Location: login.php?from=register");
        exit();
    } catch (Exception $e) {
        // Откат транзакции в случае ошибки
        $conn->rollBack();

        // Перенаправление обратно на страницу регистрации с сообщением об ошибке
        header("Location: register.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>