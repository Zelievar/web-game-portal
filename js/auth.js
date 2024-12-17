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