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
            'store_err' => '',
            'vendor_err' => '',
            'date_err' => '',
            'vat_type_err' => '',
            'vat_err' => '',
        ];
        $this->view('purchases/new', $data);
    }
}