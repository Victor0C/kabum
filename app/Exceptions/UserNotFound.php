<?php

class UserNotFound extends Exception
{
  protected $code = 404;

  public function __construct($message = "Usuário não encontrado", $code = 404)
  {
    parent::__construct($message, $code);
  }
}
