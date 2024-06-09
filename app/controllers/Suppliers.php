<?php
 class Suppliers extends Controller
 {
    private $suppliermodel;
    public function __construct()
    {
        parent::__construct();
        $this->suppliermodel = $this->model('Supplier');
    }

    public function index()
    {
        $data = [
            'title' => 'Suppliers',
            'suppliers' => $this->suppliermodel->get_all(),
        ];
        $this->view('suppliers/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add new supplier',
            'id' => '',
            'is_edit' => false,
            'supplier_name' => '',
            'contact' => '',
            'email' => '',
            'contact_person' => '',
            'active' => true,
            'supplier_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];
        $this->view('suppliers/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('supplier_msg','Invalid request method', alert_type('error'));
            redirect('suppliers/new');
            exit();
        }

        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);
        $active = filter_input(INPUT_POST, 'active', FILTER_VALIDATE_BOOLEAN);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS); 
        $supplier_name = filter_input(INPUT_POST, 'supplier_name', FILTER_SANITIZE_SPECIAL_CHARS); 
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_SPECIAL_CHARS); 
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); 
        $contact_person = filter_input(INPUT_POST, 'contact_person', FILTER_SANITIZE_SPECIAL_CHARS); 

        $data = [
            'title' => $is_edit ? 'Update supplier' : 'Add new supplier',
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'supplier_name' => !empty($supplier_name) ? trim($supplier_name) : null,
            'contact' => !empty($contact) ? trim($contact) : null,
            'email' => !empty($email) ? trim($email) : null,
            'contact_person' => !empty($contact_person) ? trim($contact_person) : null,
            'active' => $is_edit ? $active : true,
            'supplier_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];

        if(is_null($data['supplier_name'])){
            $data['supplier_name_err'] = 'Enter supplier name';
        }

        if(is_null($data['contact'])){
            $data['contact_err'] = 'Enter supplier contact.';
        }

        if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
            $data['email_err'] = 'Enter a valid email address.';
        }

        if(!empty($data['supplier_name_err']) || !empty($data['contact_err']) || !empty($data['email_err'])){
            $this->view('suppliers/new',$data);
            exit();
        }

        if($this->suppliermodel->check_exists('supplier_name', $data['supplier_name'], $data['id'])){
            $data['error'] = 'supplier already exists';
            $this->view('suppliers/new',$data);
            exit();
        }

        if($this->suppliermodel->check_exists('contact', $data['contact'], $data['id'])){
            $data['error'] = 'supplier contact already exists';
            $this->view('suppliers/new',$data);
            exit();
        }

        if(!$this->suppliermodel->create_update($data)){
            $data['error'] = 'Something went wrong while creating/updating supplier';
            $this->view('suppliers/new',$data);
            exit();
        }

        redirect('suppliers');
    }

    public function edit($id)
    {
        $supplier = $this->suppliermodel->get_supplier($id);
        if(!$supplier){
            $this->not_found('/suppliers', 'The supplier you are trying to edit doesn\'t exist');
            exit();
        }
        $data = [
            'title' => 'Edit supplier details',
            'id' => $supplier->id,
            'is_edit' => true,
            'supplier_name' => strtoupper($supplier->supplier_name),
            'contact' => $supplier->contact,
            'email' => strtolower($supplier->email),
            'contact_person' => strtolower($supplier->contact_person),
            'active' => (bool)$supplier->active,
            'supplier_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];
        $this->view('suppliers/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('supplier_msg','Invalid request method', alert_type('error'));
            redirect('suppliers/new');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($id) || is_null($id)){
            flash('supplier_msg','Unable to get selected id.', alert_type('error'));
            redirect('suppliers');
            exit();
        }

        if($this->suppliermodel->is_referenced($id)){
            flash('supplier_msg','Unable to delete supplier as its referenced elsewhere.', alert_type('error'));
            redirect('suppliers');
            exit();
        }

        if(!$this->suppliermodel->delete($id)){
            flash('supplier_msg','Something went wrong while deleting supplier.', alert_type('error'));
            redirect('suppliers');
            exit();
        }
        flash('supplier_msg','supplier deleted successfully', alert_type('success'));
        redirect('suppliers'); 
    }
 }