<?php
require_once(__DIR__ . '/utils.php');
class Router {
    private $routes;
    private $headers;
    private $notFoundCallback;

    public function __construct($globalHeaders = [], $notFoundCallback = null) {
        $this->routes = [];
        $this->headers = $globalHeaders;
        $this->notFoundCallback = $notFoundCallback;
    }

    public function get($url, $callback, $protected = null) {
        $this->addRoute("GET", $url, $callback, $protected);
    }

    public function post($url, $callback, $protected = null) {
        $this->addRoute("POST", $url, $callback, $protected);
    }

    public function put($url, $callback, $protected = null) {
        $this->addRoute("PUT", $url, $callback, $protected);
    }

    public function delete($url, $callback, $protected = null) {
        $this->addRoute("DELETE", $url, $callback, $protected);
    }

    public function set404($callback) {
        $this->notFoundCallback = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $this->getUrl();
        $path = $url['path'];

        $this->addHeaders();
        foreach ($this->routes as $route => [$callback, $protected]) {
            [$routeMethod, $routePath] = explode('@@', $route);
            if ($this->isMatch($routeMethod, $routePath, $method, $path)) {
                if (!is_null($protected) && !$this->ensureRole($protected)) {
                    Responde::unauthorized();
                }
                if (empty($_POST)) {
                    $rawJson = file_get_contents('php://input');
                    $json = json_decode($rawJson, true);
                    $_POST = (strlen($rawJson) > 0 && json_last_error() == JSON_ERROR_NONE) ? $json : [];
                }
                $params = [
                    'query' => $_GET,
                    'body' => $_POST,
                    'path' => $this->parsePath($routePath, $path),
                ];
                $callback($params);
                return;
            }
        }

        if ($this->notFoundCallback != null) {
            ($this->notFoundCallback)();
        } else {
            Responde::notFound();
        }
    }

    private function getUrl() {
        $base = "/" . basename(__DIR__);
        $url = substr($_SERVER['REQUEST_URI'], strlen($base));
        return parse_url(strlen($url) > 1 ? rtrim($url, '/') : $url);
    }

    private function addRoute($method, $url, $callback, $protected = null) {
        foreach (explode('/', $url) as $segment) {
            if (str_starts_with($segment, '$') && !preg_match('/^\$[a-z][a-z0-9_]*$/i', $segment)) {
                throw new Exception("Invalid path parameter name \"" . substr($segment, 1) . "\"; must be a legal PHP identifier.");
            }
        }
        $this->routes["$method@@$url"] = [$callback, $protected];
    }

    private function ensureRole($role) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' ? true : $_SESSION['role'] == $role);
    }

    private function isMatch($routeMethod, $routePath, $reqMethod, $reqPath) {
        if ($routeMethod === $reqMethod) {
            $routeSegments = array_slice(explode('/', $routePath), 1);
            $reqSegments = array_slice(explode('/', $reqPath), 1);

            if (count($routeSegments) == count($reqSegments)) {
                for ($i = 0; $i < count($routeSegments); $i += 1) {
                    if (str_starts_with($routeSegments[$i], '$')) {
                        continue;
                    } else if ($routeSegments[$i] != $reqSegments[$i]) {
                        return false;
                    }

                    return true;
                }
            }
        }

        return false;
    }

    private function parsePath($route, $request) {
        $routeSegments = array_slice(explode('/', $route), 1);
        $reqSegments = array_slice(explode('/', $request), 1);

        if (count($routeSegments) != count($reqSegments)) {
            throw new Exception("Path parse error: unequal number of path segments");
        }

        $params = [];
        for ($i = 0; $i < count($routeSegments); $i += 1) {
            if (str_starts_with($routeSegments[$i], '$')) {
                $name = substr($routeSegments[$i], 1);
                $value = $reqSegments[$i];
                $params[$name] = $value;
            }
        }

        return $params;
    }

    private function addHeaders() {
        foreach ($this->headers as $header) {
            header($header);
        }
    }
}
?>