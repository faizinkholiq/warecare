CREATE TABLE `warecare`.`user` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    `first_name` VARCHAR(50), 
    `last_name` VARCHAR(50), 
    `username` VARCHAR(20) NOT NULL, 
    `password` VARCHAR(255) NOT NULL, 
    `role` ENUM('administrator', 'pelapor', 'kontraktor', 'rab', 'manager') DEFAULT 'pelapor',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_by` INT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO `user` (`id`, `first_name`, `last_name`, `username`, `password`, `role`) VALUES 
    ('1', 'Super Admin', NULL, 'admin', '$2y$10$VKW9Tw5TSd/yH/2eY8XNeuXfgHiAFr2Fk94xcwRmA/M0Ml8A4BYe6', 'administrator'),
    ('2', 'Pelapor', NULL, 'pelapor', '$2y$10$SqlZayAIyxARG7qYMuxI9eUkBou5c9F/zTfVVCPM5..kbR.QvGA7W', 'pelapor'),
    ('3', 'Kontraktor', NULL, 'kontraktor', '$2y$10$mRXX1ohxNeEw7jCMEhOGYuygHz4OWBcJZ6egwwHK//FpFhwVuWKki', 'kontraktor'),
    ('4', 'Admin RAB', NULL, 'rab', '$2y$10$B4ARXBBlSFncjB0cYojx7O/rbfXGKHQ0P5yR4ggvB1EAcVUh5JEQ.', 'rab'),
    ('5', 'Manager', NULL, 'manager', '$2y$10$0NjspH3Uqq7HbykGl.300OXuOvk56yOhrFJTRKmwOmRnyjB4d1GQS', 'manager');