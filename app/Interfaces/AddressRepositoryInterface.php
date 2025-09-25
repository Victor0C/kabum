<?php
require_once __DIR__ . '/BaseRepositoryInterface.php';
interface AddressRepositoryInterface extends BaseRepositoryInterface
{
  public function getByCustomerId(int $customerId): array;
}
