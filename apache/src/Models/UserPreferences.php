<?php

namespace Models;

use Redis;

class UserPreferences
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function setTheme(string $username, string $theme): void
    {
        if ($username !== 'guest') {
            $this->redis->set("user:{$username}:theme", $theme);
        }
    }

    public function getTheme(string $username): string
    {
        return $this->redis->get("user:{$username}:theme") ?? 'styles/light.css';
    }

    public function setLanguage(string $username, string $language): void
    {
        if ($username !== 'guest') {
            $this->redis->set("user:{$username}:language", $language);
        }
    }

    public function getLanguage(string $username): string
    {
        return $this->redis->get("user:{$username}:language") ?? 'en';
    }
}