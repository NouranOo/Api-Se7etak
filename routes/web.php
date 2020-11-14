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
Route::get('/updateapp', function () {
    exec('composer dump-autoload');
    echo 'dump-autoload complete';
});

$router->get('/', function () use ($router) {
    return 'Hello in Se7etak Apies';
});

/**
 * UserAuth
 */

$router->group(['prefix' => 'Api/User', 'middleware' => ['cors2', 'cors']], function () use ($router) {
    $router->post('/Register', 'UserController@Register');
    $router->post('/Login', 'UserController@Login');
    $router->post('/LogOut', 'UserController@LogOut');
    $router->post('/LoginFacebook' , 'UserController@LoginFacebook');
 



});
$router->group(['prefix' => 'Api/User', 'middleware' => ['cors2', 'cors', 'UserAuth']], function () use ($router) {
    $router->post('/getAllAreas', 'UserController@getAllAreas');
    $router->post('/getAllCities', 'UserController@getAllCities');
    $router->post('/getAllItems', 'UserController@getAllItems');
    $router->post('/searchItems', 'UserController@searchItems');
    $router->post('/AddOrder', 'UserController@AddOrder');
    $router->post('/AddOrderByPrescriptionPhoto', 'UserController@AddOrderByPrescriptionPhoto');

   
});
