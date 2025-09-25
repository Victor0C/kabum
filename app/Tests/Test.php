<?php

require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/Mocks/CustomerRepositoryMock.php';
require_once __DIR__ . '/Mocks/AddressRepositoryMock.php';
require_once __DIR__ . '/Mocks/UserRepositoryMock.php';
require_once __DIR__ . '/../Exceptions/CpfOrRgAlreadyUse.php';
require_once __DIR__ . '/../Exceptions/UserNotFound.php';
require_once __DIR__ . '/../Exceptions/AddressNotFound.php';
require_once __DIR__ . '/../Exceptions/UserWithoutAddressException.php';
require_once __DIR__ . '/../Exceptions/InvalidCredentialsException.php';

class Test
{
  public static function run()
  {
    self::testCustomerServiceCreate();
    self::testCustomerServiceFind();
    self::testAuthServiceLogin();
    self::testAuthServiceLoginErrors();
    self::testCreateCpfOrRgAlreadyUse();
    self::testFindUserNotFound();
    self::testFindAddressNotFound();
    self::testDeleteAddressUserWithoutAddress();
    self::testDeleteAddressNotFound();

    echo "Todos os testes executados com sucesso.\n";
  }

  private static function testCustomerServiceCreate()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();

    $service = new CustomerService($customerRepo, $addressRepo);

    $data = [
      'name' => 'Victor Hugo',
      'cpf' => '12345678901',
      'rg' => '123456789',
      'birth_date' => '1990-01-01',
      'phone' => '11999999999',
      'addresses' => [
        [
          'zip' => '01001000',
          'city' => 'São Paulo',
          'state' => 'SP',
          'country' => 'Brasil',
          'street' => 'Rua Fictícia',
          'neighborhood' => 'Centro',
          'number' => '123'
        ]
      ]
    ];

    $customer = $service->create($data);

    assert($customer['name'] === 'Victor Hugo', 'Nome do cliente deve ser Victor Hugo');
    assert(!empty($customer['addresses']), 'O cliente deve ter endereços');
  }

  private static function testCustomerServiceFind()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();

    $service = new CustomerService($customerRepo, $addressRepo);

    $customer = $service->create([
      'name' => 'Teste Find',
      'cpf' => '98765432100',
      'rg' => '987654321',
      'birth_date' => '1995-01-01',
      'phone' => '11988888888',
      'addresses' => []
    ]);

    $found = $service->find($customer['id']);
    assert($found['id'] === $customer['id'], 'ID deve ser igual');
    assert($found['name'] === 'Teste Find', 'Nome deve ser Teste Find');
  }

  private static function testAuthServiceLogin()
  {
    $userRepo = new UserRepositoryMock();
    $authService = new AuthService($userRepo);

    $userRepo->create([
      'name' => 'test',
      'mail' => 'test@test.com',
      'password' => password_hash('123456', PASSWORD_BCRYPT)
    ]);

    $authService->login('test@test.com', '123456');
    assert($_SESSION['userName'] === 'test', 'O usuário logado deve ser test');
  }

  private static function testAuthServiceLoginErrors()
  {
    $userRepo = new UserRepositoryMock();
    $authService = new AuthService($userRepo);

    try {
      $authService->login('invalido@test.com', '123456');
      assert(false, 'Deveria lançar InvalidCredentialsException para email inválido');
    } catch (Exception $e) {
      assert($e instanceof InvalidCredentialsException, 'A exceção deve ser do tipo InvalidCredentialsException');
    }

    $userRepo->create([
      'name' => 'user2',
      'mail' => 'user2@test.com',
      'password' => password_hash('senha_correta', PASSWORD_BCRYPT)
    ]);

    try {
      $authService->login('user2@test.com', 'senha_errada');
      assert(false, 'Deveria lançar InvalidCredentialsException para senha inválida');
    } catch (Exception $e) {
      assert($e instanceof InvalidCredentialsException, 'A exceção deve ser do tipo InvalidCredentialsException');
    }
  }

  private static function testCreateCpfOrRgAlreadyUse()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();
    $service = new CustomerService($customerRepo, $addressRepo);

    $existingCustomer = [
      'name' => 'Victor Hugo',
      'cpf' => '11111111111',
      'rg' => '325226445',
      'birth_date' => '1990-01-01',
      'phone' => '11999999999',
      'addresses' => []
    ];

    try {
      $service->create($existingCustomer);
      assert(false, 'Deveria lançar CpfOrRgAlreadyUse');
    } catch (Exception $e) {
      assert($e instanceof CpfOrRgAlreadyUse, 'Exceção esperada: CpfOrRgAlreadyUse');
    }
  }

  private static function testFindUserNotFound()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();
    $service = new CustomerService($customerRepo, $addressRepo);

    try {
      $service->find(9999);
      assert(false, 'Deveria lançar UserNotFound');
    } catch (Exception $e) {
      assert($e instanceof UserNotFound, 'Exceção esperada: UserNotFound');
    }
  }

  private static function testFindAddressNotFound()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();
    $service = new CustomerService($customerRepo, $addressRepo);

    try {
      $service->findAddress(9999);
      assert(false, 'Deveria lançar AddressNotFound');
    } catch (Exception $e) {
      assert($e instanceof AddressNotFound, 'Exceção esperada: AddressNotFound');
    }
  }

  private static function testDeleteAddressUserWithoutAddress()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();
    $service = new CustomerService($customerRepo, $addressRepo);

    $customerWithoutAddress = $service->create([
      'name' => 'Sem Endereço',
      'cpf' => '22222222222',
      'rg' => '333333333',
      'birth_date' => '1995-01-01',
      'phone' => '11988888888',
      'addresses' => []
    ]);

    try {
      $service->deleteAddress(1, $customerWithoutAddress['id']);
      assert(false, 'Deveria lançar UserWithoutAddressException');
    } catch (Exception $e) {
      assert($e instanceof UserWithoutAddressException, 'Exceção esperada: UserWithoutAddressException');
    }
  }

  private static function testDeleteAddressNotFound()
  {
    $customerRepo = new CustomerRepositoryMock();
    $addressRepo = new AddressRepositoryMock();
    $service = new CustomerService($customerRepo, $addressRepo);

    $customerWithAddress = $service->create([
      'name' => 'Com Endereço',
      'cpf' => '33333333333',
      'rg' => '444444444',
      'birth_date' => '1992-01-01',
      'phone' => '11977777777',
      'addresses' => [
        [
          'zip' => '01001000',
          'city' => 'São Paulo',
          'state' => 'SP',
          'country' => 'Brasil',
          'street' => 'Rua Teste',
          'neighborhood' => 'Centro',
          'number' => '123'
        ]
      ]
    ]);

    try {
      $service->deleteAddress(9999, $customerWithAddress['id']);
      assert(false, 'Deveria lançar AddressNotFound');
    } catch (Exception $e) {
      assert($e instanceof AddressNotFound, 'Exceção esperada: AddressNotFound');
    }
  }
}

Test::run();
