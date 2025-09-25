<?php

interface UserRepositoryInterface
{
  public function findByMail(string $mail): ?array;
}
