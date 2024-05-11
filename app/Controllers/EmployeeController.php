<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EmployeeModel;
use App\Models\UserModel;
use App\Models\EmployeeActLog;

class EmployeeController extends BaseController
{
    public function employee()
    {
        $UserModel = new UserModel();

        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        $data['user'] = $UserModel->findAll();

        return view('user/employee', $userdata + $data);
    }
    // employee data
    public function get_employee_data()
    {
        $startdate = esc($this->request->getPost('startdate'));
        $enddate = esc($this->request->getPost('enddate'));
        $user = esc($this->request->getPost('user'));

        $model = new EmployeeModel();
        $builder = $model->select(
                        'e.employee_id, e.emp_fname, e.emp_sname, e.emp_address, e.date_join,e.created_by,
                        i.inventory_name, u.first_name, u.surname')
                        ->from('employee_tbl AS e')
                        ->join('inventory_tbl AS i', 'i.inventory_id = e.inventory_id', 'left')
                        ->join('user_tbl AS u', 'u.user_id = e.created_by', 'left');

            if (!empty($startdate)) {
                $builder->where("DATE(e.date_join) >= ", $startdate);
            }
            
            if (!empty($enddate)) {
                $builder->where("DATE(e.date_join) <= ", $enddate);
            }
            
            if (!empty($user)) {
                $builder->where("e.created_by", $user);
            }

            $employees = $builder->groupBy('e.employee_id')->findAll();

            $data = [];
            foreach ($employees as $row) {
                $fullname = $row['first_name']. ' ' .$row['surname'];
                $formattedEmployee = [
                    'employee_id' => $row['employee_id'],
                    'name' => ucwords($row['emp_fname']).' '. $row['emp_sname'],
                    'emp_address' => $row['emp_address'],
                    'date_join' => date('F d, Y', strtotime($row['date_join'])),
                    'created_by' => $fullname,
                    'inventory_name' => $row['inventory_name'],
                ];
                $data[] = $formattedEmployee;
            }
        return $this->response->setJSON($data);
    }



    // employee add page
    public function employeeAdd()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
        return view('user/employee-add', $userdata);
    }
    
    // insert employee data
    public function employeeAddInsert()
    {
        $session = session();

        $user_id = $session->get('user_id');

        // Validation Rules
        $validationRules = [
            'first_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The first name field is required.',
                ]
            ],
            'surname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The surname field is required.',
                ]
            ],
            'address' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The address field is required.',
                ]
            ],
            'picture' => [ 
                'rules' => 'uploaded[picture]|max_size[picture,1024]|is_image[picture]', 
                'errors' => [
                    'uploaded' => 'The image field is required.',
                    'max_size' => 'The image size should not exceed 1MB.',
                    'is_image' => 'Please upload a valid image file (JPEG, PNG).',
                ]
            ]
        ];

        if ($this->validate($validationRules)) {

            $picture = $this->request->getFile('picture');

            if ($picture->isValid() && !$picture->hasMoved()) {
                $imageName = $picture->getRandomName();
                
                $picture->move(ROOTPATH . 'public/images', $imageName);

                $first_name = esc($this->request->getPost('first_name'));
                $middle_name = esc($this->request->getPost('middle_name'));
                $surname = esc($this->request->getPost('surname'));
                
                $fullname = ucwords($first_name.' '. substr($middle_name,0,1).'. '. $surname);


                $data = [
                    'emp_fname' => $first_name,
                    'emp_mname' => $middle_name,
                    'emp_sname' => $surname,
                    'emp_address' => esc($this->request->getPost('address')),
                    'created_by' => esc($user_id),
                    'emp_image' =>  esc($imageName), 
                ];

                $existingEmployee = new EmployeeModel();

                $existingEmployee = $existingEmployee->where([
                    'emp_fname' => $data['emp_fname'],
                    'emp_mname' => $data['emp_mname'],
                    'emp_sname' => $data['emp_sname']
                ])->first();

                if ($existingEmployee) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Employee already exists']);
                } else {
                    $insertEmployee = new EmployeeModel();
                    $insertEmployee->insert($data);

                    $emp_id = $insertEmployee->insertID();

                    $log = [
                        'employee_id' => $emp_id,
                        'employee_name' => ucwords($fullname),
                        'created_by' => esc($user_id),
                    ];

                    $EmployeeActLog = new EmployeeActLog();
                    $EmployeeActLog->insert($log);

                    return $this->response->setJSON(['status' => 'success', 'message' => 'Employee added successfully']);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload image']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }


    public function employeeView($id) {
        $editEmployee = new EmployeeModel();
    
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }
        
        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
    
        $data['employee'] = $editEmployee->where('employee_id', $id)->first();
        
        return view('user/employee-view', $data + $userdata);
    }

    // employee edit page
    public function employeeEditPage($id) {
        $editEmployee = new EmployeeModel();
    
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }
        
        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
    
        $data['employee'] = $editEmployee->where('employee_id', $id)->first();
        
        return view('user/employee-edit', $data + $userdata);
    }
    
    
    // employee update
    public function employeeUpdate($id) {
        $employeeModel = new EmployeeModel();

        $session = session();

        $user_id = $session->get('user_id');
    
        $validationRules = [
            'first_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The first name field is required.',
                ]
            ],
            'surname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The surname field is required.',
                ]
            ],
            'address' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The address field is required.',
                ]
            ],
        ];

        if ($this->validate($validationRules)) {

            
            $first_name = esc($this->request->getPost('first_name'));
            $middle_name = esc($this->request->getPost('middle_name'));
            $surname = esc($this->request->getPost('surname'));
            
            $fullname = ucwords($first_name.' '. substr($middle_name,0,1).'. '. $surname);

            $data = [
                'emp_fname' => $first_name,
                'emp_mname' => $middle_name,
                'emp_sname' => $surname,
                'emp_address' => esc($this->request->getPost('address')),
            ];
    
            $picture = $this->request->getFile('picture');
    
            if ($picture->isValid() && !$picture->hasMoved()) {
                $imageName = $picture->getRandomName();
                $picture->move(ROOTPATH . 'public/images', $imageName);
                $data['emp_image'] = $imageName;
            }
    
            $updated = $employeeModel->set($data)->where('employee_id', $id)->update();
    
            if ($updated) {
                $log = [
                    'employee_id' => $id,
                    'employee_name' => $fullname,
                    'created_by' => esc($user_id),
                    'status' => '1',
                ];
                $EmployeeActLog = new EmployeeActLog();
                $EmployeeActLog->insert($log);

                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update employee.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }

    // delete
    public function employeeDelete($id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        $employeeModel = new EmployeeModel();
        $EmployeeActLog = new EmployeeActLog();

        $employee = $employeeModel->find($id);

        if ($employee) {
            $fullname = ucwords($employee['emp_fname'] . ' ' . substr($employee['emp_mname'], 0, 1) . '. ' . $employee['emp_sname']);
            $log = [
                'employee_id' => $id,
                'employee_name' => $fullname,
                'created_by' => esc($user_id),
                'status' => '2',
            ];
            $EmployeeActLog->insert($log);

            $result = $employeeModel->where('employee_id', $id)->delete();
        } 
        return $this->response->setJSON(['success' => $result]);
    }
    
}
