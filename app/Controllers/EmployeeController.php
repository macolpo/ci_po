<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EmployeeModel;

class EmployeeController extends BaseController
{
    public function employee()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        return view('user/employee', $userdata);
    }
    // employee data
    public function get_employee_data()
    {
        $month = esc($this->request->getPost('month'));

        $model = new EmployeeModel();
        $builder = $model->select(
                        'e.employee_id, e.emp_fname, e.emp_sname, e.emp_address, e.date_join,e.created_by,
                        i.inventory_name, u.first_name, u.surname')
                        ->from('employee_tbl AS e')
                        ->join('inventory_tbl AS i', 'i.inventory_id = e.inventory_id', 'left')
                        ->join('user_tbl AS u', 'u.user_id = e.created_by', 'left');

            if (!empty($month)) {
                $year = date('Y', strtotime($month));
                $month_number = date('m', strtotime($month));
                $builder->where("YEAR(e.date_join)", $year);
                $builder->where("MONTH(e.date_join)", $month_number);
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
            'middle_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The middle name field is required.',
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

                $data = [
                    'emp_fname' => esc($this->request->getPost('first_name')),
                    'emp_mname' => esc($this->request->getPost('middle_name')),
                    'emp_sname' => esc($this->request->getPost('surname')),
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

                    return $this->response->setJSON(['status' => 'success', 'message' => 'Employee added successfully']);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload image']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
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
    
        $validationRules = [
            'first_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The first name field is required.',
                ]
            ],
            'middle_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The middle name field is required.',
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
            $data = [
                'emp_fname' => esc($this->request->getPost('first_name')),
                'emp_mname' => esc($this->request->getPost('middle_name')),
                'emp_sname' => esc($this->request->getPost('surname')),
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
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update employee.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }

    // delete
    public function employeeDelete()
    {
        $employee_id = $this->request->getPost('id');

        $employeeModel = new EmployeeModel();

        // Perform deletion
        $result = $employeeModel->where('employee_id', $employee_id)->delete();

        return $this->response->setJSON(['success' => $result]);
    }

    

    
}
