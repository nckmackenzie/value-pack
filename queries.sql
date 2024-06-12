START TRANSACTION;

DELIMITER $$
CREATE FUNCTION fn_get_current_stock (sid varchar(100), pid varchar(100), tdate DATE) 
RETURNS decimal(18,2)
DETERMINISTIC
BEGIN 
  DECLARE balance decimal(18,2);
  SET balance = (SELECT COALESCE(SUM(qty),0) as balance 
                 FROM stock_movements m
                 WHERE (m.store_id = sid) AND (m.product_id = pid) AND (m.transaction_date <= tdate));
  RETURN balance;
END$$
DELIMITER ;


COMMIT;