<?php

namespace Models;

use Redis;

class FileStorage
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function savePdf(string $username, string $filename, string $content): void
    {
        $this->redis->set("user:{$username}:pdf:{$filename}", $content);
    }

    public function getPdf(string $username, string $filename): ?string
    {
        return $this->redis->get("user:{$username}:pdf:{$filename}") ?: null;
    }
}
