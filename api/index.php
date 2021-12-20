<?php
require_once(__DIR__ . "/router.php");
require_once(__DIR__ . "/controllers/artist.php");

$router = new Router(['Content-Type: application/json; charset=UTF-8']);

/////////////////////////
// GET routes
/////////////////////////

// Public routes

$router->get('/artists', function($params) {
    $oderby = getVar($params, ['query', 'orderby'], null);
    $desc = getVar($params, ['query', 'desc'], null);
    $limit = getVar($params, ['query', 'limit'], null);
    $offset = getVar($params, ['query', 'offset'], null);
    $query = getVar($params, ['query', 'query'], null);
    
    $data = ArtistsController::getAll($oderby, $desc, $limit, $offset, $query);
    echo(json_encode($data));
});

$router->get('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = ArtistsController::getOne($id);
    echo(json_encode($data));
});

/////////////////////////
// POST routes
/////////////////////////

// Admin routes

$router->post('/artists', function($params) {
    $body = getVar($params, ['body'], null);

    $data = ArtistsController::create($body);
    echo(json_encode($data));
});

/////////////////////////
// PUT routes
/////////////////////////

// Admin routes

$router->put('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    $body = getVar($params, ['body'], null);

    $data = ArtistsController::update($id, $body);
    echo("{\"rowsAffected\":$data}");
});

/////////////////////////
// DELETE routes
/////////////////////////

// Admin routes

$router->delete('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = ArtistsController::delete($id);
    echo("{\"rowsAffected\":$data}");
});

$router->run();
?>