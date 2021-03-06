<?php



$router->group(['namespace' => 'v1', 'prefix' => '/v1'], function ($router) {

    /**
     * Users
     *
     */
    // $router->get('/user', 'UserController@get');
    // $router->get("/download", "UserController@export");
    //register login
    $router->post("/register", "AuthController@register");
    $router->post("/login", "AuthController@login");

    //user
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', 'UserController@get');
        //post
        $router->post("/create", "UserController@create");
        //puth
        $router->put("/update/{id}", "UserController@update");
        //get
        $router->get("/detail/{id}", "UserController@show");
        //delete
        $router->delete("/delete/{id}", "UserController@delete");
        //filter
        $router->post("/filter", 'UserController@filter');

        $router->post("/check", "UserController@check");
    });

    //vehicle
    $router->group(["prefix" => "vehicle"], function () use ($router) {
        $router->get("/list", "VehicleController@index");
        $router->get("/{id}", "VehicleController@show");
        $router->put("/{id}", "VehicleController@update");
        $router->post("/add", "VehicleController@add");
        $router->post("/filter", "VehicleController@filter");
        $router->get("/download/{code}", "VehicleController@download");
    });

    //tire
    $router->group(["prefix" => "tire"], function () use ($router) {
        $router->get("/list", "TireController@index");
        $router->get("/trash", "TireController@trash");
        $router->get("/{id}", "TireController@show");
        $router->get("/assign/{id}", "TireController@getAssignTire");
        $router->delete('/{id}', "TireController@delete");
        $router->post("/filter", 'TireController@filter');
        $router->post("/assign", "TireController@assignTire");
        $router->post("/update", "TireController@update");
        $router->post("/store", "TireController@store");
    });

    //history
    $router->group(['prefix' => 'history'], function () use ($router) {
        $router->get('/list', 'HistoryController@index');
        $router->get("/tire/{id}", "HistoryController@tireHistory");
        $router->get("/position/{vehicle}/{id}", "HistoryController@positionHistory");
    });

    //service
    $router->group(['prefix' => 'service'], function () use ($router) {
        $router->post('/save', "ServiceController@store");
        $router->get("/", "ServiceController@index");
        $router->post("/filter", "ServiceController@filter");
        $router->post('/filterdate', "ServiceController@filterDate");
        $router->post("/export", "ServiceController@export");
    });
});
