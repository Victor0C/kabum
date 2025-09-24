<?php


class AuthMiddleware
{
  public function fire()
  {
    if (!isset($_SESSION['userId'])) {
      error_log('deu ruim aqui');
      header('Location: /login');
      exit;
    }
  }
}
