<?php

class Wastage
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_wastages()
    {
        $sql = "SELECT 
                    w.id,
                    wastage_date,
                    p.product_name,
                    w.qty,
                    w.wastage_value,
                    w.image_url
                FROM wastages w
                JOIN products p
                ON w.product_id = p.id
                WHERE
                    w.store_id = ?
                ORDER BY w.id DESC
                ";
        return resultset($this->db->dbh, $sql, [$_SESSION['store']]);
    }

    function save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO wastages (id, wastage_date, product_id, qty, rate, wastage_value, remarks, image_url, store_id, created_by)
                    VALUES (:id, :wastage_date, :product_id, :qty, :rate, :wastage_value, :remarks, :image_url, :store_id, :creator) ";
            
            $this->db->query($sql);
            $this->db->bind(':id',$data['id']);
            $this->db->bind(':wastage_date', $data['date']);
            $this->db->bind(':product_id', $data['product']);
            $this->db->bind(':qty', $data['qty_wasted']);
            $this->db->bind(':rate', $data['cost']);
            $this->db->bind(':wastage_value', $data['wastage_value']);
            $this->db->bind(':remarks', $data['remarks']);
            $this->db->bind(':image_url', !empty($data['file_name']) ? $data['file_name'] : null);
            $this->db->bind(':store_id', $_SESSION['store']);
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

    function update($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "UPDATE wastages SET wastage_date = :wastage_date, product_id = :product_id, qty = :qty, rate = :rate, 
                                        wastage_value = :wastage_value, remarks = :remarks, image_url = :image_url, created_by = :creator
                    WHERE (id = :id)";
            
            $this->db->query($sql);            
            $this->db->bind(':wastage_date', $data['date']);
            $this->db->bind(':product_id', $data['product']);
            $this->db->bind(':qty', $data['qty_wasted']);
            $this->db->bind(':rate', $data['cost']);
            $this->db->bind(':wastage_value', $data['wastage_value']);
            $this->db->bind(':remarks', $data['remarks']);
            $this->db->bind(':image_url', !empty($data['file_name']) ? $data['file_name'] : null);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type', 'wastage');
            $this->db->bind(':id', $data['id']);
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
        }else{
            return $this->update($data);
        }
    }

    public function get_wastage($id)
    {
        return singleset($this->db->dbh,'SELECT * FROM wastages WHERE id = ?',[$id]);
    }

    function delete($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type', 'wastage');
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query('DELETE FROM wastages WHERE (id = :id)');
            $this->db->bind(':id', $id);
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
}