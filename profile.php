<?php
include 'header.php';

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

// Получение данных о покупке из базы данных
$user_id = $_SESSION['user_id']; // ID текущего пользователя
$stmt = $conn->prepare("SELECT edition FROM purchases WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$purchase = $stmt->fetch(PDO::FETCH_ASSOC);

// Установка значения edition
$edition = $purchase ? $purchase['edition'] : 'Не указано';
?>

<section id="profile-section">
    <h1>Личный кабинет</h1>
    <div id="profile-info">
        <p><strong>Логин:</strong> <?php echo $_SESSION['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Купленная версия игры:</strong> <?php echo $edition; ?></p>
    </div>
</section>

<?php
include 'footer.php';
?>