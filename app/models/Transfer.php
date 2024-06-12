<?php 
declare(strict_types=1);
class Transfer
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_transfer_no():int
    {
        return get_next_db_no($this->db->dbh,'transfers_headers','transfer_no');
    }

    public function get_transfers()
    {
        $sql = "SELECT 
                    h.id,
                    transfer_date,
                    transfer_no,
                    s.store_name as store_name,
                    'pending' as status
                FROM
                    transfers_headers h join stores s on h.store_to = s.id
                WHERE
                    h.store_from = ?
                ORDER BY 
                    transfer_date DESC
            ";
        return resultset($this->db->dbh,$sql,[$_SESSION['store']]);
    }

    function save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $sql = "INSERT INTO transfers_headers (id, transfer_date, store_from, store_to, transfer_no, created_by) ";
            $sql .= "VALUES (:id, :transfer_date, :store_from, :store_to, :transfer_no, :creator) ";

            $this->db->query($sql);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':transfer_date', $data['transfer_date']);
            $this->db->bind(':store_from', $_SESSION['store']);
            $this->db->bind(':store_to', $data['store']);
            $this->db->bind(':transfer_no', $data['transfer_no']);           
            $this->db->bind(':creator', $_SESSION['user_id']);
            $this->db->execute();

            foreach($data['items'] as $item){
                $this->db->query('INSERT INTO transfers_details (header_id, product_id, qty) VALUES (:id, :product_id, :qty)');
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->execute();

                $this->db->query('INSERT INTO stock_movements (transaction_date,transaction_type,store_id,product_id,qty,transaction_id,created_by) VALUES (:date,:type,:store,:product,:qty,:tid,:creator)');
                $this->db->bind(':date', $data['transfer_date']);
                $this->db->bind(':type', 'transfer');
                $this->db->bind(':store', $_SESSION['store']);
                $this->db->bind(':product', $item['product_id']);
                $this->db->bind(':qty', $item['qty'] * -1);
                $this->db->bind(':tid', $data['id']);
                $this->db->bind(':creator', $_SESSION['user_id']);
                $this->db->execute();            }

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

    public function create_update($data)
    {
        if(!$data['is_edit']){
            return $this->save($data);
        }
    }
}