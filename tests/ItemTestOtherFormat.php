<?php

use Darryldecode\Cart\Cart;
use Mockery as m;

require_once __DIR__.'/helpers/SessionMock.php';

beforeEach(function () {
    $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
    $events->shouldReceive('dispatch');

    $this->cart = new Cart(
        new SessionMock(),
        $events,
        'shopping',
        'SAMPLESESSIONKEY',
        require(__DIR__.'/helpers/configMockOtherFormat.php')
    );
});

afterEach(function () {
    m::close();
});

it('can get item sum price using property with other format', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, array());

    $item = $this->cart->get(455);

    expect($item->getPriceSum())->toBe('201,980', 'Item summed price should be 201.98');
});

it('can get item sum price using array style with other format', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, array());

    $item = $this->cart->get(455);

    expect($item->getPriceSum())->toBe('201,980', 'Item summed price should be 201.98');
});