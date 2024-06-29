<?php
class Wastages extends Controller
{
    private $authmodel;
    private $wastagemodel;
    private $reusablemodel;

    public function __construct()
    {
        parent::__construct();
        $this->authmodel = $this->model('Auths');
        $this->wastagemodel = $this->model('Wastage');
        $this->reusablemodel = $this->model('Reusable');
        check_rights($this->authmodel,'wastages');
    }

    public function index()
    {
        $data = [
            'title' => 'Wastages',
            'wastages' => $this->wastagemodel->get_wastages()
        ];
        $this->view('wastages/index',$data);
 
    }

    public function new()
    {
        $data = [
            'title' => 'Add new wastage',
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'id' => '',
            'is_edit' => false,
            'product' => '',
            'date' => date('Y-m-d'),
            'qty_wasted' => '',
            'cost' => '',
            'wastage_value' => '',
            'remarks' => '',
            'file' => null,
            'file_name' => '',
            'product_err' => '',
            'date_err' => '',
            'qty_wasted_err' => '',
            'cost_err' => '',
            'remarks_err' => '',
            'errors' => []
        ];
        $this->view('wastages/new',$data);
    }

    public function create_update()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('wastage_msg','Invalid request method', alert_type('error'));
            redirect('wastages/new');
            exit();
        }

        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
        $product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);
        $qty_wasted = filter_input(INPUT_POST, 'qty_wasted', FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_ALLOW_FRACTION);
        $cost = filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $remarks = filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_SPECIAL_CHARS);
        // $file = $_FILES['file'];
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $is_edit = filter_input(INPUT_POST,'is_edit',FILTER_VALIDATE_BOOLEAN);

        $data = [
            'title' => $is_edit ? 'Update wastage' : 'Add new wastage',
            'id' => !$is_edit ? cuid() : $id,
            'is_edit' => $is_edit,
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'product' => !empty($product) ? $product : '',
            'date' => !empty($date) ? $date : '',
            'qty_wasted' => !empty($qty_wasted) ? $qty_wasted : '',
            'cost' => !empty($cost) ? $cost : '',
            'wastage_value' => !empty($qty_wasted) && !empty($cost) ? $qty_wasted * $cost : '',
            'remarks' => !empty($remarks) ? $remarks : '',
            'file' =>  null,
            'file_name' => '',
            'product_err' => '',
            'date_err' => '',
            'qty_wasted_err' => '',
            'cost_err' => '',
            'remarks_err' => '',
            'errors' => []
        ];

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 4 * 1024 * 1024; 

        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 4 * 1024 * 1024; // 4MB
    
            if ($file['size'] > $max_size) {
                $data['errors'][] = "File size exceeds the maximum allowed size (4 MB)";
            }
    
            if (!in_array($file['type'], $allowed_types)) {
                $data['errors'][] = "Invalid file type. Only JPEG, PNG, and GIF images are allowed.";
            }
    
            if (count($data['errors']) === 0) {
                $target_dir = "uploads/";
                $filename = cuid() . str_replace(' ', '_', $file["name"]);
                $target_file = $target_dir . basename($filename);
    
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    $data['file_name'] = $target_file;
                } else {
                    $data['errors'][] = "Failed to upload file";
                }
            }
        }

        if(empty($data['product'])){
            $data['product_err'] = 'Select product';
        }
        if(empty($data['date'])){
            $data['date_err'] = 'Select date';
        }
        if(empty($data['qty_wasted'])){
            $data['qty_wasted_err'] = 'Enter quantity wasted';
        }
        if(empty($data['cost'])){
            $data['cost_err'] = 'Provide rate for item';
        }
        if(empty($data['remarks'])){
            $data['remarks_err'] = 'Enter remarks on wastage';
        }
        

        if(!empty($data['date_err']) || !empty($data['qty_wasted_err']) || !empty($data['cost_err']) 
           || !empty($data['remarks_err']) || count($data['errors']) > 0){
           
           $this->view('wastages/new', $data);
           exit();
        }

        if(!$this->wastagemodel->create_update($data)){
            $data['errors'][] = 'Something went wrong while performing this action. Contact support.';
            $this->view('wastages/new', $data);
            exit();
        }

        redirect('wastages');
    }

    public function edit($id)
    {
        $wastage = $this->wastagemodel->get_wastage($id);
        if(!$wastage || empty($wastage)){
           $this->not_found('/wastages', 'The wastage you are trying to edit doesn\'t exist');
           exit();
        }
        $data = [
            'title' => 'Update wastage',
            'products' => $this->reusablemodel->get_products_by_store($_SESSION['store']),
            'id' => $wastage->id,
            'is_edit' => true,
            'product' => $wastage->product_id,
            'date' => date('Y-m-d',strtotime($wastage->wastage_date)),
            'qty_wasted' => $wastage->qty,
            'cost' => $wastage->rate,
            'wastage_value' => $wastage->qty * $wastage->rate,
            'remarks' => $wastage->remarks,
            'file' => null,
            'file_name' => $wastage->image_url,
            'product_err' => '',
            'date_err' => '',
            'qty_wasted_err' => '',
            'cost_err' => '',
            'remarks_err' => '',
            'errors' => []
        ];
        $this->view('wastages/new',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            flash('wastage_msg','Invalid request method', alert_type('error'));
            redirect('wastages');
            exit();
        }

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(empty($id)){
            flash('wastage_msg','Unable to get selected wastage.', alert_type('error'));
            redirect('wastages');
            exit();
        }
        
        if(!$this->wastagemodel->delete($id)){
            flash('wastage_msg','Cannot delete this wastage. Please try again later.', alert_type('error'));
            redirect('wastages');
            exit();
        }

        redirect('/wastages');
    }
}