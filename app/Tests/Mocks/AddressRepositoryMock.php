<?php

require_once __DIR__ . "/../../Interfaces/AddressRepositoryInterface.php";
require_once __DIR__ . "/../../Interfaces/BaseRepositoryInterface.php";
require_once __DIR__ . "/Mocks.php";

class AddressRepositoryMock implements AddressRepositoryInterface, BaseRepositoryInterface
{
  private array $data = [];
  private int $lastId = 1;

  public function __construct()
  {
    $this->data = [Mocks::getAddressMock()];
    $this->lastId = count($this->data);
  }

  public function getByCustomerId(int $customerId): array
  {
    return array_values(array_filter($this->data, fn($a) => $a['customer_id'] === $customerId));
  }

  public function create(array $data): array
  {
    $this->lastId++;
    $data['id'] = $this->lastId;
    $this->data[] = $data;
    return $data;
  }

  public function createMany(array $records): array
  {
    $result = [];
    foreach ($records as $record) {
      $result[] = $this->create($record);
    }
    return $result;
  }

  public function find(int $id): ?array
  {
    foreach ($this->data as $row) {
      if ($row['id'] === $id) return $row;
    }
    return null;
  }

  public function all(): array
  {
    return $this->data;
  }

  public function update(int $id, array $data): array
  {
    foreach ($this->data as $index => $row) {
      if ($row['id'] === $id) {
        $this->data[$index] = array_merge($row, $data, ['id' => $id]);
        return $this->data[$index];
      }
    }
    throw new Exception("Endereço não encontrado");
  }

  public function delete(int $id): bool
  {
    foreach ($this->data as $index => $row) {
      if ($row['id'] === $id) {
        array_splice($this->data, $index, 1);
        return true;
      }
    }
    return false;
  }
}
