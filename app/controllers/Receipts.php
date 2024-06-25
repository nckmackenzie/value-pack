<?php
class Receipts extends Controller
{
    private $receiptmodel;
    private $reusablemodel;
    private $authmodel;
    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->receiptmodel = $this->model('Receipt');
        check_rights($this->authmodel,'receipts');
    }

    public function index()
    {
        $data = [
            'title' => 'Receipts',
            'receipts' => $this->receiptmodel->get_receipts()
        ];
        $this->view('receipts/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add receipt',
            'stores' => $this->receiptmodel->get_transfering_stores(),
            'transfers' => [],
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'receipt_no' => $this->receiptmodel->get_receipt_no(),
            'is_edit' => false,
            'id' => '',
            'items' => [],
            'receipt_date' => date('Y-m-d'),
            'store_from' => '',
            'transfer_no' => '',
            'store_from_err' => '',
            'receipt_date_err' => '',
            'transfer_no_err' => '',
            'errors' => []
        ];
        $this->view('receipts/new',$data);
    }

    public function get_transfers()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            http_response_code(405);
            echo json_encode(['message' => 'Invalid request method.']);
            exit();
        }

        $store = filter_input(INPUT_GET,'store',FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($store)){
            http_response_code(422);
            echo json_encode(['message' => 'Store not provided.']);
            exit();
        }

        $data = [];
        foreach($this->receiptmodel->get_transfers($store) as $transfer){
            array_push($data,[
                'id' => $transfer->id,
                'transfer_no' => $transfer->transfer_no
            ]);
        }

        echo json_encode(['success' => true, 'message' => null, 'data' => $data]);
    }

    public function get_items()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            http_response_code(405);
            echo json_encode(['message' => 'Invalid request method.']);
            exit();
        }

        $transfer_no = filter_input(INPUT_GET,'transferNo',FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($transfer_no)){
            http_response_code(422);
            echo json_encode(['message' => 'Store not provided.']);
            exit();
        }

        $data = [];
        foreach($this->receiptmodel->get_items($transfer_no) as $item){
            array_push($data,[
                'id' => $item->product_id,
                'product_name' => strtoupper($item->product_name),
                'qty' => $item->qty
            ]);
        }

        echo json_encode(['success' => true, 'message' => null, 'data' => $data]);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('receipt_msg','Invalid request method.',alert_type('error'));
            redirect('receipts/new');
            exit();
        }

        $receipt_no = filter_input(INPUT_POST,'receipt_no',FILTER_SANITIZE_NUMBER_INT);
        $receipt_date = filter_input(INPUT_POST,'receipt_date',FILTER_SANITIZE_SPECIAL_CHARS);
        $store_from = filter_input(INPUT_POST,'store_from',FILTER_SANITIZE_SPECIAL_CHARS);
        $transfer_no = filter_input(INPUT_POST,'transfer_no',FILTER_SANITIZE_SPECIAL_CHARS);
        $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : [];
        $products = isset($_POST['product']) ? $_POST['product'] : [];
        $transfered_qtys = isset($_POST['transfered_qty']) ? $_POST['transfered_qty'] : [];
        $received_qtys = isset($_POST['received_qty']) ? $_POST['received_qty'] : [];
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => $is_edit ? 'Update receipt' : 'Add receipt',
            'stores' => $this->receiptmodel->get_transfering_stores(),
            'transfers' => [],
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'receipt_no' => $is_edit ? $receipt_no : $this->receiptmodel->get_receipt_no(),
            'is_edit' => $is_edit,
            'id' => $is_edit ? $id : cuid(),
            'items' => [],
            'receipt_date' => !empty($receipt_date) ? date('Y-m-d',strtotime($receipt_date)) : '',
            'store_from' => !empty($store_from) ? trim($store_from) : '',
            'transfer_no' => !empty($transfer_no) ? $transfer_no : '',
            'store_from_err' => '',
            'receipt_date_err' => '',
            'transfer_no_err' => '',
            'errors' => []
        ];

        if(empty($data['receipt_date'])){
            $data['receipt_date_err'] = 'Select receipt date';
        }
        if(!$data['is_edit']){
            if(empty($data['store_from'])){
                $data['store_from_err'] = 'Select store from';
            }else{
                $data['transfers'] = $this->receiptmodel->get_transfers($data['store_from']);
            }
            if(empty($data['transfer_no'])){
                $data['transfer_no_err'] = 'Select transfer no';
            }
        }        
        
        for ($i=0; $i < count($products); $i++) { 
            array_push($data['items'],[
                'product_id' => $product_ids[$i],
                'product_name' => $products[$i],
                'transfered_qty' => $transfered_qtys[$i],
                'received_qty' => $received_qtys[$i],
            ]);
        }
    
        if(!empty($data['receipt_date_err']) || !empty($data['transfer_no_err'] || !empty($data['store_from_err']))){
            $this->view('receipts/new',$data);
            exit();
        }
        if($this->receiptmodel->date_is_earlier($data['transfer_no'],$data['receipt_date'])){
            array_push($data['errors'],'Receipt date cannot be earlier than transfer date.');
            $this->view('receipts/new',$data);
            exit();
        }
        foreach($data['items'] as $item){
            if(empty($item['received_qty'])){
                array_push($data['errors'],'Received qty not provided for '.ucwords($item['product_name']));
            }
            if((int)$item['transfered_qty'] < (int)$item['received_qty']){
                array_push($data['errors'],'Received more than transfered for '.ucwords($item['product_name']));
            }
        }
        if(count($data['errors']) > 0){
            $this->view('receipts/new',$data);
            exit();
        }

        if(!$this->receiptmodel->create_update($data)){
            array_push($data['errors'],'Failed to save transfer.');
            $this->view('receipts/new',$data);
            exit();
        }

        redirect('receipts');
    }

    public function edit($id)
    {
        $receipt = $this->receiptmodel->get_receipt($id);
        if(!$receipt){
            $this->not_found('/receipts', 'The receipt you are trying to edit doesn\'t exist');
            exit();
        }

        $data = [
            'title' => 'Update receipt',
            'stores' => $this->receiptmodel->get_transfering_stores(),
            'transfers' => [],
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'receipt_no' => $receipt->receipt_no,
            'is_edit' => true,
            'id' => $receipt->id,
            'items' => [],
            'receipt_date' => date('Y-m-d',strtotime($receipt->receipt_date)),
            'store_from' => !empty($store_from) ? trim($store_from) : '',
            'transfer_no' => $receipt->transfer_id,
            'store_from_err' => '',
            'receipt_date_err' => '',
            'transfer_no_err' => '',
            'errors' => []
        ];

        foreach($this->receiptmodel->get_receipt_items($id) as $receipt){
            array_push($data['items'],[
                'product_id' => $receipt->product_id,
                'product_name' => $receipt->product_name,
                'transfered_qty' => $receipt->transfered_qty,
                'received_qty' => $receipt->received_qty,
            ]);
        }

        $this->view('receipts/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('receipt_msg','Invalid request method', alert_type('error'));
            redirect('receipts');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(empty($id)){
            flash('receipt_msg','Unable to get selected receipt.', alert_type('error'));
            redirect('receipts');
            exit();
        }

        if(!$this->receiptmodel->delete($id)){
            flash('receipt_msg','Cannot delete this receipt. Please try again later.', alert_type('error'));
            redirect('receipts');
            exit();
        }

        redirect('/receipts');
    }
}