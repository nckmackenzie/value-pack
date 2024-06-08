<?php

class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();  
    }
    public function index()
    {
        $data = ['title' => 'Dashboard'];
        $this->view('dashboard/index',$data);
    }
}