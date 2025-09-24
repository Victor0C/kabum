<?php
require_once __DIR__ . '/BaseRepository.php';

class UserRepository extends BaseRepository
{
  protected string $table = 'users';


  public function findByMail(string $mail)
  {
    $sql = "SELECT * FROM {$this->table} WHERE mail = :mail LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['mail' => $mail]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }
}
