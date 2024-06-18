<?php
class Customer
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_all()
    {
        return resultset($this->db->dbh,'SELECT * FROM customers',[]);
    }

    public function check_exists($field,$value,$id){
        $sql = "SELECT COUNT(*) FROM customers WHERE $field = ? AND id <> ?";
        return (int) getdbvalue($this->db->dbh,$sql,[$value,$id]) > 0;
    }

    public function get_customer($id){
        return singleset($this->db->dbh,'SELECT * FROM customers WHERE id =?',[$id]);
    }

    public function create_update($data){
       try {
        
        if($data['is_edit']){
            $sql = 'UPDATE customers SET customer_name = :customer, contact = :contact, email = :email, pin = :pin,';
            $sql .= 'active = :active WHERE id = :id';
        }else{
            $sql = 'INSERT INTO customers (id, customer_name, contact, email, pin, active) VALUES (:id, :customer, :contact, :email, :pin, :active)';
        }
        $this->db->query($sql);
        if(!$data['is_edit']){
            $this->db->bind(':id',$data['id']);
        }
        $this->db->bind(':customer',strtolower($data['customer_name']));
        $this->db->bind(':contact',strtolower($data['contact']));
        $this->db->bind(':email',strtolower($data['email']));
        $this->db->bind(':pin',strtolower($data['pin']));
        $this->db->bind(':active',$data['active']);
        if($data['is_edit']){
            $this->db->bind(':id',$data['id']);
        }
        if(!$this->db->execute()){
            return false;
        }
        return true;

       } catch (\Exception $e) {
        error_log($e->getMessage());
        return false;
       }
    }

    public function is_referenced($id):bool
    {
        $count =  (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM invoices_header WHERE customer_id = ?',[$id]);
        if($count > 0){
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM customers WHERE id = :id');
        $this->db->bind(':id', $id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}