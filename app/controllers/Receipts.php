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
            // 'receipts' => $this->receiptmodel->get_receipts()
            'receipts' => []
        ];
        $this->view('receipts/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add receipt',
            'stores' => $this->reusablemodel->get_stores(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'receipt_no' => $this->receiptmodel->get_receipt_no(),
            'is_edit' => false,
            'id' => '',
            'items' => [],
            'receipt_date' => date('Y-m-d'),
            'store' => '',
            'store_err' => '',
            'receipt_date_err' => '',
            'errors' => []
        ];
        $this->view('receipts/new',$data);
    }
}