<?php

use Darryldecode\Cart\Cart;
use Mockery as m;
use Darryldecode\Cart\CartCondition;
use Darryldecode\Tests\helpers\MockProduct;

require_once __DIR__ . '/helpers/SessionMock.php';

beforeEach(function () {
    $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
    $events->shouldReceive('dispatch');

    $this->cart = new Cart(
        new SessionMock(),
        $events,
        'shopping',
        'SAMPLESESSIONKEY',
        require(__DIR__ . '/helpers/configMock.php')
    );
});

afterEach(function () {
    m::close();
});

it('can get item sum price using property', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, []);

    $item = $this->cart->get(455);

    expect($item->getPriceSum())->toBe(201.98, 'Item summed price should be 201.98');
});

it('can get item sum price using array style', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, []);

    $item = $this->cart->get(455);

    expect($item->getPriceSum())->toBe(201.98, 'Item summed price should be 201.98');
});

it('returns empty conditions when item has no conditions', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, []);

    $item = $this->cart->get(455);

    expect($item->getConditions())->toBeEmpty('Item should have no conditions');
});

it('can get item conditions when item has conditions', function () {
    $itemCondition1 = new \Darryldecode\Cart\CartCondition(array(
        'name' => 'SALE 5%',
        'type' => 'sale',
        'target' => 'item',
        'value' => '-5%',
    ));

    $itemCondition2 = new CartCondition(array(
        'name' => 'Item Gift Pack 25.00',
        'type' => 'promo',
        'target' => 'item',
        'value' => '-25',
    ));

    $this->cart->add(455, 'Sample Item', 100.99, 2, [], [$itemCondition1, $itemCondition2]);

    $item = $this->cart->get(455);

    expect($item->getConditions())->toHaveCount(2, 'Item should have two conditions');
});

it('can associate model to item', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, [])->associate(MockProduct::class);

    $item = $this->cart->get(455);

    expect($item->associatedModel)->toBe(MockProduct::class, 'Item assocaited model should be ' . MockProduct::class);
});

it('throws exception when associating non-existing model', function () {
    expect(fn() => $this->cart->add(1, 'Test item', 1, 10.00)->associate('SomeModel'))
        ->toThrow(\Darryldecode\Cart\Exceptions\UnknownModelException::class, 'The supplied model SomeModel does not exist.');
});

it('can get associated model instance', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, [])->associate(MockProduct::class);

    $item = $this->cart->get(455);

    expect($item->model)->toBeInstanceOf(MockProduct::class);
    expect($item->model->name)->toBe('Sample Item');
    expect($item->model->id)->toBe(455);
});

it('returns null model when item has no associated model', function () {
    $this->cart->add(455, 'Sample Item', 100.99, 2, []);

    $item = $this->cart->get(455);

    expect($item->model)->toBeNull();
});
