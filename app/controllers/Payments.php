<?php

class Payments extends Controller
{
    private $authmodel;
    private $reusablemodel;
    private $paymentmodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->paymentmodel = $this->model('Payment');
        check_rights($this->authmodel,'payments');
    }

    public function index()
    {
        $data = [
            'title' => 'Payments',
            'payments' => []
        ];
        $this->view('payments/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add new payment',
            'customers' => $this->reusablemodel->get_customers(),
            'id' => '',
            'is_edit' => false,
            'payment_date' => date('Y-m-d'),
            'customer' => '',
            'payment_method' => '',
            'payment_reference' => '',
            'amount' => '',
            'invoices' => [],
            'payment_date_err' => '',
            'amount_err' => '',
            'customer_err' => '',
            'payment_reference_err' => '',
            'payment_method_err' => '',
            'errors' => []
        ];
        $this->view('payments/new',$data);
    }

    public function get_pending()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            flash('payment_msg','Invalid request method.',alert_type('error'));
            redirect('payments');
            exit();
        }

        $customer = filter_input(INPUT_GET,'customer',FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($customer)){
            echo json_encode(['message' => 'Unable to get customer information.']);
            exit();
        }

        $payments = $this->paymentmodel->get_pending_invoices($customer);
        echo json_encode(['success' => true, 'data' => $payments]);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('payment_msg','Invalid request method.',alert_type('error'));
            redirect('payments/new');
            exit();
        }

        $payment_date = filter_input(INPUT_POST,'payment_date',FILTER_SANITIZE_SPECIAL_CHARS);
        $customer = filter_input(INPUT_POST,'customer',FILTER_SANITIZE_SPECIAL_CHARS);
        $payment_method = filter_input(INPUT_POST,'payment_method',FILTER_SANITIZE_SPECIAL_CHARS);
        $payment_reference = filter_input(INPUT_POST,'payment_reference',FILTER_SANITIZE_SPECIAL_CHARS);
        $invoice_ids = isset($_POST['invoice_ids']) ? $_POST['invoice_ids'] : [];
        $invoice_nos = isset($_POST['invoice_nos']) ? $_POST['invoice_nos'] : [];
        $invoice_amounts = isset($_POST['invoice_amounts']) ? $_POST['invoice_amounts'] : [];
        $amount_dues = isset($_POST['amount_dues']) ? $_POST['amount_dues'] : [];
        $payments = isset($_POST['payments']) ? $_POST['payments'] : [];
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => !$is_edit ? 'Add new payment' : 'Update payment',
            'customers' => $this->reusablemodel->get_customers(),
            'id' => !$is_edit ? cuid() : $id,
            'is_edit' => $is_edit,
            'payment_date' => !empty($payment_date) ? date('Y-m-d',strtotime($payment_date)) : '',
            'customer' => !empty($customer) ? $customer : '',
            'payment_method' => !empty($payment_method) ? $payment_method : '',
            'payment_reference' => !empty($payment_reference) ? $payment_reference : '',
            'amount' => '',
            'invoices' => [],
            'payment_date_err' => '',
            'amount_err' => '',
            'customer_err' => '',
            'payment_reference_err' => '',
            'payment_method_err' => '',
            'errors' => []
        ];

        if(empty($data['payment_date'])){
            $data['payment_date_err'] = 'Select date';
        }
        if(empty($data['customer'])){
            $data['customer_err'] = 'Select customer';
        }
        if(empty($data['payment_method'])){
            $data['payment_method_err'] = 'Select payment method.';
        }
        if(empty($data['payment_reference'])){
            $data['payment_reference_err'] = 'Enter payment reference.';
        }
        if(count($invoice_ids) === 0){
            array_push($data['errors'],'No pending invoices found.');
        }else{
            for ($i=0; $i < count($invoice_ids); $i++) { 
                array_push($data['invoices'],[
                    'invoice_id' => $invoice_ids[$i],
                    'invoice_no' => $invoice_nos[$i],
                    'invoice_amount' => $invoice_amounts[$i],
                    'amount_due' => $amount_dues[$i],
                    'payment' => $payments[$i],
                ]);
            }
            $data['amount'] = number_format(array_sum(array_column($data['invoices'],'payment')),2);
        }

        if(!empty($data['payment_date_err']) || !empty($data['customer_err']) || !empty($data['payment_method_err']) 
            || !empty($data['payment_reference_err']) || count($data['errors']) > 0){
                
            $this->view('payments/new',$data);
            exit();
        }
        

        foreach($data['invoices'] as $invoice){
           if(to_float($invoice['amount_due']) < to_float($invoice['payment'])){
                array_push($data['errors'], "Invoice no " . $invoice['invoice_no'] ." has an overpayment.");
            }
        }

        if(count($data['errors']) > 0){
            $this->view('payments/new',$data);
            exit();
        }

        if(!$this->paymentmodel->create_update($data)){
            array_push($data['errors'], "There was a problem creating payments. Try again later.");
            $this->view('payments/new',$data);
            exit();
        }

        redirect('payments');
    }
}