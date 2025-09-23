<?php
class ValidationException extends Exception
{
  private array $errors;

  public function __construct(array $errors, $message = 'Falha na validação dos dados', $code = 422)
  {
    parent::__construct($message, $code);
    $this->errors = $errors;
  }

  public function getErrors(): array
  {
    return $this->errors;
  }
}
