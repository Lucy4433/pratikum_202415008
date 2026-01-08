-- ------------------------------------------------------
-- MySQL dump - toko_hp (terurut agar foreign key aman)
-- ------------------------------------------------------

-- ======================================================
-- 1. Tabel tanpa foreign key: merek, profil_toko, supplier, user
-- ======================================================

DROP TABLE IF EXISTS `merek`;
use toko_hp;
CREATE TABLE `merek` (
    `id_merek` int NOT NULL AUTO_INCREMENT,
    `nama_merek` varchar(100) NOT NULL,
    PRIMARY KEY (`id_merek`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `merek`
VALUES (1, 'Samsung'),
    (2, 'Xiaomi'),
    (3, 'Oppo'),
    (4, 'Vivo'),
    (5, 'Realme'),
    (6, 'Apple');

DROP TABLE IF EXISTS `profil_toko`;

CREATE TABLE `profil_toko` (
    `id_profil` int NOT NULL AUTO_INCREMENT,
    `nama_toko` varchar(100) DEFAULT NULL,
    `alamat` varchar(255) DEFAULT NULL,
    `no_telp` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`id_profil`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `profil_toko`
VALUES (
        1,
        'Toko HP Jaya',
        'Jl. Merdeka No.10',
        '081122334455'
    );

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
    `id_suplier` int NOT NULL AUTO_INCREMENT,
    `nama_suplier` varchar(100) NOT NULL,
    `alamat` varchar(255) DEFAULT NULL,
    `no_telp` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`id_suplier`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `supplier`
VALUES (
        1,
        'PT Sumber Jaya',
        'Jakarta',
        '081234567890'
    ),
    (
        2,
        'CV Teknologi Nusantara',
        'Bandung',
        '082233445566'
    ),
    (
        3,
        'PT Mega Seluler',
        'Surabaya',
        '083344556677'
    ),
    (
        4,
        'PT Indo Gadget',
        'Semarang',
        '085566778899'
    );

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    `id_user` int NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum('admin', 'kasir') DEFAULT 'kasir',
    `nama` varchar(255) DEFAULT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `status` enum('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (`id_user`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `user`
VALUES (
        1,
        'admin',
        'admin123',
        'admin',
        'Administrator',
        NULL,
        'aktif'
    ),
    (
        2,
        'kasir1',
        'kasir123',
        'kasir',
        'Aisyah',
        NULL,
        'aktif'
    ),
    (
        3,
        'kasir2',
        'kasir123',
        'kasir',
        'Rizky',
        NULL,
        'aktif'
    ),
    (
        4,
        'kasir3',
        'kasir123',
        'kasir',
        'Dimas',
        NULL,
        'aktif'
    );

-- ======================================================
-- 2. Tabel yang tergantung pada tabel di atas: produk, pembelian, orders
-- ======================================================

DROP TABLE IF EXISTS `produk`;

CREATE TABLE `produk` (
    `id_produk` int NOT NULL AUTO_INCREMENT,
    `id_merek` int NOT NULL,
    `nama_produk` varchar(150) NOT NULL,
    `harga_jual` decimal(15, 2) DEFAULT NULL,
    `harga` decimal(15, 2) NOT NULL,
    `stok` int DEFAULT '0',
    PRIMARY KEY (`id_produk`),
    KEY `fk_produk_merek` (`id_merek`),
    CONSTRAINT `fk_produk_merek` FOREIGN KEY (`id_merek`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `produk`
VALUES (
        1,
        1,
        'Samsung Galaxy A15',
        2800000.00,
        2500000.00,
        20
    ),
    (
        2,
        1,
        'Samsung Galaxy S23',
        13500000.00,
        12000000.00,
        10
    ),
    (
        3,
        2,
        'Xiaomi Redmi Note 13',
        2500000.00,
        2200000.00,
        30
    ),
    (
        4,
        2,
        'Xiaomi Poco X5',
        3200000.00,
        2900000.00,
        15
    ),
    (
        5,
        3,
        'Oppo Reno 10',
        5200000.00,
        4800000.00,
        12
    ),
    (
        6,
        4,
        'Vivo Y20',
        2100000.00,
        1900000.00,
        25
    ),
    (
        7,
        5,
        'Realme C55',
        2300000.00,
        2000000.00,
        18
    ),
    (
        8,
        6,
        'iPhone 13',
        12500000.00,
        11000000.00,
        8
    );

DROP TABLE IF EXISTS `pembelian`;

CREATE TABLE `pembelian` (
    `id_pembelian` int NOT NULL AUTO_INCREMENT,
    `no_pembelian` varchar(45) DEFAULT NULL,
    `tanggal_pembelian` datetime DEFAULT CURRENT_TIMESTAMP,
    `total` decimal(15, 2) DEFAULT '0.00',
    `id_suplier` int NOT NULL,
    PRIMARY KEY (`id_pembelian`),
    KEY `fk_pembelian_suplier` (`id_suplier`),
    CONSTRAINT `fk_pembelian_suplier` FOREIGN KEY (`id_suplier`) REFERENCES `supplier` (`id_suplier`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `pembelian`
VALUES (
        1,
        'PB-001',
        '2026-01-01 08:00:00',
        24000000.00,
        1
    ),
    (
        2,
        'PB-002',
        '2026-01-02 08:30:00',
        11000000.00,
        3
    ),
    (
        3,
        'PB-003',
        '2026-01-03 09:00:00',
        5800000.00,
        2
    );

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
    `id_order` int NOT NULL AUTO_INCREMENT,
    `no_penjualan` varchar(45) DEFAULT NULL,
    `tanggal_order` datetime DEFAULT CURRENT_TIMESTAMP,
    `total` decimal(15, 2) DEFAULT '0.00',
    `id_user` int NOT NULL,
    PRIMARY KEY (`id_order`),
    KEY `fk_orders_user` (`id_user`),
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `orders`
VALUES (
        1,
        'INV-20260101-668',
        '2026-01-01 10:15:00',
        5300000.00,
        2
    ),
    (
        2,
        'INV-20260101-669',
        '2026-01-01 11:30:00',
        13500000.00,
        3
    ),
    (
        3,
        'INV-20260102-670',
        '2026-01-02 09:45:00',
        2500000.00,
        4
    );

-- ======================================================
-- 3. Tabel yang tergantung pada tabel tahap 2: detail_pembelian, detail_order, supplier_produk, discount, pembayaran
-- ======================================================

DROP TABLE IF EXISTS `detail_pembelian`;

CREATE TABLE `detail_pembelian` (
    `id_detail` int NOT NULL AUTO_INCREMENT,
    `id_pembelian` int NOT NULL,
    `id_produk` int NOT NULL,
    `jumlah` int DEFAULT '1',
    `harga_satuan` decimal(15, 2) NOT NULL,
    PRIMARY KEY (`id_detail`),
    KEY `fk_detailpembelian_pembelian` (`id_pembelian`),
    KEY `fk_detailpembelian_produk` (`id_produk`),
    CONSTRAINT `fk_detailpembelian_pembelian` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detailpembelian_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `detail_pembelian`
VALUES (1, 1, 2, 2, 12000000.00),
    (2, 2, 8, 1, 11000000.00),
    (3, 3, 3, 2, 2900000.00);

DROP TABLE IF EXISTS `detail_order`;

CREATE TABLE `detail_order` (
    `id_detail` int NOT NULL AUTO_INCREMENT,
    `id_order` int NOT NULL,
    `id_produk` int NOT NULL,
    `jumlah_beli` int DEFAULT '1',
    `harga_satuan` decimal(15, 2) NOT NULL,
    PRIMARY KEY (`id_detail`),
    KEY `fk_detailorder_orders` (`id_order`),
    KEY `fk_detailorder_produk` (`id_produk`),
    CONSTRAINT `fk_detailorder_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detailorder_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `detail_order`
VALUES (1, 1, 3, 2, 2500000.00),
    (2, 2, 2, 1, 13500000.00),
    (3, 3, 7, 1, 2300000.00);

DROP TABLE IF EXISTS `supplier_produk`;

CREATE TABLE `supplier_produk` (
    `id_supplier_produk` int NOT NULL AUTO_INCREMENT,
    `id_supplier` int NOT NULL,
    `id_produk` int NOT NULL,
    `harga_beli` int NOT NULL,
    `stok_masuk` int NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_supplier_produk`),
    KEY `fk_supplier` (`id_supplier`),
    KEY `fk_produk` (`id_produk`),
    CONSTRAINT `fk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_suplier`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `supplier_produk`
VALUES (
        1,
        1,
        1,
        2500000,
        10,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        2,
        1,
        2,
        12000000,
        5,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        3,
        2,
        3,
        2200000,
        10,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        4,
        2,
        4,
        2900000,
        8,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        5,
        3,
        5,
        4800000,
        6,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        6,
        4,
        6,
        1900000,
        12,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        7,
        3,
        7,
        2000000,
        8,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    ),
    (
        8,
        4,
        8,
        11000000,
        4,
        '2026-01-08 14:46:16',
        '2026-01-08 14:46:16'
    );

DROP TABLE IF EXISTS `discount`;

CREATE TABLE `discount` (
    `id_discount` int NOT NULL AUTO_INCREMENT,
    `dari_date` date NOT NULL,
    `sampai_date` date NOT NULL,
    `besaran` int NOT NULL,
    `id_produk` int NOT NULL,
    PRIMARY KEY (`id_discount`),
    KEY `fk_discount_produk` (`id_produk`),
    CONSTRAINT `fk_discount_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `discount`
VALUES (
        1,
        '2026-01-01',
        '2026-01-10',
        10,
        3
    ),
    (
        2,
        '2026-01-05',
        '2026-01-15',
        15,
        2
    ),
    (
        3,
        '2026-01-01',
        '2026-01-31',
        5,
        7
    );

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
    `id_pembayaran` int NOT NULL AUTO_INCREMENT,
    `id_order` int NOT NULL,
    `total` decimal(15, 2) NOT NULL,
    `tanggal_bayar` datetime DEFAULT CURRENT_TIMESTAMP,
    `metode_pembayaran` enum('Cash', 'Transfer') DEFAULT 'Cash',
    PRIMARY KEY (`id_pembayaran`),
    KEY `fk_pembayaran_orders` (`id_order`),
    CONSTRAINT `fk_pembayaran_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

INSERT INTO
    `pembayaran`
VALUES (
        1,
        1,
        5300000.00,
        '2026-01-01 10:20:00',
        'Cash'
    ),
    (
        2,
        2,
        13500000.00,
        '2026-01-01 11:40:00',
        'Transfer'
    ),
    (
        3,
        3,
        2500000.00,
        '2026-01-02 10:00:00',
        'Cash'
    );