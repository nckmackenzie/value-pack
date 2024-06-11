<?php
class Purchases extends Controller
{
    private $purchasemodel;
    private $authmodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->purchasemodel = $this->model('Purchase');
        check_rights($this->authmodel,'purchases');
    }

    public function index()
    {
        $data = [
            'title' => 'Purchases',
            'purchases' => []
        ];
        $this->view('purchases/index', $data);
    }

    public function new()
    {
        $data =[
            'title' => 'Create purchase',
            'products' => $this->reusablemodel->get_products(),
            'suppliers' => $this->reusablemodel->get_vendors(),
            'stores' => $this->reusablemodel->get_stores(),
            'id' => '',
            'is_edit' => false,
            'date' => date('Y-m-d'),
            'vendor' => '',
            'reference' => '',
            'vat_type' => '',
            'vat' => '',
            'store' => '',
            'items' => [],
            'total' => 0,
            'store_err' => '',
            'vendor_err' => '',
            'date_err' => '',
            'vat_type_err' => '',
            'vat_err' => '',
            'error' => null
        ];
        $this->view('purchases/new', $data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('purchase_msg','Invalid request method.');
            redirect('purchases');
            exit();
        }

        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
        $vendor = filter_input(INPUT_POST, 'vendor', FILTER_SANITIZE_SPECIAL_CHARS);
        $reference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_SPECIAL_CHARS);
        $vat_type = filter_input(INPUT_POST, 'vat_type', FILTER_SANITIZE_SPECIAL_CHARS);
        $vat = filter_input(INPUT_POST, 'vat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $store = filter_input(INPUT_POST, 'store', FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);
        $products = isset($_POST['product']) ? $_POST['product'] : [];
        $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : [];
        $quantities = isset($_POST['qty']) ? $_POST['qty'] : [];
        $rates = isset($_POST['rate']) ? $_POST['rate'] : [];
        
        $data = [
            'title' => $is_edit ? 'Update purchase' : 'Create purchase',
            'products' => $this->reusablemodel->get_products(),
            'suppliers' => $this->reusablemodel->get_vendors(),
            'stores' => $this->reusablemodel->get_stores(),
            'id' => $is_edit ? $id : cuid(),
            'is_edit' => $is_edit,
            'date' => !empty($date) ? date('Y-m-d',strtotime($date)) : null ,
            'vendor' => !empty($vendor) ? trim($vendor) : null,
            'reference' => !empty($reference) ? trim($reference) : null,
            'vat_type' => !empty($vat_type) ? trim($vat_type) : null,
            'vat' => !empty($vat) ? floatval($vat) : null,
            'store' => !empty($store) ? trim($store) : null, 
            'items' => [],
            'total' => 0,
            'store_err' => '',
            'vendor_err' => '',
            'date_err' => '',
            'vat_type_err' => '',
            'vat_err' => '',
            'error' => null
        ];

        if(is_null($data['date'])){
            $data['date_err'] = 'Select date';
        }
        if(is_null($data['vendor'])){
            $data['vendor_err'] = 'Select vendor';
        }
        if(is_null($data['vat_type'])){
            $data['vat_type_err'] = 'Select vat type';
        }
        if(is_null($data['store'])){
            $data['store_err'] = 'Select store';
        }
        if(!is_null($data['vat_type']) && $data['vat_type'] !== 'no-vat' && is_null($data['vat'])){
            $data['vat_err'] = 'Select vat';
        }

        if(count($products) === 0){
            $data['error'] = 'No products added';
        }

        if(!is_null($data['error']) || !empty($data['date_err']) || !empty($data['store_err']) || !empty($data['vat_type_err'])
            || !empty($data['vat_err']) || !empty($data['store_err'])){
            $this->view('purchases/new', $data);
            exit();
        }

        for ($i=0; $i < count($products); $i++) { 
            array_push($data['items'],[
                'product_id' => $product_ids[$i],
                'product_name' => $products[$i],
                'qty' => $quantities[$i],
                'rate' => $rates[$i],
                'value' => floatval($quantities[$i]) * floatval($rates[$i])
            ]);
        }

        $data['total'] = array_sum(array_column($data['items'], 'value'));

        if(!$this->purchasemodel->create_update($data)){
            $data['error'] = 'Something went wrong while performing this action.';
            $this->view('purchases/new',$data);
            exit();
        }

        redirect('purchases');
    }
}