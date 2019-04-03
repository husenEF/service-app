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

$router->get('/', function () use ($router) {
    return view('index');
});


/*
 |------------------------------------------------------------------------
 | Additional api routes
 |------------------------------------------------------------------------
 |
 | Go to routes/api.php to add additional routes for api
 |
 */
$router->group([
    'namespace' => 'api',
//    'middleware' => 'auth:api',
    'prefix' => '/api',
], function () use ($router) {
    require __DIR__.'/../routes/api.php';
});
