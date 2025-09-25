<?php

class Injections
{
  private static $providers = [
    'Interfaces/CustomerRepositoryInterface.php' => 'Repositories/CustomerRepository.php',
    'Interfaces/AddressRepositoryInterface.php' => 'Repositories/AddressRepository.php',
    'Interfaces/UserRepositoryInterface.php' => 'Repositories/UserRepository.php',
    'Interfaces/BaseRepositoryInterface.php' => 'Repositories/BaseRepository.php',
    'Interfaces/AuthServiceInterface.php' => 'Services/AuthService.php',
    'Interfaces/CustomerServiceInterface.php' => 'Services/CustomerService.php',
  ];

  public static function fire(string $interface)
  {
    if (!isset(self::$providers[$interface])) {
      throw new Exception("Nenhum provedor encontrado para a interface: $interface");
    }

    $root = dirname(__DIR__);
    $classPath = $root . '/' . self::$providers[$interface];
    if (file_exists($classPath)) {
      require_once $classPath;

      $className = pathinfo($classPath, PATHINFO_FILENAME);

      if (!class_exists($className)) {
        throw new Exception("Classe $className não encontrada no arquivo $classPath");
      }

      return new $className();

    } else {
      throw new Exception("Arquivo da classe não encontrado: $classPath");
    }
  }
}
