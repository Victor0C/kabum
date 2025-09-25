<?php

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Interfaces/BaseRepositoryInterface.php';
require_once __DIR__ . '/../Interfaces/AddressRepositoryInterface.php';

class AddressRepository extends BaseRepository implements AddressRepositoryInterface, BaseRepositoryInterface
{
  protected string $table = 'addresses';

  public function getByCustomerId(int $customerId): array
  {
    $sql = "SELECT * FROM {$this->table} WHERE customer_id = :customer_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['customer_id' => $customerId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
