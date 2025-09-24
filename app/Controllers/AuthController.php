<?php
require_once __DIR__ . "/../Utils/RenderViews.php";
require_once __DIR__ . "/../Requests/LoginRequest.php";
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Utils/Resquets.php';

class AuthController extends RenderViews
{
  public function viewLogin()
  {
    $this->loadView('Auth/login');
  }

  public function login()
  {
    try {
  
      $data = LoginRequest::validate($_POST);
      (new AuthService)->login($data['mail'], $data['password']);
      header('Location: /');
    } catch (\Throwable $e) {
      Resquets::handlerSessionResponseErrors($e);
      header('Location: /login');
    }
  }

  public function logout()
  {
    try {
      (new AuthService)->logout();
      header('Location: /');
    } catch (\Throwable $e) {
      Resquets::handlerSessionResponseErrors($e);
      header('Location: /login');
    }
  }
}
