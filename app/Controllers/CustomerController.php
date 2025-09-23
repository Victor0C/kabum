<?php

require_once __DIR__ . "/../Utils/RenderViews.php";
require_once __DIR__ . "/../Requests/CreateCustomerRequest.php";
require_once __DIR__ . "/../Requests/UpdateCustomerRequest.php";
require_once __DIR__ . "/../Services/CustomerService.php";

session_start();

class CustomerController extends RenderViews
{
  public function viewCustomers()
  {
    $customers = (new CustomerService)->getAll();
    $this->loadView('Customers/list-customers', ['customers' => $customers]);
  }

  public function viewCustomerDetails()
  {
    try {
      $requestData = $_GET;
      $id = $requestData['id'] ?? 0;
      $customer = (new CustomerService)->find((int) $id);
      $this->loadView('Customers/customers-details', ['customer' => $customer]);
    } catch (\Throwable $e) {
      $_SESSION['msg'] = [
        'type' => 'error',
        'msg' => $e->getMessage(),
        'code' => $e->getCode()
      ];
    }
  }

  public function createCustomer()
  {
    try {
      $requestData = $_POST;
      $data = CreateCustomerRequest::validate($requestData);
      $customer = (new CustomerService)->create($data);

      $_SESSION['msg'] = [
        'type' => 'success',
        'msg' => "Cliente cadastrado com sucesso",
        'code' => 201
      ];

      header("Location: " . BASE_URL . "customer/" . $customer['id']);
      exit;
    } catch (\Throwable $e) {
      $_SESSION['msg'] = [
        'type' => 'error',
        'msg' => $e->getMessage(),
        'code' => $e->getCode()
      ];
    }
  }

  public function updateCustomer()
  {
    try {
      $requestData = $_POST;
      $id = $requestData['id'] ?? 0;
      if (!$id) {
        throw new Exception("ID do cliente não informado.", 400);
      }

      $data = UpdateCustomerRequest::validate($requestData);
      $customer = (new CustomerService)->update((int)$id, $data);

      $_SESSION['msg'] = [
        'type' => 'success',
        'msg' => "Cliente atualizado com sucesso",
        'code' => 200
      ];

      header("Location: " . BASE_URL . "customer/" . $customer['id']);
      exit;
    } catch (\Throwable $e) {
      $_SESSION['msg'] = [
        'type' => 'error',
        'msg' => $e->getMessage(),
        'code' => $e->getCode()
      ];
    }
  }

  public function deleteCustomer()
  {
    try {
      $id = $_POST['id'] ?? 0;
      (new CustomerService)->delete((int)$id);
      header("Location: " . BASE_URL . "customers");
    } catch (\Throwable $e) {
      $_SESSION['msg'] = [
        'type' => 'error',
        'msg' => $e->getMessage(),
        'code' => $e->getCode()
      ];
    }
  }
}
