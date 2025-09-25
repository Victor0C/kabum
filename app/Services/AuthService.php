<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Interfaces/AuthServiceInterface.php';
require_once __Dir__ . "/../Utils/Injections.php";

class AuthService implements AuthServiceInterface
{
  public function __construct(private ?UserRepositoryInterface $userRepo = null)
  {
    $this->userRepo = $userRepo ?? Injections::fire('Interfaces/UserRepositoryInterface.php');
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

    $_SESSION['userId'] = $user['id'];
    $_SESSION['userName'] = $user['name'];
  }

  public function logout(): void
  {
    session_destroy();
  }
}
