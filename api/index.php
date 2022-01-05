<?php
require_once(__DIR__ . "/router.php");
require_once(__DIR__ . "/utils.php");
require_once(__DIR__ . "/controllers/artist.php");
require_once(__DIR__ . "/controllers/album.php");
require_once(__DIR__ . "/controllers/track.php");
require_once(__DIR__ . "/controllers/auth.php");
require_once(__DIR__ . "/controllers/cart.php");
require_once(__DIR__ . "/controllers/genre.php");
require_once(__DIR__ . "/controllers/mediatype.php");

$router = new Router([
    'Content-Type: application/json; charset=UTF-8',
    'Accept-version: v1'
]);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/////////////////////////
// GET routes
/////////////////////////

// Public routes

$router->get('/logout', function($params) {
    AuthController::logout();
});

// User routes

$router->get('/artists', function($params) {
    $data = ArtistController::getAll(...extractQueryParams($params));
    echo(json_encode($data));
}, 'user');

$router->get('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = ArtistController::getOne($id);
    echo(json_encode($data));
}, 'user');

$router->get('/artists/$id/albums', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = AlbumController::getAllByArtistId($id);
    echo(json_encode($data));
}, 'user');

$router->get('/albums', function($params) {
    $data = AlbumController::getAll(...extractQueryParams($params));
    echo(json_encode($data));
}, 'user');

$router->get('/albums/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = AlbumController::getOne($id);
    echo(json_encode($data));
}, 'user');

$router->get('/albums/$id/tracks', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = TrackController::getAllByAlbumId($id);
    echo(json_encode($data));
}, 'user');

$router->get('/tracks', function($params) {
    $data = TrackController::getAll(...extractQueryParams($params));
    echo(json_encode($data));
}, 'user');

$router->get('/tracks/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = TrackController::getOne($id);
    echo(json_encode($data));
}, 'user');

$router->get('/genres', function($params) {
    $data = GenreController::getAll();
    echo(json_encode($data));
}, 'user');

$router->get('/genres/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = GenreController::getOne($id);
    echo(json_encode($data));
}, 'user');

$router->get('/mediatypes', function($params) {
    $data = MediaTypeController::getAll();
    echo(json_encode($data));
}, 'user');

$router->get('/mediatypes/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = MediaTypeController::getOne($id);
    echo(json_encode($data));
}, 'user');

$router->get('/customer', function($params) {
    $id = isset($_SESSION['userData']['CustomerId']) ? $_SESSION['userData']['CustomerId'] : null;

    if (is_null($id)) {
        Responde::unauthorized();
    }

    $customers = CustomerDAO::getInstance();

    $data = $customers->findByPk($id, exclude: ['Password']);
    echo(json_encode($data));
}, 'user');

$router->get('/cart', function($params) {
    $data = CartController::getCart();
    echo(json_encode($data));
}, 'user');

/////////////////////////
// POST routes
/////////////////////////

// Public routes

$router->post('/login', function($params) {
    $email = getVar($params, ['body', 'email'], null);
    $password = getVar($params, ['body', 'password'], null);

    if (!$email || !$password) {
        Responde::badRequest('Email or password not provided.');
    }

    AuthController::login($email, $password);

    echo "{\"message\":\"Successfully logged in.\"}";
});

$router->post('/signup', function($params) {
    $userData = getVar($params, ['body'], null);

    if (!$userData) {
        Responde::badRequest('Missing information.');
    }

    AuthController::signup($userData);

    echo "{\"message\":\"Account successfully created.\"}";
});

// User routes

$router->post('/cart', function($params) {
    $trackId = getVar($params, ['body', 'trackId'], null);

    if (is_null($trackId)) {
        Responde::badRequest("No trackId provided");
    }

    $numItems = CartController::addToCart($trackId);
    echo(json_encode([ 'noTracksInCart' => $numItems ]));
}, 'user');

$router->post('/checkout', function($params) {
    $billing = getVar($params, ['body', 'billing'], null);
    $res = CartController::checkout($billing);
    echo(json_encode([ 'response' => $res ]));
}, 'user');

// Admin routes

$router->post('/artists', function($params) {
    $body = getVar($params, ['body'], null);

    $data = ArtistController::create($body);
    echo(json_encode($data));
}, 'admin');

$router->post('/albums', function($params) {
    $body = getVar($params, ['body'], null);

    $data = AlbumController::create($body);
    echo(json_encode($data));
}, 'admin');

$router->post('/tracks', function($params) {
    $body = getVar($params, ['body'], null);

    $data = TrackController::create($body);
    echo(json_encode($data));
}, 'admin');

/////////////////////////
// PUT routes
/////////////////////////

// User routes

$router->put('/customer', function($params) {
    $id = isset($_SESSION['userData']['CustomerId']) ? $_SESSION['userData']['CustomerId'] : null;

    if (is_null($id)) {
        Responde::unauthorized();
    }

    $customers = CustomerDAO::getInstance();

    $data = getVar($params, ['body'], null);

    if (empty($data)) {
        Responde::badRequest("No data provided");
    }
    
    if (isset($data['Password']) && strlen($data['Password']) > 0) {
        $data['Password'] = password_hash($data['Password'],  PASSWORD_DEFAULT);
    }

    $result = $customers->update($id, $data);
    echo("{\"rowsAffected\":$result}");
}, 'user');

// Admin routes

$router->put('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    $body = getVar($params, ['body'], null);

    $data = ArtistController::update($id, $body);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

$router->put('/albums/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    $body = getVar($params, ['body'], null);

    $data = AlbumController::update($id, $body);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

$router->put('/tracks/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    $body = getVar($params, ['body'], null);

    $data = TrackController::update($id, $body);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

/////////////////////////
// DELETE routes
/////////////////////////

// User routes

$router->delete('/cart', function($params) {
    CartController::clearCart();
    echo("{\"success\":true}");
}, 'user');

$router->delete('/cart/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $numItems = CartController::removeFromCart($id);
    echo(json_encode([ 'noTracksInCart' => $numItems ]));
}, 'user');

// Admin routes

$router->delete('/artists/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = ArtistController::delete($id);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

$router->delete('/albums/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = AlbumController::delete($id);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

$router->delete('/tracks/$id', function($params) {
    $id = getVar($params, ['path', 'id'], null);
    
    $data = TrackController::delete($id);
    echo("{\"rowsAffected\":$data}");
}, 'admin');

$router->run();
?>