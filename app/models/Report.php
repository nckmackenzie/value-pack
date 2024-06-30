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

    public function get_sales_report($data)
    {
        if($data['report_type'] === 'summary'){
            $sql = "SELECT
                        p.product_name,
                        coalesce(sum(s.qty),0) as qty,
                        coalesce(sum(s.amount),0) as amount
                    FROM
                        sales s join products p on s.product_id = p.id
                    WHERE
                        (s.sale_date BETWEEN ? AND ?) AND (s.store_id = ?)
                    GROUP BY
                        p.product_name
                    ORDER BY
                        p.product_name
                    ";
        }else{
            $sql = "SELECT
                        s.sale_date,
                        p.product_name,
                        c.customer_name,
                        s.qty,
                        s.rate,
                        s.amount
                    FROM
                        sales s join products p on s.product_id = p.id join customers c on s.customer_id = c.id
                    WHERE
                        (s.sale_date BETWEEN ? AND ?) AND (s.store_id = ?)
                    ORDER BY
                        s.sale_date
                    ";
        }
        
        return resultset($this->db->dbh,$sql,[$data['start_date'],$data['end_date'],$_SESSION['store']]);
    }
}