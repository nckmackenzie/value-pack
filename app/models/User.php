<?php
class User
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    public function get_users()
    {
        $sql = 'SELECT u.id, user_name, contact, role_name, active
                FROM users u join roles r on u.role_id = r.id';
        return resultset($this->db->dbh,$sql,[]);
    }

    public function get_roles()
    {
        return resultset($this->db->dbh,'SELECT * from roles ORDER BY role_name',[]);
    }

    public function contact_exists($contact,$id)
    {
        $sql = 'SELECT count(*) FROM users WHERE contact = ? AND id <> ?';
        return (int)getdbvalue($this->db->dbh,$sql,[$contact,$id]) > 0;
    }

    public function create_update($data)
    {
        try {
            
            $this->db->dbh->beginTransaction();

            if(!$data['is_edit']){
                $sql = "INSERT INTO users (id, user_name, password, contact, role_id, active) 
                        VALUES(:id, :user_name, :password, :contact, :role_id, :active)";
                $this->db->query($sql);
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':user_name', strtolower($data['user_name']));
                $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
                $this->db->bind(':contact', $data['contact']);
                $this->db->bind(':role_id', $data['role']);
                $this->db->bind(':active', $data['active']);
                $this->db->execute();
            }else{
                $sql = "UPDATE users SET user_name = :user_name, contact = :contact, role_id = :role_id, active = :active 
                        WHERE (id = :id)";
                $this->db->query($sql);
                $this->db->bind(':user_name', strtolower($data['user_name']));
                $this->db->bind(':contact', $data['contact']);
                $this->db->bind(':role_id', $data['role']);
                $this->db->bind(':active', $data['active']);
                $this->db->bind(':id', $data['id']);
                $this->db->execute();

                $this->db->query('DELETE FROM user_stores WHERE user_id = :id');
                $this->db->bind(':id', $data['id']);
                $this->db->execute();
            }

            foreach($data['stores_allowed'] as $store){
                $this->db->query('INSERT INTO user_stores (user_id, store_id) VALUES(:user_id, :store_id)');
                $this->db->bind(':user_id', $data['id']);
                $this->db->bind(':store_id', $store);
                $this->db->execute();
            }

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

    public function get_user($id)
    {
        return singleset($this->db->dbh,'SELECT * FROM users WHERE id = ?',[$id]);
    }
}