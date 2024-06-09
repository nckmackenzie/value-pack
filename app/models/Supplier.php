<?php
class Supplier
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_all()
    {
        return resultset($this->db->dbh,'SELECT * FROM suppliers',[]);
    }

    public function check_exists($field,$value,$id){
        $sql = "SELECT COUNT(*) FROM suppliers WHERE $field = ? AND id <> ?";
        return (int) getdbvalue($this->db->dbh,$sql,[$value,$id]) > 0;
    }

    public function get_supplier($id){
        return singleset($this->db->dbh,'SELECT * FROM suppliers WHERE ID=?',[$id]);
    }

    public function create_update($data){
       try {
        
        if($data['is_edit']){
            $sql = 'UPDATE suppliers SET supplier_Name = :supplier, Contact = :contact, email = :email, contact_person = :contact_person,';
            $sql .= 'active = :active WHERE id = :id';
        }else{
            $sql = 'INSERT INTO suppliers (id,supplier_name,contact,email,contact_person,active) VALUES (:id,:supplier,:contact,:email,:contact_person,:active)';
        }
        $this->db->query($sql);
        if(!$data['is_edit']){
            $this->db->bind(':id',$data['id']);
        }
        $this->db->bind(':supplier',strtolower($data['supplier_name']));
        $this->db->bind(':contact',strtolower($data['contact']));
        $this->db->bind(':email',strtolower($data['email']));
        $this->db->bind(':contact_person',strtolower($data['contact_person']));
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
        $count =  (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM user_suppliers WHERE supplier_id = ?',[$id]);
        if($count > 0){
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM suppliers WHERE ID = :id');
        $this->db->bind(':id', $id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}