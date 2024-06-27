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
            'payments' => $this->paymentmodel->get_paid_invoices()
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

    public function create()
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

        if(!$this->paymentmodel->create($data)){
            array_push($data['errors'], "There was a problem creating payments. Try again later.");
            $this->view('payments/new',$data);
            exit();
        }

        redirect('payments');
    }

    public function edit($id)
    {
        $payment = $this->paymentmodel->get_payment($id);
        if(!$payment){
            $this->not_found('/payments', 'The payment you are trying to edit doesn\'t exist');
            exit();
        }
        $data = [
            'title' => 'Update payment',
            'customers' => $this->reusablemodel->get_customers(),
            'id' => $payment->id,
            'is_edit' => true,
            'payment_date' => date('Y-m-d',strtotime($payment->payment_date)),
            'customer' => $payment->customer_id,
            'payment_method' => $payment->payment_method,
            'payment_reference' => strtoupper($payment->payment_reference),
            'payment' => $payment->payment,
            'amount_due' => $payment->amount_due,
            'invoice_id' => $payment->invoice_id,
            'payment_id' => $payment->payment_id,
            'payment_date_err' => '',
            'amount_err' => '',
            'customer_err' => '',
            'payment_reference_err' => '',
            'payment_err' => '',
            'payment_method_err' => '',
            'errors' => []
        ];
        $this->view('payments/edit',$data);
    }

    public function update()
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
        $amount_due = filter_input(INPUT_POST,'amount_due',FILTER_SANITIZE_SPECIAL_CHARS);        
        $payment = filter_input(INPUT_POST,'payment',FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $invoice_id = filter_input(INPUT_POST,'invoice_id',FILTER_SANITIZE_SPECIAL_CHARS);
        $payment_id = filter_input(INPUT_POST,'payment_id',FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_FRACTION);

        $data = [
            'title' => 'Update payment',
            'customers' => $this->reusablemodel->get_customers(),
            'id' => $id,
            'is_edit' => true,
            'payment_date' => !empty($payment_date) ? date('Y-m-d',strtotime($payment_date)) : '',
            'customer' => !empty($customer) ? $customer : '',
            'payment_method' => !empty($payment_method) ? $payment_method : '',
            'payment_reference' => !empty($payment_reference) ? $payment_reference : '',
            'payment' => !empty($payment) ? $payment : '',
            'amount_due' => !empty($amount_due) ? $amount_due : '',
            'invoice_id' => $invoice_id,
            'payment_id' => $payment_id,
            'payment_date_err' => '',
            'amount_err' => '',
            'customer_err' => '',
            'payment_reference_err' => '',
            'payment_method_err' => '',
            'payment_err' => '',
            'errors' => []
        ];

        if(empty($data['payment_date'])){
            $data['payment_date_err'] = 'Select date';
        }else{
            $invoicedate = $this->paymentmodel->get_invoice_date($data['invoice_id']);
            if(date_validator('earlier_than_first',$invoicedate,$data['payment_date'])){
                $data['payment_date_err'] = 'Payment date cannot be earlier than invoice date.';
            }
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
        if(empty($data['payment'])){
            $data['payment_err'] = 'Enter payment.';
        }else{
            if(to_float($data['payment']) > to_float($data['amount_due'])){
                $data['payment_err'] = 'Payment cannot be greater than amount due.';
            }
        }        

        if(!empty($data['payment_date_err']) || !empty($data['customer_err']) || !empty($data['payment_method_err']) 
            || !empty($data['payment_reference_err']) || !empty($data['payment_err'])){
                
            $this->view('payments/edit',$data);
            exit();
        }

        if($this->paymentmodel->has_earlier_payments($data['invoice_id'],$data['payment_id'])){
            array_push($data['errors'], "Cannot edit this payment as there are earlier payments for same invoice.");
            $this->view('payments/edit',$data);
            exit();
        }

        if(!$this->paymentmodel->update($data)){
            array_push($data['errors'], "There was a problem updating payment. Try again later.");
            $this->view('payments/edit',$data);
            exit();
        }

        redirect('payments');
    }
}