<?php

class Expense
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function get_expenses()
    {
        $sql = "SELECT
                    e.id,
                    e.expense_date,
                    a.account_name as expense_account,
                    e.amount
                FROM
                    expenses e JOIN expense_accounts 
                    a ON e.account_id = a.id
                WHERE
                    (store_id = ?)
                ORDER BY
                    e.id DESC
        ";
        return resultset($this->db->dbh, $sql, [$_SESSION['store']]);
    }
    
    public function create_update($data)
    {
        if(!$data['is_edit']){
            $sql = "INSERT INTO expenses (id, expense_date, account_id, amount, remarks, store_id, created_by) 
                    VALUES (:id, :expense_date, :account_id, :amount, :remarks, :store_id, :creator)";
        }else{
            $sql = "UPDATE expenses SET expense_date = :expense_date, account_id = :account_id, amount = :amount, 
                                        remarks = :remarks,  created_by = :creator 
                    WHERE  (id = :id)";
        }
               
        $this->db->query($sql);
        if(!$data['is_edit']){
            $this->db->bind(':id', $data['id']);
        }
        $this->db->bind(':expense_date', $data['expense_date']);
        $this->db->bind(':account_id', $data['account_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':remarks', strtolower($data['remarks']));
        if(!$data['is_edit']){
            $this->db->bind(':store_id', $_SESSION['store']);
        }
        $this->db->bind(':creator', $_SESSION['user_id']);
        if($data['is_edit']){
            $this->db->bind(':id', $data['id']);
        }
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}