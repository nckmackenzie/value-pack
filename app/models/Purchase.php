<?php
class Purchase
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
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

    public function create_update($data)
    {
        if(!$data['is_edit']){
            return $this->save($data);
        }
    }
}