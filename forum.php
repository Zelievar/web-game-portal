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

// Обработка удаления записи
if (isset($_GET['delete']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['delete'];
    $user_id = $_SESSION['user_id'];

    // Проверяем, является ли пользователь автором записи
    $stmt = $conn->prepare("SELECT * FROM forum_posts WHERE post_id = :post_id AND author_id = :author_id");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':author_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Удаляем связанные голоса
        $stmt = $conn->prepare("DELETE FROM forum_votes WHERE post_id = :post_id");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();

        // Удаляем запись
        $stmt = $conn->prepare("DELETE FROM forum_posts WHERE post_id = :post_id");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
    }

    // Перенаправляем обратно на страницу форума
    header("Location: forum.php");
    exit();
}

// Обработка голосования
if (isset($_GET['vote']) && isset($_GET['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['post_id'];
    $vote_type = $_GET['vote']; // 'up' или 'down'
    $user_id = $_SESSION['user_id'];
    $from = isset($_GET['from']) ? $_GET['from'] : 'forum'; // По умолчанию 'forum'

    // Проверяем, голосовал ли пользователь за эту запись
    $stmt = $conn->prepare("SELECT * FROM forum_votes WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $vote = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vote) {
        // Если пользователь уже голосовал, обновляем его голос
        if ($vote['vote_type'] === $vote_type) {
            // Если голос не изменился, ничего не делаем
            redirectToPage($from, $post_id);
        } else {
            // Обновляем голос
            $stmt = $conn->prepare("UPDATE forum_votes SET vote_type = :vote_type WHERE post_id = :post_id AND user_id = :user_id");
            $stmt->bindParam(':vote_type', $vote_type);
            $stmt->bindParam(':post_id', $post_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Обновляем рейтинг записи
            $stmt = $conn->prepare("UPDATE forum_posts SET rating = rating + :vote_change WHERE post_id = :post_id");
            $stmt->bindValue(':vote_change', $vote_type === 'up' ? 2 : -2); // Изменение рейтинга на 1
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();
        }
    } else {
        // Если пользователь не голосовал, добавляем новый голос
        $stmt = $conn->prepare("INSERT INTO forum_votes (post_id, user_id, vote_type) VALUES (:post_id, :user_id, :vote_type)");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':vote_type', $vote_type);
        $stmt->execute();

        // Обновляем рейтинг записи
        $stmt = $conn->prepare("UPDATE forum_posts SET rating = rating + :vote_change WHERE post_id = :post_id");
        $stmt->bindValue(':vote_change', $vote_type === 'up' ? 1 : -1);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
    }

    // Перенаправляем обратно на ту страницу, откуда был отправлен запрос
    redirectToPage($from, $post_id);
}

// Функция для перенаправления на нужную страницу
function redirectToPage($from, $post_id) {
    if ($from === 'post') {
        header("Location: forum_post.php?post_id=" . $post_id);
    } else {
        header("Location: forum.php");
    }
    exit();
}

// Получение всех записей форума
$stmt = $conn->query("SELECT forum_posts.*, users.username FROM forum_posts JOIN users ON forum_posts.author_id = users.user_id ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="forum-section">
    <h1>Форум</h1>

    <!-- Форма создания новой записи -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="forum_handler.php" method="POST" class="forum-form">
            <h2>Создать новую тему</h2>
            <div class="form-group">
                <label for="topic">Тема:</label>
                <input type="text" name="topic" id="topic" class="forum-input" required>
            </div>
            <div class="form-group">
                <label for="content">Текст:</label>
                <textarea name="content" id="content" rows="5" class="forum-textarea" required></textarea>
            </div>
            <button type="submit" class="btn-create">Создать</button>
        </form>
    <?php else: ?>
        <p>Пожалуйста, <a href="login.php">войдите</a> или <a href="register.php">зарегистрируйтесь</a>, чтобы создать новую тему.</p>
    <?php endif; ?>

<!-- Список записей форума -->
<div class="forum-posts">
    <?php foreach ($posts as $post): ?>
        <div class="forum-post">
            <h3><?php echo htmlspecialchars($post['topic']); ?></h3>
            <p><strong>Автор:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
            <p><strong>Рейтинг:</strong> <?php echo $post['rating']; ?></p>
            <p class="post-description"><?php echo substr(htmlspecialchars($post['content']), 0, 100); ?>...</p>
            <p><a href="forum_post.php?post_id=<?php echo $post['post_id']; ?>">Просмотреть</a></p>

            <!-- Голосование -->
            <div class="vote-buttons">
                <a href="forum.php?vote=up&post_id=<?php echo $post['post_id']; ?>&from=forum" class="btn-vote">👍</a>
                <a href="forum.php?vote=down&post_id=<?php echo $post['post_id']; ?>&from=forum" class="btn-vote">👎</a>
            </div>

            <!-- Удаление записи (только для автора) -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['author_id']): ?>
                <a href="?delete=<?php echo $post['post_id']; ?>" class="btn-delete">Удалить</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
</section>

<?php
include 'footer.php';
?>