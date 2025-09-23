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
