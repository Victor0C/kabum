<?php

interface CustomerServiceInterface
{
  public function create(array $data): array;
  public function find(int $id): ?array;
  public function getAll(): array;
  public function update(int $id, array $data): array;
  public function delete(int $id): void;
  public function findAddress(int $id): ?array;
  public function deleteAddress(int $id, int $customerId): void;
}
