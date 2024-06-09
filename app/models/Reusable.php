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

    public function get_units()
    {
        $sql = 'SELECT id,UPPER(unit) as unit FROM units ORDER BY unit';
        return resultset($this->db->dbh,$sql,[]);
    }
}