<?php
class Purchase
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_purchases()
    {
        $sql = 'SELECT h.id,purchase_date,reference,supplier_name,amount_inclusive as total FROM purchases_headers h ';
        $sql .= 'join suppliers s on h.supplier_id = s.id ORDER BY purchase_date DESC';

        return resultset($this->db->dbh,$sql,[]);
    }

    function save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO purchases_headers (id, purchase_date, supplier_id, reference, vat_type, vat, store_id,amount_exclusive, vat_amount, amount_inclusive,created_by) ";
            $sql .= "VALUES (:id, :date, :vendor, :reference, :vat_type, :vat, :store, :amount_exclusive, :vat_amount, :amount_inclusive, :creator) ";

            $this->db->query($sql);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':vendor', $data['vendor']);
            $this->db->bind(':reference', strtolower($data['reference']));
            $this->db->bind(':vat_type', $data['vat_type']);
            $this->db->bind(':vat', $data['vat']);
            $this->db->bind(':store', $data['store']);
            $this->db->bind(':amount_exclusive', calculate_vat($data['vat_type'],$data['total'])[0]);
            $this->db->bind(':vat_amount', calculate_vat($data['vat_type'],$data['total'])[1]);
            $this->db->bind(':amount_inclusive', calculate_vat($data['vat_type'],$data['total'])[2]);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO purchases_details (header_id, product_id, qty, rate) VALUES (:id, :product_id, :qty, :rate)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':rate', $item['rate']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':type', 'purchase');
                $this->db->bind(':store', $data['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
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

    function update($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "UPDATE purchases_headers SET purchase_date=:date, supplier_id=:vendor, reference=:reference, vat_type=:vat_type, ";
            $sql .= "vat=:vat, store_id=:store, amount_exclusive=:amount_exclusive, vat_amount=:vat_amount, amount_inclusive=:amount_inclusive, ";
            $sql .= "created_by = :creator WHERE id=:id ";

            $this->db->query($sql);            
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':vendor', $data['vendor']);
            $this->db->bind(':reference', strtolower($data['reference']));
            $this->db->bind(':vat_type', $data['vat_type']);
            $this->db->bind(':vat', $data['vat']);
            $this->db->bind(':store', $data['store']);
            $this->db->bind(':amount_exclusive', calculate_vat($data['vat_type'],$data['total'])[0]);
            $this->db->bind(':vat_amount', calculate_vat($data['vat_type'],$data['total'])[1]);
            $this->db->bind(':amount_inclusive', calculate_vat($data['vat_type'],$data['total'])[2]);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM purchases_details WHERE (header_id = :header_id)');
            $this->db->bind(':header_id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type','purchase');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO purchases_details (header_id, product_id, qty, rate) VALUES (:id, :product_id, :qty, :rate)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':rate', $item['rate']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':type', 'purchase');
                $this->db->bind(':store', $data['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
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

    public function create_update($data)
    {
        if(!$data['is_edit']){
            return $this->save($data);
        }else{
            return $this->update($data);
        }
    }

    public function get_purchase($id)
    {
        $count = (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM purchases_headers WHERE id = ?',[$id]);
        if($count === 0) return false;

        return singleset($this->db->dbh,'SELECT * FROM purchases_headers WHERE id = ?',[$id]);
    }

    public function get_purchase_products($id)
    {
        $sql = "SELECT d.product_id,product_name,qty,rate,FORMAT((qty * rate),'N2') as total FROM purchases_details d ";
        $sql .= "join products p on d.product_id = p.id WHERE d.header_id = ?";
        return resultset($this->db->dbh,$sql,[$id]);
    }

    public function purchase_exists($id):bool
    {
        return (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM purchases_headers WHERE id=?',[$id]) > 0;
    }

    public function delete($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('DELETE FROM purchases_details WHERE (header_id = :header_id)');
            $this->db->bind(':header_id',$id);
            $this->db->execute();

            $this->db->query('DELETE FROM purchases_headers WHERE (id = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type','purchase');
            $this->db->bind(':id',$id);
            $this->db->execute();

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