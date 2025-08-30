<?php

class SessionMock
{
    protected array $session = [];

    public function has(string $key): bool
    {
        return isset($this->session[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->session[$key] ?? null;
    }

    public function put(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }
}