<?php
session_start();

// Подключение к Redis
$redis = new Redis();
$redis->connect('redis_db', 6379); // 'redis' — имя контейнера Redis

$username = $_SESSION['username'] ?? 'guest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['theme'])) {
        $theme = $data['theme'];
        if ($username !== 'guest') {
            $redis->set("user:{$username}:theme", $theme);
        }
        $response['theme_message'] = 'Theme saved successfully';
    }

    if (!empty($data['language'])) {
        $language = $data['language'];
        if ($username !== 'guest') {
            $redis->set("user:{$username}:language", $language);
        }
        $response['language_message'] = 'Language saved successfully';
    }

    echo json_encode($response);
    exit;
}

$theme = $redis->get("user:{$username}:theme") ?? 'styles/light.css';
$language = $redis->get("user:{$username}:language") ?? 'en';
if (empty($theme)) $theme = 'styles/light.css';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Shop</title>
    <link id="theme-stylesheet" rel="stylesheet" href="<?php echo $theme; ?>">

</head>
<body>
<button id="theme-button">Switch Theme</button>
<label for="language-select">Select Language:</label>
<select id="language-select">
    <option value="en" <?php echo $language === 'en' ? 'selected' : ''; ?>>English</option>
    <option value="ru" <?php echo $language === 'ru' ? 'selected' : ''; ?>>Russian</option>
    <option value="fr" <?php echo $language === 'fr' ? 'selected' : ''; ?>>French</option>
</select>

<p id="welcome-message">
    <?php
    if ($language === 'ru') {
        echo "Добро пожаловать!";
    } elseif ($language === 'fr') {
        echo "Bienvenue!";
    } else {
        echo "Welcome!";
    }
    ?>
</p>

<?php echo " " . htmlspecialchars($username); ?>

<script>
    // Функция смены темы
    function toggleTheme() {
        const currentTheme = document.getElementById('theme-stylesheet').getAttribute('href');

        // Определяем следующую тему
        let newTheme;
        if (currentTheme === 'styles/light.css') {
            newTheme = 'styles/dark.css';
        } else {
            newTheme = 'styles/light.css';
        }

        // Устанавливаем новую тему
        document.getElementById('theme-stylesheet').setAttribute('href', newTheme);

        // Сохраняем выбор пользователя в cookie, отправляя запрос на сервер
        fetch('prac4.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ theme: newTheme })
        }).then(response => response.json())
        .then(data => console.log(data.message));
    }

    function changeLanguage(event) {
        const selectedLanguage = event.target.value;

        fetch('prac4.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ language: selectedLanguage })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.language_message);
            updateWelcomeMessage(selectedLanguage);
        })
        .catch(error => console.error('Error:', error));
    }

    // Обновляем приветственное сообщение на основе языка
    function updateWelcomeMessage(language) {
        const welcomeMessage = document.getElementById('welcome-message');

        if (language === 'ru') {
            welcomeMessage.textContent = "Добро пожаловать!";
        } else if (language === 'fr') {
            welcomeMessage.textContent = "Bienvenue!";
        } else {
            welcomeMessage.textContent = "Welcome!";
        }
    }

    // Назначаем обработчики событий
    document.getElementById('theme-button').addEventListener('click', toggleTheme);
    document.getElementById('language-select').addEventListener('change', changeLanguage);
</script>

</body>
</html>
