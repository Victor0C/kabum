<?php

require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Repositories/AddressRepository.php';
require_once __DIR__ . '/../Interfaces/CustomerServiceInterface.php';
require_once __DIR__ . '/../Utils/Injections.php';
require_once __DIR__ . '/../Interfaces/CustomerRepositoryInterface.php';
require_once __DIR__ . '/../Interfaces/AddressRepositoryInterface.php';
require_once __DIR__ . '/../Exceptions/CpfOrRgAlreadyUse.php';
require_once __DIR__ . '/../Exceptions/UserNotFound.php';
require_once __DIR__ . '/../Exceptions/AddressNotFound.php';
require_once __DIR__ . '/../Exceptions/UserWithoutAddressException.php';

class CustomerService implements CustomerServiceInterface
{
  public function __construct(
    private ?CustomerRepositoryInterface $customerRepo = null,
    private ?AddressRepositoryInterface $addressRepo = null
  ) {
    $this->customerRepo = $customerRepo ?? Injections::fire('Interfaces/CustomerRepositoryInterface.php');
    $this->addressRepo = $addressRepo ?? Injections::fire('Interfaces/AddressRepositoryInterface.php');
  }

  public function create(array $data): array
  {
    $existing = $this->customerRepo->verifyCPFAndRG($data['cpf'], $data['rg']);

    if ($existing) {
      throw new CpfOrRgAlreadyUse();
    }

    $customer = $this->customerRepo->create([
      'name' => $data['name'],
      'cpf' => $data['cpf'],
      'rg' => $data['rg'],
      'birth_date' => $data['birth_date'],
      'phone' => $data['phone'],
    ]);

    if (!empty($data['addresses'])) {
      $address = array_map(function ($address) use ($customer) {
        return [
          'customer_id' => $customer['id'],
          'zip' => $address['zip'],
          'city' => $address['city'],
          'country' => $address['country'],
          'street' => $address['street'],
          'state' => $address['state'],
          'neighborhood' => $address['neighborhood'],
          'number' => $address['number'],
        ];
      }, $data['addresses']);

      $addressCriados = $this->addressRepo->createMany($address);
      $customer['addresses'] = $addressCriados;
    }

    return $customer;
  }

  public function find(int $id): ?array
  {
    $customer = $this->customerRepo->find($id);
    if (!$customer) {
      throw new UserNotFound();
    }

    $customer['addresses'] = $this->addressRepo->getByCustomerId($id);
    return $customer;
  }

  public function getAll(): array
  {
    $customers = $this->customerRepo->all();

    foreach ($customers as &$customer) {
      $customer['addresses'] = $this->addressRepo->getByCustomerId($customer['id']);
    }

    return $customers;
  }

  public function update(int $id, array $data): array
  {
    $existing = $this->customerRepo->verifyCPFAndRG($data['cpf'], $data['rg'], $id);

    if ($existing) {
      throw new CpfOrRgAlreadyUse();
    }

    $customer = $this->customerRepo->update($id, [
      'name' => $data['name'],
      'cpf' => $data['cpf'],
      'rg' => $data['rg'],
      'birth_date' => $data['birth_date'],
      'phone' => $data['phone'],
    ]);

    if (!empty($data['addresses'])) {
      foreach ($data['addresses'] as $address) {
        if (!empty($address['id'])) {
          $data['addresses'] = $this->addressRepo->update($address['id'], $address);
        } else {
          $address['customer_id'] = $id;
          $data['addresses'] = $this->addressRepo->create($address);
        }
      }
    }

    $customer['addresses'] = $data['addresses'];
    return $customer;
  }

  public function delete(int $id): void
  {
    $this->find($id);
    $this->customerRepo->delete($id);
  }


  public function findAddress(int $id): ?array
  {
    $customer = $this->addressRepo->find($id);
    if (!$customer) {
      throw new AddressNotFound();
    }

    $customer['addresses'] = $this->addressRepo->getByCustomerId($id);
    return $customer;
  }

  public function deleteAddress(int $id, int $customerId): void
  {
    $customer = $this->find($customerId);

    if (count($customer['addresses']) == 0) {
      throw new UserWithoutAddressException();
    }

    $found = false;
    foreach ($customer['addresses'] as $key => $address) {
      if ($address['id'] == $id) {
        $found = true;
        break;
      }
    }

    if (!$found) {
      throw new AddressNotFound();
    }

    $this->addressRepo->delete($id);
  }
}
