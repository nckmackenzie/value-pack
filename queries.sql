START TRANSACTION;

ALTER TABLE `wastages` ADD `store_id` VARCHAR(100) NOT NULL AFTER `image_url`;

INSERT INTO `forms` (`id`, `form_name`, `module`, `module_id`, `path`, `menu_order`) VALUES (NULL, 'expenses', 'transactions', '15', 'expenses', '40');

COMMIT;