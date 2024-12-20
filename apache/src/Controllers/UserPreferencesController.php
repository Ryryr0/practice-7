<?php
// src/Controllers/UserPreferencesController.php

namespace Controllers;

use Models\UserPreferences;

class UserPreferencesController
{
    private UserPreferences $userPreferences;

    public function __construct(UserPreferences $userPreferences)
    {
        $this->userPreferences = $userPreferences;
    }

    public function handleRequest(): void
    {
        session_start();
        $username = $_SESSION['username'] ?? 'guest';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = [];

            if (!empty($data['theme'])) {
                $this->userPreferences->setTheme($username, $data['theme']);
                $response['theme_message'] = 'Theme saved successfully';
            }

            if (!empty($data['language'])) {
                $this->userPreferences->setLanguage($username, $data['language']);
                $response['language_message'] = 'Language saved successfully';
            }

            echo json_encode($response);
            exit;
        }

        $theme = $this->userPreferences->getTheme($username);
        $language = $this->userPreferences->getLanguage($username);

        require_once __DIR__ . '/../Views/prac4_view.php';
    }
}
