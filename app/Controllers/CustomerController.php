<?php

require_once __DIR__ . "/../Utils/RenderViews.php";
require_once __DIR__ . "/../Requests/CreateCustomerRequest.php";
require_once __DIR__ . "/../Requests/UpdateCustomerRequest.php";
require_once __DIR__ . "/../Services/CustomerService.php";
require_once __DIR__ . '/../Utils/Resquets.php';
require_once __Dir__ . "/../Utils/Injections.php";
require_once __DIR__ . "/../Interfaces/CustomerServiceInterface.php";

class CustomerController extends RenderViews
{

  public function __construct(private ?CustomerServiceInterface $customerService = null)
  {
    $this->customerService = $customerService ?? Injections::fire('Interfaces/CustomerServiceInterface.php');
  }

  public function viewCustomers()
  {
    $customers = $this->customerService->getAll();
    $this->loadView('Customers/list-customers', ['customers' => $customers]);
  }

  public function viewCustomerDetails(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $customer = $this->customerService->find((int) $id);
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
      $customer = $this->customerService->create($data);

      Resquets::jsonResponse($customer, 201);
    } catch (\Throwable $e) {
      Resquets::handlerJsonResponseErrors($e);
    }
  }

  public function viewUpdateCustomer(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $customer = $this->customerService->find((int) $id);
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
      $customer = $this->customerService->update((int)$id, $data);

      Resquets::jsonResponse($customer, 200);
    } catch (\Throwable $e) {
      Resquets::handlerJsonResponseErrors($e);
    }
  }

  public function deleteCustomer(array $params)
  {
    try {
      $id = $params['id'] ?? 0;
      $this->customerService->delete((int)$id);
      Resquets::jsonResponse([], 204);
    } catch (\Throwable $e) {
      Resquets::handlerJsonResponseErrors($e);
    }
  }

  public function deleteAddress(array $params){
    try {
      $id = $params['id'] ?? 0;
      $customerId = $params['customerId'] ?? 0;
      $this->customerService->deleteAddress((int)$id, (int) $customerId);
      Resquets::jsonResponse([], 204);
    } catch (\Throwable $e) {
      Resquets::handlerJsonResponseErrors($e);
    }
  }
}
