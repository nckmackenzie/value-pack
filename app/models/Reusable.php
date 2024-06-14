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
        $sql = 'SELECT ID as id,UPPER(Store_Name) as store_name FROM stores WHERE Active=?';
        return resultset($this->db->dbh,$sql,[true]);
    }

    public function get_units()
    {
        $sql = 'SELECT id,UPPER(unit) as unit FROM units ORDER BY unit';
        return resultset($this->db->dbh,$sql,[]);
    }

    public function get_products()
    {
        return resultset($this->db->dbh,'SELECT id,ucase(product_name) as product_name from products order by product_name',[]);
    }

    public function get_vendors()
    {
        return resultset($this->db->dbh,'SELECT id,ucase(supplier_name) as supplier_name from suppliers order by supplier_name',[]);
    }

    public function get_products_by_store($store)
    {
        $sql = 'SELECT 
                    product_id as id,
                    product_name as product_name 
                FROM product_stores s 
                    join products p on s.product_id = p.id 
                WHERE 
                    store_id = ?
                ORDER BY 
                    product_name';
        return resultset($this->db->dbh,$sql,[$store]);
    }

    public function get_current_stock_balance($store,$product,$date)
    {
        return getdbvalue($this->db->dbh,'SELECT fn_get_current_stock(?,?,?) AS Stock;',[$store,$product,$date]);
    }

    public function get_customers()
    {
        return resultset($this->db->dbh,'SELECT id,customer_name from customers order by customer_name;',[]);
    }

}