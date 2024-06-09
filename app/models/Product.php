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
        return resultset($this->db->dbh,'SELECT * FROM products ORDER BY product_name',[]);
    }

    public function create_update($data){
        try {
            $this->db->dbh->beginTransaction();

            if(!$data['is_edit']){
                $sql = "INSERT INTO products (id,product_name,product_code,unit_id,buying_price,selling_price,reorder_level,created_by) ";
                $sql .= "VALUES (:id,:product_name,:product_code,:unit_id,:buying_price,:selling_price,:reorder_level,:creator)";
            }else{
                $sql = "UPDATE products SET product_name = :product_name, product_code = :product_code, unit_id = :unit_id, buying_price = :buying_price, ";
                $sql .= "selling_price = :selling_price,reorder_level = :reorder_level,created_by = :creator WHERE (id = :id)";
            }

            $this->db->query($sql);
            if(!$data["is_edit"]){
                $this->db->bind(':id',$data['id']);
            }           
            $this->db->bind(':product_name',strtolower($data['name']));
            $this->db->bind(':product_code',strtolower($data['code']));
            $this->db->bind(':unit_id',$data['unit']);
            $this->db->bind(':buying_price',$data['buying_price']);
            $this->db->bind(':selling_price',$data['selling_price']);
            $this->db->bind(':reorder_level',$data['restock_level']);
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
}