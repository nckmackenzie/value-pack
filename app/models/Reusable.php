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
        $sql = 'SELECT ID,UPPER(Store_Name) as StoreName FROM stores WHERE Active=?';
        return resultset($this->db->dbh,$sql,[true]);
    }
}