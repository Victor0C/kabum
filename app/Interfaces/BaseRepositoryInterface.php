<?php

interface BaseRepositoryInterface
{
  public function create(array $data): array;
  public function createMany(array $records): array;
  public function find(int $id): ?array;
  public function all(): array;
  public function update(int $id, array $data): array;
  public function delete(int $id): bool;
}
