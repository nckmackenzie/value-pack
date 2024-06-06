<?php

class Reusable
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    
    public function get_stores()
    {
        $sql = 'SELECT ID,UPPER(Store_Name) as Store_Name FROM stores WHERE Active=?';
        return resultset($this->db->dbh,$sql,[true]);
    }
}