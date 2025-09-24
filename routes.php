<?php

$routes = [
  ['GET', '/login', 'AuthController@viewLogin'],
  ['POST', '/login', 'AuthController@login'],
  ['GET', '/', 'CustomerController@viewCustomers'],
  ['GET', '/create', 'CustomerController@viewCreateCustomer'],
  ['POST', '/create', 'CustomerController@createCustomer'],
  ['GET', '/customer/{id}', 'CustomerController@viewCustomerDetails'],
  ['GET', '/update/customer/{id}', 'CustomerController@viewUpdateCustomer'],
  ['PUT', '/update/customer/{id}', 'CustomerController@updateCustomer'],
  ["DELETE", '/delete/customer/{id}', 'CustomerController@deleteCustomer'],
  ["DELETE", '/delete/address/{id}/customer/{customerId}', 'CustomerController@deleteAddress'],
];
