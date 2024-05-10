<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginDashboardController extends BaseController
{
    public function loginDashboard()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Basic validation
        if (empty($email) || empty($password)) {
            $session->setFlashdata('msg', 'Please provide both email and password.');
            return redirect()->to('/');
        }

        // Fetch user data from the database
        $data = $model->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                $session_data = [
                    'user_id' => $data['user_id'],
                    'first_name' => $data['first_name'],
                ];
                $session->set($session_data);
                // Redirect to the dashboard
                return redirect()->to('user/');
            } else {
                // Invalid password
                $session->setFlashdata('msg', 'Invalid email or password.');
                return redirect()->to('/');
            }
        } else {
            // User not found
            $session->setFlashdata('msg', 'Invalid email or password.');
            return redirect()->to('/');
        }
    }
    public function Dashboard()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/');
        }
        $userdata = [
            'user_id' => $session->get('user_id'),
            'first_name' => $session->get('first_name')
        ];
    
        // Load the dashboard view
        return view('user/index',$userdata);
    }

    public function Logout()
    {
        $session = session();
        $session->destroy();
        
        // Redirect to home page
        return redirect()->to('/');
    }

}
