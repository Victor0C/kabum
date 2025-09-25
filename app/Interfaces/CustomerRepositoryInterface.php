<?php
require_once __DIR__ .'/BaseRepositoryInterface.php';
interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
  public function verifyCPFAndRG(string $cpf, string $rg, ?int $userId = null): bool;
}
