CREATE TABLE IF NOT EXISTS report (
  id INT AUTO_INCREMENT PRIMARY KEY,
  
  no VARCHAR(20) NULL,
  
  entity_id INT NOT NULL,
  project_id INT NOT NULL,
  company_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  category_id INT NOT NULL,

  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Pending', 'On Process', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending',

  is_rab BOOLEAN DEFAULT FALSE,
  rab_file VARCHAR(255),
  rab_final_file VARCHAR(255),

  processed_by INT NULL,
  processed_at DATETIME NULL,

  approved_by INT NULL,
  approved_at DATETIME NULL,

  completed_by INT NULL,
  completed_at DATETIME NULL,

  created_by INT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  updated_by INT NULL,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_report_entity      FOREIGN KEY (entity_id)     REFERENCES entity(id)     ON DELETE CASCADE,
  CONSTRAINT fk_report_project     FOREIGN KEY (project_id)    REFERENCES project(id)     ON DELETE CASCADE,
  CONSTRAINT fk_report_company     FOREIGN KEY (company_id)    REFERENCES company(id)    ON DELETE CASCADE,
  CONSTRAINT fk_report_warehouse   FOREIGN KEY (warehouse_id)  REFERENCES warehouse(id)   ON DELETE CASCADE,
  CONSTRAINT fk_report_category    FOREIGN KEY (category_id)   REFERENCES category(id)   ON DELETE CASCADE
);

-- ALTER TABLE `report` ADD `no` VARCHAR(20) NOT NULL AFTER `id`, ADD UNIQUE `unique_report_no` (`no`); 

CREATE TABLE report_evidences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (report_id) REFERENCES report(id) ON DELETE CASCADE
);

CREATE TABLE report_works (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (report_id) REFERENCES report(id) ON DELETE CASCADE
);

CREATE TABLE report_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    level SMALLINT NOT NULL DEFAULT 1,
    parent_id INT DEFAULT NULL,
    no VARCHAR(20) NOT NULL,
    description VARCHAR(255) NOT NULL,
    status ENUM('OK', 'Not OK') DEFAULT 'OK',
    `condition` ENUM('Tidak Butuh Perbaikan', 'Butuh Perbaikan') DEFAULT 'Tidak Butuh Perbaikan',
    information TEXT,
    FOREIGN KEY (report_id) REFERENCES report(id) ON DELETE CASCADE
);