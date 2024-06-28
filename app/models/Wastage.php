<?php

class Wastage
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    function save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO wastages (id, wastage_date, product_id, qty, rate, wastage_value, remarks, image_url, created_by)
                    VALUES (:id, :wastage_date, :product_id, :qty, :rate, :wastage_value, :remarks, :image_url, :creator) ";
            
            $this->db->query($sql);
            $this->db->bind(':id',$data['id']);
            $this->db->bind(':wastage_date', $data['date']);
            $this->db->bind(':product_id', $data['product']);
            $this->db->bind(':qty', $data['qty_wasted']);
            $this->db->bind(':rate', $data['cost']);
            $this->db->bind(':wastage_value', $data['wastage_value']);
            $this->db->bind(':remarks', $data['remarks']);
            $this->db->bind(':image_url', !empty($data['file_name']) ? $data['file_name'] : null);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) 
                              VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':type', 'wastage');
            $this->db->bind(':store', $_SESSION['store']);
            $this->db->bind(':product', $data['product']);
            $this->db->bind(':qty', $data['qty_wasted'] * -1);
            $this->db->bind(':tid', $data['id']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();  

            if(!$this->db->dbh->commit()){
                return false;
            }
            
            return true;


            
        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }

    public function create_update($data)
    {
        if(!$data['is_edit']){
            return $this->save($data);
        }
    }
}