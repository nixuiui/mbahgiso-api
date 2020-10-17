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

$router->get('general/version',             'GeneralController@version');
$router->get('general/time',                'GeneralController@time');
$router->get('general/recomendation-price', 'GeneralController@recomendationPrice');

$router->group(['middleware' => 'auth'], function ($router) {
    $router->post('logout',             'AuthController@logout');

    $router->get('/profile',            ['uses' => 'ProfileController@getProfileDetail', 'middleware' => 'auth']);
    $router->post('/profile/edit',      ['uses' => 'ProfileController@editProfile', 'middleware' => 'auth']);
    $router->post('/profile/topup',     ['uses' => 'ProfileController@topup', 'middleware' => 'auth']);
    
    $router->get('/news',                   ['uses' => 'DataController@news', 'middleware' => 'auth']);
    $router->get('/market/index',           ['uses' => 'DataController@marketIndex', 'middleware' => 'auth']);
    $router->get('/market/komoditas',       ['uses' => 'DataController@marketKomoditas', 'middleware' => 'auth']);
    $router->get('/dividens',               ['uses' => 'DataController@dividens', 'middleware' => 'auth']);
    $router->get('/recomendation/{type}',   ['uses' => 'DataController@getRecomendation', 'middleware' => 'auth']);
    $router->post('/recomendation/buy',     ['uses' => 'DataController@buyRecomendation', 'middleware' => 'auth']);
    $router->get('/recomendationdata/today',    ['uses' => 'DataController@todayRecomendationData', 'middleware' => 'auth']);
    $router->post('/recomendationdata/buy',     ['uses' => 'DataController@buyRecomendationData', 'middleware' => 'auth']);
    $router->post('/recomendationdata/check',   ['uses' => 'DataController@checkRecomendationData', 'middleware' => 'auth']);
    $router->post('/dividen/buy',               ['uses' => 'DataController@buyDividen', 'middleware' => 'auth']);
    $router->post('/dividen/check',             ['uses' => 'DataController@checkDividen', 'middleware' => 'auth']);
    $router->get('/consultation/buy',           ['uses' => 'ProfileController@buyConsultation', 'middleware' => 'auth']);
    $router->get('/livetrading/buy',            ['uses' => 'ProfileController@buyLiveTrading', 'middleware' => 'auth']);
    
});