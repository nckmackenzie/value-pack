<?php
class Store
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_all()
    {
        return resultset($this->db->dbh,'SELECT * FROM stores',[]);
    }

    public function store_exists($data){
        $count = (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM stores WHERE Store_Name=? AND ID <> ?',[$data['store_name'],$data['id']]);
        return $count > 0;
    }

    public function get_store($id){
        return singleset($this->db->dbh,'SELECT * FROM stores WHERE ID=?',[$id]);
    }

    public function create_update($data){
        if($data['is_edit']){
            $this->db->query('UPDATE stores SET Store_Name=:store, Active=:active WHERE ID = :id');
            $this->db->bind(':store',strtolower($data['store_name']));
            $this->db->bind(':active',$data['active']);
            $this->db->bind(':id',$data['id']);
        }else{
            $this->db->query('INSERT INTO stores (ID,Store_Name) VALUES (:id,:store)');
            $this->db->bind(':id',cuid());
            $this->db->bind(':store',strtolower($data['store_name']));
        }
        if(!$this->db->execute()){
            return false;
        }
        return true;
    }

    public function is_referenced($id):bool
    {
        
        $count =  (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM user_stores WHERE store_id = ?',[$id]);
        if($count > 0){
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM stores WHERE ID = :id');
        $this->db->bind(':id', $id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}