<?php
class Transfers extends Controller
{
    private $transfermodel;
    private $authmodel;
    private $reusablemodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->transfermodel = $this->model('Transfer');
        check_rights($this->authmodel,'transfers');
    }

    public function index()
    {
        $data =[
            'title' => 'Transfers',
            'transfers' => $this->transfermodel->get_transfers()
        ];
        $this->view('transfers/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add transfer',
            'stores' => $this->reusablemodel->get_stores(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'transfer_no' => $this->transfermodel->get_transfer_no(),
            'is_edit' => false,
            'id' => '',
            'items' => [],
            'transfer_date' => date('Y-m-d'),
            'store' => '',
            'store_err' => '',
            'transfer_date_err' => '',
            'errors' => []
        ];
        $this->view('transfers/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('transfer_msg','Invalid request method', alert_type('error'));
            redirect('transfers');
            exit();
        }

        $transfer_date = filter_input(INPUT_POST,'transfer_date',FILTER_SANITIZE_SPECIAL_CHARS);
        $to_store = filter_input(INPUT_POST,'store',FILTER_SANITIZE_SPECIAL_CHARS);
        $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : [];
        $products = isset($_POST['product']) ? $_POST['product'] : [];
        $quantities = isset($_POST['qty']) ? $_POST['qty'] : [];
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => $is_edit ? 'Update transfer' : 'Add transfer',
            'stores' => $this->reusablemodel->get_stores(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'transfer_no' => $this->transfermodel->get_transfer_no(),
            'is_edit' => $is_edit,
            'id' => !$is_edit ? cuid() : $id,
            'items' => [],
            'transfer_date' => !empty($transfer_date) ? date('Y-m-d',strtotime($transfer_date)) : '',
            'store' => !empty($to_store) ? trim($to_store) : '',
            'store_err' => '',
            'transfer_date_err' => '',
            'errors' => []
        ];

        if(empty($data['transfer_date'])){
            $data['transfer_date_err'] = 'Select transfer date';
        }
        if(empty($data['store'])){
            $data['store_err'] = 'Select store to transfer to';
        }
        if(count($product_ids) === 0){
            array_push($data['errors'],'No products entered for transfer');
        }else{
            for ($i=0; $i < count($products); $i++) { 
                array_push($data['items'],[
                    'product_id' => $product_ids[$i],
                    'product_name' => $products[$i],
                    'qty' => $quantities[$i],
                ]);
            }
        }

        if(!empty($data['transfer_date_err']) || !empty($data['store_err']) || count($data['errors']) > 0){
            $this->view('transfers/new',$data);
            exit();
        }

        foreach($data['items'] as $item){
            $curr_value = $this->reusablemodel->get_current_stock_balance($_SESSION['store'],$item['product_id'],$data['transfer_date']);
            if((int)$curr_value < (int)$item['qty']){
                array_push($data['errors'],'Insufficient stock for product '.$item['product_name']);
            }
        }

        if(count($data['errors']) > 0){
            $this->view('transfers/new',$data);
            exit();
        }

        if(!$this->transfermodel->create_update($data)){
            array_push($data['errors'],'Failed to save transfer.');
            $this->view('transfers/new',$data);
            exit();
        }

        redirect('transfers');
    }

    public function edit($id)
    {
        $transfer = $this->transfermodel->get_transfer($id);
        if(!$transfer){
            $this->not_found('/products', 'The transfer you are trying to edit doesn\'t exist');
            exit();
        }
        $data = [
            'title' => 'Update transfer',
            'stores' => $this->reusablemodel->get_stores(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'transfer_no' => $transfer->transfer_no,
            'is_edit' => true,
            'id' => $transfer->id,
            'items' => [],
            'transfer_date' => date('Y-m-d',strtotime($transfer->transfer_date)),
            'store' => $transfer->store_to,
            'store_err' => '',
            'transfer_date_err' => '',
            'errors' => []
        ];

        $products = $this->transfermodel->get_transfer_items($transfer->id);

        foreach($products as $product){
            array_push($data['items'],[
                'product_id' => $product->product_id,
                'product_name' => strtoupper($product->product_name),
                'qty' => $product->qty,
            ]);
        }

        $this->view('transfers/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('transfer_msg','Invalid request method', alert_type('error'));
            redirect('transfers');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(empty($id)){
            flash('transfer_msg','Unable to get selected transfer.', alert_type('error'));
            redirect('transfers');
            exit();
        }

        if($this->transfermodel->check_is_receipt($id)){
            flash('transfer_msg','Cannot delete transfer as its already received.', alert_type('error'));
            redirect('transfers');
            exit();
        }

        if(!$this->transfermodel->delete($id)){
            flash('transfer_msg','Cannot delete this transfer. Please try again later.', alert_type('error'));
            redirect('transfers');
            exit();
        }

        redirect('/transfers');
    }
}