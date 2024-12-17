<?php
include 'header.php';

// Проверка наличия ошибки
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!-- Hero Section -->
<section id="login-hero">
    <div class="hero-content">
        <h1>Добро пожаловать</h1>
        <p>Войдите, чтобы получить доступ к своему аккаунту.</p>
    </div>
</section>

<!-- Форма входа -->
<section id="login-form-section">
    <div class="login-form-container">
        <h2>Вход</h2>

        <!-- Уведомление об ошибке -->
        <?php if ($error === 'invalid_credentials'): ?>
            <p class="error-message">Неверное имя пользователя или пароль.</p>
        <?php elseif ($error): ?>
            <p class="error-message">Ошибка: <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form id="login-form" method="POST" action="login_handler.php">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" placeholder="Введите имя пользователя" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit" class="btn-login">Войти</button>
        </form>
        <div class="register-link">
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>