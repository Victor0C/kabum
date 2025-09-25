<?php


class AuthMiddleware
{
  public function fire()
  {
    if (!isset($_SESSION['userId'])) {
      header('Location: /login');
      exit;
    }
  }
}
