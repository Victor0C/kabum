<?php

require_once __DIR__ . '/BaseRepository.php';

class AddressRepository extends BaseRepository
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
