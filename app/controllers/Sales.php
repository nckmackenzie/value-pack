<?php 
class Sales extends Controller
{
    private $authmodel;
    private $salemodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->salemodel = $this->model('Sale');
        $this->reusablemodel = $this->model('Reusable');
        check_rights($this->authmodel,'daily sales');
    }

    public function index()
    {
        $data = [
            'title' => 'Daily Sales',
            'sales' => $this->salemodel->get_sales()
        ];
        $this->view('sales/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'New Sales',
            'customers' => $this->reusablemodel->get_customers(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'id' => '',
            'is_edit' => false,
            'sale_no' => $this->salemodel->get_sale_no(),
            'sale_date' => date('Y-m-d'),
            'customer' => '',
            'sale_type' => '',
            'qty' => '',
            'rate' => '',
            'current_stock' => '',
            'total_value' => '',
            'product' => '',
            'sale_date_err' => '',
            'customer_err' => '',
            'sale_type_err' => '',
            'qty_err' => '',
            'product_err' => '',
            'error' => null
        ];
        $this->view('sales/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('sale_msg','Invalid request method.', alert_type('error'));
            redirect('sales');
            exit();
        }

        $sale_date = filter_input(INPUT_POST,'sale_date',FILTER_SANITIZE_SPECIAL_CHARS);
        $customer = filter_input(INPUT_POST,'customer',FILTER_SANITIZE_SPECIAL_CHARS);
        $sale_type = filter_input(INPUT_POST,'sale_type',FILTER_SANITIZE_SPECIAL_CHARS);
        $product = filter_input(INPUT_POST,'product',FILTER_SANITIZE_SPECIAL_CHARS);
        $sale_no = filter_input(INPUT_POST,'sale_no',FILTER_SANITIZE_NUMBER_INT);
        $rate = filter_input(INPUT_POST,'rate',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $qty = filter_input(INPUT_POST,'qty',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $current_stock = filter_input(INPUT_POST,'current_stock',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $total_value = filter_input(INPUT_POST,'total_value',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => $is_edit ? 'Update sales' : 'New sales',
            'customers' => $this->reusablemodel->get_customers(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'sale_no' => $is_edit ? $sale_no : $this->salemodel->get_sale_no(),
            'sale_date' => !empty($sale_date) ? date('Y-m-d',strtotime($sale_date)) : null,
            'customer' => !empty($customer) ? $customer : null,
            'sale_type' => !empty($sale_type) ? $sale_type : null,
            'product' => !empty($product) ? $product : null,
            'qty' => !empty($qty) ? $qty : null,
            'rate' => !empty($rate) ? $rate : null,
            'current_stock' => !empty($current_stock) ? $current_stock : null,
            'total_value' => !empty($total_value) ? $total_value : null,
            'sale_date_err' => '',
            'customer_err' => '',
            'sale_type_err' => '',
            'qty_err' => '',
            'product_err' => '',
            'error' => null
        ];

        if(is_null($data['sale_date'])){
            $data['sale_date_err'] = 'Select date';
        }else{
            if(date_validator('greater_than_today',$data['sale_date'])){
                $data['sale_date_err'] = 'Date cannot be greater than today';
            }
        }
        if(is_null($data['customer'])){
            $data['customer_err'] = 'Select customer';
        }
        if(is_null($data['sale_type'])){
            $data['sale_type_err'] = 'Select sale type';
        }
        if(is_null($data['product'])){
            $data['product_err'] = 'Select product';
        }

        if(!empty($data['sale_date_err']) || !empty($data['customer_err']) || !empty($data['sale_type_err']) || 
           !empty($data['product_err'])){
            $this->view('sales/new',$data);
            exit();
        }

        if(!$this->salemodel->create_update($data)){
            $data['error'] = 'Something went wrong. Please try again.';
            $this->view('sales/new',$data);
            exit();
        }

        redirect('sales');
    }
}