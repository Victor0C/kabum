<?php

class AddressNotFound extends Exception
{
  protected $code = 404;

  public function __construct($message = "Endereço não encontrado", $code = 404)
  {
    parent::__construct($message, $code);
  }
}
