CREATE TABLE IF NOT EXISTS report (
  id INT AUTO_INCREMENT PRIMARY KEY,

  entity_id INT NOT NULL,
  project_id INT NOT NULL,
  company_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  category_id INT NOT NULL,

  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Pending', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending',

  rab BOOLEAN DEFAULT FALSE,
  photo_rab VARCHAR(255),
  photo_works VARCHAR(255),

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  created_by INT NULL,
  updated_by INT NULL,

  CONSTRAINT fk_report_entity      FOREIGN KEY (entity_id)     REFERENCES entity(id)     ON DELETE CASCADE,
  CONSTRAINT fk_report_project     FOREIGN KEY (project_id)    REFERENCES project(id)     ON DELETE CASCADE,
  CONSTRAINT fk_report_company     FOREIGN KEY (company_id)    REFERENCES company(id)    ON DELETE CASCADE,
  CONSTRAINT fk_report_warehouse   FOREIGN KEY (warehouse_id)  REFERENCES warehouse(id)   ON DELETE CASCADE,
  CONSTRAINT fk_report_category    FOREIGN KEY (category_id)   REFERENCES category(id)   ON DELETE CASCADE
);

ALTER TABLE `report` ADD `no` VARCHAR(20) NOT NULL AFTER `id`, ADD UNIQUE `unique_report_no` (`no`); 

CREATE TABLE report_evidences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (report_id) REFERENCES report(id) ON DELETE CASCADE
);