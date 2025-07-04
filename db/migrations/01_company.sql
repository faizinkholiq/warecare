CREATE TABLE IF NOT EXISTS warecare.company (
    id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name varchar(100) NOT NULL,
    status varchar(20) DEFAULT NULL,
    npwp varchar(20) DEFAULT NULL,
    nib varchar(20) DEFAULT NULL,
    phone varchar(20) DEFAULT NULL,
    email varchar(50) DEFAULT NULL,
    address text DEFAULT NULL
);

INSERT IGNORE INTO warecare.company (
    id,
    name,
    status,
    npwp,
    nib,
    phone,
    email,
    address
) VALUES (
    1,
    'PT INTERNATIONAL AKATSUKI BUSINESS',
    'PMA',
    '63.830.203.4-086.000',
    '2403220003156',
    '+62881038567073',
    'm.jay@nexinngroup.com',
    'Business Park Kebon Jeruk, Blok H 1-2 Jalan Raya Meruya Illir Nomor 88 Desa/Kelurahan Meruya Utara Kec. Kembangan, Kota Adm. Jakarta Barat Provinsi DKI Jakarta, Kode Pos 11620'
)