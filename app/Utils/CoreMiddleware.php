<?php

class CoreMiddleware
{
  private $default = ['StartSessionMiddleware'];

  public function fire(array $middlewares)
  {
    foreach ($this->default as $key => $middleware) {
      $this->execute($middleware);;
    }

    foreach ($middlewares as $key => $middleware) {
      $this->execute($middleware);
    }
  }

  private function execute($middleware)
  {
    require_once __DIR__ . "/../Middlewares/$middleware.php";

    $middleware = new $middleware();
    $middleware->fire();
  }
}
