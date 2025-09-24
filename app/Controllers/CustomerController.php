<?php

require_once __DIR__ . "/../Utils/RenderViews.php";
require_once __DIR__ . "/../Requests/CreateCustomerRequest.php";
require_once __DIR__ . "/../Requests/UpdateCustomerRequest.php";
require_once __DIR__ . "/../Services/CustomerService.php";
require_once __DIR__ . '/../Utils/Resquets.php';

class CustomerController extends RenderViews
{
  public function viewCustomers()
  {
    $customers = (new CustomerService)->getAll();
    $this->loadView('Customers/list-customers', ['customers' => $customers]);
  }

  public function viewCustomerDetails(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $customer = (new CustomerService)->find((int) $id);
      $this->loadView('Customers/customers-details', ['customer' => $customer]);
    } catch (\Throwable $e) {
      header('Location: /');
      exit;
    }
  }

  public function viewCreateCustomer()
  {
    $this->loadView('Customers/create-update-customer');
  }

  public function createCustomer()
  {
    try {
      $requestData = json_decode(file_get_contents('php://input'), true);
      $data = CreateCustomerRequest::validate($requestData);
      $customer = (new CustomerService)->create($data);

      jsonResponse($customer, 201);
    } catch (\Throwable $e) {
      handlerResponseErrors($e);
    }
  }

  public function viewUpdateCustomer(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $customer = (new CustomerService)->find((int) $id);
      $this->loadView('Customers/create-update-customer', ['customer' => $customer]);
    } catch (\Throwable $e) {
      header('Location: /');
      exit;
    }
  }
  public function updateCustomer(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $requestData = json_decode(file_get_contents('php://input'), true);
      $data = UpdateCustomerRequest::validate($requestData);
      $customer = (new CustomerService)->update((int)$id, $data);

      jsonResponse($customer, 200);
    } catch (\Throwable $e) {
      handlerResponseErrors($e);
    }
  }

  public function deleteCustomer(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      (new CustomerService)->delete((int)$id);
      jsonResponse([], 204);
    } catch (\Throwable $e) {
      handlerResponseErrors($e);
    }
  }
}
