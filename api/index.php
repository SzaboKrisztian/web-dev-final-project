<?php
require_once("router.php");

$router = new Router();

$router->get("/", function () {
	session_start();
	var_dump($_SESSION);
	die();
});

$router->post('/auth/signin', function ($parameters) {
	require_once('controllers/auth/signin.php');
});

$router->post('/auth/signup', function ($parameters) {
	require_once('controllers/auth/signup.php');
});

$router->post("/auth/signout", function ($parameters) {
	require_once('controllers/auth/signout.php');
});

$router->post("/auth/admin", function ($parameters) {
	require_once('controllers/auth/admin.php');
});

$router->set_404_callback(function () {
	header('HTTP/1.0 404 NOT FOUND');
	echo json_encode(["response" => "Not found."]);
});

$router->set_global_headers(['Content-Type: application/json', 'Accept-version: v1']);

$router->run();
