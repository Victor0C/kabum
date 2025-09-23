<?php

$routes = [
  ['GET', '/', 'CustomerController@viewCustomers'],
  ['GET', '/create', 'CustomerController@viewCreateCustomer'],
  ['POST', '/create', 'CustomerController@createCustomer'],
  ['GET', '/{id}', 'CustomerController@viewCustomerDetails'],
];
