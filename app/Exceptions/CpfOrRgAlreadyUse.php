<?php

class CpfOrRgAlreadyUse extends Exception
{
  protected $code = 422;

  public function __construct($message = "Já existe outro cliente cadastrado com este CPF ou RG", $code = 422)
  {
    parent::__construct($message, $code);
  }
}
