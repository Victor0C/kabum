<?php

$routes = [
  ['GET', '/', 'CustomerController@viewCustomers'],
  ['GET', '/create', 'CustomerController@viewCreateCustomer'],
  ['POST', '/create', 'CustomerController@createCustomer'],
  ['GET', '/customer/{id}', 'CustomerController@viewCustomerDetails'],
  ['GET', '/update/customer/{id}', 'CustomerController@viewUpdateCustomer'],
  ['PUT', '/update/customer/{id}', 'CustomerController@updateCustomer'],
  ["DELETE", '/delete/customer/{id}', 'CustomerController@deleteCustomer'],
];
