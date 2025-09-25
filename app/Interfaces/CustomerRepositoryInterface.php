<?php

interface CustomerRepositoryInterface
{
  public function verifyCPFAndRG(string $cpf, string $rg, ?int $userId = null): bool;
}
