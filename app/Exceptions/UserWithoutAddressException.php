<?php

class UserWithoutAddressException extends Exception
{
  protected $code = 422;

  public function __construct($message = "O usuário não pode ficar sem endereço", $code = 422)
  {
    parent::__construct($message, $code);
  }
}
