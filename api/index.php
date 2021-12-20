<?php
require_once(__DIR__ . "/router.php");
require_once(__DIR__ . "/controllers/artist.php");

$router = new Router(['Content-Type: application/json; charset=UTF-8']);

$justPrint = function ($params) {
    $params['method'] = $_SERVER['REQUEST_METHOD'];
    var_dump($params);
};

$router->get('/', $justPrint);
$router->post('/', $justPrint);
$router->put('/', $justPrint);
$router->delete('/', $justPrint);

$router->get('/artists', function($params) {
    $oderby = getVar($params, ['query', 'orderby'], null);
    $desc = getVar($params, ['query', 'desc'], null);
    $limit = getVar($params, ['query', 'limit'], null);
    $offset = getVar($params, ['query', 'offset'], null);

    $data = ArtistsController::getAll($oderby, $desc, $limit, $offset);
    echo(json_encode($data));
});

$router->run();
?>