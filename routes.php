<?php

$routes = [
  ['GET', '/', 'CustomerController@viewCustomers'],
  ['GET', '/{id}', 'CustomerController@viewCustomerDetails'],
];
