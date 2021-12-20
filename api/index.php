<?php
require_once(__DIR__ . "/router.php");
require_once(__DIR__ . "/controllers/artist.php");

$router = new Router(['Content-Type: application/json; charset=UTF-8']);

$router->get('/artists', function ($params) {
    // echo AristsController::getAll(
    //     orderby: $params['query']['orderby'],
    //     desc: $params['query']['desc'],
    //     limit: $params['query']['limit'],
    //     offset: $params['query']['offset'],
    // );
    echo "test";
});

$router->run();
?>