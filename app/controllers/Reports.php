<?php

 class Reports extends Controller
 {
    private $authmodel;
    private $reportmodel;
    private $reusablemodel;

    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->reusablemodel = $this->model('Reusable');
        $this->reportmodel = $this->model('Report');        
    }

    public function index()
    {
        $data = ['title' => 'Reports'];
        $this->view('reports/index', $data);
    }

    public function stockreport()
    {
        $data = [
            'title' => 'Stock Report',
            'start_date' => '',
            'end_date' => '',
        ];
        $this->view('reports/stockreport', $data);
    }

    public function stock_report_gen()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit();
        }

        $start_date = filter_input(INPUT_GET, 'start', FILTER_SANITIZE_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_GET, 'end', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
            'start_date' => !empty($start_date) ? date('Y-m-d',strtotime($start_date)) : '',
            'end_date' => !empty($end_date) ? date('Y-m-d',strtotime($end_date)) : '',
        ];

        if(date_validator('earlier_than_first', $data['start_date'], $data['end_date'])){
            echo json_encode(['success' => false, 'message' => 'Start date cannot be after end date.']);
            exit();
        }

        $stocks = $this->reportmodel->get_stock_report($data);

        echo json_encode(['success' => true, 'message' => null, 'data' => $stocks]);
    }

    public function sales()
    {
        $data = [
            'title' => 'Stock Report',
        ];
        $this->view('reports/salesreport', $data);
    }

    public function sales_report()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit();
        }

        $start_date = filter_input(INPUT_GET, 'start', FILTER_SANITIZE_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_GET, 'end', FILTER_SANITIZE_SPECIAL_CHARS);
        $report_type = filter_input(INPUT_GET,'report_type', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
            'start_date' => !empty($start_date) ? date('Y-m-d',strtotime($start_date)) : '',
            'end_date' => !empty($end_date) ? date('Y-m-d',strtotime($end_date)) : '',            
            'report_type' => !empty($report_type) ? $report_type : '',
        ];

        if(date_validator('earlier_than_first', $data['start_date'], $data['end_date'])){
            echo json_encode(['success' => false, 'message' => 'Start date cannot be after end date.']);
            exit();
        }

        $sales = $this->reportmodel->get_sales_report($data);

        echo json_encode(['success' => true, 'message' => null, 'data' => $sales]);
    }

    public function pendinginvoices()
    {
        $data = ['title' => 'Pending Invoices','invoices' => $this->reportmodel->get_pending_invoices()];
        $this->view('reports/pendinginvoices', $data);
    }
 }