<?php
class Database
{
  private static $db_host = DB_HOST;
  private static $db_user = DB_USER;
  private static $db_pass = DB_PASS;
  private static $db_name = DB_NAME;

  public static function getConnection()
  {
    try {
      $pdo = new PDO(
        "mysql:dbname=" . self::$db_name . ";host=" . self::$db_host,
        self::$db_user,
        self::$db_pass
      );
      return $pdo;
    } catch (PDOException $e) {
      throw new Exception("Algo inesperado aconteceu");
      exit();
    }
  }
}
