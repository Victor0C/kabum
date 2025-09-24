<?php
require_once __DIR__ . "/CoreMiddleware.php";
class RouterUtils
{
  protected $routes;

  public function execute()
  {

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url = rtrim($url, '/');
    if ($url === '') {
      $url = '/';
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $routerFound = false;

    foreach ($this->routes as $route) {

      if (strtoupper($method) !== strtoupper($route['method'])) {
        continue;
      }

      preg_match_all('/{(\w+)}/', $route['path'], $paramNames);
      $paramNames = $paramNames[1];

      $pattern = '#^' . preg_replace('/{(\w+)}/', '([\w-]+)', $route['path']) . '$#';

      if (preg_match($pattern, $url, $matches)) {
        array_shift($matches);
        $routerFound = true;

        (new CoreMiddleware)->fire((isset($route['middleware']) && !empty($route['middleware'])) ? $route['middleware'] : []);

        $params = [];
        foreach ($paramNames as $index => $name) {
          $params[$name] = $matches[$index] ?? null;
        }

        [$currentController, $action] = explode('@', $route['action']);
        require_once __DIR__ . "/../Controllers/$currentController.php";

        $controller = new $currentController();
        $controller->$action($params);
        break;
      }
    }

    if (!$routerFound) {
      header("HTTP/1.0 404 Not Found");
      echo "Rota não encontrada.";
    }
  }

  public function verifyProtectRoute() {}
}
