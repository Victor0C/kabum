<?php
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Interfaces/BaseRepositoryInterface.php';
require_once __DIR__ . '/../Interfaces/UserRepositoryInterface.php';

class UserRepository extends BaseRepository implements UserRepositoryInterface, BaseRepositoryInterface
{
  protected string $table = 'users';


  public function findByMail(string $mail): ?array
  {
    $sql = "SELECT * FROM {$this->table} WHERE mail = :mail LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['mail' => $mail]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }
}
