START TRANSACTION;

CREATE TABLE `receipts_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `receipt_date` DATE NOT NULL, 
  `receipt_no` INT NOT NULL, 
  `transfer_id` VARCHAR(100) NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


ALTER TABLE 
  `receipts_headers` 
ADD 
  CONSTRAINT `fk_receipt_transfer_id` FOREIGN KEY (`transfer_id`) REFERENCES `transfers_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `receipts_headers` 
ADD 
  CONSTRAINT `fk_receipt_user_id` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `receipts_details` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `header_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `transfered_qty` DECIMAL(18, 2) NOT NULL, 
  `received_qty` DECIMAL(18, 2) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `receipts_details` 
ADD 
  CONSTRAINT `fk_receipt_details_header_id` FOREIGN KEY (`header_id`) REFERENCES `receipts_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `receipts_details` 
ADD 
  CONSTRAINT `fk_receipt_details_product_id` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'daily sales', 'transactions', 
    '15', 'sales', '20'
  );

CREATE TABLE `customers` (
  `id` VARCHAR(100) NOT NULL, 
  `customer_name` VARCHAR(255) NOT NULL, 
  `contact` VARCHAR(15) NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `customers` (`id`, `customer_name`, `contact`) 
VALUES 
  (
    'hw8kkckkgwkk0oo0gkw0o8sg', 'walk-in', 
    NULL
  );

CREATE TABLE `sales` (
  `id` VARCHAR(100) NOT NULL, 
  `sale_date` DATE NOT NULL, 
  `sale_no` INT NOT NULL, 
  `customer_id` VARCHAR(100) NOT NULL, 
  `sale_type` ENUM('refill', 'sale') NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18,2) NOT NULL,
  `rate` DECIMAL(18,2) NOT NULL,  
  `amount` DECIMAL(18, 2) NOT NULL, 
  `store_id` VARCHAR(100) NOT NULL,
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `sales` 
ADD 
  CONSTRAINT `fk_sales_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `sales` 
ADD 
  CONSTRAINT `fk_sales_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `sales` 
ADD 
  CONSTRAINT `fk_sales_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE 
  `sales` 
ADD 
  CONSTRAINT `fk_sales_store` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `receipts_headers` ADD `store_id` VARCHAR(100) NOT NULL AFTER `transfer_id`;

INSERT INTO `forms` (`id`, `form_name`, `module`, `module_id`, `path`, `menu_order`) VALUES (NULL, 'roles', 'admin', '5', 'roles', '10');

ALTER TABLE 
  `products` 
ADD 
  `is_stock_item` TINYINT NOT NULL DEFAULT '1' 
AFTER 
  `allow_nil`;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'invoices', 'transactions', 
    '15', 'invoices', '25'
  );

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'customers', 'master entry', 
    '10', 'customers', '20'
  );

ALTER TABLE 
  `customers` 
ADD 
  `email` VARCHAR(255) NOT NULL 
AFTER 
  `contact`, 
ADD 
  `pin` VARCHAR(15) NOT NULL 
AFTER 
  `email`;

ALTER TABLE `customers` ADD `active` TINYINT NOT NULL DEFAULT '1' AFTER `pin`;

CREATE TABLE `water`.`invoices_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `invoice_date` DATE NOT NULL, 
  `invoice_no` INT NOT NULL, 
  `customer_id` VARCHAR(100) NOT NULL, 
  `vat_type` ENUM('no-vat','exclusive','inclusive') NOT NULL, 
  `vat` INT NULL, 
  `exclusive_amount` DECIMAL(18, 2) NOT NULL, 
  `vat_amount` DECIMAL(18, 2) NOT NULL, 
  `inclusive_amount` DECIMAL(18, 2) NOT NULL, 
  `cucn` VARCHAR(100) NULL, 
  `cusn` VARCHAR(100) NULL, 
  `store_id` VARCHAR(100) NOT NULL,
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


ALTER TABLE 
  `invoices_headers` 
ADD 
  CONSTRAINT `fk_invoice_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `invoices_headers` 
ADD 
  CONSTRAINT `fk_invoice_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `invoices_headers` ADD CONSTRAINT `fk_invoice_store` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `invoices_details` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `header_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18, 2) NOT NULL, 
  `rate` DECIMAL(18, 2) NOT NULL, 
  `gross` DECIMAL(18, 2) NOT NULL, 
  `description` VARCHAR(250) NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `invoices_details` 
ADD 
  CONSTRAINT `fk_invoice_details_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `invoices_details` 
ADD 
  CONSTRAINT `fk_invoice_details_header` FOREIGN KEY (`header_id`) REFERENCES `invoices_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- for test server 
DELIMITER $$
CREATE FUNCTION `fn_right_set`(fid varchar(100), rid varchar(100)) RETURNS TINYINT
    DETERMINISTIC
BEGIN 
  DECLARE is_set TINYINT;
  SET is_set = (SELECT COUNT(*)
                FROM role_rights
                WHERE (form_id = fid) AND (role_id = rid));
  RETURN is_set;
END$$
DELIMITER ;
COMMIT;