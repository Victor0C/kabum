<?php

class ManageEnv
{

  public static function loadEnv(string $path)
  {
    if (!file_exists($path)) {
      throw new Exception(".env file not found at $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) continue;

      [$key, $value] = array_map('trim', explode('=', $line, 2));

      if (!empty($key)) {
        $value = trim($value, '"\'');
        $_ENV[$key] = $value;
        putenv("$key=$value");
      }
    }
  }
}
