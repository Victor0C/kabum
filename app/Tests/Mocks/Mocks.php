<?php

class Mocks
{
  public static function getUserMock()
  {
    return [

      'id' => 1,
      'name' => 'admin',
      'mail' => 'admin@admin.com',
      'password' => '$2a$12$PzIpoK9m9ixoUxUd2/fQpuerGN1le9P9DJcr.YZpiGUaRU50WNcsu',
      'created_at' => '2025-09-24 19:30:05',
      'updated_at' => '2025-09-24 19:57:06',

    ];
  }

  public static function getCustomerMock()
  {
    return
      [
        'id' => 1,
        'name' => 'VICTOR HUGO OLIVEIRA CUNHA',
        'cpf' => '11111111111',
        'rg' => '325226445',
        'birth_date' => '2025-09-23',
        'phone' => '75983299332',
        'created_at' => '2025-09-23 23:04:47',
        'updated_at' => '2025-09-23 23:04:47',
        'addresses' => [
          self::getAddressMock()
        ]
      ];
  }

  public static function getAddressMock()
  {
    return [
      'id' => 1,
      'customer_id' => 1,
      'zip' => '48750000',
      'city' => 'Retirolândia',
      'state' => 'AC',
      'country' => 'Brasil',
      'street' => 'Rua 31 de Março',
      'neighborhood' => 'Centro',
      'number' => '169',
      'created_at' => '2025-09-23 23:24:07',
      'updated_at' => '2025-09-24 20:38:58'
    ];
  }
}
