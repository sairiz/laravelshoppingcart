<?php

/*
|--------------------------------------------------------------------------
| Test Configuration
|--------------------------------------------------------------------------
|
| The configuration for Pest PHP testing framework.
|
*/

uses()->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Global functions for tests
|
*/

if (!function_exists('mockSession')) {
    function mockSession(): SessionMock {
        require_once __DIR__.'/helpers/SessionMock.php';
        return new SessionMock();
    }
}

if (!function_exists('mockEvents')) {
    function mockEvents() {
        $events = Mockery::mock('Illuminate\\Contracts\\Events\\Dispatcher');
        $events->shouldReceive('dispatch');
        return $events;
    }
}

if (!function_exists('mockConfig')) {
    function mockConfig(): array {
        return require(__DIR__.'/helpers/configMock.php');
    }
}

if (!function_exists('createCart')) {
    function createCart(string $instance = 'shopping', string $sessionKey = 'SAMPLESESSIONKEY', ?array $config = null): \Darryldecode\Cart\Cart {
        return new \Darryldecode\Cart\Cart(
            mockSession(),
            mockEvents(),
            $instance,
            $sessionKey,
            $config ?: mockConfig()
        );
    }
}