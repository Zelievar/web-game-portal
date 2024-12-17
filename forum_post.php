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

// Получение записи по ID
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $stmt = $conn->prepare("SELECT forum_posts.*, users.username FROM forum_posts JOIN users ON forum_posts.author_id = users.user_id WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        die("Запись не найдена.");
    }
} else {
    die("Не указан ID записи.");
}
?>

<section id="forum-post-section">
    <h1><?php echo htmlspecialchars($post['topic']); ?></h1>
    <p><strong>Автор:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
    <p><strong>Рейтинг:</strong> <?php echo $post['rating']; ?></p>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

<!-- Кнопки голосования -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="vote-buttons">
        <a href="forum.php?vote=up&post_id=<?php echo $post['post_id']; ?>&from=post" class="btn-vote">👍</a>
        <a href="forum.php?vote=down&post_id=<?php echo $post['post_id']; ?>&from=post" class="btn-vote">👎</a>
    </div>
<?php endif; ?>

    <a href="forum.php" class="btn-back">Назад</a>
</section>

<?php
include 'footer.php';
?>