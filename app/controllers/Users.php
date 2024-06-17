<?php
class Users extends Controller
{
    private $authmodel;
    private $usermodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->usermodel = $this->model('User');
        $this->reusablemodel = $this->model('Reusable');
        check_rights($this->authmodel,'users');
    }

    public function index()
    {
        $data = [
            'title' => 'Users',
            'users' => $this->usermodel->get_users()
        ];
        $this->view('users/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create user',
            'stores' => $this->reusablemodel->get_stores(),
            'roles' => $this->usermodel->get_roles(),
            'id' => '',
            'is_edit' => false,
            'user_name' => '',
            'password' => '',
            'confirm_password' => '',
            'contact' => '',
            'role' => '',
            'active' => true,
            'stores_allowed' => [],
            'user_name_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'role_err' => '',
            'contact_err' => '',
            'store_err' => '',
            'error' => null
        ];
        $this->view('users/new', $data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('user_msg','Invalid request method.', alert_type('error'));
            redirect('users/new');
            exit();
        }

        $user_name = filter_input(INPUT_POST,'user_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $contact = filter_input(INPUT_POST,'contact', FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);
        $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_input(INPUT_POST,'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS);
        $role = filter_input(INPUT_POST,'role', FILTER_SANITIZE_NUMBER_INT);
        $active = filter_input(INPUT_POST,'active', FILTER_VALIDATE_BOOLEAN);        

        $data = [
            'title' => $is_edit ? 'Update user' : 'Create user',
            'stores' => $this->reusablemodel->get_stores(),
            'roles' => $this->usermodel->get_roles(),
            'id' => $is_edit ? $id : cuid(true),
            'is_edit' => $is_edit,
            'user_name' => !empty($user_name) ? trim($user_name) : '',
            'password' => !empty($password) ? trim($password) : '',
            'confirm_password' => !empty($confirm_password) ? trim($confirm_password) : '',
            'contact' => !empty($contact) ? trim($contact) : '',
            'role' => !empty($role) ? (int)$role : '',
            'stores_allowed' => isset($_POST['store'])  ? $_POST['store'] : [],
            'active' => !$is_edit ? true : $active ?? false,
            'user_name_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'role_err' => '',
            'contact_err' => '',
            'store_err' => '',
            'error' => null
        ];

        if(empty($data['user_name'])){
            $data['user_name_err'] = 'Enter user name';
        }
        if(empty($data['password']) && $is_edit === false){
            $data['password_err'] = 'Enter password';
        }
        if(empty($data['confirm_password']) && $is_edit === false){
            $data['confirm_password_err'] = 'Confirm password';
        }
        if(empty($data['contact'])){
            $data['contact_err'] = 'Enter phone number';
        }
        if(empty($data['role'])){
            $data['role_err'] = 'Select user role';
        }
        if(count($data['stores_allowed']) === 0){
            $data['store_err'] = 'Select at least one store';
        }

        if(!empty($data['password']) && !empty($data['confirm_password']) && ($data['password'] !== $data['confirm_password'])){
            $data['confirm_password_err'] = 'Password not matched';
        }

        if(!empty($data['user_name_err']) || !empty($data['password_err']) || !empty($data['confirm_password_err']) 
            || !empty($data['role_err']) || !empty($data['contact_err']) || !empty($data['store_err'])){
                $this->view('users/new', $data);
                exit();
        }

        if($this->usermodel->contact_exists($data['contact'],$data['id'])){
            $data['error'] = 'Contact already exists.';
            $this->view('users/new', $data);
            exit();
        }

        if(!$this->usermodel->create_update($data)){
            $data['error'] = 'Something went wrong. Please try again later.';
            $this->view('users/new', $data);
            exit();
        }

        redirect('users');
    }

    public function edit($id)
    {
        $user = $this->usermodel->get_user($id);
        if(!$user){
            $this->not_found('/users', 'The user you are trying to edit doesn\'t exist');
            exit();
        }
        $data = [
            'title' => 'Update user',
            'stores' => $this->reusablemodel->get_stores(),
            'roles' => $this->usermodel->get_roles(),
            'id' => $user->id,
            'is_edit' => true,
            'user_name' => strtoupper($user->user_name),
            'password' => '',
            'confirm_password' => '',
            'contact' => $user->contact,
            'role' => $user->role_id,
            'active' => (bool)$user->active,
            'stores_allowed' => array_column($this->authmodel->get_user_stores($user->id), 'store_id'),
            'user_name_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'role_err' => '',
            'contact_err' => '',
            'store_err' => '',
            'error' => null
        ];
        // $user_stores = array_column($this->authmodel->get_user_stores($user->id), 'store_id');
        $this->view('users/new', $data);
    }
}