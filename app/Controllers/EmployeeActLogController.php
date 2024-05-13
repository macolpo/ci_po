<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeActLog;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
class EmployeeActLogController extends BaseController
{
    public function activity()
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

        return view('user/employee-activity-log', $userdata + $data);
    }

    public function activityData()
    {
        $EmployeeActLog = new EmployeeActLog();

        $startdate = esc($this->request->getPost('startdate'));
        $enddate = esc($this->request->getPost('enddate'));
        $user = esc($this->request->getPost('user'));

        $EmployeeActLog->select('e.employee_id, e.employee_name, e.status, e.created_by, e.created_at, u.first_name, u.surname')
            ->from('employee_act_log AS e')
            ->join('user_tbl AS u', 'u.user_id = e.created_by', 'left');

        if (!empty($startdate)) {
            $EmployeeActLog->where("DATE(e.created_at) >= ", $startdate);
        }

        if (!empty($enddate)) {
            $EmployeeActLog->where("DATE(e.created_at) <= ", $enddate);
        }

        if (!empty($user)) {
            $EmployeeActLog->where("e.created_by", $user);
        }

        $activity = $EmployeeActLog->groupBy('e.emp_act_id')->findAll();

        $data = [];
        foreach($activity as $row) {
            $user = $row['first_name'].' '. $row['surname'];
            $activityLog = [
                'employee_id' => $row['employee_id'],
                'employee_name' => $row['employee_name'],
                'status' => $row['status'],
                'created_by' => $user,
                'created_at' => date('M d, Y H:i:s', strtotime($row['created_at'])),
            ];
            $data[] = $activityLog;
        }
        
        return $this->response->setJSON($data);
    }

    
}
