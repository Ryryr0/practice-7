<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Shop</title>
    <link id="theme-stylesheet" rel="stylesheet" href="<?php echo htmlspecialchars($theme); ?>">
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
        function toggleTheme() {
            const currentTheme = document.getElementById('theme-stylesheet').getAttribute('href');

            let newTheme = currentTheme === '../styles/light.css' ? '../styles/dark.css' : '../styles/light.css';

            document.getElementById('theme-stylesheet').setAttribute('href', newTheme);

            fetch('?path=prac4', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ theme: newTheme })
            }).then(response => response.json())
              .then(data => console.log(data.theme_message));
        }

        function changeLanguage(event) {
            const selectedLanguage = event.target.value;

            fetch('?path=prac4', {
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

        document.getElementById('theme-button').addEventListener('click', toggleTheme);
        document.getElementById('language-select').addEventListener('change', changeLanguage);
    </script>
</body>
</html>