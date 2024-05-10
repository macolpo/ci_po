<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class RegisterController extends BaseController
{
    public function registerPage()
    {
      return view('register');
    }

    public function registerPageAdd()
    {
        $validationRules = [
            'firstname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The firstname field is required.',
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
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'The email field is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'is_unique' => 'This email address is already registered.',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'The password field is required.',
                    'min_length' => 'The password must be at least 8 characters long.',
                ]
            ],
            'cpassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'The confirm password field is required.',
                    'matches' => 'The confirm password must match the password field.',
                ]
            ],
        ];

        if ($this->validate($validationRules)) {
            $model = new UserModel();

            $firstname = esc($this->request->getVar('firstname'));
            $surname = esc($this->request->getVar('surname'));
            $address = esc($this->request->getVar('address'));
            $email = esc($this->request->getVar('email'));
            $password = esc($this->request->getVar('password'));


            $data = [
                'first_name' => $firstname,
                'surname' => $surname,
                'address' => $address,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            if ($model->where('email', $data['email'])->first()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists']);
            } else {
                $model->insert($data);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Register successfully']);
            }
        } else {
            return $this->response->setJSON(['status' => 'validation_error', 'errors' => $this->validator->getErrors()]);
        }
    }



}
