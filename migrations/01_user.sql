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
    ('1', 'Super Admin', NULL, 'admin', '$2y$10$52Dbpl2i.k0J/5QIQKYBR.v2mSIq6rVoV.pnEzuXBvFmRst91T8pS', 'administrator'),
    ('2', 'Pelapor', NULL, 'pelapor', '$2y$10$3w8/2N306t56uuI7JwMFfuOJXOZRLMDNNziw4kXQuJIb2Ni9LOduO', 'pelapor'),
    ('3', 'Kontraktor', NULL, 'kontraktor', '$2y$10$osMwazI8BRR6N7ZvZc6xHeoIDVFvOtT48XlBh1W7wOHm8FhdPBUHy', 'kontraktor'),
    ('4', 'Admin RAB', NULL, 'rab', '$2y$10$TKmdVXDkEqnaEW1UyJQS0uqx9XVCy.N6jqwUwGAl99s2kaVvA.MFu', 'rab'),
    ('5', 'Manager', NULL, 'manager', '$2y$10$SrQclmuGyIZbOWdqGj9w.e.lbU66vd2s82hEbiR40i8tUdmi1zGAW', 'manager');