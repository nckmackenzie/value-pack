<?php
declare(strict_types= 1);
class Auths
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function contact_exists($contact):bool {
        $sql = 'SELECT COUNT(*) FROM users WHERE contact=?';
        return (int)getdbvalue($this->db->dbh,$sql,[$contact]) > 0;
    }
    
    public function get_user($userid){
        return singleset($this->db->dbh,'SELECT * FROM users WHERE contact=?',[$userid]);
    }

    public function check_rights($form)
    {
        return check_role_rights($this->db->dbh,$_SESSION['role'],$form);
    }
}