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


COMMIT;