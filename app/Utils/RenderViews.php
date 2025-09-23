<?php

class RenderViews
{
  private $layout = "Default";

  public function loadView($view, $args)
  {
    extract($args);
    require_once __DIR__ . "/../Views/layouts/$this->layout/header.php";
    require_once __DIR__ . "/../Views/Pages/$view.php";
    require_once __DIR__ . "/../Views/layouts/$this->layout/footer.php";
  }
}
