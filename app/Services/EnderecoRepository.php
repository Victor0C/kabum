<?php

require_once __DIR__ . '/Database.php';

class EnderecoRepository
{
  private PDO $pdo;
  private string $table = 'enderecos';

  public function __construct()
  {
    $this->pdo = Database::getConnection();
  }

  public function create(array $data): array
  {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));

    $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
    $stmt->execute($data);

    $data['id'] = (int) $this->pdo->lastInsertId();
    return $data;
  }

  public function createMany(array $enderecos): array
  {
    if (empty($enderecos)) return [];

    $columns = array_keys($enderecos[0]);
    $columnsList = implode(', ', $columns);

    $placeholdersArr = [];
    $bindings = [];

    foreach ($enderecos as $index => $endereco) {
      $placeholders = [];
      foreach ($columns as $col) {
        $key = $col . $index;
        $placeholders[] = ':' . $key;
        $bindings[$key] = $endereco[$col];
      }
      $placeholdersArr[] = '(' . implode(', ', $placeholders) . ')';
    }

    $sql = "INSERT INTO {$this->table} ($columnsList) VALUES " . implode(', ', $placeholdersArr);
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($bindings);

    $lastId = (int) $this->pdo->lastInsertId();
    foreach ($enderecos as $i => &$endereco) {
      $endereco['id'] = $lastId + $i;
    }

    return $enderecos;
  }

  public function find(int $id): ?array
  {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
  }

  public function all(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function update(int $id, array $data): array
  {
    $set = [];
    foreach ($data as $col => $val) {
      $set[] = "$col = :$col";
    }

    $stmt = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id");
    $data['id'] = $id;
    $stmt->execute($data);

    return $this->find($id);
  }

  public function delete(int $id): bool
  {
    $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function where(array $conditions): array
  {
    $clauses = [];
    foreach ($conditions as $col => $val) {
      $clauses[] = "$col = :$col";
    }
    $where = implode(' AND ', $clauses);

    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE $where");
    $stmt->execute($conditions);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
