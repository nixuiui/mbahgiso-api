<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// date_default_timezone_set('Asia/Jakarta');

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('check-username',     'AuthController@username');
$router->post('check-phone-number',     'AuthController@phoneNumber');
$router->post('check-email',        'AuthController@email');
$router->post('register',           'AuthController@register');
$router->post('forgot-password',    'AuthController@forgotPassword');
$router->post('login',              'AuthController@login');

$router->get('general/version',     'GeneralController@version');
$router->get('general/time',        'GeneralController@time');

$router->group(['middleware' => 'auth'], function ($router) {
    $router->post('logout',             'AuthController@logout');

    $router->get('/profile',            ['uses' => 'ProfileController@getProfileDetail', 'middleware' => 'auth']);
    $router->post('/profile/edit',      ['uses' => 'ProfileController@editProfile', 'middleware' => 'auth']);
});