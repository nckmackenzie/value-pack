<?php
/*
 * Base Controller
 * Loads the model and views
 */
class Controller {
    public function __construct() {
        if (!$this->should_skip_authentication()) {
            $this->is_authenticate();
        }
    }


    public function model($model)
    {
        //require model file
        require_once '../app/models/' . $model . '.php';

        #new instance of model
        return new $model;
    }

    protected function is_authenticate() {
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
            exit();
        }
    }

    protected function should_skip_authentication() {
        return false;
    }

    //load view
    public function view($view, $data = [])
    {
        //check for view file
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        }
        else{
            //view doesn't exists
            die('View Doesn\'t Exist');
        }
    }

    public function not_found($path,$message)
    {
        $data = [
            'title' => 'Not found',
            'path' => $path,
            'message' => $message
        ];
        $this->view('pages/not-found',$data);
    }
}