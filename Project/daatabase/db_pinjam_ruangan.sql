/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: db_pinjam_ruangan
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-5ubuntu0.1 from Ubuntu

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `gedung`
--

DROP TABLE IF EXISTS `gedung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gedung` (
  `id_gedung` varchar(100) NOT NULL,
  `nama_gedung` varchar(100) DEFAULT NULL,
  `jumlah_lantai` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_gedung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gedung`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gedung` WRITE;
/*!40000 ALTER TABLE `gedung` DISABLE KEYS */;
INSERT INTO `gedung` VALUES
('FPEB001','Gedung FPEB B',12),
('FPSD001','Gedung FPSD A',9),
('MIPA001','Gedung FPMIPA A',5);
/*!40000 ALTER TABLE `gedung` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `petugas`
--

DROP TABLE IF EXISTS `petugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas` (
  `id_petugas` varchar(100) NOT NULL,
  `nama_petugas` varchar(100) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_petugas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petugas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `petugas` WRITE;
/*!40000 ALTER TABLE `petugas` DISABLE KEYS */;
INSERT INTO `petugas` VALUES
('P001','Jajang ballet','089765213'),
('P002','Ujang Uhuyy','0824879821'),
('P003','MR Sakamoto','0876543212');
/*!40000 ALTER TABLE `petugas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `petugas_dan_gedung`
--

DROP TABLE IF EXISTS `petugas_dan_gedung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_dan_gedung` (
  `id_petugas` varchar(100) NOT NULL,
  `id_gedung` varchar(100) NOT NULL,
  PRIMARY KEY (`id_petugas`,`id_gedung`),
  KEY `id_gedung` (`id_gedung`),
  CONSTRAINT `petugas_dan_gedung_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`),
  CONSTRAINT `petugas_dan_gedung_ibfk_2` FOREIGN KEY (`id_gedung`) REFERENCES `gedung` (`id_gedung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petugas_dan_gedung`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `petugas_dan_gedung` WRITE;
/*!40000 ALTER TABLE `petugas_dan_gedung` DISABLE KEYS */;
INSERT INTO `petugas_dan_gedung` VALUES
('P002','MIPA001');
/*!40000 ALTER TABLE `petugas_dan_gedung` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruangan` (
  `id_ruangan` varchar(100) NOT NULL,
  `nama_ruangan` varchar(100) DEFAULT NULL,
  `kapasitas_ruangan` int(11) DEFAULT NULL,
  `status_ruangan` varchar(100) NOT NULL DEFAULT 'Tersedia',
  `id_gedung` varchar(20) NOT NULL,
  `peminjam` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_ruangan`),
  KEY `fk_ruangan_gedung` (`id_gedung`),
  CONSTRAINT `fk_ruangan_gedung` FOREIGN KEY (`id_gedung`) REFERENCES `gedung` (`id_gedung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruangan`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ruangan` WRITE;
/*!40000 ALTER TABLE `ruangan` DISABLE KEYS */;
INSERT INTO `ruangan` VALUES
('FPEB A001','Ruang Bisnis',50,'Tersedia','MIPA001',NULL),
('FPSD A001','Studio Musik',30,'Tersedia','FPSD001',NULL),
('MIPA A002','Ruang Prodi',25,'Tersedia','FPSD001',NULL),
('MIPA A100','Lab AI',35,'Tersedia','MIPA001',NULL);
/*!40000 ALTER TABLE `ruangan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-11-10 21:59:21
