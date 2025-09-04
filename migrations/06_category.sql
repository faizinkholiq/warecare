CREATE TABLE `warecare`.`category` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `code` VARCHAR (10),
    `name` VARCHAR(50), 
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
); 

INSERT INTO `warecare`.`category` (`code`, `name`) VALUES
('CM', 'Complain'),
('STGS', 'Serah Terima Gudang Sewa'),
('KT', 'Kerja Tambah');