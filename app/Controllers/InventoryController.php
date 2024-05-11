<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;
use App\Models\CategoryModel;

class InventoryController extends BaseController
{
    public function inventory()
    {
        $category = new CategoryModel();
    
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }
        
        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
    
        $data['categories'] = $category->findAll();
        
        return view('user/item-list', $data + $userdata);
    }


    public function inventoryData()
    {
        $category = $this->request->getPost('category');

        $model = new InventoryModel();
        $builder = $model->select('i.inventory_id, i.inventory_name, i.inventory_sn, i.inventory_pn, i.status, c.category_name')
            ->from('inventory_tbl AS i')
            ->join('category_tbl AS c', 'c.category_id = i.category_id', 'left')->groupBy('i.inventory_id'); 

        if (!empty($category)) {
            $builder->where('i.category_id', $category);
        }

        $data = $builder->findAll();

        return $this->response->setJSON($data);
    }

    public function inventoryAdd()
    {
        $model = new CategoryModel();

        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        $data['categories'] = $model->findAll();
      
        return view('user/item-add', $userdata + $data);
    }

    public function inventoryAddInsert()
    {
        // Validation Rules
        $validationRules = [
            'inventory_name' => 'required',
            'category' => 'required',
            'product_no' => 'required',
            'serial_no' => 'required',
        ];

        $validationMessages = [
            'inventory_name' => [
                'required' => 'The inventory name field is required.',
            ],
            'category' => [
                'required' => 'Please select a category.',
            ],
            'product_no' => [
                'required' => 'The product number field is required.',
            ],
            'serial_no' => [
                'required' => 'The serial number field is required.',
            ],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }

        $data = [
            'inventory_name' => esc($this->request->getPost('inventory_name')),
            'category_id' => esc($this->request->getPost('category')),
            'inventory_pn' => esc($this->request->getPost('product_no')),
            'inventory_sn' => esc($this->request->getPost('serial_no')),
        ];

        $inventoryModel = new InventoryModel();
        
        // Check if inventory already exists
        $existingInventory = $inventoryModel->where($data)->first();
        if ($existingInventory) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Inventory already exists']);
        }

        // Insert inventory
        $inventoryModel->insert($data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Inventory added successfully']);
    }


    public function inventoryEditPage($id) {
        $InventoryModel = new InventoryModel();
        $CategoryModel = new CategoryModel();
    
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }
        
        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
    
        $data['inventory'] = $InventoryModel->where('inventory_id', $id)->first();

        $category['categories'] = $CategoryModel->findAll();

        
        return view('user/item-edit', $data + $category + $userdata);
    }

    public function inventoryUpdate($id) {
        $inventoryModel = new inventoryModel();
    
        $validationRules = [
            'inventory_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The inventory name field is required.',
                ]
            ],
            'category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a category.',
                ]
            ],
            
            'product_no' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The product number field is required.',
                ]
            ],
            'serial_no' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The serial number field is required.',
                ]
            ],
        ];


        if ($this->validate($validationRules)) {
            $data = [
                'inventory_name' => $this->request->getPost('inventory_name'), // No need to escape here
                'category_id' => $this->request->getPost('category'), // No need to escape here
                'inventory_pn' => $this->request->getPost('product_no'), // No need to escape here
                'inventory_sn' => $this->request->getPost('serial_no'), // No need to escape here
            ];
            $updated = $inventoryModel->set($data)->where('inventory_id', $id)->update();
            if ($updated) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update inventory.']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }

    public function inventoryDelete()
    {
        $inventory_id = $this->request->getPost('id');

        $inventoryModel = new inventoryModel();

        $result = $inventoryModel->delete($inventory_id);

        return $this->response->setJSON(['success' => $result]);
    }
}
