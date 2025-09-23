<?php
require_once __DIR__ . '/../Exceptions/ValidationException.php';

function jsonResponse($body, int $statusCode = 200): void
{
  header('Content-Type: application/json');
  http_response_code($statusCode);

  if (!is_string($body)) {
    $body = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  }

  echo $body;
  exit;
}

function handlerResponseErrors(Throwable $e)
{
  if ($e instanceof ValidationException) {
    jsonResponse(['errors' => $e->getErrors()], (int) $e->getCode());
  } else {
    $code = (int) $e->getCode();
    $status = $code >= 400 && $code < 500 ? $code : 500;

    if($status >= 500){
      error_log("[ERROR] " . $e->getMessage());
    }

    jsonResponse([
      'message' => $status === 500 ? "Erro interno do servidor" : $e->getMessage()
    ], $status);
  }
}
