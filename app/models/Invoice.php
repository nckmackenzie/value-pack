<?php

class invoice
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_invoices()
    {
        $sql = "SELECT 
                    i.id,
                    invoice_date,
                    invoice_no,
                    customer_name,
                    inclusive_amount
                FROM
                    invoices_headers i join customers c on i.customer_id = c.id
                WHERE 
                    store_id=?";
        return resultset($this->db->dbh,$sql,[$_SESSION['store']]);
    }

    public function get_invoice_no()
    {
        return get_next_db_no($this->db->dbh,'invoices_headers','invoice_no');
    }

    function save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO invoices_headers (id, invoice_date, customer_id, invoice_no, vat_type, vat, exclusive_amount, vat_amount, inclusive_amount, store_id, created_by) ";
            $sql .= "VALUES (:id, :date, :customer, :invoice_no, :vat_type, :vat, :amount_exclusive, :vat_amount, :amount_inclusive, :store, :creator) ";

            $this->db->query($sql);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':customer', $data['customer']);
            $this->db->bind(':invoice_no', strtolower($data['invoice_no']));
            $this->db->bind(':vat_type', $data['vat_type']);
            $this->db->bind(':vat', $data['vat']);            
            $this->db->bind(':amount_exclusive', calculate_vat($data['vat_type'],$data['total'])[0]);
            $this->db->bind(':vat_amount', calculate_vat($data['vat_type'],$data['total'])[1]);
            $this->db->bind(':amount_inclusive', calculate_vat($data['vat_type'],$data['total'])[2]);
            $this->db->bind(':store', $_SESSION['store']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO invoices_details (header_id, product_id, qty, rate, gross) VALUES (:id, :product_id, :qty, :rate, :gross)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':rate', $item['rate']);
                $this->db->bind(':gross', floatval($item['qty']) * floatval($item['rate']));
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':type', 'invoice');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['qty'] * -1);
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

            $sql = "UPDATE invoices_headers SET invoice_date = :date, customer_id = :customer, vat_type = :vat_type, vat = :vat, 
                                               exclusive_amount = :amount_exclusive, vat_amount = :vat_amount, inclusive_amount = :amount_inclusive, created_by = :creator 
                    WHERE id = :id";
            
            $this->db->query($sql);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':customer', $data['customer']);
            $this->db->bind(':vat_type', $data['vat_type']);
            $this->db->bind(':vat', $data['vat']);            
            $this->db->bind(':amount_exclusive', calculate_vat($data['vat_type'],$data['total'])[0]);
            $this->db->bind(':vat_amount', calculate_vat($data['vat_type'],$data['total'])[1]);
            $this->db->bind(':amount_inclusive', calculate_vat($data['vat_type'],$data['total'])[2]);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM invoice_details WHERE (header_id = :header_id)');
            $this->db->bind(':header_id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type','invoice');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO invoices_details (header_id, product_id, qty, rate, gross) VALUES (:id, :product_id, :qty, :rate, :gross)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':rate', $item['rate']);
                $this->db->bind(':gross', floatval($item['qty']) * floatval($item['rate']));
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':type', 'invoice');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['qty'] * -1);
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

    public function get_invoice($id)
    {
        $sql = "SELECT 
                    i.id,
                    invoice_date,
                    invoice_no,
                    customer_name,
                    contact,
                    pin,
                    exclusive_amount,
                    vat_amount,
                    inclusive_amount
                FROM
                    invoices_headers i join customers c on i.customer_id = c.id
                WHERE 
                    i.id=?";
        return singleset($this->db->dbh,$sql,[$id]);
    }

    public function invoice_items($id)
    {
        $sql = "SELECT
                    product_name,
                    qty,
                    rate,
                    gross
                FROM 
                    invoices_details d join products p on d.product_id = p.id
                WHERE 
                    d.header_id = ?
        ";
        return resultset($this->db->dbh,$sql,[$id]);
    }
}