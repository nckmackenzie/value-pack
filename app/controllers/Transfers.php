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
}