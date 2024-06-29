<?php

class Expenseaccounts extends Controller
{
    private $expenseaccountmodel;
    public function __construct()
    {
        parent::__construct();
        $this->expenseaccountmodel = $this->model('Expenseaccount');
    }

    public function index()
    {
        $data = [
            'title' => 'Expense Accounts',
            'accounts' => $this->expenseaccountmodel->get_all(),
        ];
        $this->view('expenseaccounts/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add new expense account',
            'id' => '',
            'is_edit' => false,
            'account_name' => '',
            'active' => true,
            'account_name_err' => '',
            'error' => null
        ];
        $this->view('expenseaccounts/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('expenseaccount_msg','Invalid request method', alert_type('error'));
            redirect('expenseaccounts/new');
            exit();
        }

        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);
        $active = filter_input(INPUT_POST, 'active', FILTER_VALIDATE_BOOLEAN);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS); 
        $account_name = filter_input(INPUT_POST, 'account_name', FILTER_SANITIZE_SPECIAL_CHARS); 

        $data = [
            'title' => $is_edit ? 'Update expense account' : 'Add new expense account',
            'id' => !empty($id) ? $id : '',
            'is_edit' => $is_edit,
            'account_name' => !empty($account_name) ? trim($account_name) : null,
            'active' => $is_edit ? $active ?? false : true,
            'account_name_err' => '',
            'error' => null
        ];

        if(is_null($data['account_name'])){
            $data['account_name_err'] = 'Enter account name';
        }

        if(!empty($data['account_name_err'])){
            $this->view('expenseaccounts/new',$data);
            exit();
        }

        if($this->expenseaccountmodel->account_exists($data)){
            $data['error'] = 'Account already exists';
            $this->view('expenseaccounts/new',$data);
            exit();
        }

        if(!$this->expenseaccountmodel->create_update($data)){
            $data['error'] = 'Something went wrong while creating/updating expenseaccount';
            $this->view('expenseaccounts/new',$data);
            exit();
        }

        redirect('expenseaccounts');
    }

    public function edit($id)
    {
        $expenseaccount = $this->expenseaccountmodel->get_account($id);
        $data = [
            'title' => 'Edit expense account details',
            'id' => $expenseaccount->id,
            'is_edit' => true,
            'account_name' => strtoupper($expenseaccount->account_name),
            'active' => (bool)$expenseaccount->active,
            'account_name_err' => '',
            'error' => null
        ];
        $this->view('expenseaccounts/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('expenseaccount_msg','Invalid request method', alert_type('error'));
            redirect('expenseaccounts/new');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($id) || is_null($id)){
            flash('expenseaccount_msg','Unable to get selected id.', alert_type('error'));
            redirect('expenseaccounts');
            exit();
        }

        if($this->expenseaccountmodel->is_referenced($id)){
            flash('expenseaccount_msg','Unable to delete account as its referenced elsewhere.', alert_type('error'));
            redirect('expenseaccounts');
            exit();
        }

        if(!$this->expenseaccountmodel->delete($id)){
            flash('expenseaccount_msg','Something went wrong while deleting expenseaccount.', alert_type('error'));
            redirect('expenseaccounts');
            exit();
        }
        flash('expenseaccount_msg','Account deleted successfully', alert_type('success'));
        redirect('expenseaccounts'); 
    }
}