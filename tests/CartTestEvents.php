<?php

use Darryldecode\Cart\Cart;
use Mockery as m;

require_once __DIR__.'/helpers/SessionMock.php';

const CART_INSTANCE_NAME = 'shopping';

afterEach(function () {
    m::close();
});

it('fires cart created event', function () {
    $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    expect(true)->toBeTrue();
});

it('fires cart adding and added events', function () {
    $events = m::mock('Illuminate\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.adding', m::type('array'), true);
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.added', m::type('array'), true);

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    $cart->add(455, 'Sample Item', 100.99, 2, []);

    expect(true)->toBeTrue();
});

it('fires events multiple times when adding multiple items', function () {
    $events = m::mock('Illuminate\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(2)->with(CART_INSTANCE_NAME.'.adding', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(2)->with(CART_INSTANCE_NAME.'.added', m::type('array'), true);

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    $cart->add(455, 'Sample Item 1', 100.99, 2, []);
    $cart->add(562, 'Sample Item 2', 100.99, 2, []);

    expect(true)->toBeTrue();
});

it('fires events for adding multiple items in single call', function () {
    $events = m::mock('Illuminate\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.adding', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.added', m::type('array'), true);

    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 856,
            'name' => 'Sample Item 3',
            'price' => 50.25,
            'quantity' => 4,
            'attributes' => []
        ),
    );

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    $cart->add($items);

    expect(true)->toBeTrue();
});

it('fires events when removing cart item', function () {
    $events = m::mock('Illuminate\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.adding', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.added', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(1)->with(CART_INSTANCE_NAME.'.removing', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(1)->with(CART_INSTANCE_NAME.'.removed', m::type('array'), true);

    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 856,
            'name' => 'Sample Item 3',
            'price' => 50.25,
            'quantity' => 4,
            'attributes' => []
        ),
    );

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    $cart->add($items);

    $cart->remove(456);

    expect(true)->toBeTrue();
});

it('fires events when clearing cart', function () {
    $events = m::mock('Illuminate\Events\Dispatcher');
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.created', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.adding', m::type('array'), true);
    $events->shouldReceive('dispatch')->times(3)->with(CART_INSTANCE_NAME.'.added', m::type('array'), true);
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.clearing', m::type('array'), true);
    $events->shouldReceive('dispatch')->once()->with(CART_INSTANCE_NAME.'.cleared', m::type('array'), true);

    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 4,
            'attributes' => []
        ),
        array(
            'id' => 856,
            'name' => 'Sample Item 3',
            'price' => 50.25,
            'quantity' => 4,
            'attributes' => []
        ),
    );

    $cart = new Cart(
        new SessionMock(),
        $events,
        CART_INSTANCE_NAME,
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMock.php')
    );

    $cart->add($items);

    $cart->clear();

    expect(true)->toBeTrue();
});