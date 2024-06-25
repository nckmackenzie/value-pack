<?php

class Receipt
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_receipts()
    {
        $sql = "SELECT
                    r.id,
                    receipt_date,
                    receipt_no,
                    s.Store_Name as store_name
                FROM
                    receipts_headers r join transfers_headers t on r.transfer_id = t.id
                    join stores s on t.store_from = s.ID
                WHERE
                    (store_id = ?)
                ORDER BY
                    receipt_no DESC
                ";
        return resultset($this->db->dbh,$sql,[$_SESSION['store']]);
    }

    public function get_receipt_no()
    {
        return get_next_db_no($this->db->dbh,'receipts_headers','receipt_no');
    }

    public function get_transfering_stores()
    {
        $sql = "SELECT
                    t.store_from as id,
                    s.store_name
                FROM
                    transfers_headers t join stores s on t.store_from = s.ID
                WHERE
                    (t.id NOT IN (SELECT transfer_id FROM receipts_headers))
                GROUP BY
                    t.store_from,
                    s.store_name";

        return resultset($this->db->dbh,$sql,[]);
    }

    public function get_transfers($store)
    {
        $sql = "SELECT
                    id,
                    transfer_no
                FROM
                    transfers_headers 
                WHERE
                    (store_from = ?) AND (id NOT IN (SELECT transfer_id FROM receipts_headers))
                ORDER BY
                    transfer_no DESC
                ";

        return resultset($this->db->dbh,$sql,[$store]);
    }

    public function get_items($transfer_no)
    {
        $sql = "SELECT
                    product_id,
                    product_name,
                    qty
                FROM
                    transfers_details t join products p on t.product_id = p.id
                WHERE
                    (header_id = ?)
                ";

        return resultset($this->db->dbh,$sql,[$transfer_no]);
    }

    public function date_is_earlier($transfer,$date)
    {
        $transfer_date = getdbvalue($this->db->dbh,'SELECT transfer_date FROM transfers_headers WHERE id=?',[$transfer]);
        return $date < date('Y-m-d',strtotime($transfer_date));
    }

    function save($data)
    {
        try {
            $receipt_no = $this->get_receipt_no();
            $this->db->dbh->beginTransaction();

            $sql = 'INSERT INTO receipts_headers (id,receipt_date, receipt_no, transfer_id, store_id, created_by) 
                    VALUES(:id,:receipt_date,:receipt_no,:transfer_id,:store_id,:creator)';
            $this->db->query($sql);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':receipt_date',$data['receipt_date']);
            $this->db->bind(':receipt_no', $receipt_no);
            $this->db->bind(':transfer_id', $data['transfer_no']);
            $this->db->bind(':store_id', $_SESSION['store']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $sql = 'INSERT INTO receipts_details (header_id, product_id, transfered_qty, received_qty)
                        VALUES (:id, :product_id, :transfered_qty, :received_qty)';
                $this->db->query($sql);
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':transfered_qty', $item['transfered_qty']);
                $this->db->bind(':received_qty', $item['received_qty']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['receipt_date']);
                $this->db->bind(':type', 'receipt');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['received_qty']);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->execute();        
            }

            if(!$this->db->dbh->commit()){
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    function update($data)
    {
        try {
            $this->db->dbh->beginTransaction();

            $sql = 'UPDATE receipts_headers SET receipt_date = :receipt_date, created_by = :creator 
                    WHERE (id = :id)';
            $this->db->query($sql);
            $this->db->bind(':receipt_date',$data['receipt_date']);
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM receipts_details WHERE (header_id = :header_id)');
            $this->db->bind(':header_id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type','receipt');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $sql = 'INSERT INTO receipts_details (header_id, product_id, transfered_qty, received_qty)
                        VALUES (:id, :product_id, :transfered_qty, :received_qty)';
                $this->db->query($sql);
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':transfered_qty', $item['transfered_qty']);
                $this->db->bind(':received_qty', $item['received_qty']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['receipt_date']);
                $this->db->bind(':type', 'receipt');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['received_qty']);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->execute();        
            }

            if(!$this->db->dbh->commit()){
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
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

    public function get_receipt_items($id)
    {
        $sql = 'SELECT 
                    r.product_id,
                    p.product_name,
                    r.transfered_qty, 
                    r.received_qty
                FROM 
                    receipts_details r 
                    join products p on r.product_id = p.id 
                WHERE r.header_id = ?';
        return resultset($this->db->dbh,$sql,[$id]);
    }

    public function get_receipt($id)
    {
        $count = (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM receipts_headers WHERE id = ?',[$id]);
        if($count === 0) return false;

        return singleset($this->db->dbh,'SELECT * FROM receipts_headers WHERE id = ?',[$id]);
    }

    public function delete($id)
    {
        try {
            
            $this->db->dbh->beginTransaction();

            $this->db->query('DELETE FROM receipts_details WHERE (header_id = :header_id)');
            $this->db->bind(':header_id',$id);
            $this->db->execute();

            $this->db->query('DELETE FROM stock_movements WHERE (transaction_type = :type) AND (transaction_id = :id)');
            $this->db->bind(':type','receipt');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('DELETE FROM receipts_headers WHERE (id = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }
            
            return true;


        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage());
            return false;
        }
    }
}