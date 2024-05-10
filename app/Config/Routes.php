<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// register
$routes->get('register', 'RegisterController::registerPage');
$routes->post('register', 'RegisterController::registerPageAdd');

// login and logout dashboard
$routes->post('userlogin', 'LoginDashboardController::loginDashboard');
$routes->get('user/', 'LoginDashboardController::Dashboard');
$routes->get('logout', 'LoginDashboardController::Logout');

// ------------- employee ----------------
$routes->get('user/employee', 'EmployeeController::employee');
$routes->post('user/employee', 'EmployeeController::get_employee_data');
$routes->get('user/employee-add', 'EmployeeController::employeeAdd');
$routes->post('user/employee-insert', 'EmployeeController::employeeAddInsert');
$routes->get('user/employee-edit/(:num)', 'EmployeeController::employeeEditPage/$1');
$routes->post('user/employee-update/(:num)', 'EmployeeController::employeeUpdate/$1');
$routes->post('user/employee-delete', 'EmployeeController::employeeDelete/$1');
// ------------- end employee ----------------

// ------------- Inventory ----------------
$routes->get('user/item-list', 'InventoryController::inventory');
$routes->post('user/item-list', 'InventoryController::inventoryData');
$routes->get('user/item-add', 'InventoryController::inventoryAdd');
$routes->post('user/item-add', 'InventoryController::inventoryAddInsert');
$routes->get('user/item-edit/(:num)', 'InventoryController::inventoryEditPage/$1');
$routes->post('user/item-update/(:num)', 'InventoryController::inventoryUpdate/$1');
$routes->post('user/item-delete', 'InventoryController::inventoryDelete/$1');
// ------------- End Inventory ----------------

// ------------- Assign Inventory ----------------
$routes->get('user/assign-item', 'AssignController::assign');
$routes->post('user/assign-item', 'AssignController::assignData');
$routes->post('user/assign-item/(:num)', 'AssignController::assignItem/$1');
$routes->get('user/get-employees', 'AssignController::getEmployees');
// ------------- End Assign ----------------

// ------------- Return Inventory ----------------
$routes->get('user/return-item', 'ReturnController::return'); 
$routes->post('user/return-item', 'ReturnController::returnData'); 
$routes->post('user/return-item/(:num)', 'ReturnController::returnItem/$1');
$routes->get('user/get-employeesReturn', 'ReturnController::getEmployees');
// ------------- End Return ----------------

// ------------- Category ----------------
$routes->get('user/item-category', 'CategoryController::category');
$routes->post('user/item-category', 'CategoryController::categoryData');
$routes->post('user/item-category/insert', 'CategoryController::categoryInsert');
$routes->post('user/item-category/update', 'CategoryController::categoryUpdate');
// ------------- End Category ----------------




