CREATE TABLE `warecare`.`user` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `first_name` VARCHAR(50), 
    `last_name` VARCHAR(50), 
    `username` VARCHAR(20) NOT NULL, 
    `password` VARCHAR(255) NOT NULL, 
    `role` ENUM('administrator', 'pelapor', 'kontraktor', 'rab', 'manager') DEFAULT 'pelapor',
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
); 

INSERT INTO `user` (
    `id`, 
    `first_name`, 
    `last_name`, 
    `username`, 
    `password`, 
    `role`
) VALUES (
    '1', 
    'Super Admin', 
    NULL, 
    'admin', 
    '$2y$10$14TupGE3hyGvHmiwyUbtyOd43I9jik86/W4ucMjQaojLmJC9SUdZa', -- admin@warecare 
    '1'
);