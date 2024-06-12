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
        $data =['title' => 'Transfers','transfers' => []];
        $this->view('transfers/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add transfer',
            'stores' => $this->reusablemodel->get_stores(),
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'is_edit' => false,
            'id' => '',
            'items' => [],
            'transfer_date' => date('Y-m-d'),
            'transfer_no' => 1,
            'store' => '',
            'store_err' => '',
            'transfer_date_err' => '',
            'error' => null
        ];
        $this->view('transfers/new',$data);
    }
}