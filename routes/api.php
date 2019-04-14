<?php

$router->group(['namespace' => 'v1', 'prefix' => '/v1'], function ($router) {

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
        $router->get("/{id}", "VehicleController@show");
        $router->put("/{id}", "VehicleController@update");
        $router->post("/add", "VehicleController@add");
    });

    //tire
    $router->group(["prefix" => "tire"], function () use ($router) {
        $router->get("/list", "TireController@index");
        $router->delete('/{id}', "TireController@delete");
    });

    //history
    $router->group(['prefix' => 'history'], function () use ($router) {
        $router->get('/list', 'HistoryController@index');
        $router->get("/tire/{id}", "HistoryController@tireHistory");
        $router->get("/position/{vehicle}/{id}", "HistoryController@positionHistory");
    });
});
