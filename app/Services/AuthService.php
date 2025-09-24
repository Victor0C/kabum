<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthService
{
  private UserRepository $userRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
  }

  public function login(string $mail, string $password): void
  {
    $user = $this->userRepo->findByMail($mail);

    if(!$user){
      throw new Exception("Credenciais inválidas", 401);
    }

    if (!password_verify($password, $user['password'])) {
      throw new Exception("Credenciais inválidas", 401);
    }

    $_SESSION['useId'] = $user['id'];
  }

  public function logout(): void
  {
    session_destroy();
  }
}
