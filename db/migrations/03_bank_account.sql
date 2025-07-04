CREATE TABLE IF NOT EXISTS warecare.bank_account (
    id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
    currency smallint NOT NULL,
    bank_name varchar(100) NOT NULL,
    bank_address varchar(100) NOT NULL,
    account_name varchar(100) NOT NULL,
    account_no varchar(20) NOT NULL,
    swift_code varchar(20) NOT NULL,
    bank_code varchar(20) NOT NULL,
    branch_code varchar(20) NOT NULL
);

INSERT IGNORE INTO warecare.bank_account (
    id,
    currency,
    bank_name,
    bank_address,
    account_name,
    account_no,
    swift_code,
    bank_code,
    branch_code
) VALUES 
(
    1,
    1,
    'Mandiri Bank',
    'Branch Jakarta Kresna SCBD',
    'PT INTERNATIONAL AKATSUKI BUSINESS',
    '1020.0105.5788.9',
    'BMRIIDJA',
    '8',
    '10228'
),
(
    2,
    2,
    'Mandiri Bank',
    'Branch Jakarta Kresna SCBD',
    'PT INTERNATIONAL AKATSUKI BUSINESS',
    '1020.0105.5786.3',
    'BMRIIDJA',
    '8',
    '10228'
);