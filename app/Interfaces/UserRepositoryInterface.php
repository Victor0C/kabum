<?php
require_once __DIR__ . '/BaseRepositoryInterface.php';
interface UserRepositoryInterface extends BaseRepositoryInterface
{
  public function findByMail(string $mail): ?array;
}
