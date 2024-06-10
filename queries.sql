START TRANSACTION;

CREATE TABLE `user_stores` (
  `user_id` VARCHAR(100) NOT NULL, 
  `store_id` VARCHAR(100) NOT NULL, 
  CONSTRAINT user_store_pk PRIMARY KEY (user_id, store_id)
) ENGINE = InnoDB;

ALTER TABLE 
  `user_stores` 
ADD 
  CONSTRAINT `fk_user_stores_userid` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE 
  `user_stores` 
ADD 
  CONSTRAINT `fk_user_stores_storeid` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
  
CREATE TABLE `units` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `unit` VARCHAR(100) NOT NULL, 
  `abbreviation` VARCHAR(15) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


INSERT INTO `units` (`id`, `unit`, `abbreviation`) VALUES
(1, 'default', 'def'),
(2, 'kilograms', 'kgs'),
(3, 'litres', 'ltr'),
(4, 'pieces', 'pcs'),
(5, 'inches', 'in'),
(6, 'bag', 'bg'),
(7, 'bucket', 'bkt'),
(8, 'box', 'bx'),
(9, 'packet', 'pkt'),
(10, 'carton', 'ctn'),
(11, 'each', 'ea'),
(12, 'foot', 'ft'),
(13, 'meter', 'm'),
(14, 'millimeter', 'mm'),
(15, 'grams', 'g'),
(16, 'bale', 'bl'),
(17, 'board foot', 'bf'),
(18, 'bottle', 'btl'),
(19, 'can', 'can'),
(20, 'card', 'crd'),
(21, 'centimeter', 'cm'),
(22, 'crates', 'crts'),
(23, 'cuts', 'cuts'),
(24, 'dozen', 'dzn'),
(25, 'drum', 'drm'),
(26, 'gallon', 'gal'),
(27, 'hours', 'hrs'),
(28, 'pound', 'lb'),
(29, 'length', 'lgth'),
(30, 'millilitres', 'mil'),
(31, 'months', 'mon'),
(32, 'pairs', 'prs'),
(33, 'ream', 'rm'),
(34, 'roll', 'rol'),
(35, 'set', 'set'),
(36, 'sheet', 'sht'),
(37, 'square foot', 'sqft'),
(38, 'square meter', 'sqm'),
(39, 'strip', 'strp'),
(40, 'timber', 'tmbr'),
(41, 'tin', 'tin'),
(42, 'tubes', 'tb'),
(43, 'users', 'usr'),
(44, 'width', 'wdth'),
(45, 'liquid meters', 'lqmtr');

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'products', 'admin', '5', 'products', 
    '15'
  );

CREATE TABLE `products` (
  `id` VARCHAR(100) NOT NULL PRIMARY KEY, 
  `product_name` VARCHAR(255) NOT NULL, 
  `product_code` VARCHAR(150) NULL, 
  `unit_id` INT NOT NULL, 
  `buying_price` DECIMAL(18, 2) NOT NULL DEFAULT '0', 
  `selling_price` DECIMAL(18, 2) NOT NULL DEFAULT '0', 
  `description` TEXT NULL, 
  `reorder_level` INT NULL, 
  `allow_nil` TINYINT NOT NULL DEFAULT '0', 
  `active` TINYINT NOT NULL DEFAULT '1',
  `created_by` VARCHAR(100) NOT NULL,
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

ALTER TABLE 
  `products` 
ADD 
  CONSTRAINT `fk_product_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `product_stores` (
  `product_id` VARCHAR(100) NOT NULL, 
  `store_id` VARCHAR(100) NOT NULL, 
  CONSTRAINT product_store_pk PRIMARY KEY (product_id, store_id)
) ENGINE = InnoDB;

ALTER TABLE 
  `product_stores` 
ADD 
  CONSTRAINT `fk_product_store_product_id` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE 
  `product_stores` 
ADD 
  CONSTRAINT `fk_product_store_store_id` FOREIGN KEY (`store_id`) REFERENCES `stores`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'suppliers', 'master entry', 
    '10', 'suppliers', '15'
  );

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'purchases', 'transactions', 
    '25', 'purchases', '5'
  );

CREATE TABLE `suppliers` (
  `id` VARCHAR(100) NOT NULL, 
  `supplier_name` VARCHAR(255) NOT NULL, 
  `contact` VARCHAR(15) NULL, 
  `email` VARCHAR(255) NULL, 
  `contact_person` VARCHAR(255) NULL, 
  `active` TINYINT NOT NULL DEFAULT '1', 
  `created_by` VARCHAR(100) NOT NULL, 
  `created_on` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


COMMIT;