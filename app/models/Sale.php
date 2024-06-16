<?php 
class Sale
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_sale_no()
    {
        return get_next_db_no($this->db->dbh,'sales','sale_no');
    }

    public function create_update($data)
    {

        try {
            $this->db->dbh->beginTransaction();

            if(!$data['is_edit']){
                $sql = "INSERT INTO sales (id, sale_date, sale_no, customer_id, sale_type, product_id, qty, rate, amount, created_by) 
                        VALUES (:id,:sale_date,:sale_no,:customer_id,:sale_type,:product_id,:qty,:rate,:amount,:created_by) ";
            }else{
                $sql = "UPDATE sales SET sale_date = :sale_date, sale_no = :sale_no, customer_id = :customer_id, sale_type = :sale_type,  
                                         product_id = :product_id, qty = :qty, rate = :rate, amount = :amount, created_by = :created_by
                        WHERE (id = :id) ";
            }
            $this->db->query($sql);
            if(!$data['is_edit']){
                $this->db->bind(':id',$data['id']);
            }
            $this->db->bind(':sale_date',$data['sale_date']);
            $this->db->bind(':sale_no',$data['sale_no']);
            $this->db->bind(':customer_id',$data['customer']);
            $this->db->bind(':sale_type',$data['sale_type']);
            $this->db->bind(':product_id',$data['product']);
            $this->db->bind(':qty',$data['qty']);
            $this->db->bind(':rate',$data['rate']);
            $this->db->bind(':amount',$data['total_value']);
            $this->db->bind(':created_by',$_SESSION['user_id']);
            if($data['is_edit']){
                $this->db->bind(':id',$data['id']); 
            }
            $this->db->execute();

            if(!$data['is_edit']){
                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) 
                                  VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['sale_date']);
                $this->db->bind(':type', 'sale');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $data['product']);
                $this->db->bind(':qty', $data['qty'] * -1);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->execute();                      
            }else{
                $this->db->query('UPDATE stock_movements SET transaction_date = :date,product_id = :product, qty = :qty,
                                                             created_by = :creator 
                                  WHERE (transaction_id = :tid) AND (transaction_type = :type)');
                $this->db->bind(':date', $data['sale_date']);
                $this->db->bind(':product', $data['product']);
                $this->db->bind(':qty', $data['qty'] * -1);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':type', 'sale');
                $this->db->execute();    
            }            
               

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