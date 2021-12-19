<?php
/**
 * Router Capability for PHP 
 * Inspired by "Custom PHP Router w/o libraries" YouTube Video
 * @author Onescu Radu-Mihai
 */
class Router {
	private $routes = [];
	private $headers = [];
	private $callback_404;

	public function get($path, $callback) {
		$this->add_route("GET", $path, $callback);
	}

	public function post($path, $callback) {
		$this->add_route("POST", $path, $callback);
	}

	public function put($path, $callback) {
		$this->add_route("PUT", $path, $callback);
	}

	public function delete($path, $callback) {
		$this->add_route("DELETE", $path, $callback);
	}

	public function set_global_headers($headers) {
		$this->headers = $headers;
	}

	public function set_404_callback($callback) {
		$this->callback_404 = $callback;
	}

	public function run() {
		$request_path = $this->get_path();
		$request_method = $_SERVER['REQUEST_METHOD'];

		$callback = null;
		$parameters = [];
		foreach ($this->routes as $route) {
			//Check if methods match and if requested path is inside routes
			if ($request_method == $route['method'] && $request_path == $route['path']) {
				$callback = $route['callback'];
				break;
			} else {
				$params = $this->search_path_including_parameters($request_path, $route['path']);
				if($params) {
					$parameters = $params;
					$callback = $route['callback'];
					break;
				}
			}
		}

		$callback = $callback ? $callback : $this->callback_404;

		if (!$callback) {
			header('HTTP/1.0 404 NOT FOUND');
			return;
		}



		$this->add_headers();
		call_user_func_array($callback, [
			array_merge($_GET, $_POST, $parameters)
		]);
	}

	/**
	 * Helper Functions
	 */
	private function add_route($method, $path, $callback) {
		if (strlen($path) > 1) {
			$path = rtrim($path, "/");
		}

		$this->routes[$method . $path] = [
			'path' => $path,
			'method' => $method,
			'callback' => $callback
		];
	}

	private function get_path() {
		//Get Path Parameteres from URL
		$url = strtok($_SERVER['REQUEST_URI'], "?");
		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		//Remove the name of the folder where the file is located, and everything before it
		$folder_name = '/' . basename(__DIR__);
		$url = substr($url, strpos($url, $folder_name) + strlen($folder_name));

		//If url path has more than one character and it ends in "/", remove the slash
		//This is used to make sure we do not remove the "/" when that is the only path
		if (strlen($url) > 1 && str_ends_with($url, '/')) {
			$url = substr($url, 0, -1);
		}

		return $url;
	}

	private function add_headers() {
		foreach ($this->headers as $header) {
			header($header);
		}
	}

	private function search_path_including_parameters($request_path, $route_path) {
		$request_parts = explode("/", ltrim($request_path, '/'));
		$route_parts = explode("/", ltrim($route_path, '/'));
		$params = [];

		//Check if route has the same number of parts as the requested path
		if (count($request_parts) != count($route_parts)) {
			return;
		}
		foreach ($route_parts as $key => $route_part) {
			//Check if route part starts with $ (meaning that it is a parameter)
			if (str_starts_with($route_part, '$')) {
				$params[substr($route_part, 1)] = $request_parts[$key];
			}
			//If route part is not a parameter and it is not equal to the requested part from same position exit
			else if ($route_part != $request_parts[$key]) {
				return;
			}
		}
		return $params;
	}
}