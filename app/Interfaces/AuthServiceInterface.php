<?php

interface AuthServiceInterface
{
  public function login(string $mail, string $password): void;
  public function logout(): void;
}
