<?php

$router->group(['namespace' => 'v1', 'prefix' => '/v1'], function($router) {
  
  /**
   * Users
   *
   */
  $router->get('/user', 'UserController@get');

  //register login
  $router->post("/register", "AuthController@register");
  $router->post("/login", "AuthController@login");

  //user
  $router->group(['prefix' => 'user'], function () use ($router) {
      //post
      $router->post("/create", "UserController@create");
      //puth
      $router->put("/update/{id}", "UserController@update");
      //get
      $router->get("/detail/{id}", "UserController@show");
      //delete
      $router->delete("/delete/{id}", "UserController@delete");
  });

  //vehicle
  $router->group(["prefix" => "vehicle"], function () use ($router) {
      $router->get("/list", "VehicleController@index");
  });

  //tire
  $router->group(["prefix" => "tire"], function () use ($router) {
      $router->get("/list", "TireController@index");
  });
});
