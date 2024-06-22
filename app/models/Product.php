<?php
declare(strict_types= 1);
class Product
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function check_exists($field,$item,$id):bool {
        $sql = "SELECT COUNT(*) FROM products WHERE $field = ? AND id <> ?";
        return (int) getdbvalue($this->db->dbh,$sql,[$item,$id]) > 0;
    }

    public function get_products(){
        $sql = "
            SELECT
                id,
                product_name,
                product_code,
                unit_id,
                buying_price,
                selling_price,
                description,
                IFNULL(reorder_level,0) AS reorder_level,
                is_stock_item,
                active,
                fn_get_current_stock(?,id,CURDATE()) as current_stock
            FROM 
                products
            ORDER BY
                product_name
        ";
        return resultset($this->db->dbh,$sql,[$_SESSION['store']]);
    }

    public function get_product($id){
        $count = (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM products WHERE id = ?',[$id]);
        if($count === 0) return false;

        return singleset($this->db->dbh,'SELECT * FROM products WHERE id = ?',[$id]);
    }

    public function get_product_stores($id){
        return resultset($this->db->dbh,'SELECT store_id FROM product_stores WHERE product_id = ?',[$id]);
    }    

    public function create_update($data){
        try {
            $this->db->dbh->beginTransaction();

            if(!$data['is_edit']){
                $sql = "INSERT INTO products (id,product_name,product_code,unit_id,buying_price,selling_price,reorder_level,is_stock_item,created_by) ";
                $sql .= "VALUES (:id,:product_name,:product_code,:unit_id,:buying_price,:selling_price,:reorder_level,:is_stock,:creator)";
            }else{
                $sql = "UPDATE products SET product_name = :product_name, product_code = :product_code, unit_id = :unit_id, buying_price = :buying_price, ";
                $sql .= "selling_price = :selling_price,reorder_level = :reorder_level, is_stock_item = :is_stock, created_by = :creator WHERE (id = :id)";
            }

            $this->db->query($sql);
            if(!$data["is_edit"]){
                $this->db->bind(':id',$data['id']);
            }           
            $this->db->bind(':product_name',strtolower($data['name']));
            $this->db->bind(':product_code',$data['code']);
            $this->db->bind(':unit_id',$data['unit']);
            $this->db->bind(':buying_price',!empty($data['buying_price']) ? $data['buying_price'] : 0);
            $this->db->bind(':selling_price',!empty($data['selling_price']) ? $data['selling_price'] : 0);
            $this->db->bind(':reorder_level',$data['restock_level']);
            $this->db->bind(':is_stock',$data['is_stock']);
            $this->db->bind(':creator',$_SESSION['user_id']);
            if($data["is_edit"]){
                $this->db->bind(':id',$data['id']);
            }    
            $this->db->execute();           

            if($data['is_edit']){
                $this->db->query('DELETE FROM product_stores WHERE (product_id = :product_id)');
                $this->db->bind(':product_id',$data['id']);
                $this->db->execute();
            }

            $sql = "INSERT INTO product_stores (product_id,store_id) VALUES(:product_id,:store_id) ";
            foreach($data['stores_allowed'] as $store){
                $this->db->query($sql);
                $this->db->bind(':product_id',$data['id']);
                $this->db->bind(':store_id',$store);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }

            return true;

        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage());
            return false;
        }
    }

    public function product_found($product_id):bool
    {
        return (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM products WHERE id=?',[$product_id]) > 0;
    }

    public function get_rate($product_id,$is_sale = false)
    {
        if(!$is_sale){
            return floatval(getdbvalue($this->db->dbh,'SELECT IFNULL(buying_price,0) FROM products WHERE id=?',[$product_id]));
        }

        return floatval(getdbvalue($this->db->dbh,'SELECT IFNULL(selling_price,0) FROM products WHERE id=?',[$product_id]));
    }

    public function get_current_stock_balance($store,$product,$date)
    {
        return getdbvalue($this->db->dbh,'SELECT fn_get_current_stock(?,?,?) AS Stock;',[$store,$product,$date]);
    }

    public function is_referenced($id)
    {
        return (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM stock_movements WHERE product_id = ?',[$id]) > 0;
    }

    public function delete($id)
    {
        try {
            
            $this->db->dbh->beginTransaction();

            $this->db->query('DELETE FROM product_stores WHERE product_id = :product_id');
            $this->db->bind(':product_id', $id);
            $this->db->execute();

            $this->db->query('DELETE FROM products WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();

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
}