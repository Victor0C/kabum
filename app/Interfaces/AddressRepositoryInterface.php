<?php

interface AddressRepositoryInterface
{
  public function getByCustomerId(int $customerId): array;
}
