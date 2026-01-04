<<<<<<< HEAD
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: toko_hp
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `pembelian`
--

DROP TABLE IF EXISTS `pembelian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembelian` (
  `id_pembelian` int NOT NULL AUTO_INCREMENT,
  `no_pembelian` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pembelian` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(15,2) DEFAULT '0.00',
  `id_suplier` int NOT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `fk_pembelian_suplier` (`id_suplier`),
  CONSTRAINT `fk_pembelian_suplier` FOREIGN KEY (`id_suplier`) REFERENCES `supplier` (`id_suplier`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembelian`
--

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;
INSERT INTO `pembelian` VALUES (9,'1','2025-11-04 09:00:00',30000000.00,1),(10,'2','2025-11-04 10:30:00',20000000.00,2),(11,'3','2025-11-04 11:15:00',10500000.00,3),(12,'4','2025-11-04 12:00:00',15000000.00,1),(13,'5','2025-11-04 13:45:00',4500000.00,2);
/*!40000 ALTER TABLE `pembelian` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-01 10:29:29
=======
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: toko_hp
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `pembelian`
--

DROP TABLE IF EXISTS `pembelian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembelian` (
  `id_pembelian` int NOT NULL AUTO_INCREMENT,
  `no_pembelian` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pembelian` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(15,2) DEFAULT '0.00',
  `id_suplier` int NOT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `fk_pembelian_suplier` (`id_suplier`),
  CONSTRAINT `fk_pembelian_suplier` FOREIGN KEY (`id_suplier`) REFERENCES `supplier` (`id_suplier`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembelian`
--

LOCK TABLES `pembelian` WRITE;
/*!40000 ALTER TABLE `pembelian` DISABLE KEYS */;
INSERT INTO `pembelian` VALUES (9,'1','2025-11-04 09:00:00',30000000.00,1),(10,'2','2025-11-04 10:30:00',20000000.00,2),(11,'3','2025-11-04 11:15:00',10500000.00,3),(12,'4','2025-11-04 12:00:00',15000000.00,1),(13,'5','2025-11-04 13:45:00',4500000.00,2);
/*!40000 ALTER TABLE `pembelian` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-01 10:29:29
>>>>>>> 24b0057b57dba49477784891984fe0707ca2ad1b
