<?php
include 'header.php';
?>

  <!-- Первый экран (Hero Section) -->
  <section id="hero">
    <img src="https://i.pinimg.com/originals/3e/de/fa/3edefa3a792f7025ad0a7b56519330e8.gif" alt="Hero GIF" id="hero-gif">
    <div class="hero-content">
      <h1>Возвращение героя</h1>
      <p>Стань частью легенды. Уничтожай врагов. Окунись в мир Warhammer 40,000.</p>
      <div class="buttons">
        <a href="buy.php" class="btn-buy">Купить игру</a>
        <a href="https://rutube.ru/video/3324660dafea3a2b129b2aa8fba6e580/?t=6&r=plemwd" class="btn-trailer">Трейлер</a>
      </div>
    </div>
  </section>

  <!-- Раздел "О игре" -->
  <section id="about">
    <h2>О чем игра?</h2>
    <p>Warhammer 40,000: Space Marine 2 — это продолжение культовой игры, в которой вы снова возьмете на себя роль Космического Десантника. Сражайтесь с ордами Хаоса, орками и другими угрозами в эпическом одиночном или кооперативном режиме. Используйте мощное оружие, бронированные доспехи и сверхчеловеческую силу, чтобы защитить человечество от полного уничтожения.</p>
    <div class="gallery">
      <img src="https://vkplay.ru/pre_0x736_resize/hotbox/content_files/Stories/2024/05/17/156e2c0a9d214175a3df62ca443ae067.jpg?quality=85" alt="Скриншот 1">
      <img src="https://cdn-prod.scalefast.com/public/assets/user/13772687/sample/ba43e8cc15c5369ce299f8a2720e55e6.jpg" alt="1">
      <img src="https://avatars.mds.yandex.net/i?id=98f168409f1bae214155cfb54f0ecf6d_l-9148257-images-thumbs&n=13" alt="Скриншот 3">
    </div>
  </section>

  <!-- Раздел "Особенности игры" -->
  <section id="features">
    <h2>Что делает игру уникальной?</h2>
    <ul>
      <li>Эпические сражения: Бои в масштабе 1 на 100.</li>
      <li>Совершенно новый движок: Улучшенная графика и анимации.</li>
      <li>Кооперативный режим: Играйте с друзьями.</li>
      <li>Модернизируемый оружие и броня: Подберите снаряжение под свой стиль игры.</li>
      <li>Глубокий сюжет: Погрузитесь в мир Warhammer 40k.</li>
    </ul>
    <div class="feature-image-container">
    <img src="https://i0.wp.com/news.xbox.com/ru-ru/wp-content/uploads/sites/9/2021/12/SpaceMarine2_RevealTrailer_screenshot_logo_1080p_02.jpg?resize=1920%2C1080&ssl=1" alt="Космический десантник" class="feature-image">
    </div>
</section>

  <!-- Раздел "Media" -->
  <section id="media">
    <h2>Погрузись в мир игры</h2>
    <div class="media-gallery">
      <img src="https://cdn-prod.scalefast.com/public/assets/user/13772687/sample/d9a057b6c6439a8118b0499f382c8c4b.jpg" alt="Media 1">
      <img src="https://digiseller.mycdn.ink/preview/319113/p1_4389593_d8fbe9db.jpg" alt="Media 2">
      <img src="https://img.ixbt.site/live/topics/preview/00/07/81/21/5541a6f189.jpg" alt="Media 3">
    </div> <br> <br>
    <a href="media.php" class="btn-media">Перейти в Медиа</a>
  </section>

  <!-- Раздел "Форум" -->
  <section id="forum">
    <h2>Общайтесь с сообществом</h2>
    <p>Присоединяйтесь к форуму, чтобы обсудить игру, поделиться впечатлениями и узнать новости.</p> <br>
    <a href="forum.php" class="btn-forum">Перейти на форум</a>
  </section>

  <!-- Подвал -->
  <?php
  include 'footer.php';
  ?>

  <script>
    // Функция для проверки авторизации
    function checkAuth() {
        const user = localStorage.getItem('user');
        if (user) {
            const userData = JSON.parse(user);
            document.getElementById('login-link').style.display = 'none';
            document.getElementById('profile-link').textContent = userData.username;
        } else {
            document.getElementById('profile-link').style.display = 'none';
        }
    }

    // Проверка авторизации при загрузке страницы
    window.onload = function () {
        checkAuth();
    };
  </script>
</body>
</html>