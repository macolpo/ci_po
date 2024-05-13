<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\EmployeeModel;

class AssignController extends BaseController
{
    protected $session;

    public function __construct(){
        $this->session = session();
    }

    public function assign()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        return view('user/assign-item', $userdata);
    }

    public function getEmployees()
    {
        $model = new EmployeeModel();
        $employees = $model->where('inventory_id', '0')->findAll();
        
        return $this->response->setJSON($employees);
    }

    public function assignData()
    {

        $model = new InventoryModel();
        $builder = $model->select('i.inventory_id, i.inventory_name, i.inventory_sn, i.inventory_pn, i.status, c.category_name')
            ->from('inventory_tbl AS i')
            ->join('category_tbl AS c', 'c.category_id = i.category_id', 'left')
            ->where('i.status = 0')
            ->groupBy('i.inventory_id')
            ->orderBy('i.created_at', 'ASC'); 
    
        $assign = $builder->findAll(); 

        $data = [];
        foreach ($assign as $row) {
            $assignItem = [
                'inventory_id' => $row['inventory_id'],
                'inventory_name' => $row['inventory_name'],
                'inventory_sn' => $row['inventory_sn'],
                'inventory_pn' => $row['inventory_pn'],
                'status' => $row['status'],
                'category_name' => $row['category_name'],
            ];
            $data[] = $assignItem;
        }
        return $this->response->setJSON($data);
    }

    public function assignItem($id) {
        $inventoryModel = new inventoryModel();
        $employeeModel = new employeeModel();

        $empId = $this->request->getPost('employeeId'); 

        $employeeExists = $employeeModel->find($empId);

        if (!$employeeExists) {
            return $this->response->setJSON(['status' => 'noid', 'message' => 'The specified employee ID does not exist.']);
        }

        $validationRules = [
            'employeeId' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The assign to field is required.',
                ]
            ],
        ];
    
        if ($this->validate($validationRules)) {
    
            $data = [
                'status' => '1',
            ];
            $dataEmp = [
                'inventory_id' => $id,
            ];

            $updated = $inventoryModel->set($data)->where('inventory_id', $id)->update();
            $updatedEmp = $employeeModel->set($dataEmp)->where('employee_id', $empId)->update();
    
            if ($updated && $updatedEmp) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update inventory or assign employee.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }
    
    
    

}
