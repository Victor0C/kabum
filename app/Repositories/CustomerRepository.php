<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Interfaces/BaseRepositoryInterface.php';
require_once __DIR__ . '/../Interfaces/CustomerRepositoryInterface.php';

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface, BaseRepositoryInterface
{
  protected string $table = 'customers';

  public function verifyCPFAndRG(string $cpf, string $rg, ?int $userId = null): bool
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