<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\EmployeeModel;

class ReturnController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }
    

    public function return()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        return view('user/return-item', $userdata);
    }

    public function getEmployees()
    {
        $model = new EmployeeModel();
        $employees = $model->where('inventory_id', '1')->findAll();
        
        return $this->response->setJSON($employees);
    }

    public function returnData()
    {

        $model = new InventoryModel();
        $builder = $model->select('i.inventory_id, i.inventory_name, i.inventory_sn, i.inventory_pn, i.status, c.category_name, e.employee_id, e.emp_fname, e.emp_sname')
            ->from('inventory_tbl AS i')
            ->join('category_tbl AS c', 'c.category_id = i.category_id', 'left')
            ->join('employee_tbl AS e', 'e.inventory_id = i.inventory_id', 'left') 
            ->where('i.status', 1); 
        
        $builder->groupBy('i.inventory_id'); 
        
        $return = $builder->findAll(); 
        
        $data = [];
        foreach ($return as $row) {
            $returnItem = [
                'employee_id' => $row['employee_id'],
                'name' => ucwords($row['emp_fname'] . ' ' . $row['emp_sname']),
                'inventory_id' => $row['inventory_id'],
                'inventory_name' => $row['inventory_name'],
                'inventory_sn' => $row['inventory_sn'],
                'inventory_pn' => $row['inventory_pn'],
                'status' => $row['status'],
                'category_name' => $row['category_name'],
            ];
            $data[] = $returnItem;
        }
        return $this->response->setJSON($data);
        
    }

    public function returnItem($id) {
        $inventoryModel = new InventoryModel();
        $employeeModel = new EmployeeModel();

            $data = [
                'status' => '0',
            ];
    
            $dataEmp = [
                'inventory_id' => '0',
            ];
    
            $updated = $inventoryModel->set($data)->where('inventory_id', $id)->update();
            $updatedEmp = $employeeModel->set($dataEmp)->where('inventory_id', $id)->update();
    
            if ($updated && $updatedEmp) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update inventory or return employee.']);
            }
        } 
    }
    
