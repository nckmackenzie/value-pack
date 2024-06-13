START TRANSACTION;

CREATE TABLE `receipts_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `receipt_date` DATE NOT NULL, 
  `receipt_no` INT NOT NULL, 
  `transfer_id` VARCHAR(100) NOT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` INT NOT NULL DEFAULT CURRENT_TIMESTAMP, 
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


COMMIT;