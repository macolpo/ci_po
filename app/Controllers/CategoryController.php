<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    public function category()
    {
        $session = session();

        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }

        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];

        return view('user/item-category', $userdata);
    }

    public function categoryData()
    {
        $model = new CategoryModel();

        $data = $model->findAll();

        return $this->response->setJSON($data);

    }

    public function categoryInsert()
    {
        $validationRules = [
            'category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The category name field is required.',
                ]
            ],
        ];

        if ($this->validate($validationRules)) {
            $data = [
                'category_name' => esc($this->request->getPost('category')),
            ];

            $existingCategory = new CategoryModel();

            $existingCategory = $existingCategory->where($data)->first();

            if ($existingCategory) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Category already exists']);
            } else {
                $insertCategory = new CategoryModel();
                $insertCategory->insert($data);

                return $this->response->setJSON(['status' => 'success', 'message' => 'Category added successfully']);
            }
        } else {
        return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }

    public function categoryUpdate()
    {
        $CategoryModel = new CategoryModel();

        $validationRules = [
            'categoryId' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The category id field is required',
                ]
            ],
            'editCategory' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The category name field is required.',
                ]
            ],
           
        ];
        $categoryId = esc($this->request->getPost('categoryId')); 
        $categoryName = esc($this->request->getPost('editCategory'));

        if ($this->validate($validationRules)) {
            $data = [
                'category_name' => $categoryName,
            ];

            $CategoryModel->set($data)->where('category_id', $categoryId)->update();

            return $this->response->setJSON(['status' => 'success', 'message' => 'Category added successfully']);
            
        } else {
        return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }

    
}
