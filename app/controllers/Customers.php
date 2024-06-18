<?php 
class Customers extends Controller
{
    private $customermodel;
    private $authmodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->customermodel = $this->model('Customer');
        check_rights($this->authmodel,'customers');
    }

    public function index()
    {
        $data = [
            'title' => 'Customers',
            'customers' => $this->customermodel->get_all(),
        ];
        $this->view('customers/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add new customer',
            'id' => '',
            'is_edit' => false,
            'customer_name' => '',
            'contact' => '',
            'email' => '',
            'pin' => '',
            'active' => true,
            'customer_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];
        $this->view('customers/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('customer_msg','Invalid request method', alert_type('error'));
            redirect('customers/new');
            exit();
        }

        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);
        $active = filter_input(INPUT_POST, 'active', FILTER_VALIDATE_BOOLEAN);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS); 
        $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_SPECIAL_CHARS); 
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_SPECIAL_CHARS); 
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); 
        $pin = filter_input(INPUT_POST, 'pin', FILTER_SANITIZE_SPECIAL_CHARS); 

        $data = [
            'title' => $is_edit ? 'Update customer' : 'Add new customer',
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'customer_name' => !empty($customer_name) ? trim($customer_name) : null,
            'contact' => !empty($contact) ? trim($contact) : null,
            'email' => !empty($email) ? trim($email) : null,
            'pin' => !empty($pin) ? trim($pin) : null,
            'active' => $is_edit ? $active ?? false : true,
            'customer_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];

        if(is_null($data['customer_name'])){
            $data['customer_name_err'] = 'Enter customer name';
        }

        if(is_null($data['contact'])){
            $data['contact_err'] = 'Enter customer contact.';
        }

        if(!is_null($data['email']) && !filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
            $data['email_err'] = 'Enter a valid email address.';
        }

        if(!empty($data['customer_name_err']) || !empty($data['contact_err']) || !empty($data['email_err'])){
            $this->view('customers/new',$data);
            exit();
        }

        if($this->customermodel->check_exists('customer_name', $data['customer_name'], $data['id'])){
            $data['error'] = 'customer already exists';
            $this->view('customers/new',$data);
            exit();
        }

        if($this->customermodel->check_exists('contact', $data['contact'], $data['id'])){
            $data['error'] = 'customer contact already exists';
            $this->view('customers/new',$data);
            exit();
        }

        if($this->customermodel->check_exists('pin', $data['pin'], $data['id'])){
            $data['error'] = 'customer pin already exists';
            $this->view('customers/new',$data);
            exit();
        }

        if(!$this->customermodel->create_update($data)){
            $data['error'] = 'Something went wrong while creating/updating customer';
            $this->view('customers/new',$data);
            exit();
        }

        redirect('customers');
    }

    public function edit($id)
    {
        $customer = $this->customermodel->get_customer($id);
        if(!$customer){
            $this->not_found('/customers', 'The customer you are trying to edit doesn\'t exist');
            exit();
        }
        $data = [
            'title' => 'Edit customer details',
            'id' => $customer->id,
            'is_edit' => true,
            'customer_name' => strtoupper($customer->customer_name),
            'contact' => $customer->contact,
            'email' => strtolower($customer->email),
            'pin' => strtolower($customer->pin),
            'active' => (bool)$customer->active,
            'customer_name_err' => '',
            'contact_err' => '',
            'email_err' => '',
            'error' => null
        ];
        $this->view('customers/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('customer_msg','Invalid request method', alert_type('error'));
            redirect('customers/new');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($id) || is_null($id)){
            flash('customer_msg','Unable to get selected id.', alert_type('error'));
            redirect('customers');
            exit();
        }

        if($this->customermodel->is_referenced($id)){
            flash('customer_msg','Unable to delete customer as its referenced elsewhere.', alert_type('error'));
            redirect('customers');
            exit();
        }

        if(!$this->customermodel->delete($id)){
            flash('customer_msg','Something went wrong while deleting customer.', alert_type('error'));
            redirect('customers');
            exit();
        }
        flash('customer_msg','Customer deleted successfully!', alert_type('success'));
        redirect('customers'); 
    }

}