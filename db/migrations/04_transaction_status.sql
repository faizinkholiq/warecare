CREATE TABLE IF NOT EXISTS warecare.transaction_status (
    id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name varchar(20) NOT NULL
);

INSERT IGNORE INTO warecare.transaction_status (id, name) VALUES 
(
    1,
    'Paid'
);