<?php

abstract class BaseRepository
{
  protected PDO $pdo;
  protected string $table;

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

  public function createMany(array $records): array
  {
    if (empty($records)) return [];

    $columns = array_keys($records[0]);
    $columnsList = implode(', ', $columns);

    $placeholdersArr = [];
    $bindings = [];

    foreach ($records as $index => $record) {
      $placeholders = [];
      foreach ($columns as $col) {
        $key = $col . $index;
        $placeholders[] = ':' . $key;
        $bindings[$key] = $record[$col];
      }
      $placeholdersArr[] = '(' . implode(', ', $placeholders) . ')';
    }

    $sql = "INSERT INTO {$this->table} ($columnsList) VALUES " . implode(', ', $placeholdersArr);
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($bindings);

    $lastId = (int) $this->pdo->lastInsertId();
    foreach ($records as $i => &$record) {
      $record['id'] = $lastId + $i;
    }

    return $records;
  }

  public function find(int $id): ?array
  {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
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
    $bindings = [];

    foreach ($conditions as $index => $cond) {
      if (is_array($cond) && isset($cond['column'], $cond['value'])) {
        $operator = $cond['operator'] ?? '=';
        $boolean = strtoupper($cond['boolean'] ?? 'AND');
        $key = $cond['column'] . '_' . $index;

        $clauses[] = ($index === 0 ? '' : " $boolean ") . "{$cond['column']} $operator :$key";
        $bindings[$key] = $cond['value'];
      } elseif (is_scalar($cond)) {
        $key = $index;
        $clauses[] = "$index = :$key";
        $bindings[$key] = $cond;
      }
    }

    $sql = "SELECT * FROM {$this->table}";
    if (!empty($clauses)) {
      $sql .= " WHERE " . implode('', $clauses);
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($bindings);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
