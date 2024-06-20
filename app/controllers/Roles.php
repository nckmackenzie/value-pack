<?php
class Roles extends Controller
{
    private $authmodel;
    private $rolemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->rolemodel = $this->model('Role');
        check_rights($this->authmodel,'roles');
    }

    public function index()
    {
        $data = [
            'title' => 'Roles',
            'roles' => $this->rolemodel->get_roles()
        ];
        $this->view('roles/index',$data);
    }

    public function new()
    {
        $data =[
            'title' => 'New role',
            'role_name' => '',
            'id' => '',
            'is_edit' => false,
            'forms' => [],
            'role_name_err' => '',
            'error' => null
        ];

        foreach($this->rolemodel->get_forms() as $form){
            array_push($data['forms'],[
                'form_id' => $form->id,
                'form_name' => $form->form_name,
                'module' => $form->module,
                'checked' => false
            ]);
        }
        $this->view('roles/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('role_msg','Invalid request method.','alert-danger');
            redirect('roles/new');
            exit();
        }

        $role_name = filter_input(INPUT_POST,'role_name',FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);
        $states = isset($_POST['state']) ? $_POST['state'] : [];
        $modules = isset($_POST['module']) ? $_POST['module'] : [];
        $form_ids = isset($_POST['form_id']) ? $_POST['form_id'] : [];
        $form_names = isset($_POST['form_name']) ? $_POST['form_name'] : [];

        $data = [
            'title' => $is_edit ? 'Update role' : 'New role',
            'role_name' => !empty($role_name) ? trim($role_name) : null,
            'id' => $is_edit ? (int)$id : null,
            'is_edit' => $is_edit,
            'forms' => [],
            'role_name_err' => '',
            'error' => null
        ];

        if(count($form_ids) === 0){
            $data['error'] = 'Please select at least one form';
        }else{
            for ($i=0; $i < count($form_ids); $i++) { 
                array_push($data['forms'],[
                    'form_id' => $form_ids[$i],
                    'form_name' => $form_names[$i],
                    'module' => $modules[$i],
                    'checked' => $states[$i],
                ]);
            }        }
        if(is_null($data['role_name'])){
            $data['role_name_err'] = 'Please enter role name';
        }

      
        if(!empty($data['role_name_err']) || !is_null($data['error'])){
            $this->view('roles/new',$data);
            exit();
        }

        if($this->rolemodel->role_exists($data['role_name'],$data['id'])){
            $data['error'] = 'Role already exists';
            $this->view('roles/new',$data);
            exit();
        }

        if(!$this->rolemodel->create_update($data)){
            $data['error'] = 'Something went wrong while performing this task. Please try again.';
            $this->view('roles/new',$data);
            exit();
        }

        redirect('roles');
    }
    
    public function edit($id)
    {
        $role = $this->rolemodel->get_role($id);
        if(!$role){
            $this->not_found('/roles', 'The role you are trying to edit doesn\'t exist');
            exit();
        }

        $data = [
            'title' => 'Update role',
            'role_name' => strtoupper($role->role_name),
            'id' => $role->id,
            'is_edit' => true,
            'forms' => [],
            'role_name_err' => '',
            'error' => null
        ];

        foreach($this->rolemodel->get_forms(true, $role->id) as $form){
            array_push($data['forms'],[
                'form_id' => $form->id,
                'form_name' => $form->form_name,
                'module' => $form->module,
                'checked' => (bool)$form->checked,
            ]);
        }
        $this->view('roles/new',$data);
    }

    function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('role_msg','Invalid request method.','alert-danger');
            redirect('roles/new');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($id)){
            flash('role_msg','Unable to get selected role.', alert_type('error'));
            redirect('roles');
            exit();
        }

        if($this->rolemodel->is_referenced($id)){
            flash('role_msg','Cannot delete this role as its already assigned to other users.', alert_type('error'));
            redirect('roles');
            exit();
        }

        if(!$this->rolemodel->delete($id)){
            flash('role_msg','Something went wrong while performing this task. Please try again.', alert_type('error'));
            redirect('roles');
            exit();
        }

        flash('role_msg','Role deleted successfully.', alert_type('success'));
        redirect('roles');
    }
}