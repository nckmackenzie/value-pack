START TRANSACTION;

CREATE TABLE `transfers_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `transfer_date` DATE NOT NULL, 
  `store_from` VARCHAR(100) NOT NULL, 
  `store_to` VARCHAR(100) NOT NULL, 
  `transfer_no` INT NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (
    `id`(100)
  )
) ENGINE = InnoDB;

ALTER TABLE 
  `transfers_headers` 
ADD 
  CONSTRAINT `fk_transfer_store_from` FOREIGN KEY (`store_from`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `transfers_headers` 
ADD 
  CONSTRAINT `fk_transfer_store_to` FOREIGN KEY (`store_to`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `transfers_headers` 
ADD 
  CONSTRAINT `fk_transfer_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `transfers_details` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `header_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18, 2) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


ALTER TABLE 
  `transfers_details` 
ADD 
  CONSTRAINT `fk_transfer_details_header_id` FOREIGN KEY (`header_id`) REFERENCES `transfers_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `transfers_details` 
ADD 
  CONSTRAINT `fk_transfer_details_product_id` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'receipts', 'transactions', 
    '15', 'receipts', '15'
  );


COMMIT;