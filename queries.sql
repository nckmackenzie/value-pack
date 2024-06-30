START TRANSACTION;

ALTER TABLE `wastages` ADD `store_id` VARCHAR(100) NOT NULL AFTER `image_url`;

INSERT INTO `forms` (`id`, `form_name`, `module`, `module_id`, `path`, `menu_order`) VALUES (NULL, 'expenses', 'transactions', '15', 'expenses', '40');

INSERT INTO `forms` (`id`, `form_name`, `module`, `module_id`, `path`, `menu_order`) VALUES (NULL, 'expense accounts', 'master entry', '10', 'expenseaccounts', '20');

CREATE TABLE `expense_accounts` (
  `id` VARCHAR(100) NOT NULL, 
  `account_name` VARCHAR(100) NOT NULL, 
  `active` TINYINT NOT NULL DEFAULT '1', 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `expenses` (
  `id` VARCHAR(100) NOT NULL, 
  `expense_date` DATE NOT NULL, 
  `account_id` VARCHAR(100) NOT NULL, 
  `amount` DECIMAL(18, 2) NOT NULL, 
  `remarks` TEXT NULL, 
  `store_id` VARCHAR(100) NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'stock report', 'reports', '20', 
    'reports/stockreport', '5'
  );

UPDATE `forms` SET `module_id` = '15' WHERE `forms`.`id` = 5;

DELIMITER $$
CREATE FUNCTION `fn_opening_bal`(sid varchar(100), pid varchar(100), tdate DATE) RETURNS decimal(18,2)
    DETERMINISTIC
BEGIN 
  DECLARE balance decimal(18,2);
  SET balance = (SELECT COALESCE(SUM(qty),0) as balance 
                 FROM stock_movements m
                 WHERE (m.store_id = sid) AND (m.product_id = pid) AND (m.transaction_date < tdate));
  RETURN balance;
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION `fn_get_movement_in`(sid varchar(100), pid varchar(100), sdate DATE, edate DATE) RETURNS decimal(18,2)
    DETERMINISTIC
BEGIN 
  DECLARE total decimal(18,2);
  SET total = (SELECT COALESCE(SUM(qty),0) as total 
               FROM stock_movements m
               WHERE (m.store_id = sid) AND (m.product_id = pid) AND 	(m.transaction_date BETWEEN sdate AND edate) AND (m.qty > 0));
  RETURN total;
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION `fn_get_movement_out`(sid varchar(100), pid varchar(100), sdate DATE, edate DATE) RETURNS decimal(18,2)
    DETERMINISTIC
BEGIN 
  DECLARE total decimal(18,2);
  SET total = (SELECT COALESCE(SUM(qty),0) as total 
               FROM stock_movements m
               WHERE (m.store_id = sid) AND (m.product_id = pid) AND 	(m.transaction_date BETWEEN sdate AND edate) AND (m.qty < 0));
  RETURN total;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_get_stock_report(IN sid VARCHAR(100), IN sdate DATE, IN edate DATE )
BEGIN
    SELECT 
    	p.product_name,
    	fn_opening_bal(sid,p.id,sdate) as opening_bal,
        fn_get_movement_in(sid, p.id, sdate, edate) as movement_in,
        (fn_get_movement_out(sid, p.id, sdate, edate) * -1) as movement_out,
        (fn_opening_bal(sid,p.id,sdate) + fn_get_movement_in(sid, p.id, sdate, edate)) - (fn_get_movement_out(sid, p.id, sdate, edate) * -1) as balance
    FROM 
    	products p
    WHERE
    	p.active = 1
    ORDER BY
    	p.product_name
    ;
END $$
DELIMITER ;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'sales report', 'reports', '20', 
    'reports/sales', '10'
  ), 
  (
    NULL, 'pending invoices', 'reports', 
    '20', 'reports/pendinginvoices', 
    '15'
  );


COMMIT;