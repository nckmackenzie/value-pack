<?php

class Report
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_stock_report($data)
    {
        $sql = "CALL sp_get_stock_report(?, ?, ?)";
        return resultset($this->db->dbh,$sql,[$_SESSION['store'],$data['start_date'],$data['end_date']]);
    }
}