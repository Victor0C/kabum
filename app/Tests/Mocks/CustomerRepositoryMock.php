<?php
require_once __DIR__ . "/../../Interfaces/CustomerRepositoryInterface.php";
require_once __DIR__ . "/../../Interfaces/BaseRepositoryInterface.php";
require_once __DIR__ . "/Mocks.php";

class CustomerRepositoryMock implements CustomerRepositoryInterface, BaseRepositoryInterface
{
  private array $data = [];
  private int $lastId = 1;

  public function __construct()
  {
    $this->data = [Mocks::getCustomerMock()];
    $this->lastId = count($this->data);
  }

  public function verifyCPFAndRG(string $cpf, string $rg, ?int $userId = null): bool
  {
    foreach ($this->data as $user) {
      if (($user['cpf'] === $cpf || $user['rg'] === $rg) && $user['id'] !== $userId) {
        return true;
      }
    }
    return false;
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
    foreach ($this->data as $user) {
      if ($user['id'] === $id) return $user;
    }
    return null;
  }

  public function all(): array
  {
    return $this->data;
  }

  public function update(int $id, array $data): array
  {
    foreach ($this->data as $index => $user) {
      if ($user['id'] === $id) {
        $this->data[$index] = array_merge($user, $data, ['id' => $id]);
        return $this->data[$index];
      }
    }
    throw new Exception("Usuário não encontrado");
  }

  public function delete(int $id): bool
  {
    foreach ($this->data as $index => $user) {
      if ($user['id'] === $id) {
        array_splice($this->data, $index, 1);
        return true;
      }
    }
    return false;
  }
}
