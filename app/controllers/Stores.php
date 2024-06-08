<?php

class Stores extends Controller
{
    private $storemodel;
    public function __construct()
    {
        parent::__construct();
        $this->storemodel = $this->model('Store');
    }

    public function index()
    {
        $data = [
            'title' => 'Stores',
            'stores' => $this->storemodel->get_all(),
        ];
        $this->view('stores/index',$data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add new store',
            'id' => '',
            'is_edit' => false,
            'store_name' => '',
            'store_name_err' => '',
            'error' => null
        ];
        $this->view('stores/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('store_msg','Invalid request method', alert_type('error'));
            redirect('stores/new');
            exit();
        }

        $is_edit = filter_input(INPUT_POST, 'is_edit', FILTER_VALIDATE_BOOLEAN);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); 
        $store_name = filter_input(INPUT_POST, 'store_name', FILTER_SANITIZE_SPECIAL_CHARS); 

        $data = [
            'title' => $is_edit ? 'Update store' : 'Add new store',
            'id' => !empty($id) ? $id : '',
            'is_edit' => $is_edit,
            'store_name' => !empty($store_name) ? trim($store_name) : null,
            'store_name_err' => '',
            'error' => null
        ];

        if(is_null($data['store_name'])){
            $data['store_name_err'] = 'Enter store name';
        }

        if(!empty($data['store_name_err'])){
            $this->view('stores/new',$data);
            exit();
        }

        if($this->storemodel->store_exists($data)){
            $data['error'] = 'Store already exists';
            $this->view('stores/new',$data);
            exit();
        }

        if(!$this->storemodel->create_update($data)){
            $data['error'] = 'Something went wrong while creating/updating store';
            $this->view('stores/new',$data);
            exit();
        }

        redirect('stores');
    }
}