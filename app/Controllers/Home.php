<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
      $session = session();

      if ($session->has('user_id')) {
          return redirect()->to('user/');
      }
      return view('index');
    }

    
    
}
