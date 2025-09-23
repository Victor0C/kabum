<?php

$routes = [
  ['GET', '/', 'CustomerController@viewCustomers'],
  ['GET', '/create', 'CustomerController@viewCreateCustomer'],
  ['POST', '/create', 'CustomerController@createCustomer'],
  ['GET', '/customer/{id}', 'CustomerController@viewCustomerDetails'],
];
