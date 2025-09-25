<?php

class InvalidCredentialsException extends Exception
{
  protected $code = 401;

  public function __construct($message = "Credenciais inválidas", $code = 401)
  {
    parent::__construct($message, $code);
  }
}
