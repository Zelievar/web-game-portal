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

// Получение данных о покупке текущего пользователя
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$edition = 'Не указано';

if ($user_id) {
    $stmt = $conn->prepare("SELECT edition FROM purchases WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    $edition = $purchase ? $purchase['edition'] : 'Не указано';
}
?>

<section id="buy-hero">
    <div class="hero-content">
        <h1>Приготовься к битве</h1>
        <p>Выбери свое издание и стань героем Warhammer 40,000.</p>
    </div>
</section>

<!-- Раздел "Выбор издания" -->
<section id="editions">
    <h2>Выбери свое издание</h2>
    <div class="edition-cards">
        <!-- Стандартное издание -->
        <div class="edition-card">
            <img src="https://staticg.sportskeeda.com/editor/2024/09/5fc5a-17252818650929-1920.jpg" alt="Стандартное издание">
            <h3>Стандартное издание</h3>
            <p>Основная игра с базовым контентом.</p>
            <?php if ($edition === 'Standard' || $edition === 'Collector' || $edition === 'Digital'): ?>
                <p class="price">Куплено</p>
                <button class="btn-buy" disabled>Куплено</button>
            <?php else: ?>
                <p class="price">$59.99</p>
                <button class="btn-buy" data-edition="Standard">Купить</button>
            <?php endif; ?>
        </div>
        <!-- Цифровое издание -->
        <div class="edition-card">
            <img src="https://staticg.sportskeeda.com/editor/2024/09/d1257-17252819365480-1920.jpg" alt="Цифровое издание">
            <h3>Цифровое издание</h3>
            <p>Игра с цифровыми бонусами и дополнительным контентом.</p>
            <?php if ($edition === 'Digital' || $edition === 'Collector'): ?>
                <p class="price">Куплено</p>
                <button class="btn-buy" disabled>Куплено</button>
            <?php elseif ($edition === 'Standard'): ?>
                <p class="price">$59.99</p> <!-- Скидка для пользователей, купивших стандартное -->
                <button class="btn-buy" data-edition="Digital">Купить</button>
            <?php else: ?>
                <p class="price">$69.99</p>
                <button class="btn-buy" data-edition="Digital">Купить</button>
            <?php endif; ?>
        </div>
        <!-- Коллекционное издание -->
        <div class="edition-card">
            <img src="https://staticg.sportskeeda.com/editor/2024/09/8f8ae-17252819145338-1920.jpg" alt="Коллекционное издание">
            <h3>Коллекционное издание</h3>
            <p>Игра, артбук, фигурка Космического Десантника и дополнительный контент.</p>
            <?php if ($edition === 'Collector'): ?>
                <p class="price">Куплено</p>
                <button class="btn-buy" disabled>Куплено</button>
            <?php elseif ($edition === 'Standard'): ?>
                <p class="price">$99.99</p> <!-- Скидка для пользователей, купивших стандартное -->
                <button class="btn-buy" data-edition="Collector">Купить</button>
            <?php elseif ($edition === 'Digital'): ?>
                <p class="price">$99.99</p> <!-- Скидка для пользователей, купивших цифровое -->
                <button class="btn-buy" data-edition="Collector">Купить</button>
            <?php else: ?>
                <p class="price">$129.99</p>
                <button class="btn-buy" data-edition="Collector">Купить</button>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Модальное окно подтверждения покупки -->
<div id="purchase-modal" class="modal">
    <div class="modal-content">
        <h2>Подтверждение покупки</h2>
        <p>Вы собираетесь купить версию игры: <span id="modal-edition"></span></p>
        <button id="confirm-purchase">Подтвердить покупку</button>
        <button id="cancel-purchase">Отмена</button>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
    // Получаем все кнопки покупки
    const buyButtons = document.querySelectorAll('.btn-buy');
    const modal = document.getElementById('purchase-modal');
    const modalEdition = document.getElementById('modal-edition');
    const confirmPurchaseButton = document.getElementById('confirm-purchase');
    const cancelPurchaseButton = document.getElementById('cancel-purchase');

    // Открываем модальное окно при нажатии на кнопку покупки
    buyButtons.forEach(button => {
        button.addEventListener('click', () => {
            const edition = button.getAttribute('data-edition');
            modalEdition.textContent = edition;
            modal.style.display = 'block';
        });
    });

    // Подтверждение покупки
    confirmPurchaseButton.addEventListener('click', () => {
        const edition = modalEdition.textContent;
        // Отправляем данные на сервер
        fetch('purchase_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `edition=${edition}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Покупка успешно подтверждена!');
                modal.style.display = 'none';
                // Обновляем интерфейс
                location.reload();
            } else {
                alert('Ошибка при подтверждении покупки: ' + data.message);
            }
        });
    });

    // Закрытие модального окна
    cancelPurchaseButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });
</script>