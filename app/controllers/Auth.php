<?php

class Auth extends Publicontroller
{
    private $authmodel;
    private $reusablemodel;    
    private $no_auth_actions = ['login'];
    public function __construct()
    {
        if (!in_array($this->get_current_action(), $this->no_auth_actions)) {
            parent::__construct(); 
        }
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
    }

    private function get_current_action() {
        return strtolower(str_replace('Action', '', debug_backtrace()[1]['function']));
    }
    public function login()
    {
        if(isset($_SESSION['user_id'])){
            redirect('dashboard');
            exit();
        }
        $data = [
            'title' => 'Login',
            'user_id' => '',
            'password' => '',
            'user_id_err' => '',
            'password_err' => '',
            'stores' => $this->reusablemodel->get_stores(),
            'store' => '',
            'store_err' => '',
            'error' => null,
        ];
        $this->view('auth/login',$data);
    }

    public function login_act()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('login_msg','Invalid request made.',alert_type('warning'));
            redirect('auth/login');
            exit(0);
        }

        $data = [
            'title' => 'Login',
            'user_id' => isset($_POST['user_id']) && !empty(trim($_POST['user_id'])) ? trim($_POST['user_id']) : null,
            'password' => isset($_POST['password']) && !empty(trim($_POST['password'])) ? trim($_POST['password']) : null,
            'user_id_err' => '',
            'password_err' => '',
            'stores' => $this->reusablemodel->get_stores(),
            'store' => isset($_POST['store']) && !empty(trim($_POST['store'])) ? trim($_POST['store']) : null,
            'store_err' => '',
            'error' => null
        ];

        if(is_null($data['user_id'])){
            $data['user_id_err'] = 'Enter contact';
        }
        if(is_null($data['password'])){
            $data['password_err'] = 'Enter password';
        }
        if(is_null($data['store'])){
            $data['store_err'] = 'Select store';
        }

        if(!empty($data['user_id_err']) || !empty($data['password_err']) || !empty($data['store_err'])){
            $this->view('auth/login',$data);
            exit();
        }
        
        if(!$this->authmodel->contact_exists($data['user_id'])){
            $data['error'] = 'Invalid contact or password';
            $this->view('auth/login',$data);
            exit();
        }    

        $user = $this->authmodel->get_user($data['user_id']);

        if(!password_verify($data['password'],$user->password)){
            $data['error'] = 'Invalid user or password';
            $this->view('auth/login',$data);
            exit();
        }

        if((int)$user->role_id > 1 && ((int)$user->store_id !== (int)$data['store'])){
            $data['error'] = 'User not registered for selected store';
            $this->view('auth/login',$data);
            exit();
        }

        $this->create_user_session($user,$data['store']);
        
    }

    function create_user_session($user,$store)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->user_name;
        $_SESSION['role'] = $user->role_id;
        $_SESSION['store'] = $store;
        $_SESSION['is_main'] = false;
        $_SESSION['is_admin'] = $user->role_id < 3;
        redirect('dashboard');
    }
}