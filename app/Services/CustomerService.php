<?php

require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Repositories/AddressRepository.php';

class CustomerService
{
  private CustomerRepository $customerRepo;
  private AddressRepository $addressRepo;

  public function __construct()
  {
    $this->customerRepo = new CustomerRepository();
    $this->addressRepo = new AddressRepository();
  }

  public function create(array $data): array
  {
    $existing = $this->customerRepo->verifyCPFAndRG($data['cpf'], $data['rg']);

    if ($existing) {
      throw new Exception("Já existe um cliente cadastrado com este CPF ou RG.", 422);
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
      throw new Exception("Cliente não encontrado", 404);
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
  {;
    $existing = $this->customerRepo->verifyCPFAndRG($data['cpf'], $data['rg'], $id);

    if ($existing) {
      throw new Exception("Já existe outro cliente cadastrado com este CPF ou RG.", 422);
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

  public function delete(int $id): bool
  {
    $this->find($id);
    return $this->customerRepo->delete($id);
  }
}
