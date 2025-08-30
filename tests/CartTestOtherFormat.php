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

it('can calculate cart sub total with other format', function () {
    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 1,
            'attributes' => array()
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 1,
            'attributes' => array()
        ),
        array(
            'id' => 856,
            'name' => 'Sample Item 3',
            'price' => 50.25,
            'quantity' => 1,
            'attributes' => array()
        ),
    );

    $this->cart->add($items);

    expect($this->cart->getSubTotal())->toBe('187,490', 'Cart should have sub total of 187,490');

    // if we remove an item, the sub total should be updated as well
    $this->cart->remove(456);

    expect($this->cart->getSubTotal())->toBe('119,500', 'Cart should have sub total of 119,500');
});

it('updates sub total when item quantity is updated', function () {
    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 3,
            'attributes' => array()
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 1,
            'attributes' => array()
        ),
    );

    $this->cart->add($items);

    expect($this->cart->getSubTotal())->toBe('273,220', 'Cart should have sub total of 273.22');

    // when cart's item quantity is updated, the subtotal should be updated as well
    $this->cart->update(456, array('quantity' => 2));

    expect($this->cart->getSubTotal())->toBe('409,200', 'Cart should have sub total of 409.2');
});

it('updates sub total when item quantity is reduced', function () {
    $items = array(
        array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 67.99,
            'quantity' => 3,
            'attributes' => array()
        ),
        array(
            'id' => 568,
            'name' => 'Sample Item 2',
            'price' => 69.25,
            'quantity' => 1,
            'attributes' => array()
        ),
    );

    $this->cart->add($items);

    expect($this->cart->getSubTotal())->toBe('273,220', 'Cart should have sub total of 273.22');

    // when cart's item quantity is updated, the subtotal should be updated as well
    $this->cart->update(456, array('quantity' => -1));

    // get the item to be evaluated
    $item = $this->cart->get(456);

    expect($item['quantity'])->toBe(2, 'Item quantity of with item ID of 456 should now be reduced to 2');
    expect($this->cart->getSubTotal())->toBe('205,230', 'Cart should have sub total of 205.23');
});