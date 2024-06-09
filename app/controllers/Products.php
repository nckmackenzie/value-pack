<?php

class Products extends Controller
{
    private $productmodel;
    private $authmodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->productmodel = $this->model('Product');
        $this->reusablemodel = $this->model('Reusable');
        $this->authmodel = $this->model('Auths');
        check_rights($this->authmodel,'products');
    }

    function index()
    {
        $data = [
            'title' => 'Products',
            'products' => $this->productmodel->get_products(),
        ];
        $this->view('products/index', $data);
    }

    function new()
    {
        $data= [
            'units' => $this->reusablemodel->get_units(),
            'stores' => $this->reusablemodel->get_stores(),
            'title' => 'Create product',
            'id' => null,
            'is_edit' => false,
            'name' => '',
            'code' => '',
            'unit' => '',
            'buying_price' => '',
            'selling_price' => '',
            'description' => '',
            'restock_level' => '',
            'stores_allowed' => [],
            'allow_nil' => false,
            'active' => true,
            'name_err' => '',
            'code_err' => '',
            'unit_err' => '',
            'selling_price_err' => '',
            'stores_err' => '',
            'error' => null,
        ];
        $this->view('products/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('product_msg','Invalid request method', alert_type('error'));
            redirect('products');
            exit();
        }
        
        $product_name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $product_code = filter_input(INPUT_POST,'code', FILTER_SANITIZE_SPECIAL_CHARS);
        $unit = filter_input(INPUT_POST,'unit', FILTER_SANITIZE_NUMBER_INT);
        $buying_price = filter_input(INPUT_POST,'buying_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $selling_price = filter_input(INPUT_POST,'selling_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $description = filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $restock_level = filter_input(INPUT_POST,'restock_level', FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_ALLOW_FRACTION);
        $active = filter_input(INPUT_POST,'active', FILTER_VALIDATE_BOOLEAN);
        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
            'units' => $this->reusablemodel->get_units(),
            'stores' => $this->reusablemodel->get_stores(),
            'title' =>  $is_edit ? 'Update Product' : 'Create product',
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'name' => !empty(trim($product_name)) ? trim($product_name) : null,
            'code' => !empty(trim($product_code)) ? trim($product_code) : null,
            'unit' => !empty($unit) ? $unit : null,
            'buying_price' => !empty($buying_price) ? floatval($buying_price) : 0,
            'selling_price' => !empty($selling_price) ? floatval($selling_price) : 0,
            'description' => !empty($description) ? $description : null,
            'restock_level' => !empty($restock_level) ? $restock_level : null,
            'stores_allowed' => isset($_POST['stores'])  ? $_POST['stores'] : [],
            'allow_nil' => false,
            'active' => !$is_edit ? true : $active,
            'name_err' => '',
            'code_err' => '',
            'unit_err' => '',
            'selling_price_err' => '',
            'stores_err' => '',
            'error' => null
        ];

            
        if(is_null($data['name'])){
            $data['name_err'] = 'Enter product name';
        }
        if(is_null($data['unit'])){
            $data['unit_err'] = 'Select unit of measurement.';
        }
        if(!is_null($data['selling_price']) && !is_null($data['buying_price']) && ($buying_price > $selling_price)){
            $data['selling_price_err'] = 'Selling price is lower than buying price.';
        }
        
        if(!empty($data['name_err']) || !empty($data['unit_err']) || !empty($data['selling_price_err']) || !empty($data['stores_err'])){
            $this->view('products/new',$data);  
            exit();
        }

        if(count($data['stores_allowed']) === 0){
            $data['error'] = 'Select at least one store';
            $this->view('products/new',$data);
            exit();
        }

        if($this->productmodel->check_exists('product_name',strtolower($data['name']),$data['id'])){
            $data['error'] = 'Product name already exists';
        }
        if($this->productmodel->check_exists('product_code',strtolower($data['code']),$data['id'])){
            $data['error'] = 'Product code already exists';
        }

        if(!$this->productmodel->create_update($data)){
            $data['error'] = 'Something went wrong while perfoming this action.';
            $this->view('products/new',$data);
            exit();
        }

        redirect('products');
    }

    public function edit($id)
    {        
        $product = $this->productmodel->get_product($id);
        if(!$product){
            $this->not_found('/products', 'The product you are trying to edit doesn\'t exist');
            exit();
        }
        $data= [
            'units' => $this->reusablemodel->get_units(),
            'stores' => $this->reusablemodel->get_stores(),
            'title' => 'Update product',
            'id' => $product->id,
            'is_edit' => true,
            'name' => strtoupper($product->product_name),
            'code' => strtoupper($product->product_code),
            'unit' => $product->unit_id,
            'buying_price' => $product->buying_price,
            'selling_price' => $product->selling_price,
            'description' => $product->description,
            'restock_level' => $product->reorder_level,
            'stores_allowed' => [],
            'allow_nil' => false,
            'active' => $product->active,
            'name_err' => '',
            'code_err' => '',
            'unit_err' => '',
            'selling_price_err' => '',
            'stores_err' => '',
            'error' => null,
        ];
        $stores = $this->productmodel->get_product_stores($id);
        foreach($stores as $store){
            array_push($data['stores_allowed'],$store->store_id);
        }
        $this->view('products/new',$data);
    }
}