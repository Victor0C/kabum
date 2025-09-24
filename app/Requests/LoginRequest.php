<?php

require_once __DIR__ . '/../Interfaces/RequestInterface.php';

class LoginRequest implements RequestInterface
{
  public static function validate(array $data): array
  {
    $errors = [];
    $validated = [];


    if (empty($data['mail'])) {
      $errors['mail'] = "O e-mail é obrigatório.";
    } elseif (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
      $errors['mail'] = "Formato de e-mail inválido.";
    } else {
      $validated['mail'] = $data['mail'];
    }

    if (empty($data['password'])) {
      $errors['password'] = "A senha é obrigatória.";
    } else {
      $validated['password'] = $data['password'];
    }

    if (!empty($errors)) {
      throw new ValidationException($errors);
    }

    return $validated;
  }
}
