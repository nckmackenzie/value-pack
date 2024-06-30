<?php 
class Expenses extends Controller
{
    private $reusablemodel;
    private $authmodel;
    private $expensemodel;

    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->expensemodel = $this->model('Expense');
        check_rights($this->authmodel,'expenses');
    }

    public function index()
    {
        $data = [
            'title' => 'Expenses',
            'expenses' => $this->expensemodel->get_expenses()
        ];
        $this->view('expenses/index',$data);
    }
    
    public function new()
    {
        $data = [
            'title' => 'Add Expense',
            'accounts' => $this->reusablemodel->get_expense_accounts(),
            'id' => '',
            'is_edit' => false,
            'amount' => '',
            'remarks' => '',
            'expense_date' => date('Y-m-d'),
            'account_id' => '',
            'amount_err' => '',
            'account_id_err' => '',
            'expense_date_err' => '',
            'errors' => []
        ];
        $this->view('expenses/new',$data);
    }
    
    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('expense_msg','Invalid request method', alert_type('error'));
            redirect('expenses/new');
            exit();
        }

        $expense_date = filter_input(INPUT_POST, 'expense_date', FILTER_SANITIZE_SPECIAL_CHARS);
        $account_id = filter_input(INPUT_POST, 'account_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $remarks = filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => $is_edit ? 'Update expense' : 'Add new expense',
            'accounts' => $this->reusablemodel->get_expense_accounts(),
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'amount' => !empty($amount) ? $amount : '',
            'remarks' => !empty($remarks) ? $remarks : null,
            'expense_date' => !empty($expense_date) ? date('Y-m-d',strtotime($expense_date)) : '',
            'account_id' => !empty($account_id) ? $account_id : '',
            'amount_err' => '',
            'account_id_err' => '',
            'expense_date_err' => '',
            'errors' => []
        ];

        if(empty($data['expense_date'])){
            $data['expense_date_err'] = 'Select expense date';
        }
        if(empty($data['amount'])){
            $data['amount_err'] = 'Enter amount';
        }
        if(empty($data['account_id'])){
            $data['account_id_err'] = 'Select expense account';
        }

        if(!empty($data['account_id_err']) || !empty($data['expense_date_err']) || !empty($data['amount_err'])){
            $this->view('expenses/new', $data);
            exit();
        }

        if(!$this->expensemodel->create_update($data)){
            $data['errors'][] = 'Something went wrong while performing this action. Contact support';
            $this->view('expenses/new', $data);
            exit();
        }

        redirect('expenses');
    }
}