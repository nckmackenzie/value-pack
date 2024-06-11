START TRANSACTION;

ALTER TABLE `stock_movements` CHANGE `transaction_id` `transaction_id` VARCHAR(100) NULL DEFAULT NULL;

INSERT INTO `forms` (
  `id`, `form_name`, `module`, `module_id`, 
  `path`, `menu_order`
) 
VALUES 
  (
    NULL, 'transfers', 'transactions', 
    '15', 'transfers', '10'
  );


COMMIT;