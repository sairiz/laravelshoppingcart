<?php namespace Darryldecode\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
	/**
	 * Boot the service provider.
	 */
	public function boot(): void
	{
		if (function_exists('config_path')) {
			$this->publishes([
				__DIR__.'/config/config.php' => config_path('shopping_cart.php'),
			], 'config');
		}
	}

	/**
	 * Register the service provider.
	 */
	public function register(): void
	{
		$this->mergeConfigFrom(__DIR__.'/config/config.php', 'shopping_cart');

		$this->app->singleton('cart', function ($app) {
            $storageClass = config('shopping_cart.storage');
            $eventsClass = config('shopping_cart.events');

            $storage = $storageClass ? new $storageClass() : $app['session'];
            $events = $eventsClass ? new $eventsClass() : $app['events'];
			$instanceName = 'cart';

            // default session or cart identifier. This will be overridden when calling Cart::session($sessionKey)->add() etc..
            // like when adding a cart for a specific user name. Session Key can be string or maybe a unique identifier to bind a cart
            // to a specific user, this can also be a user ID
			$session_key = '4yTlTDKu3oJOfzD';

			return new Cart(
				$storage,
				$events,
				$instanceName,
				$session_key,
				config('shopping_cart')
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 */
	public function provides(): array
	{
		return [];
	}
}
