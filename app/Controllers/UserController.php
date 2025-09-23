<?php

require_once __DIR__ . "/../Utils/RenderViews.php";

session_start();

class UserController extends RenderViews
{

  public function viewUsers()
  {
    $this->loadView('Users/list-users', []);
  }
}
