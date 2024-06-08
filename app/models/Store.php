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

    public function create_update($data){
        if($data['is_edit']){

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
}