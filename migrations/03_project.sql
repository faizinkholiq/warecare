CREATE TABLE `warecare`.`project` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `name` VARCHAR(50), 
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
); 