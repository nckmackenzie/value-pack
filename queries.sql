START TRANSACTION;

CREATE TABLE `vats` (
  `id` int(11) NOT NULL,
  `vat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `vats` (`id`, `vat_name`) VALUES
(16, 'vat-16%');


ALTER TABLE `vats`
  ADD PRIMARY KEY (`id`);


CREATE TABLE `purchases_headers` (
  `id` VARCHAR(100) NOT NULL, 
  `purchase_date` DATE NOT NULL, 
  `supplier_id` VARCHAR(100) NOT NULL, 
  `reference` VARCHAR(100) NULL, 
  `vat_type` ENUM(
    'no-vat', 'inclusive', 'exclusive'
  ) NOT NULL, 
  `vat` INT NULL, 
  `store_id` VARCHAR(100) NOT NULL, 
  `amount_exclusive` DECIMAL(18, 2) NOT NULL DEFAULT '0', 
  `vat_amount` DECIMAL(18, 2) NOT NULL DEFAULT '0', 
  `amount_inclusive` DECIMAL(18, 2) NOT NULL DEFAULT '0', 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `purchases_headers` 
ADD 
  CONSTRAINT `fk_purchases_vendor` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `purchases_headers` 
ADD 
  CONSTRAINT `fk_purchases_store` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `purchases_headers` 
ADD 
  CONSTRAINT `fk_purchases_vat` FOREIGN KEY (`vat`) REFERENCES `vats`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `purchases_headers` 
ADD 
  CONSTRAINT `fk_purchases_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `purchases_details` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `header_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18, 2) NOT NULL, 
  `rate` DECIMAL(18, 2) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `purchases_details` 
ADD 
  CONSTRAINT `fk_purchases_details_header` FOREIGN KEY (`header_id`) REFERENCES `purchases_headers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `purchases_details` 
ADD 
  CONSTRAINT `fk_purchases_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `stock_movements` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `transaction_date` DATE NOT NULL, 
  `transaction_type` VARCHAR(100) NOT NULL, 
  `store_id` VARCHAR(100) NOT NULL, 
  `product_id` VARCHAR(100) NOT NULL, 
  `qty` DECIMAL(18, 2) NOT NULL, 
  `transaction_id` INT NULL, 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE 
  `stock_movements` 
ADD 
  CONSTRAINT `fk_stock_movement_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `stock_movements` 
ADD 
  CONSTRAINT `fk_stock_movement_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `stock_movements` 
ADD 
  CONSTRAINT `fk_stock_movement_store` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;


COMMIT;