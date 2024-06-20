<?php
class Role
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_roles()
    {
        return resultset($this->db->dbh,'SELECT * FROM roles WHERE id > 2',[]);
    }

    public function get_forms($is_edit = false, $role = null)
    {
        if(!$is_edit){
            $sql = 'SELECT 
                        id, 
                        module,
                        form_name
                    FROM  
                        forms
                    ORDER BY module_id, menu_order';
            return resultset($this->db->dbh,$sql,[]);
        }else{
            $sql = 'SELECT 
                        id, 
                        module,
                        form_name,
                        fn_right_set(f.id,?) as checked
                    FROM  
                        forms f
                    ORDER BY module_id, menu_order';
            return resultset($this->db->dbh,$sql,[$role]);
        }
    }

    public function role_exists($role,$id)
    {
        $sql = 'SELECT COUNT(*) FROM roles WHERE id <> ? AND role_name = ?';
        return (int)getdbvalue($this->db->dbh,$sql,[$id,$role]) > 0;
    }

    public function create_update($data)
    {
        try {
            
            $this->db->dbh->beginTransaction();

            if(!$data['is_edit']){
                $this->db->query('INSERT INTO roles (role_name) values(:role_name)');
                $this->db->bind(':role_name', strtolower($data['role_name']));
                $this->db->execute();
            }
            $id = $data['is_edit'] ? $data['id'] : $this->db->dbh->lastInsertId();

            if($data['is_edit']){
                $this->db->query('UPDATE roles SET role_name = :role_name WHERE id = :id');
                $this->db->bind(':role_name', strtolower($data['role_name']));
                $this->db->bind(':id', $data['id']);
                $this->db->execute();

                $this->db->query('DELETE FROM role_rights WHERE role_id = :role_id');
                $this->db->bind(':role_id', $id);
                $this->db->execute();
            }
            
            foreach($data['forms'] as $form){
                if($form['checked'] === 'true'){
                    $this->db->query('INSERT INTO role_rights (role_id, form_id) VALUES(:role_id, :form_id)');
                    $this->db->bind(':role_id', $id);
                    $this->db->bind(':form_id', $form['form_id']);
                    $this->db->execute();
                }
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

    public function get_role($id)
    {
        return singleset($this->db->dbh,'SELECT * FROM roles WHERE id = ?',[$id]);
    }
}