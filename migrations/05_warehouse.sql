CREATE TABLE `warecare`.`warehouse` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `company_id` INT NOT NULL,
    `name` VARCHAR(50), 
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_warehouse_company
    FOREIGN KEY (company_id) REFERENCES company(id)
    ON DELETE CASCADE
); 