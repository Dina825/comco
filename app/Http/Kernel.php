<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		//'App\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'adminauth' => 'App\Http\Middleware\AdminAuthenticate',
        'adminredirect' => 'App\Http\Middleware\AdminRedirect',
        'userauth' => 'App\Http\Middleware\UserAuthenticate',
        'userredirect' => 'App\Http\Middleware\UserRedirect',
        'salesauth' => 'App\Http\Middleware\SalesAuthenticate',
        'salesredirect' => 'App\Http\Middleware\SalesRedirect',
        'shopauth' => 'App\Http\Middleware\ShopAuthenticate',
        'shopredirect' => 'App\Http\Middleware\ShopRedirect',
        'areamangerauth' => 'App\Http\Middleware\AreamanagerAuthenticate',
        'areamanagerredirect' => 'App\Http\Middleware\AreamanagerRedirect',
	];

}
