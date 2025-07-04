CREATE TABLE IF NOT EXISTS warecare.currency (
    id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
    code varchar(10) NOT NULL,
    name varchar(50) NOT NULL
);

INSERT IGNORE INTO warecare.currency (id, code, name) VALUES 
    (1, 'USD', 'US Dollar'),
    (2, 'IDR', 'Indonesian Rupiah');