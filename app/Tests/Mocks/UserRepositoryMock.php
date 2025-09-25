<?php

require_once __DIR__ . "/../../Interfaces/UserRepositoryInterface.php";
require_once __DIR__ . "/../../Interfaces/BaseRepositoryInterface.php";
require_once __DIR__ . "/Mocks.php";

class UserRepositoryMock implements UserRepositoryInterface, BaseRepositoryInterface
{
  private array $users;

  public function __construct()
  {
    $this->users = [Mocks::getUserMock()];
  }

  public function findByMail(string $mail): ?array
  {
    foreach ($this->users as $user) {
      if ($user['mail'] === $mail) {
        return $user;
      }
    }
    return null;
  }

  public function create(array $data): array
  {
    $id = count($this->users) + 1;
    $data['id'] = $id;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    $data['addresses'] = [];
    $this->users[] = $data;
    return $data;
  }

  public function createMany(array $records): array
  {
    $results = [];
    foreach ($records as $record) {
      $results[] = $this->create($record);
    }
    return $results;
  }

  public function find(int $id): ?array
  {
    foreach ($this->users as $user) {
      if ($user['id'] === $id) {
        return $user;
      }
    }
    return null;
  }

  public function all(): array
  {
    return $this->users;
  }

  public function update(int $id, array $data): array
  {
    foreach ($this->users as &$user) {
      if ($user['id'] === $id) {
        $user = array_merge($user, $data);
        $user['updated_at'] = date('Y-m-d H:i:s');
        return $user;
      }
    }
    throw new Exception("Usuário não encontrado", 404);
  }

  public function delete(int $id): bool
  {
    foreach ($this->users as $key => $user) {
      if ($user['id'] === $id) {
        unset($this->users[$key]);
        $this->users = array_values($this->users);
        return true;
      }
    }
    return false;
  }
}
