CREATE TABLE `warecare`.`company` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `project_id` INT NOT NULL,
    `name` VARCHAR(50), 
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
); 