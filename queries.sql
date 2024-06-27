START TRANSACTION;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'material conversion', 'transactions', 
    '15', 'conversions', '16'
  );

  CREATE TABLE `conversions_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `conversion_date` DATE NOT NULL, 
  `final_product` VARCHAR(100) NOT NULL, 
  `converted_qty` DECIMAL(18, 2) NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `conversions_headers` 
ADD 
  CONSTRAINT `fk_conversions_product` FOREIGN KEY (`final_product`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `conversions_headers` 
ADD 
  CONSTRAINT `fk_conversions_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `conversions_details` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `header_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18, 2) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `conversions_details` 
ADD 
  CONSTRAINT `fk_conversions_details_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


--HERE
CREATE TABLE `invoices_payments` (
  `id` VARCHAR(100) NOT NULL, 
  `invoice_id` VARCHAR(100) NOT NULL, 
  `payment_date` DATE NOT NULL, 
  `amount` DECIMAL(18, 2) NOT NULL, 
  `payment_id` INT NOT NULL,
  `payment_method` ENUM('CASH','MPESA','CHEQUE','BANK'),
  `payment_reference` VARCHAR(255) NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `invoices_payments` 
ADD 
  CONSTRAINT `fk_payments_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `invoices_payments` 
ADD 
  CONSTRAINT `fk_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

DELIMITER $$
CREATE FUNCTION `fn_get_invoice_balance`(iid varchar(100)) RETURNS decimal(18,2)
    DETERMINISTIC
BEGIN 
  DECLARE paid decimal(18,2);
  DECLARE invoice_amount decimal(18,2);
  
  SET paid = (SELECT COALESCE(SUM(amount),0) as paid 
              FROM invoices_payments 
              WHERE (invoice_id = iid));
                 
  SET invoice_amount = (SELECT COALESCE(inclusive_amount,0) as amount 
                 		FROM invoices_headers 
                 		WHERE (id = iid));
                 
  RETURN invoice_amount - paid;
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION `fn_get_invoice_due`(iid varchar(100), pid INT) RETURNS decimal(18,2)
    DETERMINISTIC
BEGIN 
  DECLARE paid decimal(18,2);
  DECLARE invoice_amount decimal(18,2);
  
  SET paid = (SELECT COALESCE(SUM(amount),0) as paid 
              FROM invoices_payments 
              WHERE (invoice_id = iid) AND (payment_id < pid));
                 
  SET invoice_amount = (SELECT COALESCE(inclusive_amount,0) as amount 
                 		FROM invoices_headers 
                 		WHERE (id = iid));
                 
  RETURN invoice_amount - paid;
END$$
DELIMITER ;

COMMIT;