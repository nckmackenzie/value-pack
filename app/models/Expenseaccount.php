<?php
class Expenseaccount
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_all()
    {
        return resultset($this->db->dbh,'SELECT * FROM expense_accounts',[]);
    }

    public function account_exists($data){
        $count = (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM expense_accounts WHERE account_name=? AND id <> ?',[$data['account_name'],$data['id']]);
        return $count > 0;
    }

    public function get_account($id){
        return singleset($this->db->dbh,'SELECT * FROM expense_accounts WHERE id=?',[$id]);
    }

    public function create_update($data){
        if($data['is_edit']){
            $this->db->query('UPDATE expense_accounts SET account_name=:store, active=:active WHERE id = :id');
            $this->db->bind(':store',strtolower($data['account_name']));
            $this->db->bind(':active',$data['active']);
            $this->db->bind(':id',$data['id']);
        }else{
            $this->db->query('INSERT INTO expense_accounts (id,account_name) VALUES (:id,:store)');
            $this->db->bind(':id',cuid());
            $this->db->bind(':store',strtolower($data['account_name']));
        }
        if(!$this->db->execute()){
            return false;
        }
        return true;
    }

    public function is_referenced($id):bool
    {
        
        $count =  (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM expenses WHERE account_id = ?',[$id]);
        if($count > 0){
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM expense_accounts WHERE id = :id');
        $this->db->bind(':id', $id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}