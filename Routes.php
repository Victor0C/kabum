<?php
require_once __DIR__ . '/app/Utils/RouterUtils.php';

class Routes extends RouterUtils
{
  protected $routes = [
    [
      'method' => 'GET',
      'path' => '/login',
      'action' => 'AuthController@viewLogin'
    ],
    [
      'method' => 'POST',
      'path' => '/login',
      'action' => 'AuthController@login'
    ],
    [
      'method' => 'POST',
      'path' => '/logout',
      'action' => 'AuthController@logout'
    ],
    [
      'method' => 'GET',
      'path' => '/',
      'action' => 'CustomerController@viewCustomers',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'GET',
      'path' => '/create',
      'action' => 'CustomerController@viewCreateCustomer',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'POST',
      'path' => '/create',
      'action' => 'CustomerController@createCustomer',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'GET',
      'path' => '/customer/{id}',
      'action' => 'CustomerController@viewCustomerDetails',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'GET',
      'path' => '/update/customer/{id}',
      'action' => 'CustomerController@viewUpdateCustomer',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'PUT',
      'path' => '/update/customer/{id}',
      'action' => 'CustomerController@updateCustomer',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'DELETE',
      'path' => '/delete/customer/{id}',
      'action' => 'CustomerController@deleteCustomer',
      'middleware' => ['AuthMiddleware']
    ],
    [
      'method' => 'DELETE',
      'path' => '/delete/address/{id}/customer/{customerId}',
      'action' => 'CustomerController@deleteAddress',
      'middleware' => ['AuthMiddleware']
    ],
  ];
}
