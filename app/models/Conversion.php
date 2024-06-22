<?php
class Conversion
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function create_update($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO conversions_headers (id, conversion_date, final_product, converted_qty, created_by) ";
            $sql .= "VALUES (:id, :conversion_date, :final_product, :converted_qty, :creator) ";

            $this->db->query($sql);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':conversion_date', $data['date']);
            $this->db->bind(':final_product', $data['final_product']);
            $this->db->bind(':converted_qty', $data['converted_qty']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO conversions_details (header_id, product_id, qty) VALUES (:id, :product_id, :qty)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['converted_qty']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':type', 'conversion');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['converted_qty'] * -1);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->execute();        
            }

            $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':type', 'conversion');
            $this->db->bind(':store', $_SESSION['store']);
            $this->db->bind(':product', $data['final_product']);
            $this->db->bind(':qty', $data['converted_qty']);
            $this->db->bind(':tid', $data['id']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }
            
            return true;

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage());
            return false;
        }
    }
}