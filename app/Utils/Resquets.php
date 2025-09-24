<?php
require_once __DIR__ . '/../Exceptions/ValidationException.php';

class Resquets
{
  public static function jsonResponse($body = null, int $statusCode = 200): void
  {
    http_response_code($statusCode);

    if ($statusCode === 204) {
      exit;
    }

    header('Content-Type: application/json');

    if (!is_string($body)) {
      $body = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    echo $body;
    exit;
  }

  public static function handlerJsonResponseErrors(Throwable $e): void
  {
    if ($e instanceof ValidationException) {
      self::jsonResponse(['errors' => $e->getErrors()], (int) $e->getCode());
    } else {
      $code = (int) $e->getCode();
      $status = $code >= 400 && $code < 500 ? $code : 500;

      if ($status >= 500) {
        error_log("[ERROR] " . $e->getMessage());
      }

      self::jsonResponse([
        'message' => $status == 500 ? "Erro interno do servidor" : $e->getMessage()
      ], $status);
    }
  }

  public static function handlerSessionResponseErrors(Throwable $e): void
  {
    if ($e instanceof ValidationException) {
      $_SESSION['errors'] = ['msg' => $e->getErrors(), 'code' => (int) $e->getCode()];
    } else {
      $code = (int) $e->getCode();
      $status = $code >= 400 && $code < 500 ? $code : 500;

      if ($status >= 500) {
        error_log("[ERROR] " . $e->getMessage());
      }

      $_SESSION['errors'] = ['msg' => $status == 500 ? "Erro interno do servidor" : $e->getMessage(), 'code' => $status];
    }
  }
}
