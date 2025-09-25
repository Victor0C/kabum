<?php
require_once __DIR__ . "/../Utils/RenderViews.php";
require_once __DIR__ . "/../Requests/LoginRequest.php";
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Utils/Resquets.php';
require_once __DIR__ . "/../Interfaces/AuthServiceInterface.php";
require_once __Dir__ . "/../Utils/Injections.php";

class AuthController extends RenderViews
{
  public function __construct(private ?AuthServiceInterface $authService = null)
  {
    $this->authService = $authService ?? Injections::fire('Interfaces/AuthServiceInterface.php');
  }

  public function viewLogin()
  {
    $this->loadView('Auth/login');
  }

  public function login()
  {
    try {
      $data = LoginRequest::validate($_POST);
      $this->authService->login($data['mail'], $data['password']);
      header('Location: /');
    } catch (\Throwable $e) {
      Resquets::handlerSessionResponseErrors($e);
      header('Location: /login');
    }
  }

  public function logout()
  {
    try {
      $this->authService->logout();
      header('Location: /');
    } catch (\Throwable $e) {
      Resquets::handlerSessionResponseErrors($e);
      header('Location: /login');
    }
  }
}
