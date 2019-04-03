<?php

$router->group(['namespace' => 'v1', 'prefix' => '/v1'], function($router) {
  
  /**
   * Users
   *
   */
  $router->get('/user', 'UserController@get');

});
