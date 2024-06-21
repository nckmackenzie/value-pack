<?php
class Conversions extends Controller
{
    private $authmodel;
    private $reusablemodel;
    private $conversionmodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->conversionmodel = $this->model('Conversion');
        check_rights($this->authmodel,'material conversion');
    }

    public function index()
    {
        $data = [
            'title' => 'Material Conversion',
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'id' => '',
            'is_edit' => false,
            'items' => [],
            'date' => date('Y-m-d'),
            'final_product' => '',
            'date_err' => '',
            'final_product_err' => '',
            'converted_qty' => '',
            'converted_qty_err' => '',
            'errors' => []
        ];
        $this->view('conversions/index', $data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('conversion_msg','Invalid request method', alert_type('error'));
            redirect('conversions');
            exit();
        }

        $date = filter_input(INPUT_POST,'date',FILTER_SANITIZE_SPECIAL_CHARS);
        $final_product = filter_input(INPUT_POST,'final_product',FILTER_SANITIZE_SPECIAL_CHARS);
        $converted_qty = filter_input(INPUT_POST,'converted_qty',FILTER_SANITIZE_NUMBER_INT,FILTER_FLAG_ALLOW_FRACTION);
        $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : [];
        $products = isset($_POST['product']) ? $_POST['product'] : [];
        $quantities = isset($_POST['qty']) ? $_POST['qty'] : [];
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => 'Material Conversion',
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'is_edit' => $is_edit,
            'id' => !$is_edit ? cuid() : $id,
            'items' => [],
            'date' => !empty($date) ? date('Y-m-d',strtotime($date)) : '',
            'final_product' => !empty($final_product) ? trim($final_product) : '',
            'converted_qty' => !empty($converted_qty) ? $converted_qty : '',
            'date_err' => '',
            'final_product_err' => '',
            'converted_qty_err' => '',
            'errors' => []
        ];

        if(empty($data['date'])){
            $data['date_err'] = 'Select conversion date';
        }
        if(empty($data['final_product'])){
            $data['final_product_err'] = 'Select final product';
        }
        if(count($product_ids) === 0){
            array_push($data['errors'],'No products entered for conversion');
        }else{
            for ($i=0; $i < count($products); $i++) { 
                array_push($data['items'],[
                    'product_id' => $product_ids[$i],
                    'product_name' => $products[$i],
                    'converted_qty' => $quantities[$i],
                ]);
            }
        }

        if(!empty($data['date_err']) || !empty($data['final_product_err']) || count($data['errors']) > 0){
            $this->view('conversions/new',$data);
            exit();
        }

        foreach($data['items'] as $item){
            $curr_value = $this->reusablemodel->get_current_stock_balance($_SESSION['store'],$item['product_id'],$data['date']);
            if((int)$curr_value < (int)$item['converted_qty']){
                array_push($data['errors'],'Insufficient stock for product '.$item['product_name']);
            }
        }

        if(in_array($data['final_product'],array_column($data['items'],'product_id'))){
            array_push($data['errors'],'Final product cannot be a product in the conversion list.');
        }

        if(count($data['errors']) > 0){
            $this->view('conversions/new',$data);
            exit();
        }

        if(!$this->conversionmodel->create_update($data)){
            array_push($data['errors'],'Failed to save conversion.');
            $this->view('conversions/new',$data);
            exit();
        }

        redirect('conversions');
    }
}