<?php

require_once __DIR__ . '/BaseRepository.php';

class CustomerRepository extends BaseRepository
{
  protected string $table = 'customers';

  public function verifyCPFAndRG(string $cpf, string $rg, int $userId = null): bool
  {
    $sql = "SELECT 1 FROM {$this->table} WHERE (cpf = :cpf OR rg = :rg)";

    $params = [
      'cpf' => $cpf,
      'rg' => $rg,
    ];

    if ($userId !== null) {
      $sql .= " AND id != :userId";
      $params['userId'] = $userId;
    }

    $sql .= " LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return (bool) $stmt->fetchColumn();
  }
}