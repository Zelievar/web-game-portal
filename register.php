<?php
include 'header.php';

// Проверка наличия ошибки
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!-- Hero Section -->
<section id="register-hero">
    <div class="hero-content">
        <h1>Присоединяйся к битве</h1>
        <p>Зарегистрируйся и стань частью легенды Warhammer 40,000.</p>
    </div>
</section>

<!-- Форма регистрации -->
<section id="register-form-section">
    <div class="register-form-container">
        <h2>Регистрация</h2>

        <!-- Уведомление об ошибке -->
        <?php if ($error === 'invalid_username'): ?>
            <p class="error-message">Имя пользователя может содержать только латинские буквы, цифры, символы _ и -.</p>
        <?php elseif ($error): ?>
            <p class="error-message">Ошибка: <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form id="register-form" method="POST" action="register_handler.php">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" placeholder="Введите имя пользователя" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Введите email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit" class="btn-register">Зарегистрироваться</button>
        </form>
        <div class="login-link">
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>