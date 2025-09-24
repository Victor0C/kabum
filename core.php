<?php

class Core
{
  private $routes;

  public function __construct($routes)
  {
    $this->setRoutes($routes);
  }

  public function run()
  {
    if (!isset($_SESSION)) {
      session_start();
    }

    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url = rtrim($url, '/');
    if ($url === '') {
      $url = '/';
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $routerFound = false;

    foreach ($this->getRoutes() as $route) {
      [$routeMethod, $path, $controllerAndAction] = $route;

      if (strtoupper($method) !== strtoupper($routeMethod)) {
        continue;
      }

      preg_match_all('/{(\w+)}/', $path, $paramNames);
      $paramNames = $paramNames[1];

      $pattern = '#^' . preg_replace('/{(\w+)}/', '([\w-]+)', $path) . '$#';

      if (preg_match($pattern, $url, $matches)) {
        array_shift($matches);
        $routerFound = true;

        $params = [];
        foreach ($paramNames as $index => $name) {
          $params[$name] = $matches[$index] ?? null;
        }

        [$currentController, $action] = explode('@', $controllerAndAction);
        require_once __DIR__ . "/app/Controllers/$currentController.php";

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

  protected function getRoutes()
  {
    return $this->routes;
  }

  protected function setRoutes($routes)
  {
    $this->routes = $routes;
  }
}
