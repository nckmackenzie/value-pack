<?php
class Payment

{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_paid_invoices()
    {
        $sql = "SELECT
                    p.id,
                    p.payment_date,
                    i.invoice_no,
                    c.customer_name,
                    p.amount
                FROM
                    invoices_payments p join invoices_headers i on p.invoice_id = i.id
                    join customers c on i.customer_id = c.id
                ORDER BY
                    payment_date DESC
        ";
        return resultset($this->db->dbh,$sql,[]);
    }

    public function get_pending_invoices($customer)
    {
        $sql = "SELECT
                    h.id,
                    h.invoice_no,
                    h.inclusive_amount as invoice_amount,
                    fn_get_invoice_balance(h.id) as amount_due
                FROM
                    invoices_headers h
                WHERE
                    h.customer_id = ?
                HAVING 
                    amount_due > 0
                ORDER BY
                    h.created_on
        ";
        return resultset($this->db->dbh,$sql,[$customer]);
    }

    public function create($data){
        if(!$data['is_edit']){
            $payment_id = get_next_db_no($this->db->dbh,'invoices_payments','payment_id');
            try {
                
                $this->db->dbh->beginTransaction();

                $sql = 'INSERT INTO invoices_payments (id, invoice_id, payment_date, amount, payment_id, payment_method, payment_reference, created_by)
                        VALUES(:id, :invoice_id, :payment_date, :amount, :payment_id, :payment_method, :payment_reference, :creator)';
                for ($i=0; $i < count($data['invoices']); $i++) { 
                    $payment =  $data['invoices'][$i]['payment'];
                    if(!empty($payment) && !is_null($payment) && floatval($payment) > 0) {
                        $this->db->query($sql);
                        $this->db->bind(':id', cuid());
                        $this->db->bind(':invoice_id', $data['invoices'][$i]['invoice_id']);
                        $this->db->bind(':payment_date',$data['payment_date']);
                        $this->db->bind(':amount',floatval($payment));
                        $this->db->bind(':payment_id', $payment_id + $i);
                        $this->db->bind(':payment_method',$data['payment_method']);
                        $this->db->bind(':payment_reference',$data['payment_reference']);
                        $this->db->bind(':creator',$_SESSION['user_id']);
                        $this->db->execute();
                    }
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
    }

    public function get_invoice_date($invoice_id)
    {
        return getdbvalue($this->db->dbh,'SELECT invoice_date FROM invoices_headers WHERE id = ?',[$invoice_id]);
    }

    public function get_payment($id)
    {
        $sql = "SELECT 
                    p.id,
                    p.invoice_id,
                    p.payment_id,
                    payment_date,
                    amount as payment,
                    payment_method,
                    payment_reference,
                    customer_id,
                    fn_get_invoice_due(p.invoice_id,p.payment_id) as amount_due
                FROM 
                    invoices_payments p join invoices_headers h on p.invoice_id = h.id
                WHERE
                    p.id = ?
                ";
        return singleset($this->db->dbh,$sql,[$id]);
    }

    public function update($data){
        if($data['is_edit']){
            $sql = 'UPDATE invoices_payments set payment_date = :payment_date, amount = :amount, payment_method = :payment_method, 
                                                 payment_reference = :payment_reference, created_by = :creator
                    WHERE (id = :id)';
            $this->db->query($sql);            
            $this->db->bind(':payment_date',$data['payment_date']);
            $this->db->bind(':amount',floatval($data['payment']));
            $this->db->bind(':payment_method',$data['payment_method']);
            $this->db->bind(':payment_reference',$data['payment_reference']);
            $this->db->bind(':creator',$_SESSION['user_id']);
            $this->db->bind(':id', $data['id']);            
              
            if(!$this->db->execute()){
                return false;
            }

            return true;
        }
    }

    public function has_earlier_payments($invoice_id,$payment_id)
    {
        return (int)getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM invoices_payments WHERE invoice_id = ? AND payment_id < ?',[$invoice_id,$payment_id]) > 0;
    }
}