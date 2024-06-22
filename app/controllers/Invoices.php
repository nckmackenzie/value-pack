<?php
class Invoices extends Controller
{
    private $authmodel;
    private $invoicemodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->invoicemodel = $this->model('Invoice');
        $this->reusablemodel = $this->model('Reusable');
        check_rights($this->authmodel,'invoices');
    }

    public function index()
    {
        $data = [
            'title' => 'Invoices',
            'invoices' => $this->invoicemodel->get_invoices()
        ];
        $this->view('invoices/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Create invoice',
            'customers' => $this->reusablemodel->get_customers(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'invoice_no' => $this->invoicemodel->get_invoice_no(),
            'id' => '',
            'is_edit' => false,
            'date' => date('Y-m-d'),
            'customer' => '',
            'vat_type' => '',
            'vat' => '',
            'items' => [],
            'total' => 0,
            'date_err' => '',
            'customer_err' => '',
            'vat_type_err' => '',
            'vat_err' => '',
            'errors' => []
           ];
        $this->view('invoices/new', $data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('invoice_msg','Invalid request method.');
            redirect('invoices');
            exit();
        }

        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
        $customer = filter_input(INPUT_POST, 'customer', FILTER_SANITIZE_SPECIAL_CHARS);
        $vat_type = filter_input(INPUT_POST, 'vat_type', FILTER_SANITIZE_SPECIAL_CHARS);
        $vat = filter_input(INPUT_POST, 'vat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);
        $products = isset($_POST['products']) ? $_POST['products'] : [];
        $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : [];
        $quantities = isset($_POST['qty']) ? $_POST['qty'] : [];
        $rates = isset($_POST['rate']) ? $_POST['rate'] : [];
        
        $data = [
            'title' => $is_edit ? 'Update invoice' : 'Create invoice',
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'customers' => $this->reusablemodel->get_customers(),
            'stores' => $this->reusablemodel->get_stores(),
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'date' => !empty($date) ? date('Y-m-d',strtotime($date)) : null ,
            'customer' => !empty($customer) ? trim($customer) : null,
            'invoice_no' => !$is_edit ? $this->invoicemodel->get_invoice_no() : null,
            'vat_type' => !empty($vat_type) ? trim($vat_type) : null,
            'vat' => !empty($vat) ? floatval($vat) : null,
            'items' => [],
            'total' => 0,
            'customer_err' => '',
            'date_err' => '',
            'vat_type_err' => '',
            'vat_err' => '',
            'errors' => []
        ];

        if(is_null($data['date'])){
            $data['date_err'] = 'Select date';
        }
        if(is_null($data['customer'])){
            $data['customer_err'] = 'Select customer';
        }
        if(is_null($data['vat_type'])){
            $data['vat_type_err'] = 'Select vat type';
        }
        if(!is_null($data['vat_type']) && $data['vat_type'] !== 'no-vat' && is_null($data['vat'])){
            $data['vat_err'] = 'Select vat';
        }

        if(count($products) === 0){
            array_push($data['errors'], 'No products added.');
        }else{
            for ($i=0; $i < count($products); $i++) { 
                array_push($data['items'],[
                    'product_id' => $product_ids[$i],
                    'product_name' => $products[$i],
                    'qty' => $quantities[$i],
                    'rate' => $rates[$i],
                    'value' => floatval($quantities[$i]) * floatval($rates[$i])
                ]);
            }
        }

        if(count($data['errors']) > 0 || !empty($data['date_err']) || !empty($data['store_err']) || !empty($data['vat_type_err'])
            || !empty($data['vat_err']) || !empty($data['store_err'])){
            $this->view('invoices/new', $data);
            exit();
        }

        $data['total'] = array_sum(array_column($data['items'], 'value'));

        foreach($data['items'] as $item){
            $curr_value = $this->reusablemodel->get_current_stock_balance($_SESSION['store'],$item['product_id'],$data['date']);
            if((int)$curr_value < (int)$item['qty']){
                array_push($data['errors'],'Insufficient stock for product '.$item['product_name']);
            }
        }

        if(count($data['errors']) > 0){
            $this->view('invoices/new',$data);
            exit();
        }

        if(!$this->invoicemodel->create_update($data)){
            array_push($data['errors'], 'Something went wrong while performing this action.');
            $this->view('invoices/new',$data);
            exit();
        }

        redirect('invoices');
    }

    public function print($id)
    {
        $invoice = $this->invoicemodel->get_invoice($id);
        if(!$invoice){
            $this->not_found('/invoices','Invoice not found');
            exit();
        }

        $data = [
            'title' => 'Print Invoice',
            'header' => $invoice,
            'items' => $this->invoicemodel->invoice_items($id)
        ];
        $this->view('invoices/print', $data);
    }
}