<?php

require_once __DIR__ . '/BaseRepository.php';

class CustomerRepository extends BaseRepository
{
  protected string $table = 'customers';

  public function verifyCPFAndRG(string $cpf, string $rg): bool
  {
    $stmt = $this->pdo->prepare(
      "SELECT 1 FROM {$this->table} WHERE cpf = :cpf OR rg = :rg LIMIT 1"
    );

    $stmt->execute([
      'cpf' => $cpf,
      'rg' => $rg,
    ]);

    return (bool) $stmt->fetchColumn();
  }
}