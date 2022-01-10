CREATE DATABASE  IF NOT EXISTS `inaf` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `inaf`;
-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: 104.199.87.84    Database: inaf
-- ------------------------------------------------------
-- Server version	5.7.34-google-log

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

-- SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '095943f7-c265-11e8-b93d-42010af0082e:1-97255737';

--
-- Table structure for table `IMPORT_ARTICOLI`
--

DROP TABLE IF EXISTS `IMPORT_ARTICOLI`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `IMPORT_ARTICOLI` (
  `Codice` text,
  `Codice_Produttore` text,
  `Codice_Fornitore` text,
  `Descrizione_Breve` text,
  `Unita_Misura` text,
  `Pezzi_Confezione` int(11) DEFAULT NULL,
  `Confezioni_Bancale` int(11) DEFAULT NULL,
  `Gestione_Lotti` int(11) DEFAULT NULL,
  `Ragione_Sociale` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IMPORT_ARTICOLI`
--

LOCK TABLES `IMPORT_ARTICOLI` WRITE;
/*!40000 ALTER TABLE `IMPORT_ARTICOLI` DISABLE KEYS */;
INSERT INTO `IMPORT_ARTICOLI` VALUES ('000_431-abc/B','431_123',NULL,'Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','UPC',27000,1500,1,''),('000_431-abc/N','431_123',NULL,'Bottiglia 0,5 Lt Tritan Verde - ri-Modificata - Neutro','UPC',27000,1500,1,'SNIPS s.r.l. '),('000_440-12345','440_aaa',NULL,'Bottiglia H2O 0,75 lt Tritan Blu - ri-Modificata','UPC',500,20,1,'SNIPS s.r.l. '),('000441_Verde','000441',NULL,'Bottiglia H2O 0,75 Lt Verde verde','UPC',2000,20,0,'SNIPS s.r.l. '),('00107-New','00107',NULL,'Oval bottle 250 ml HDPE White neck 24/410','UPC',8,2,1,'Paedi Protect AG'),('00113-Nuovo','00113',NULL,'Oval 200 ml bottle PET white 24/410','UPC',10,5,1,'Paedi Protect AG'),('00123-Vecchio','00123',NULL,'Oval bottle 200 ml HDPE White neck 24/410 ','UPC',0,0,1,'Paedi Protect AG'),('010.315.003-yes','010.315.003',NULL,'Flacone Shampoo Dog  PVC Verde anonimo, senza tappo-->xx','UPC',100,40,1,'CICIEFFE S.R.L. '),('010.315.096-Qui','010.315.096',NULL,'Flacone Resol Shampoo HDPE Bianco anonimo, senza tappo peso 28/30 gr','UPC',0,0,1,'CICIEFFE S.R.L. '),('010.315.192-aaa','010.315.192',NULL,'Flacone Atena/Olimpia 1000 ml HDPE Bianco, anonimo, senza tappo','UPC',0,0,1,'CICIEFFE S.R.L. '),('010.315.193-bbb','010.315.193',NULL,'Capsula Flip-top bianca 655 chiusura 28/410 - prova','UPC',2500,500,1,''),('018954_33','018954',NULL,'Flacone Cremona 330 ml HDPE Pearlviolett 3% ser. JW PH Volume Shampoo 300 ml - XX','UPC',0,0,1,'Cura Marketing GmbH '),('018956_44','018956',NULL,'Flacone Cremona 330 ml HDPE Gold 3% ser. JW PH Re-Activate Hair & Root Shampoo ser. 2 colori -YY','UPC',100,3,1,'Cura Marketing GmbH '),('019050','019050',NULL,'Flacone Cremona 330 ml HDPE Tiffany Blue Pearl 3% ser. JW PH Hydro Shampoo 300 ml ','UPC',0,0,1,'Cura Marketing GmbH '),('019567','019567',NULL,'Flacone Lodi 750 ml HDPE Perlilla ser. 1 colore','UPC',0,0,1,'Cura Marketing GmbH '),('100ELL-New','100ELL',NULL,'Flacone Ellittico 100 ml HDPE bianco','UPC',2000,150,1,''),('000430','000430','C095004','Bottiglia 0,5 lt Tritan Blu','UPC',180,12,0,'SNIPS s.r.l. '),('000431','000431','C095004','Bottiglia 0,5 Lt Tritan Verde','UPC',180,12,0,'SNIPS s.r.l. '),('000440','000440','C095004','Bottiglia H2O 0,75 lt Tritan Blu','UPC',152,12,0,'SNIPS s.r.l. '),('000441','000441','C095004','Bottiglia H2O 0,75 Lt Verde','UPC',152,12,0,'SNIPS s.r.l. '),('00107','00107','57865576887','Oval bottle 250 ml HDPE White neck 24/410 (Ely 250 ml)','UPC',270,12,0,'Paedi Protect AG'),('00113','00113','57865576887','Oval 200 ml bottle PET white 24/410 (Ely 200 ml PET)','UPC',400,12,0,'Paedi Protect AG'),('00123','00123','57865576887','Oval bottle 200 ml HDPE White neck 24/410 (Ely 200 ml HDPE)','UPC',350,12,0,'Paedi Protect AG'),('010.315.003','010.315.003','2002024','Flacone Shampoo Dog  PVC Verde anonimo, senza tappo','UPC',380,4560,0,'CICIEFFE S.R.L. '),('06008351','06008351','C0894001','Ecosfera attacco rapido','UPC',1000,0,0,'PUCCIPLAST  spa'),('06008352','06008352','C0894001','Eco Sfera attacco rapido HDPE Neutro','UPC',1000,0,0,'PUCCIPLAST  spa'),('06008353','06008353','C0894001','Eco I Sfera attacco rapido HDPE Blu anticalcare','UPC',1000,0,0,'PUCCIPLAST  spa'),('13010','13010','P2002031','Minifermentatore 6 bolle','UPC',500,0,0,'FERRARI group s.r.l. '),('13070','13070','P2002031','Minifermentatore 2 bolle','UPC',500,0,0,'FERRARI group s.r.l. '),('150.03.012004','150.03.012004','542545464','Flacone Mod. PIC 250 ml HDPE Bianco 30 gr ','UPC',500,3000,0,'SUAREZ Company srl'),('150.03.012005','150.03.012005','542545464','Flacone Mod. PIC 250 ml HDPE Nero 30 gr ','UPC',500,3000,0,'SUAREZ Company srl'),('150.03.012008','150.03.012008','542545464','Flacone EB 30 ml HDPE Bianco 24/410 ','UPC',2000,12,0,'SUAREZ Company srl'),('GEL250PVC/N','GEL250PVC','VIM.','Gel 250 ml PVC Neutro','UPC',200,16,1,'SM PACK S.P.A.'),('KRISTAL500-24/B','KRISTAL500-24','','Flacone Kristal 500 ml PET chiusura 24/410 - Bianco','UPC',312,20,1,''),('KRISTAL500-24/N','KRISTAL500-24','','Flacone Kristal 500 ml PET chiusura 24/410 - Neutro','UPC',312,20,1,'');
/*!40000 ALTER TABLE `IMPORT_ARTICOLI` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `atch_attachment`
--

DROP TABLE IF EXISTS `atch_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `atch_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `category` varchar(10) DEFAULT NULL,
  `path` varchar(200) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reference_id_atch` (`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atch_attachment`
--

LOCK TABLES `atch_attachment` WRITE;
/*!40000 ALTER TABLE `atch_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `atch_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_group`
--

DROP TABLE IF EXISTS `core_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person20_idx` (`modified_by`),
  KEY `fk_person_person00` (`created_by`),
  KEY `fk_person_person100` (`assigned`),
  CONSTRAINT `fk_person_person00` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person100` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person200` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_group`
--

LOCK TABLES `core_group` WRITE;
/*!40000 ALTER TABLE `core_group` DISABLE KEYS */;
INSERT INTO `core_group` VALUES (1,NULL,'Amministratore','AC',NULL,NULL,NULL,'2019-06-03 12:49:02','2019-06-03 12:49:02',NULL,0,'2019-06-03 12:49:02'),(2,NULL,'Operatore','OP',NULL,NULL,NULL,'2019-06-03 12:49:02','2019-06-03 12:49:02',NULL,0,'2019-06-03 12:49:02');
/*!40000 ALTER TABLE `core_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_group_has_person`
--

DROP TABLE IF EXISTS `core_group_has_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_group_has_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person20_idx` (`modified_by`),
  KEY `fk_person_person2001_idx` (`person_id`),
  KEY `fk_person_person2002_idx` (`group_id`),
  KEY `fk_person_person000` (`created_by`),
  KEY `fk_person_person1000` (`assigned`),
  CONSTRAINT `fk_person_person000` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person1000` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2000` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2001` FOREIGN KEY (`person_id`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2002` FOREIGN KEY (`group_id`) REFERENCES `core_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_group_has_person`
--

LOCK TABLES `core_group_has_person` WRITE;
/*!40000 ALTER TABLE `core_group_has_person` DISABLE KEYS */;
INSERT INTO `core_group_has_person` VALUES (2,NULL,1,1,1,NULL,NULL,'2019-06-03 12:50:10','2019-06-03 12:50:10',NULL,0,'2019-06-03 12:50:10'),(33,'7B82C74EF1624742A4B06AD0F90A64FC',42,2,1,1,1,'1970-01-01 00:33:41','1970-01-01 00:33:41',NULL,0,'2021-06-07 08:34:28'),(34,'88b0e4a88469d094f580e8062bd7cabf',43,1,1,1,1,'1970-01-01 01:33:41','1970-01-01 01:33:41',NULL,0,'2021-07-30 16:05:42');
/*!40000 ALTER TABLE `core_group_has_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_gui`
--

DROP TABLE IF EXISTS `core_gui`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_gui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_item` tinyint(1) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gui_gui1_idx` (`parent_id`),
  CONSTRAINT `fk_gui_gui1` FOREIGN KEY (`parent_id`) REFERENCES `core_gui` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_gui`
--

LOCK TABLES `core_gui` WRITE;
/*!40000 ALTER TABLE `core_gui` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_gui` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_permission`
--

DROP TABLE IF EXISTS `core_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `ext_oid` varchar(32) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `execute` tinyint(1) DEFAULT NULL,
  `read` tinyint(1) DEFAULT NULL,
  `write` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `secret_token` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person20_idx` (`modified_by`),
  KEY `fk_person_person202_idx` (`person_id`),
  KEY `fk_person_person2021_idx` (`group_id`),
  KEY `fk_person_person010` (`created_by`),
  KEY `fk_person_person1010` (`assigned`),
  CONSTRAINT `fk_person_person010` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person1010` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2010` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2020` FOREIGN KEY (`person_id`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2021` FOREIGN KEY (`group_id`) REFERENCES `core_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_permission`
--

LOCK TABLES `core_permission` WRITE;
/*!40000 ALTER TABLE `core_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_person`
--

DROP TABLE IF EXISTS `core_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `title` varchar(10) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web_page_address` varchar(100) DEFAULT NULL,
  `im_address` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `postcode` varchar(45) DEFAULT NULL,
  `number` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) NOT NULL DEFAULT 'Europe/London',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person2_idx` (`assigned`),
  KEY `fk_person_person_idx` (`modified_by`),
  KEY `fk_person_person1_idx` (`created_by`),
  CONSTRAINT `fk_person_person` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person1` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_person`
--

LOCK TABLES `core_person` WRITE;
/*!40000 ALTER TABLE `core_person` DISABLE KEYS */;
INSERT INTO `core_person` VALUES (1,NULL,'n3tester','$2y$10$RJzf.MCywxdHqpqwEfd8DejnwlWCl3UYqPIVba29Oi8mevFR46Wx6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$10$RJzf.MCywxdHqpqwEfd8DejnwlWCl3UYqPIVba29Oi8mevFR46Wx6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,NULL,0,'2020-07-17 08:13:58'),(42,'ADE29C7D19844FAFBABA10E153004081','operatore','$2y$10$Cp.nnJTMesPVYPfdkEEV2OJErFQAJg48mC9IqIhZp3J9lwHg5sh56',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'1970-01-01 00:32:50',NULL,0,'2021-06-07 08:37:54'),(43,'3ff9920ada6baf67a5e57cc99e96618c','administrator','$2y$10$SKv6eQG/CfwFrFrDPuyWmestdYeNuRFSapuUMEGeRYCYcm/Adjxmm',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',1,1,1,'1970-01-01 01:33:41','1970-01-01 01:33:41',NULL,0,'2021-07-30 16:05:42');
/*!40000 ALTER TABLE `core_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document`
--

DROP TABLE IF EXISTS `dcmt_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(8) NOT NULL,
  `document_type` varchar(1) NOT NULL,
  `direction` varchar(2) DEFAULT NULL,
  `accounting_document` tinyint(4) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `progressive` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `reference_document_id` int(11) DEFAULT NULL,
  `purchase_order` varchar(45) DEFAULT NULL,
  `delivery_window_start` datetime DEFAULT NULL,
  `delivery_window_end` datetime DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `exchange_rate` decimal(20,4) DEFAULT NULL,
  `causal` text,
  `payment_solution_id` int(11) DEFAULT NULL,
  `totally_paid` tinyint(4) DEFAULT NULL,
  `totally_paid_date` datetime DEFAULT NULL,
  `discount_amount` decimal(20,4) DEFAULT NULL,
  `discount_percentage` decimal(6,3) DEFAULT NULL,
  `surcharge_amount` decimal(20,4) DEFAULT NULL,
  `surcharge_percentage` decimal(6,3) DEFAULT NULL,
  `taxed_amount` decimal(20,4) DEFAULT NULL,
  `aspect_code` varchar(6) DEFAULT NULL,
  `document_total` decimal(20,4) DEFAULT NULL,
  `processed` tinyint(4) DEFAULT '0',
  `port_code` varchar(6) DEFAULT NULL,
  `shipping_code` varchar(40) DEFAULT NULL,
  `transport_code` varchar(6) DEFAULT NULL,
  `courier_business_name` varchar(100) DEFAULT NULL,
  `courier_driver_name` varchar(100) DEFAULT NULL,
  `courier_driver_lastname` varchar(100) DEFAULT NULL,
  `courier_driving_license_number` varchar(100) DEFAULT NULL,
  `courier_registered_street` varchar(60) DEFAULT NULL,
  `courier_registered_number` varchar(8) DEFAULT NULL,
  `courier_registered_zip` varchar(5) DEFAULT NULL,
  `courier_registered_city` varchar(60) DEFAULT NULL,
  `courier_registered_province` varchar(2) DEFAULT NULL,
  `courier_registered_country` varchar(2) DEFAULT NULL,
  `transport_datetime` datetime DEFAULT NULL,
  `packages_quantity` int(11) DEFAULT NULL,
  `gross_weight` decimal(20,4) DEFAULT NULL,
  `net_weight` decimal(20,4) DEFAULT NULL,
  `weight_unit_measure` varchar(6) DEFAULT NULL,
  `volume` decimal(20,4) DEFAULT NULL,
  `volume_unit_measure` varchar(6) DEFAULT NULL,
  `note` text,
  `deposit_percentage` decimal(6,2) DEFAULT NULL,
  `fixed_deposit` decimal(20,4) DEFAULT NULL,
  `virtual_stamp` tinyint(4) DEFAULT NULL,
  `virtual_stamp_amount` decimal(20,4) DEFAULT NULL,
  `first_transferor` text,
  `intent_letter` text,
  `reverse_charge` tinyint(4) DEFAULT NULL,
  `split_payment` tinyint(4) DEFAULT NULL,
  `status_code` varchar(6) DEFAULT NULL,
  `status_description` varchar(45) DEFAULT NULL,
  `sender_code` varchar(100) DEFAULT NULL,
  `sender_business_name` varchar(100) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_lastname` varchar(100) DEFAULT NULL,
  `sendet_fiscal_code` varchar(16) DEFAULT NULL,
  `sender_vat_number` varchar(28) DEFAULT NULL,
  `sender_collectability` varchar(3) DEFAULT NULL,
  `sender_pec` varchar(256) DEFAULT NULL,
  `sender_sdi` varchar(7) DEFAULT NULL,
  `sender_type` varchar(10) DEFAULT NULL,
  `sender_registered_receiver` varchar(250) DEFAULT NULL,
  `sender_registered_street` varchar(60) DEFAULT NULL,
  `sender_registered_number` varchar(8) DEFAULT NULL,
  `sender_registered_zip` varchar(5) DEFAULT NULL,
  `sender_registered_city` varchar(60) DEFAULT NULL,
  `sender_registered_province` varchar(2) DEFAULT NULL,
  `sender_registered_country` varchar(2) DEFAULT NULL,
  `sender_registered_code` varchar(32) DEFAULT NULL,
  `sender_dispatch_receiver` varchar(250) DEFAULT NULL,
  `sender_dispatch_street` varchar(60) DEFAULT NULL,
  `sender_dispatch_number` varchar(8) DEFAULT NULL,
  `sender_dispatch_zip` varchar(5) DEFAULT NULL,
  `sender_dispatch_city` varchar(60) DEFAULT NULL,
  `sender_dispatch_province` varchar(2) DEFAULT NULL,
  `sender_dispatch_country` varchar(2) DEFAULT NULL,
  `sender_dispatch_code` varchar(32) DEFAULT NULL,
  `receiver_code` varchar(100) DEFAULT NULL,
  `receiver_business_name` varchar(100) DEFAULT NULL,
  `receiver_name` varchar(100) DEFAULT NULL,
  `receiver_lastname` varchar(100) DEFAULT NULL,
  `receiver_fiscal_code` varchar(16) DEFAULT NULL,
  `receiver_vat_number` varchar(28) DEFAULT NULL,
  `receiver_collectability` varchar(3) DEFAULT NULL,
  `receiver_pec` varchar(256) DEFAULT NULL,
  `receiver_sdi` varchar(7) DEFAULT NULL,
  `receiver_type` varchar(10) DEFAULT NULL,
  `receiver_registered_receiver` varchar(250) DEFAULT NULL,
  `receiver_registered_street` varchar(60) DEFAULT NULL,
  `receiver_registered_number` varchar(8) DEFAULT NULL,
  `receiver_registered_zip` varchar(5) DEFAULT NULL,
  `receiver_registered_city` varchar(60) DEFAULT NULL,
  `receiver_registered_province` varchar(2) DEFAULT NULL,
  `receiver_registered_country` varchar(2) DEFAULT NULL,
  `receiver_registered_code` varchar(32) DEFAULT NULL,
  `receiver_dispatch_receiver` varchar(250) DEFAULT NULL,
  `receiver_dispatch_street` varchar(60) DEFAULT NULL,
  `receiver_dispatch_number` varchar(8) DEFAULT NULL,
  `receiver_dispatch_zip` varchar(5) DEFAULT NULL,
  `receiver_dispatch_city` varchar(60) DEFAULT NULL,
  `receiver_dispatch_province` varchar(2) DEFAULT NULL,
  `receiver_dispatch_country` varchar(2) DEFAULT NULL,
  `receiver_dispatch_code` varchar(32) DEFAULT NULL,
  `owner_code` varchar(100) DEFAULT NULL,
  `owner_business_name` varchar(100) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `owner_lastname` varchar(100) DEFAULT NULL,
  `owner_fiscal_code` varchar(16) DEFAULT NULL,
  `owner_vat_code` varchar(28) DEFAULT NULL,
  `owner_collectability` varchar(3) DEFAULT NULL,
  `owner_pec` varchar(256) DEFAULT NULL,
  `owner_sdi` varchar(7) DEFAULT NULL,
  `owner_type` varchar(10) DEFAULT NULL,
  `owner_registered_receiver` varchar(250) DEFAULT NULL,
  `owner_registered_street` varchar(60) DEFAULT NULL,
  `owner_registered_number` varchar(8) DEFAULT NULL,
  `owner_registered_zip` varchar(5) DEFAULT NULL,
  `owner_registered_city` varchar(60) DEFAULT NULL,
  `owner_registered_province` varchar(2) DEFAULT NULL,
  `owner_registered_country` varchar(2) DEFAULT NULL,
  `owner_registered_code` varchar(32) DEFAULT NULL,
  `owner_dispatch_receiver` varchar(250) DEFAULT NULL,
  `owner_dispatch_street` varchar(60) DEFAULT NULL,
  `owner_dispatch_number` varchar(8) DEFAULT NULL,
  `owner_dispatch_zip` varchar(5) DEFAULT NULL,
  `owner_dispatch_city` varchar(60) DEFAULT NULL,
  `owner_dispatch_province` varchar(2) DEFAULT NULL,
  `owner_dispatch_country` varchar(2) DEFAULT NULL,
  `owner_dispatch_code` varchar(32) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_document_dcmt_document1_idx` (`reference_document_id`),
  KEY `fk_dcmt_document_dcmt_payment_solution_idx` (`payment_solution_id`),
  CONSTRAINT `fk_dcmt_document_dcmt_document1` FOREIGN KEY (`reference_document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_dcmt_document_dcmt_payment_solution` FOREIGN KEY (`payment_solution_id`) REFERENCES `dcmt_document_payment_solution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document`
--

LOCK TABLES `dcmt_document` WRITE;
/*!40000 ALTER TABLE `dcmt_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_aspect_code`
--

DROP TABLE IF EXISTS `dcmt_document_aspect_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_aspect_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_aspect_code`
--

LOCK TABLES `dcmt_document_aspect_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_aspect_code` DISABLE KEYS */;
INSERT INTO `dcmt_document_aspect_code` VALUES (1,'466C115AF64C4C49955FE56ABBD9F451','BAN','BANCALE',41,41,41,'2021-01-11 08:11:09','2021-01-11 08:11:09',NULL,0,'2021-01-11 09:11:09'),(2,'716DF0D29D0442A99AFCE228B75A5FD1','COL','COLLO',41,41,41,'2021-01-22 08:34:49','2021-01-22 08:34:49',NULL,0,'2021-01-22 09:34:49');
/*!40000 ALTER TABLE `dcmt_document_aspect_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_company_default`
--

DROP TABLE IF EXISTS `dcmt_document_company_default`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_company_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `parameter_name` varchar(100) NOT NULL,
  `parameter_value` varchar(100) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_company_default`
--

LOCK TABLES `dcmt_document_company_default` WRITE;
/*!40000 ALTER TABLE `dcmt_document_company_default` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_company_default` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_config`
--

DROP TABLE IF EXISTS `dcmt_document_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_type` varchar(1) DEFAULT NULL COMMENT 'I  -> invoice\nT -> transport document\nO -> order\nQ -> Price quotation\n',
  `code` varchar(8) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `document_direction` varchar(3) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_config`
--

LOCK TABLES `dcmt_document_config` WRITE;
/*!40000 ALTER TABLE `dcmt_document_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_document_export_v`
--

DROP TABLE IF EXISTS `dcmt_document_export_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_document_export_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_document_export_v` AS SELECT 
 1 AS `status_description`,
 1 AS `code`,
 1 AS `progressive`,
 1 AS `number`,
 1 AS `receiver_business_name`,
 1 AS `receiver_name`,
 1 AS `receiver_lastname`,
 1 AS `date`,
 1 AS `delivery_window_start`,
 1 AS `delivery_window_end`,
 1 AS `causal`,
 1 AS `document_total`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dcmt_document_installment`
--

DROP TABLE IF EXISTS `dcmt_document_installment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_installment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_id` int(11) NOT NULL,
  `amount` decimal(20,4) DEFAULT NULL,
  `bank` varchar(80) DEFAULT NULL,
  `iban` varchar(34) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `bic` varchar(11) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `payment_mode` varchar(45) DEFAULT NULL,
  `payment_days` int(11) DEFAULT NULL,
  `payment_added_days` int(11) DEFAULT NULL,
  `payment_reference_terms` varchar(3) DEFAULT NULL,
  `installment_number` int(11) DEFAULT NULL,
  `document_date` varchar(45) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `paid` tinyint(4) DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_installment_dcmt_document1_idx` (`document_id`),
  CONSTRAINT `fk_dcmt_installment_dcmt_document1` FOREIGN KEY (`document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=481 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_installment`
--

LOCK TABLES `dcmt_document_installment` WRITE;
/*!40000 ALTER TABLE `dcmt_document_installment` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_installment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_installment_payment`
--

DROP TABLE IF EXISTS `dcmt_document_installment_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_installment_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `installment_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `amount` decimal(17,2) NOT NULL,
  `code` varchar(10) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_document_installment_payment_installment_idx` (`installment_id`),
  CONSTRAINT `fk_dcmt_document_installment_payment_installment_idx` FOREIGN KEY (`installment_id`) REFERENCES `dcmt_document_installment` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_installment_payment`
--

LOCK TABLES `dcmt_document_installment_payment` WRITE;
/*!40000 ALTER TABLE `dcmt_document_installment_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_installment_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_document_installment_payment_v`
--

DROP TABLE IF EXISTS `dcmt_document_installment_payment_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_document_installment_payment_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_document_installment_payment_v` AS SELECT 
 1 AS `id`,
 1 AS `installment_id`,
 1 AS `date`,
 1 AS `amount`,
 1 AS `code`,
 1 AS `payment_code_description`,
 1 AS `erased`,
 1 AS `last_update`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dcmt_document_number`
--

DROP TABLE IF EXISTS `dcmt_document_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(4) NOT NULL,
  `value` int(11) NOT NULL,
  `year` varchar(4) NOT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_year_UNIQUE` (`year`,`document_type`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_number`
--

LOCK TABLES `dcmt_document_number` WRITE;
/*!40000 ALTER TABLE `dcmt_document_number` DISABLE KEYS */;
INSERT INTO `dcmt_document_number` VALUES (11,'ORDS',5,'2020',0,'2020-10-16 10:33:01'),(12,'ORDB',1,'2020',0,'2020-10-13 07:23:38'),(13,'TRDS',13,'2020',0,'2020-12-18 10:28:13'),(14,'TRDB',1,'2020',0,'2020-10-13 07:24:09'),(21,'INVS',11,'2020',0,'2020-12-18 11:07:46'),(22,'QUOT',5,'2020',0,'2020-10-16 10:15:12'),(23,'INVB',0,'2020',0,'2020-09-02 09:48:02'),(24,'NCRE',1,'2020',0,'2020-10-12 14:42:03'),(25,'INVS',0,'2019',0,'2020-09-28 13:18:47'),(26,'TRDR',4,'2020',0,'2020-10-13 12:55:04'),(27,'INVS',6,'2021',0,'2021-03-12 09:58:37'),(28,'TRDS',10,'2021',0,'2021-04-07 07:52:38'),(29,'TRDB',1,'2021',0,'2021-01-22 09:32:12');
/*!40000 ALTER TABLE `dcmt_document_number` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_payment_code`
--

DROP TABLE IF EXISTS `dcmt_document_payment_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_payment_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `value` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_payment_code`
--

LOCK TABLES `dcmt_document_payment_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_payment_code` DISABLE KEYS */;
INSERT INTO `dcmt_document_payment_code` VALUES (1,'MP01','contanti',0,'2019-06-28 13:00:00'),(2,'MP02','assegno',0,'2019-06-28 13:00:00'),(3,'MP03','assegno circolare',0,'2019-06-28 13:00:00'),(4,'MP04','contanti presso Tesoreria',0,'2019-06-28 13:00:00'),(5,'MP05','bonifico',0,'2019-06-28 13:00:00'),(6,'MP06','vaglia cambiario',0,'2019-06-28 13:00:00'),(7,'MP07','bollettino bancario',0,'2019-06-28 13:00:00'),(8,'MP08','carta di pagamento',0,'2019-06-28 13:00:00'),(9,'MP09','RID',0,'2019-06-28 13:00:00'),(10,'MP10','RID utenze',0,'2019-06-28 13:00:00'),(11,'MP11','RID veloce',0,'2019-06-28 13:00:00'),(12,'MP12','RIBA',0,'2019-06-28 13:00:00'),(13,'MP13','MAV',0,'2019-06-28 13:00:00'),(14,'MP14','quietanza erario',0,'2019-06-28 13:00:00'),(15,'MP15','giroconto su conti di contabilità speciale',0,'2019-06-28 13:00:00'),(16,'MP16','domiciliazione bancaria',0,'2019-06-28 13:00:00'),(17,'MP17','domiciliazione postale',0,'2019-06-28 13:00:00'),(18,'MP18','bollettino di c/c postale',0,'2019-06-28 13:00:00'),(19,'MP19','SEPA Direct Debit',0,'2019-06-28 13:00:00'),(20,'MP20','SEPA Direct Debit CORE',0,'2019-06-28 13:00:00'),(21,'MP21','SEPA Direct Debit B2B',0,'2019-06-28 13:00:00'),(22,'MP22','Trattenuta su somme già riscosse',0,'2019-06-28 13:00:00');
/*!40000 ALTER TABLE `dcmt_document_payment_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_document_payment_schedule_v`
--

DROP TABLE IF EXISTS `dcmt_document_payment_schedule_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_document_payment_schedule_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_document_payment_schedule_v` AS SELECT 
 1 AS `id`,
 1 AS `document_id`,
 1 AS `document_type`,
 1 AS `document_code`,
 1 AS `company_name`,
 1 AS `document_total`,
 1 AS `totally_paid`,
 1 AS `document_number`,
 1 AS `document_date`,
 1 AS `payment_mode`,
 1 AS `payent_mode_description`,
 1 AS `installment_amount`,
 1 AS `paied_amount`,
 1 AS `unpaid_amount`,
 1 AS `expiry_date`,
 1 AS `installment_paid`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dcmt_document_payment_solution`
--

DROP TABLE IF EXISTS `dcmt_document_payment_solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_payment_solution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL,
  `discount` decimal(20,4) DEFAULT NULL,
  `deposit_percentage` decimal(20,4) DEFAULT NULL,
  `deposit_fixed` decimal(20,4) DEFAULT NULL,
  `collection_bank` varchar(200) DEFAULT NULL,
  `invoice_date` tinyint(1) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_payment_solution`
--

LOCK TABLES `dcmt_document_payment_solution` WRITE;
/*!40000 ALTER TABLE `dcmt_document_payment_solution` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_payment_solution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_payment_solution_row`
--

DROP TABLE IF EXISTS `dcmt_document_payment_solution_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_payment_solution_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_solution_id` int(11) DEFAULT NULL,
  `payment_code_id` int(11) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `taxable` decimal(18,2) DEFAULT NULL,
  `tax` decimal(18,2) DEFAULT NULL,
  `expense` decimal(18,2) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `bill` varchar(45) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_payment_solution_idx` (`payment_solution_id`),
  KEY `fk_dcmt_payment_code_idx` (`payment_code_id`),
  CONSTRAINT `fk_dcmt_payment_code` FOREIGN KEY (`payment_code_id`) REFERENCES `dcmt_document_payment_code` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dcmt_payment_solution` FOREIGN KEY (`payment_solution_id`) REFERENCES `dcmt_document_payment_solution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_payment_solution_row`
--

LOCK TABLES `dcmt_document_payment_solution_row` WRITE;
/*!40000 ALTER TABLE `dcmt_document_payment_solution_row` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_payment_solution_row` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_port_code`
--

DROP TABLE IF EXISTS `dcmt_document_port_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_port_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(9) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_port_code`
--

LOCK TABLES `dcmt_document_port_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_port_code` DISABLE KEYS */;
INSERT INTO `dcmt_document_port_code` VALUES (1,'FCB0213AB37A0D0FA406537818F92084','PFR','Porto Franco',NULL,NULL,NULL,'2020-08-18 12:00:00','2020-08-18 12:00:00',NULL,0,'2020-08-18 12:00:00'),(2,'D497E8EA1D8CF320B814947B8F11DBC7','PAS','Porto Assegnato',NULL,NULL,NULL,'2020-08-18 12:00:00','2020-08-18 12:00:00',NULL,0,'2020-08-18 12:00:00');
/*!40000 ALTER TABLE `dcmt_document_port_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_progressive`
--

DROP TABLE IF EXISTS `dcmt_document_progressive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_progressive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(1) NOT NULL,
  `value` int(11) NOT NULL,
  `year` varchar(4) NOT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_progressive`
--

LOCK TABLES `dcmt_document_progressive` WRITE;
/*!40000 ALTER TABLE `dcmt_document_progressive` DISABLE KEYS */;
INSERT INTO `dcmt_document_progressive` VALUES (130,'O',6,'2020',0,'2020-07-31 08:39:31'),(141,'T',18,'2020',0,'2020-08-20 14:31:00'),(148,'I',12,'2020',0,'2020-08-21 09:09:32'),(149,'Q',5,'2020',0,'2020-09-01 13:26:13'),(150,'N',1,'2020',0,'2020-09-04 13:06:32'),(151,'I',0,'2019',0,'2020-09-28 13:18:47'),(152,'I',6,'2021',0,'2021-01-07 09:36:05'),(153,'T',11,'2021',0,'2021-01-11 09:11:58');
/*!40000 ALTER TABLE `dcmt_document_progressive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_row`
--

DROP TABLE IF EXISTS `dcmt_document_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_id` int(11) NOT NULL,
  `parent_invoice_id` int(11) DEFAULT NULL,
  `parent_row_id` int(11) DEFAULT NULL,
  `document_type` varchar(1) NOT NULL,
  `document_purchase_order` varchar(45) DEFAULT NULL,
  `code` varchar(8) NOT NULL,
  `row_number` int(11) DEFAULT NULL,
  `causal` text,
  `description` text,
  `buy_tax_rate` decimal(20,4) DEFAULT NULL,
  `buy_tax_kind` varchar(10) DEFAULT NULL,
  `buy_tax_normative_reference` varchar(100) DEFAULT NULL,
  `sell_tax_rate` decimal(20,4) DEFAULT NULL,
  `sell_tax_kind` varchar(10) DEFAULT NULL,
  `sell_tax_normative_reference` varchar(100) DEFAULT NULL,
  `tax_deductibility_percent` decimal(6,2) NOT NULL DEFAULT '100.00',
  `lot_code` varchar(100) DEFAULT NULL,
  `processable_quantity` decimal(20,4) DEFAULT NULL,
  `processed_quantity` decimal(20,4) DEFAULT NULL,
  `discount` decimal(20,4) DEFAULT NULL,
  `discount_percentage` decimal(6,3) DEFAULT NULL,
  `added_discount` decimal(20,4) DEFAULT NULL,
  `added_discount_percentage` decimal(6,3) DEFAULT NULL,
  `surcharge` decimal(20,4) DEFAULT NULL,
  `surcharge_percentage` decimal(6,3) DEFAULT NULL,
  `added_surcharge` decimal(20,4) DEFAULT NULL,
  `added_surcharge_percentage` decimal(20,4) DEFAULT NULL,
  `total_discount` decimal(20,4) DEFAULT NULL,
  `total_discount_percentage` decimal(6,3) DEFAULT NULL,
  `total_surcharge` decimal(20,4) DEFAULT NULL,
  `total_surcharge_percentage` decimal(20,4) DEFAULT NULL,
  `unit_price` decimal(20,4) DEFAULT NULL,
  `unit_price_discounted` decimal(20,4) DEFAULT NULL,
  `discounted_unit_price_surcharged` decimal(20,4) DEFAULT NULL,
  `taxable_amount` decimal(20,4) DEFAULT NULL,
  `tax_amount` decimal(20,4) DEFAULT NULL,
  `taxed_amount` decimal(20,4) DEFAULT NULL,
  `quantity` decimal(20,4) DEFAULT NULL,
  `measure_unit` varchar(6) DEFAULT NULL,
  `total_price` decimal(20,4) DEFAULT NULL,
  `external_code` varchar(100) DEFAULT NULL,
  `internal_code` varchar(100) DEFAULT NULL,
  `credit_application` varchar(64) DEFAULT NULL,
  `return_number` varchar(64) DEFAULT NULL,
  `commission_1` decimal(20,4) DEFAULT NULL,
  `commission_2` decimal(20,4) DEFAULT NULL,
  `commission_1_percentage` decimal(6,3) DEFAULT NULL,
  `commission_2_percentage` decimal(6,3) DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `note` text,
  `free_gift` tinyint(4) DEFAULT NULL,
  `status_code` varchar(6) DEFAULT NULL,
  `net_weight` decimal(20,4) DEFAULT NULL,
  `gross_weight` decimal(20,4) DEFAULT NULL,
  `volume` decimal(20,4) DEFAULT NULL,
  `weight_unit_measure` varchar(6) DEFAULT NULL,
  `volume_unit_measure` varchar(6) DEFAULT NULL,
  `width_unit_measure` decimal(20,4) DEFAULT NULL,
  `height_unit_measure` decimal(20,4) DEFAULT NULL,
  `depth_unit_measure` decimal(20,4) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_row_dcmt_document_idx` (`document_id`),
  KEY `fk_dcmt_row_dcmt_invoice_idx` (`parent_invoice_id`),
  CONSTRAINT `fk_dcmt_row_dcmt_document` FOREIGN KEY (`document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_dcmt_row_dcmt_invoice` FOREIGN KEY (`parent_invoice_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=828 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_row`
--

LOCK TABLES `dcmt_document_row` WRITE;
/*!40000 ALTER TABLE `dcmt_document_row` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_row` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_row_has_item`
--

DROP TABLE IF EXISTS `dcmt_document_row_has_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_row_has_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_row_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_document_row_has_item_dcmt_document_row1_idx` (`document_row_id`),
  CONSTRAINT `fk_dcmt_document_row_has_item_dcmt_document_row1` FOREIGN KEY (`document_row_id`) REFERENCES `dcmt_document_row` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_row_has_item`
--

LOCK TABLES `dcmt_document_row_has_item` WRITE;
/*!40000 ALTER TABLE `dcmt_document_row_has_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_row_has_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_document_row_v`
--

DROP TABLE IF EXISTS `dcmt_document_row_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_document_row_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_document_row_v` AS SELECT 
 1 AS `row_number`,
 1 AS `description`,
 1 AS `external_code`,
 1 AS `internal_code`,
 1 AS `sell_tax_rate`,
 1 AS `unit_price`,
 1 AS `quantity`,
 1 AS `measure_unit`,
 1 AS `total_price`,
 1 AS `id`,
 1 AS `credit_application`,
 1 AS `return_number`,
 1 AS `commission_1`,
 1 AS `commission_2`,
 1 AS `commission_1_percentage`,
 1 AS `commission_2_percentage`,
 1 AS `delivery_date`,
 1 AS `note`,
 1 AS `free_gift`,
 1 AS `status_code`,
 1 AS `net_weight`,
 1 AS `gross_weight`,
 1 AS `volume`,
 1 AS `weight_unit_measure`,
 1 AS `volume_unit_measure`,
 1 AS `oid`,
 1 AS `document_id`,
 1 AS `parent_invoice_id`,
 1 AS `parent_row_id`,
 1 AS `document_type`,
 1 AS `document_purchase_order`,
 1 AS `code`,
 1 AS `buy_tax_rate`,
 1 AS `buy_tax_kind`,
 1 AS `buy_tax_normative_reference`,
 1 AS `sell_tax_kind`,
 1 AS `sell_tax_normative_reference`,
 1 AS `lot_code`,
 1 AS `processable_quantity`,
 1 AS `processed_quantity`,
 1 AS `discount`,
 1 AS `discount_percentage`,
 1 AS `added_discount`,
 1 AS `added_discount_percentage`,
 1 AS `surcharge`,
 1 AS `surcharge_percentage`,
 1 AS `added_surcharge`,
 1 AS `added_surcharge_percentage`,
 1 AS `total_discount`,
 1 AS `total_discount_percentage`,
 1 AS `total_surcharge`,
 1 AS `total_surcharge_percentage`,
 1 AS `unit_price_discounted`,
 1 AS `discounted_unit_price_surcharged`,
 1 AS `taxable_amount`,
 1 AS `tax_amount`,
 1 AS `taxed_amount`,
 1 AS `causal`,
 1 AS `erased`,
 1 AS `parent_document_id`,
 1 AS `parent_document_number`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dcmt_document_shipping_code`
--

DROP TABLE IF EXISTS `dcmt_document_shipping_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_shipping_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_shipping_code`
--

LOCK TABLES `dcmt_document_shipping_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_shipping_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_shipping_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_status_code`
--

DROP TABLE IF EXISTS `dcmt_document_status_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_status_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_status_code`
--

LOCK TABLES `dcmt_document_status_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_status_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_status_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_document_tax_calculation_v`
--

DROP TABLE IF EXISTS `dcmt_document_tax_calculation_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_document_tax_calculation_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_document_tax_calculation_v` AS SELECT 
 1 AS `year`,
 1 AS `month`,
 1 AS `month_description`,
 1 AS `sales_tax`,
 1 AS `purchases_tax`,
 1 AS `to_be_paid_tax`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dcmt_document_total_tax`
--

DROP TABLE IF EXISTS `dcmt_document_total_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_total_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_id` int(11) NOT NULL,
  `tax_rate` decimal(6,3) DEFAULT NULL,
  `tax_kind` varchar(10) DEFAULT NULL,
  `taxable_amount` decimal(20,4) DEFAULT NULL,
  `tax_amount` decimal(20,4) DEFAULT NULL,
  `tax_deductibility_percent` decimal(6,2) DEFAULT '100.00',
  `normative_reference` varchar(100) DEFAULT NULL,
  `collectability` varchar(2) DEFAULT NULL,
  `document_date` datetime DEFAULT NULL,
  `total` decimal(21,4) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_total_tax_dcmt_document1_idx` (`document_id`),
  CONSTRAINT `fk_dcmt_total_tax_dcmt_document1` FOREIGN KEY (`document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9222 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_total_tax`
--

LOCK TABLES `dcmt_document_total_tax` WRITE;
/*!40000 ALTER TABLE `dcmt_document_total_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_total_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmt_document_transport_code`
--

DROP TABLE IF EXISTS `dcmt_document_transport_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dcmt_document_transport_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmt_document_transport_code`
--

LOCK TABLES `dcmt_document_transport_code` WRITE;
/*!40000 ALTER TABLE `dcmt_document_transport_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmt_document_transport_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `dcmt_payment_solution_row_v`
--

DROP TABLE IF EXISTS `dcmt_payment_solution_row_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_payment_solution_row_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_payment_solution_row_v` AS SELECT 
 1 AS `payment_sol`,
 1 AS `days`,
 1 AS `taxable`,
 1 AS `tax`,
 1 AS `payment_solution_id`,
 1 AS `id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `dcmt_payment_solution_v`
--

DROP TABLE IF EXISTS `dcmt_payment_solution_v`;
/*!50001 DROP VIEW IF EXISTS `dcmt_payment_solution_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `dcmt_payment_solution_v` AS SELECT 
 1 AS `id`,
 1 AS `code`,
 1 AS `description`,
 1 AS `discount`,
 1 AS `deposit_percentage`,
 1 AS `deposit_fixed`,
 1 AS `collection_bank`,
 1 AS `invoice_date`,
 1 AS `days`,
 1 AS `erased`,
 1 AS `last_update`,
 1 AS `rate`,
 1 AS `payment_solution_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `einv_addr`
--

DROP TABLE IF EXISTS `einv_addr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_addr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_einv_nom` int(11) NOT NULL,
  `indirizzo` varchar(60) NOT NULL,
  `numero_civico` varchar(8) DEFAULT NULL,
  `cap` varchar(5) NOT NULL,
  `comune` varchar(60) NOT NULL,
  `provincia` varchar(2) DEFAULT NULL,
  `nazione` varchar(2) NOT NULL,
  `tipologia` varchar(2) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_addr_einv_nom1_idx` (`id_einv_nom`),
  CONSTRAINT `fk_einv_addr_einv_nom1` FOREIGN KEY (`id_einv_nom`) REFERENCES `einv_nom` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_addr`
--

LOCK TABLES `einv_addr` WRITE;
/*!40000 ALTER TABLE `einv_addr` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_addr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_addr_type`
--

DROP TABLE IF EXISTS `einv_addr_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_addr_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `nome_breve` varchar(2) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_addr_type`
--

LOCK TABLES `einv_addr_type` WRITE;
/*!40000 ALTER TABLE `einv_addr_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_addr_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_config`
--

DROP TABLE IF EXISTS `einv_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `parameter_name` varchar(128) NOT NULL,
  `parameter_value` varchar(128) NOT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=427 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_config`
--

LOCK TABLES `einv_config` WRITE;
/*!40000 ALTER TABLE `einv_config` DISABLE KEYS */;
INSERT INTO `einv_config` VALUES (424,NULL,'it_idpaese','IT','2020-10-13 08:07:19'),(425,NULL,'it_idcodice','ABC123D456','2020-10-13 08:07:19'),(426,NULL,'cp_regime_fiscale','RF01','2020-10-13 08:07:19');
/*!40000 ALTER TABLE `einv_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body`
--

DROP TABLE IF EXISTS `einv_inv_body`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_einv_inv_header` int(11) NOT NULL,
  `dg_dgd_TipoDocumento` varchar(4) NOT NULL,
  `dg_dgd_Divisa` varchar(3) NOT NULL,
  `dg_dgd_Data` datetime NOT NULL,
  `dg_dgd_Numero` varchar(20) NOT NULL,
  `dg_dgd_dr_TipoRitenuta` varchar(4) DEFAULT NULL,
  `dg_dgd_dr_ImportoRitenuta` decimal(17,2) DEFAULT NULL,
  `dg_dgd_dr_AliquotaRitenuta` decimal(6,2) DEFAULT NULL,
  `dg_dgd_dr_CausalePagamento` varchar(2) DEFAULT NULL,
  `dg_dgd_db_BolloVirtuale` varchar(2) DEFAULT NULL,
  `dg_dgd_db_ImportoBollo` decimal(17,2) DEFAULT NULL,
  `dg_dgd_ImportoTotaleDocumento` decimal(17,2) DEFAULT NULL,
  `dg_dgd_Arrotondamento` decimal(17,2) DEFAULT NULL,
  `dg_dgd_Art73` varchar(2) DEFAULT NULL,
  `dg_dtra_dav_ifi_IdPaese` varchar(2) DEFAULT NULL,
  `dg_dtra_dav_ifi_IdCodice` varchar(28) DEFAULT NULL,
  `dg_dtra_dav_CodiceFiscale` varchar(16) DEFAULT NULL,
  `dg_dtra_dav_a_Denominazione` varchar(80) DEFAULT NULL,
  `dg_dtra_dav_a_Nome` varchar(60) DEFAULT NULL,
  `dg_dtra_dav_a_Cognome` varchar(60) DEFAULT NULL,
  `dg_dtra_dav_a_Titolo` varchar(10) DEFAULT NULL,
  `dg_dtra_dav_a_CodEORI` varchar(17) DEFAULT NULL,
  `dg_dtra_dav_NumeroLicenzaGuida` varchar(20) DEFAULT NULL,
  `dg_dtra_MezzoTrasporto` varchar(80) DEFAULT NULL,
  `dg_dtra_CausaleTrasporto` varchar(100) DEFAULT NULL,
  `dg_dtra_NumeroColli` int(11) DEFAULT NULL,
  `dg_dtra_Descrizione` varchar(100) DEFAULT NULL,
  `dg_dtra_UnitaMisuraPeso` varchar(10) DEFAULT NULL,
  `dg_dtra_PesoLordo` decimal(9,2) DEFAULT NULL,
  `dg_dtra_PesoNetto` decimal(9,2) DEFAULT NULL,
  `dg_dtra_DataOraRitiro` datetime DEFAULT NULL,
  `dg_dtra_DataInizioTrasporto` datetime DEFAULT NULL,
  `dg_dtra_TipoResa` varchar(3) DEFAULT NULL,
  `dg_dtra_inre_Indirizzo` varchar(60) DEFAULT NULL,
  `dg_dtra_inre_NumeroCivico` varchar(8) DEFAULT NULL,
  `dg_dtra_inre_CAP` int(11) DEFAULT NULL,
  `dg_dtra_inre_Comune` varchar(60) DEFAULT NULL,
  `dg_dtra_inre_Provincia` varchar(2) DEFAULT NULL,
  `dg_dtra_inre_Nazione` varchar(2) DEFAULT NULL,
  `dg_dtra_DataOraConsegna` datetime DEFAULT NULL,
  `dg_fpr_NumeroFatturaPrincipale` varchar(20) DEFAULT NULL,
  `dg_fpr_DataFatturaPrincipale` datetime DEFAULT NULL,
  `dv_Data` datetime DEFAULT NULL,
  `fed_dv_TotalePercorso` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_einv_inv_header_idx` (`id_einv_inv_header`),
  CONSTRAINT `fk_einv_inv_body_einv_inv_header` FOREIGN KEY (`id_einv_inv_header`) REFERENCES `einv_inv_header` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body`
--

LOCK TABLES `einv_inv_body` WRITE;
/*!40000 ALTER TABLE `einv_inv_body` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_allegati`
--

DROP TABLE IF EXISTS `einv_inv_body_allegati`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_allegati` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `NomeAttachment` varchar(60) NOT NULL,
  `AlgoritomoCompressione` varchar(10) DEFAULT NULL,
  `FormatoAttachment` varchar(10) DEFAULT NULL,
  `DescrizioneAttachment` varchar(100) DEFAULT NULL,
  `Attachment` text NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_allegati_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_allegati_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_allegati`
--

LOCK TABLES `einv_inv_body_allegati` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_allegati` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_allegati` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_causali`
--

DROP TABLE IF EXISTS `einv_inv_body_causali`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_causali` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(10) NOT NULL,
  `Causale` varchar(200) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_causalii_einv_inv_body_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_causalii_einv_inv_body` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_causali`
--

LOCK TABLES `einv_inv_body_causali` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_causali` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_causali` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_beni_servizi`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_beni_servizi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_beni_servizi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `dl_NumeroLinea` int(11) NOT NULL,
  `dl_TipoCessionePrestazione` varchar(2) DEFAULT NULL,
  `dl_Descrizione` varchar(1000) NOT NULL,
  `dl_Quantita` decimal(20,8) DEFAULT NULL,
  `dl_UnitaMisura` varchar(10) DEFAULT NULL,
  `dl_DataInizioPeriodo` datetime DEFAULT NULL,
  `dl_DataFinePeriodo` datetime DEFAULT NULL,
  `dl_PrezzoUnitario` decimal(23,8) NOT NULL,
  `dl_PrezzoTotale` decimal(23,8) NOT NULL,
  `dl_AliquotaIVA` decimal(8,2) NOT NULL,
  `dl_Ritenuta` varchar(2) DEFAULT NULL,
  `dl_Natura` varchar(2) DEFAULT NULL,
  `dl_RiferimentoAmministrazione` varchar(20) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_dati_beni_servizi_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_dati_beni_servizi_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_beni_servizi`
--

LOCK TABLES `einv_inv_body_dati_beni_servizi` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_beni_servizi` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_beni_servizi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_cassa_previdenziale`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_cassa_previdenziale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_cassa_previdenziale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `TipoCassa` varchar(4) NOT NULL,
  `AICassa` decimal(6,2) NOT NULL,
  `ImportoContributoCassa` decimal(17,2) NOT NULL,
  `ImponibileCassa` decimal(17,2) DEFAULT NULL,
  `AliquotaIVA` decimal(6,2) NOT NULL,
  `Ritenuta` varchar(2) DEFAULT NULL,
  `Natura` varchar(2) DEFAULT NULL,
  `RiferimentoAmministrazione` varchar(20) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_cassa_previdenziale_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_cp_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_cassa_previdenziale`
--

LOCK TABLES `einv_inv_body_dati_cassa_previdenziale` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_cassa_previdenziale` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_cassa_previdenziale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_contratto`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_contratto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_contratto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `IdDocumento` varchar(20) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `NumItem` varchar(20) DEFAULT NULL,
  `CodiceCommessaConvenzione` varchar(100) DEFAULT NULL,
  `CodiceCUP` varchar(15) DEFAULT NULL,
  `CodiceCIG` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_contratto_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_contratto_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_contratto`
--

LOCK TABLES `einv_inv_body_dati_contratto` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_contratto` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_contratto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_convenzione`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_convenzione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_convenzione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `IdDocumento` varchar(20) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `NumItem` varchar(20) DEFAULT NULL,
  `CodiceCommessaConvenzione` varchar(100) DEFAULT NULL,
  `CodiceCUP` varchar(15) DEFAULT NULL,
  `CodiceCIG` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_contratto_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_contratto_einv_inv_body10` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_convenzione`
--

LOCK TABLES `einv_inv_body_dati_convenzione` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_convenzione` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_convenzione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_ddt`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_ddt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_ddt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `NumeroDDT` varchar(20) NOT NULL,
  `DataDDT` datetime NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_ddt_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_ddt_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_ddt`
--

LOCK TABLES `einv_inv_body_dati_ddt` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_ddt` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_ddt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_ddt_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_ddt_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_ddt_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dati_dtt_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_ddt_riferimento_numero_linea_dati_ddt_idx` (`einv_inv_body_dati_dtt_id`),
  CONSTRAINT `fk_einv_inv_body_dati_ddt_riferimento_numero_linea_dati_ddt` FOREIGN KEY (`einv_inv_body_dati_dtt_id`) REFERENCES `einv_inv_body_dati_ddt` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_ddt_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_dati_ddt_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_ddt_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_ddt_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_fatture_collegate`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_fatture_collegate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_fatture_collegate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `IdDocumento` varchar(20) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `NumItem` varchar(20) DEFAULT NULL,
  `CodiceCommessaConvenzione` varchar(100) DEFAULT NULL,
  `CodiceCUP` varchar(15) DEFAULT NULL,
  `CodiceCIG` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_contratto_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_contratto_einv_inv_body1000` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_fatture_collegate`
--

LOCK TABLES `einv_inv_body_dati_fatture_collegate` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_fatture_collegate` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_fatture_collegate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_ordine_acquisto`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_ordine_acquisto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_ordine_acquisto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `IdDocumento` varchar(20) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `NumItem` varchar(20) DEFAULT NULL,
  `CodiceCommessaConvenzione` varchar(100) DEFAULT NULL,
  `CodiceCUP` varchar(15) DEFAULT NULL,
  `CodiceCIG` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_ordine_acquisto_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_ordine_acquisto_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_ordine_acquisto`
--

LOCK TABLES `einv_inv_body_dati_ordine_acquisto` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_ordine_acquisto` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_ordine_acquisto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_pagamento`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `CondizioniPagamento` varchar(4) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table5_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_pagamento_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_pagamento`
--

LOCK TABLES `einv_inv_body_dati_pagamento` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_pagamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_ricezione`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_ricezione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_ricezione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `IdDocumento` varchar(20) NOT NULL,
  `Data` datetime DEFAULT NULL,
  `NumItem` varchar(20) DEFAULT NULL,
  `CodiceCommessaConvenzione` varchar(100) DEFAULT NULL,
  `CodiceCUP` varchar(15) DEFAULT NULL,
  `CodiceCIG` varchar(15) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_contratto_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_contratto_einv_inv_body100` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_ricezione`
--

LOCK TABLES `einv_inv_body_dati_ricezione` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_ricezione` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_ricezione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_riepilogo`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_riepilogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_riepilogo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `AliquotaIVA` decimal(8,2) NOT NULL,
  `Natura` varchar(2) DEFAULT NULL,
  `SpeseAccessorie` decimal(17,2) DEFAULT NULL,
  `Arrotondamento` decimal(23,2) DEFAULT NULL,
  `ImponibileImporto` decimal(17,2) NOT NULL,
  `Imposta` decimal(17,2) NOT NULL,
  `EsigibilitaIVA` varchar(1) DEFAULT NULL,
  `RiferimentoNormativo` varchar(100) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_riepilogo_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_riepilogo_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_riepilogo`
--

LOCK TABLES `einv_inv_body_dati_riepilogo` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_riepilogo` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_riepilogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dati_sal`
--

DROP TABLE IF EXISTS `einv_inv_body_dati_sal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dati_sal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `dg_dsal_RiferimentoFase` int(11) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_sal_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_dati_sal_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dati_sal`
--

LOCK TABLES `einv_inv_body_dati_sal` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dati_sal` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dati_sal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dbs_altri_dati_gestionali`
--

DROP TABLE IF EXISTS `einv_inv_body_dbs_altri_dati_gestionali`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dbs_altri_dati_gestionali` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dbs_id` int(11) NOT NULL,
  `TipoDato` varchar(10) NOT NULL,
  `RiferimentoTesto` varchar(60) DEFAULT NULL,
  `RiferimentoNumero` decimal(23,2) DEFAULT NULL,
  `RiferimentoData` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table3_einv_inv_body_dati_beni_servizi1_idx` (`einv_inv_body_dbs_id`),
  CONSTRAINT `fk_einv_inv_body_dbs_adg_einv_inv_body_dati_beni_servizi1` FOREIGN KEY (`einv_inv_body_dbs_id`) REFERENCES `einv_inv_body_dati_beni_servizi` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dbs_altri_dati_gestionali`
--

LOCK TABLES `einv_inv_body_dbs_altri_dati_gestionali` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dbs_altri_dati_gestionali` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dbs_altri_dati_gestionali` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dbs_codice_articolo`
--

DROP TABLE IF EXISTS `einv_inv_body_dbs_codice_articolo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dbs_codice_articolo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dbs_id` int(11) NOT NULL,
  `CodiceTipo` varchar(35) NOT NULL,
  `CodiceValore` varchar(35) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table2_einv_inv_body_dati_beni_servizi1_idx` (`einv_inv_body_dbs_id`),
  CONSTRAINT `fk_einv_inv_body_dbs_ca_einv_inv_body_dati_beni_servizi1` FOREIGN KEY (`einv_inv_body_dbs_id`) REFERENCES `einv_inv_body_dati_beni_servizi` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dbs_codice_articolo`
--

LOCK TABLES `einv_inv_body_dbs_codice_articolo` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dbs_codice_articolo` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dbs_codice_articolo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dbs_sconto_maggiorazione`
--

DROP TABLE IF EXISTS `einv_inv_body_dbs_sconto_maggiorazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dbs_sconto_maggiorazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dbs_id` int(11) NOT NULL,
  `Tipo` varchar(2) NOT NULL,
  `Percentuale` decimal(6,2) DEFAULT NULL,
  `Importo` decimal(17,2) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dbs_codice_articolo_einv_inv_body_dati_ben_idx` (`einv_inv_body_dbs_id`),
  CONSTRAINT `fk_einv_inv_body_dbs_sm_einv_inv_body_dati_beni_1` FOREIGN KEY (`einv_inv_body_dbs_id`) REFERENCES `einv_inv_body_dati_beni_servizi` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dbs_sconto_maggiorazione`
--

LOCK TABLES `einv_inv_body_dbs_sconto_maggiorazione` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dbs_sconto_maggiorazione` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dbs_sconto_maggiorazione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dcontr_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_dcontr_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dcontr_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dcontr_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_contratto_rifermento_numero_linea_idx` (`einv_inv_body_dcontr_id`),
  CONSTRAINT `fk_einv_inv_body_dcontr_rifermento_numero_linea_dcontr` FOREIGN KEY (`einv_inv_body_dcontr_id`) REFERENCES `einv_inv_body_dati_contratto` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dcontr_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_dcontr_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dcontr_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dcontr_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dconv_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_dconv_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dconv_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dconv_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_convezione_riferimento_numero_linea_d_idx` (`einv_inv_body_dconv_id`),
  CONSTRAINT `fk_einv_inv_body_dconv_riferimento_numero_linea_dconv` FOREIGN KEY (`einv_inv_body_dconv_id`) REFERENCES `einv_inv_body_dati_convenzione` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dconv_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_dconv_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dconv_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dconv_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dfc_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_dfc_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dfc_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dfc_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_fatture_collegate_riferimento_numero__idx` (`einv_inv_body_dfc_id`),
  CONSTRAINT `fk_einv_inv_body_dfc_riferimento_numero_linea_dfc` FOREIGN KEY (`einv_inv_body_dfc_id`) REFERENCES `einv_inv_body_dati_fatture_collegate` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dfc_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_dfc_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dfc_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dfc_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_doa_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_doa_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_doa_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_doa_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `einv_inv_body_doa_riferimento_numero_linea_einv_inv_dati_or_idx` (`einv_inv_body_doa_id`),
  CONSTRAINT `einv_inv_body_doa_riferimento_numero_linea_einv_inv_doa` FOREIGN KEY (`einv_inv_body_doa_id`) REFERENCES `einv_inv_body_dati_ordine_acquisto` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_doa_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_doa_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_doa_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_doa_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_dr_riferimento_numero_linea`
--

DROP TABLE IF EXISTS `einv_inv_body_dr_riferimento_numero_linea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_dr_riferimento_numero_linea` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dr_id` int(10) NOT NULL,
  `RiferimentoNumeroLinea` smallint(4) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_dati_ricezione_riferimento_numero_linea_da_idx` (`einv_inv_body_dr_id`),
  CONSTRAINT `fk_einv_inv_body_dr_riferimento_numero_linea_dr` FOREIGN KEY (`einv_inv_body_dr_id`) REFERENCES `einv_inv_body_dati_ricezione` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_dr_riferimento_numero_linea`
--

LOCK TABLES `einv_inv_body_dr_riferimento_numero_linea` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_dr_riferimento_numero_linea` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_dr_riferimento_numero_linea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_body_sconto_maggiorazione`
--

DROP TABLE IF EXISTS `einv_inv_body_sconto_maggiorazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_body_sconto_maggiorazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_id` int(11) NOT NULL,
  `Tipo` varchar(2) NOT NULL,
  `Percentuale` decimal(6,2) DEFAULT NULL,
  `Importo` decimal(17,2) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_body_sconto_maggiorazione_einv_inv_body1_idx` (`einv_inv_body_id`),
  CONSTRAINT `fk_einv_inv_body_sconto_maggiorazione_einv_inv_body1` FOREIGN KEY (`einv_inv_body_id`) REFERENCES `einv_inv_body` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_body_sconto_maggiorazione`
--

LOCK TABLES `einv_inv_body_sconto_maggiorazione` WRITE;
/*!40000 ALTER TABLE `einv_inv_body_sconto_maggiorazione` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_body_sconto_maggiorazione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_cod_modalita_pagamento`
--

DROP TABLE IF EXISTS `einv_inv_cod_modalita_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_cod_modalita_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` varchar(4) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_cod_modalita_pagamento`
--

LOCK TABLES `einv_inv_cod_modalita_pagamento` WRITE;
/*!40000 ALTER TABLE `einv_inv_cod_modalita_pagamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_cod_modalita_pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_cod_natura`
--

DROP TABLE IF EXISTS `einv_inv_cod_natura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_cod_natura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` varchar(4) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_cod_natura`
--

LOCK TABLES `einv_inv_cod_natura` WRITE;
/*!40000 ALTER TABLE `einv_inv_cod_natura` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_cod_natura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_cod_regime_fiscale`
--

DROP TABLE IF EXISTS `einv_inv_cod_regime_fiscale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_cod_regime_fiscale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` varchar(4) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_cod_regime_fiscale`
--

LOCK TABLES `einv_inv_cod_regime_fiscale` WRITE;
/*!40000 ALTER TABLE `einv_inv_cod_regime_fiscale` DISABLE KEYS */;
INSERT INTO `einv_inv_cod_regime_fiscale` VALUES (1,'RF01','Ordinario',0,'2020-08-12 15:00:00'),(2,'RF02','Contribuenti minimi (art.1, c.96-117, L. 244/07)',0,'2020-08-12 15:00:00'),(3,'RF04','Agricoltura e attività connesse e pesca (artt.34 e 34-bis, DPR 633/72)',0,'2020-08-12 15:00:00'),(4,'RF05','Vendita sali e tabacchi (art.74, c.1, DPR. 633/72)',0,'2020-08-12 15:00:00'),(5,'RF06','Commercio fiammiferi (art.74, c.1, DPR  633/72)',0,'2020-08-12 15:00:00'),(6,'RF07','Editoria (art.74, c.1, DPR  633/72)',0,'2020-08-12 15:00:00'),(7,'RF08','Gestione servizi telefonia pubblica (art.74, c.1, DPR 633/72)',0,'2020-08-12 15:00:00'),(8,'RF09','Rivendita documenti di trasporto pubblico e di sosta (art.74, c.1, DPR  633/72)',0,'2020-08-12 15:00:00'),(9,'RF10','Intrattenimenti, giochi e altre attività di cui alla tariffa allegata al DPR 640/72 (art.74, c.6, DPR 633/72)',0,'2020-08-12 15:00:00'),(10,'RF11','Agenzie viaggi e turismo (art.74-ter, DPR 633/72)',0,'2020-08-12 15:00:00'),(11,'RF12','Agriturismo (art.5, c.2, L. 413/91)',0,'2020-08-12 15:00:00'),(12,'RF13','Vendite a domicilio (art.25-bis, c.6, DPR  600/73)',0,'2020-08-12 15:00:00'),(13,'RF14','Rivendita beni usati, oggetti d’arte, d’antiquariato o da collezione (art.36, DL 41/95)',0,'2020-08-12 15:00:00'),(14,'RF15','Agenzie di vendite all’asta di oggetti d’arte, antiquariato o da collezione (art.40-bis, DL 41/95)',0,'2020-08-12 15:00:00'),(15,'RF16','IVA per cassa P.A. (art.6, c.5, DPR 633/72)',0,'2020-08-12 15:00:00'),(16,'RF17','IVA per cassa (art. 32-bis, DL 83/2012)',0,'2020-08-12 15:00:00'),(17,'RF18','Altro',0,'2020-08-12 15:00:00'),(18,'RF19','Regime forfettario (art.1, c.54-89, L. 190/2014)',0,'2020-08-12 15:00:00');
/*!40000 ALTER TABLE `einv_inv_cod_regime_fiscale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_cod_tipo_cassa`
--

DROP TABLE IF EXISTS `einv_inv_cod_tipo_cassa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_cod_tipo_cassa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` varchar(4) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_cod_tipo_cassa`
--

LOCK TABLES `einv_inv_cod_tipo_cassa` WRITE;
/*!40000 ALTER TABLE `einv_inv_cod_tipo_cassa` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_cod_tipo_cassa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_cod_tipo_documento`
--

DROP TABLE IF EXISTS `einv_inv_cod_tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_cod_tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` varchar(4) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_cod_tipo_documento`
--

LOCK TABLES `einv_inv_cod_tipo_documento` WRITE;
/*!40000 ALTER TABLE `einv_inv_cod_tipo_documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_cod_tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_dp_dettaglio_pagamento`
--

DROP TABLE IF EXISTS `einv_inv_dp_dettaglio_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_dp_dettaglio_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `einv_inv_body_dp_id` int(11) NOT NULL,
  `Beneficiario` varchar(200) DEFAULT NULL,
  `ModalitaPagamento` varchar(4) NOT NULL,
  `DataRiferimentoTerminiPagamento` datetime DEFAULT NULL,
  `GiorniTerminiPagamento` int(11) DEFAULT NULL,
  `DataScadenzaPagamento` datetime DEFAULT NULL,
  `ImportoPagamento` decimal(17,2) NOT NULL,
  `CodUfficioPostale` varchar(20) DEFAULT NULL,
  `CognomeQuietanzante` varchar(60) DEFAULT NULL,
  `NomeQuietanzante` varchar(60) DEFAULT NULL,
  `CFQuietanzante` varchar(16) DEFAULT NULL,
  `TitoloQuietanzante` varchar(10) DEFAULT NULL,
  `IstitutoFinanziario` varchar(80) DEFAULT NULL,
  `IBAN` varchar(34) DEFAULT NULL,
  `ABI` varchar(5) DEFAULT NULL,
  `CAB` varchar(5) DEFAULT NULL,
  `BIC` varchar(11) DEFAULT NULL,
  `ScontoPagamentoAnticipato` decimal(17,2) DEFAULT NULL,
  `DataLimitePagamentoAnticipato` datetime DEFAULT NULL,
  `PenalitaPagamentiRitardati` decimal(17,2) DEFAULT NULL,
  `DataDecorrenzaPenale` datetime DEFAULT NULL,
  `CodicePagamento` varchar(60) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_einv_inv_dp_dettaglio_pagamento_einv_inv_body_dati_pagam_idx` (`einv_inv_body_dp_id`),
  CONSTRAINT `fk_einv_inv_dp_dettaglio_pagamento_einv_inv_body_dati_pagamen1` FOREIGN KEY (`einv_inv_body_dp_id`) REFERENCES `einv_inv_body_dati_pagamento` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_dp_dettaglio_pagamento`
--

LOCK TABLES `einv_inv_dp_dettaglio_pagamento` WRITE;
/*!40000 ALTER TABLE `einv_inv_dp_dettaglio_pagamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_dp_dettaglio_pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_inv_header`
--

DROP TABLE IF EXISTS `einv_inv_header`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_inv_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_it_IdPaese` varchar(2) NOT NULL,
  `dt_it_IdCodice` varchar(28) NOT NULL,
  `dt_ProgressivoInvio` int(11) NOT NULL,
  `dt_FormatoTrasmissione` varchar(5) NOT NULL,
  `dt_CodiceDestinatario` varchar(7) NOT NULL,
  `dt_ct_Telefono` varchar(24) DEFAULT NULL,
  `dt_ct_Email` varchar(128) DEFAULT NULL,
  `dt_PECDestinatario` varchar(128) DEFAULT NULL,
  `cp_da_ifi_IdPaese` varchar(2) NOT NULL,
  `cp_da_ifi_IdCodice` varchar(28) NOT NULL,
  `cp_da_CodiceFiscale` varchar(16) DEFAULT NULL,
  `cp_da_a_Denominazione` varchar(128) DEFAULT NULL,
  `cp_da_a_Nome` varchar(128) DEFAULT NULL,
  `cp_da_a_Cognome` varchar(128) DEFAULT NULL,
  `cp_da_a_Titolo` varchar(24) DEFAULT NULL,
  `cp_da_a_CodEORI` varchar(17) DEFAULT NULL,
  `cp_da_AlboProfessionale` varchar(60) DEFAULT NULL,
  `cp_da_ProvinciaAlbo` varchar(2) DEFAULT NULL,
  `cp_da_NumeroIscrizioneAlbo` varchar(60) DEFAULT NULL,
  `cp_da_DataIscrizioneAlbo` datetime DEFAULT NULL,
  `cp_da_RegimeFiscale` varchar(4) NOT NULL,
  `cp_s_Indirizzo` varchar(60) NOT NULL,
  `cp_s_NumeroCivico` varchar(8) DEFAULT NULL,
  `cp_s_CAP` varchar(5) NOT NULL,
  `cp_s_Comune` varchar(60) NOT NULL,
  `cp_s_Provincia` varchar(2) DEFAULT NULL,
  `cp_s_Nazione` varchar(2) NOT NULL,
  `cp_so_Indirizzo` varchar(60) DEFAULT NULL,
  `cp_so_NumeroCivico` varchar(8) DEFAULT NULL,
  `cp_so_CAP` varchar(5) DEFAULT NULL,
  `cp_so_Comune` varchar(60) DEFAULT NULL,
  `cp_so_Provincia` varchar(2) DEFAULT NULL,
  `cp_so_Nazione` varchar(2) DEFAULT NULL,
  `cp_ir_Uffcio` varchar(2) DEFAULT NULL,
  `cp_ir_NumeroRea` varchar(20) DEFAULT NULL,
  `cp_ir_CapitaleSociale` decimal(17,2) DEFAULT NULL,
  `cp_ir_SocioUnico` varchar(2) DEFAULT NULL,
  `cp_ir_StatoLiquidazione` varchar(2) DEFAULT NULL,
  `cp_c_Telefono` varchar(12) DEFAULT NULL,
  `cp_c_Fax` varchar(12) DEFAULT NULL,
  `cp_c_Email` varchar(256) DEFAULT NULL,
  `cp_c_RiferimentoAmministrazione` varchar(20) DEFAULT NULL,
  `rf_da_ifi_IdPaese` varchar(2) DEFAULT NULL,
  `rf_da_ifi_IdCodice` varchar(28) DEFAULT NULL,
  `rf_da_CodiceFiscale` varchar(16) DEFAULT NULL,
  `rf_da_a_Denominazione` varchar(80) DEFAULT NULL,
  `rf_da_a_Nome` varchar(60) DEFAULT NULL,
  `rf_da_a_Cognome` varchar(60) DEFAULT NULL,
  `rf_da_a_Titolo` varchar(10) DEFAULT NULL,
  `rf_da_a_CodEORI` varchar(17) DEFAULT NULL,
  `cc_da_ifi_IdPaese` varchar(2) DEFAULT NULL,
  `cc_da_ifi_IdCodice` varchar(28) DEFAULT NULL,
  `cc_da_CodiceFiscale` varchar(16) DEFAULT NULL,
  `cc_da_a_Denominazione` varchar(128) DEFAULT NULL,
  `cc_da_a_Nome` varchar(128) DEFAULT NULL,
  `cc_da_a_Cognome` varchar(128) DEFAULT NULL,
  `cc_da_a_Titolo` varchar(24) DEFAULT NULL,
  `cc_da_a_CodEORI` varchar(17) DEFAULT NULL,
  `cc_s_Indirizzo` varchar(60) NOT NULL,
  `cc_s_NumeroCivico` varchar(8) DEFAULT NULL,
  `cc_s_CAP` varchar(5) NOT NULL,
  `cc_s_Comune` varchar(60) NOT NULL,
  `cc_s_Provincia` varchar(2) DEFAULT NULL,
  `cc_s_Nazione` varchar(2) NOT NULL,
  `cc_so_Indirizzo` varchar(60) DEFAULT NULL,
  `cc_so_NumeroCivico` varchar(8) DEFAULT NULL,
  `cc_so_CAP` varchar(5) DEFAULT NULL,
  `cc_so_Comune` varchar(60) DEFAULT NULL,
  `cc_so_Provincia` varchar(2) DEFAULT NULL,
  `cc_so_Nazione` varchar(2) DEFAULT NULL,
  `cc_rf_ifi_IdPaese` varchar(2) DEFAULT NULL,
  `cc_rf_ifi_IdCodice` varchar(28) DEFAULT NULL,
  `cc_rf_Denominazione` varchar(80) DEFAULT NULL,
  `cc_rf_Nome` varchar(60) DEFAULT NULL,
  `cc_rf_Cognome` varchar(60) DEFAULT NULL,
  `tiose_da_ifi_IdPaese` varchar(2) DEFAULT NULL,
  `tiose_da_ifi_IdCodice` varchar(28) DEFAULT NULL,
  `tiose_da_CodiceFiscale` varchar(16) DEFAULT NULL,
  `tiose_da_a_Denominazione` varchar(80) DEFAULT NULL,
  `tiose_da_a_Nome` varchar(60) DEFAULT NULL,
  `tiose_da_a_Cognome` varchar(60) DEFAULT NULL,
  `tiose_da_a_Titolo` varchar(10) DEFAULT NULL,
  `tiose_da_a_CodEORI` varchar(17) DEFAULT NULL,
  `SoggettoEmittente` varchar(2) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_inv_header`
--

LOCK TABLES `einv_inv_header` WRITE;
/*!40000 ALTER TABLE `einv_inv_header` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_inv_header` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `einv_nom`
--

DROP TABLE IF EXISTS `einv_nom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `einv_nom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ragione_sociale` varchar(80) DEFAULT NULL,
  `partita_iva` varchar(30) DEFAULT NULL,
  `codice_fiscale` varchar(30) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `email_pec` varchar(256) DEFAULT NULL,
  `codice_destinatario` varchar(7) DEFAULT NULL,
  `sito_web` varchar(256) DEFAULT NULL,
  `nome` varchar(60) DEFAULT NULL,
  `cognome` varchar(60) DEFAULT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `fax` varchar(12) DEFAULT NULL,
  `regime_fiscale` varchar(4) NOT NULL DEFAULT 'RF01',
  `formato_trasmissione` varchar(5) DEFAULT NULL,
  `tipologia` varchar(2) NOT NULL,
  `esigibilita` varchar(2) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `einv_nom`
--

LOCK TABLES `einv_nom` WRITE;
/*!40000 ALTER TABLE `einv_nom` DISABLE KEYS */;
/*!40000 ALTER TABLE `einv_nom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `item_archive_v`
--

DROP TABLE IF EXISTS `item_archive_v`;
/*!50001 DROP VIEW IF EXISTS `item_archive_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `item_archive_v` AS SELECT 
 1 AS `id`,
 1 AS `sku`,
 1 AS `ean`,
 1 AS `description`,
 1 AS `pieces_per_pack`,
 1 AS `pack_per_pallet`,
 1 AS `batch_management`,
 1 AS `note`,
 1 AS `tax_rate_id`,
 1 AS `measure_unit_id`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `item_erased`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `item_attribute_light_v`
--

DROP TABLE IF EXISTS `item_attribute_light_v`;
/*!50001 DROP VIEW IF EXISTS `item_attribute_light_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `item_attribute_light_v` AS SELECT 
 1 AS `id`,
 1 AS `oid`,
 1 AS `item_id`,
 1 AS `attribute_id`,
 1 AS `value`,
 1 AS `modified_by`,
 1 AS `created_by`,
 1 AS `assigned`,
 1 AS `create_date`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `erased`,
 1 AS `last_update`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `item_light_v`
--

DROP TABLE IF EXISTS `item_light_v`;
/*!50001 DROP VIEW IF EXISTS `item_light_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `item_light_v` AS SELECT 
 1 AS `id`,
 1 AS `sku`,
 1 AS `ean`,
 1 AS `description`,
 1 AS `pieces_per_pack`,
 1 AS `pack_per_pallet`,
 1 AS `batch_management`,
 1 AS `note`,
 1 AS `tax_rate_id`,
 1 AS `measure_unit_id`,
 1 AS `clifor`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `item_erased`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `item_light_v_OLD`
--

DROP TABLE IF EXISTS `item_light_v_OLD`;
/*!50001 DROP VIEW IF EXISTS `item_light_v_OLD`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `item_light_v_OLD` AS SELECT 
 1 AS `id`,
 1 AS `sku`,
 1 AS `ean`,
 1 AS `description`,
 1 AS `pieces_per_pack`,
 1 AS `note`,
 1 AS `tax_rate_id`,
 1 AS `measure_unit_id`,
 1 AS `pack_per_pallet`,
 1 AS `batch_management`,
 1 AS `clifor`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `item_erased`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `name_address`
--

DROP TABLE IF EXISTS `name_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) NOT NULL,
  `country` varchar(2) NOT NULL,
  `province` varchar(2) DEFAULT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(60) NOT NULL,
  `street` varchar(60) NOT NULL,
  `line2` varchar(60) DEFAULT NULL,
  `line3` varchar(60) DEFAULT NULL,
  `number` varchar(8) DEFAULT NULL,
  `lat` varchar(45) DEFAULT NULL,
  `long` varchar(45) DEFAULT NULL,
  `other_address_details` varchar(200) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person20_idx` (`modified_by`),
  KEY `fk_person_person00` (`created_by`),
  KEY `fk_person_person100` (`assigned`),
  KEY `fk_name_address_name_company1_idx` (`name_company_id`),
  CONSTRAINT `fk_name_address_name_company1` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person001` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person1001` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person2003` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4642 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_address`
--

LOCK TABLES `name_address` WRITE;
/*!40000 ALTER TABLE `name_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_address_has_address_type`
--

DROP TABLE IF EXISTS `name_address_has_address_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_address_has_address_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_address_id` int(11) NOT NULL,
  `name_address_type_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_address_type_name_address1_idx` (`name_address_id`),
  KEY `fk_name_address_has_address_type_name_address_type1_idx` (`name_address_type_id`),
  CONSTRAINT `fk_name_address_has_address_type_name_address_type1` FOREIGN KEY (`name_address_type_id`) REFERENCES `name_address_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_name_address_type_name_address1` FOREIGN KEY (`name_address_id`) REFERENCES `name_address` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_address_has_address_type`
--

LOCK TABLES `name_address_has_address_type` WRITE;
/*!40000 ALTER TABLE `name_address_has_address_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_address_has_address_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_address_type`
--

DROP TABLE IF EXISTS `name_address_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_address_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_address_type`
--

LOCK TABLES `name_address_type` WRITE;
/*!40000 ALTER TABLE `name_address_type` DISABLE KEYS */;
INSERT INTO `name_address_type` VALUES (1,'886aa78fce97df6fdf89e00c8a149a51','B',NULL,NULL,NULL,0,'2020-07-24 00:00:00'),(2,'6fbfd7d6d589982e162bf4a218aef94a','S',NULL,NULL,NULL,0,'2020-07-24 00:00:00'),(3,'b285fdfb3de73d16dee73731945dcf69','T',NULL,NULL,NULL,0,'2020-07-24 00:00:00'),(4,'a50d6f14112045ec2f0204d485b5d0d3','W',NULL,NULL,NULL,0,'2020-07-24 00:00:00');
/*!40000 ALTER TABLE `name_address_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_bank`
--

DROP TABLE IF EXISTS `name_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `description` varchar(80) DEFAULT NULL,
  `abi` varchar(5) DEFAULT NULL,
  `cab` varchar(5) DEFAULT NULL,
  `cin` varchar(2) DEFAULT NULL,
  `bic` varchar(11) DEFAULT NULL,
  `bank_account` varchar(20) DEFAULT NULL,
  `iban` varchar(34) DEFAULT NULL,
  `credit` decimal(18,4) DEFAULT NULL,
  `effects_bank_account` varchar(20) DEFAULT NULL,
  `effects_credit` decimal(18,4) DEFAULT NULL,
  `effects_cin` varchar(2) DEFAULT NULL,
  `effects_code` varchar(100) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bank_company_idx` (`name_company_id`),
  CONSTRAINT `fk_bank_company` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_bank`
--

LOCK TABLES `name_bank` WRITE;
/*!40000 ALTER TABLE `name_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_company`
--

DROP TABLE IF EXISTS `name_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `description` text,
  `industry` varchar(100) DEFAULT NULL,
  `annual_revenue` decimal(18,2) DEFAULT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `ownership` varchar(100) DEFAULT NULL,
  `employees` int(11) DEFAULT NULL,
  `vat_number` varchar(28) DEFAULT NULL,
  `fiscal_code` varchar(16) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_person_person20_idx` (`modified_by`),
  KEY `fk_person_person0` (`created_by`),
  KEY `fk_person_person10` (`assigned`),
  CONSTRAINT `fk_person_person0` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person10` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_person_person20` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7916 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_company`
--

LOCK TABLES `name_company` WRITE;
/*!40000 ALTER TABLE `name_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_company_custom_parameter`
--

DROP TABLE IF EXISTS `name_company_custom_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_company_custom_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `company_id` int(11) NOT NULL,
  `company_role` varchar(2) NOT NULL,
  `parameter_name` varchar(128) NOT NULL,
  `parameter_value` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_name_company_company_id_idx` (`company_id`),
  CONSTRAINT `fk_name_company_company_id` FOREIGN KEY (`company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=403 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_company_custom_parameter`
--

LOCK TABLES `name_company_custom_parameter` WRITE;
/*!40000 ALTER TABLE `name_company_custom_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_company_custom_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `name_company_export_v`
--

DROP TABLE IF EXISTS `name_company_export_v`;
/*!50001 DROP VIEW IF EXISTS `name_company_export_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `name_company_export_v` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `last_name`,
 1 AS `business_name`,
 1 AS `description`,
 1 AS `vat_number`,
 1 AS `fiscal_code`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `name_contact`
--

DROP TABLE IF EXISTS `name_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) NOT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `phonetic_first` varchar(45) DEFAULT NULL,
  `phonetic_middle` varchar(45) DEFAULT NULL,
  `phonetic_last` varchar(45) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `street_address` varchar(60) DEFAULT NULL,
  `street_address_line2` varchar(60) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(5) DEFAULT NULL,
  `po_box` varchar(45) DEFAULT NULL,
  `label` varchar(45) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `notes` text,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_supplier_name_company1_idx` (`name_company_id`),
  CONSTRAINT `fk_name_supplier_name_company1000` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_contact`
--

LOCK TABLES `name_contact` WRITE;
/*!40000 ALTER TABLE `name_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_customer`
--

DROP TABLE IF EXISTS `name_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) NOT NULL,
  `customer_code` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_supplier_name_company1_idx` (`name_company_id`),
  CONSTRAINT `fk_name_supplier_name_company101` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_customer`
--

LOCK TABLES `name_customer` WRITE;
/*!40000 ALTER TABLE `name_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_email`
--

DROP TABLE IF EXISTS `name_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_contact_id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `function` varchar(45) DEFAULT NULL,
  `label` varchar(45) DEFAULT NULL,
  `do_not_use` tinyint(1) DEFAULT NULL,
  `not_valid` tinyint(1) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_contact_copy1_name_contact1_idx` (`name_contact_id`),
  CONSTRAINT `fk_name_contact_copy1_name_contact1` FOREIGN KEY (`name_contact_id`) REFERENCES `name_contact` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_email`
--

LOCK TABLES `name_email` WRITE;
/*!40000 ALTER TABLE `name_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_group_has_company`
--

DROP TABLE IF EXISTS `name_group_has_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_group_has_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_company_has_person_name_company1_idx` (`company_id`),
  KEY `fk_name_group_has_company_core_group1_idx` (`group_id`),
  CONSTRAINT `fk_name_company_has_person_name_company1` FOREIGN KEY (`company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_name_group_has_company_core_group1` FOREIGN KEY (`group_id`) REFERENCES `core_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=732 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_group_has_company`
--

LOCK TABLES `name_group_has_company` WRITE;
/*!40000 ALTER TABLE `name_group_has_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_group_has_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_owned_company`
--

DROP TABLE IF EXISTS `name_owned_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_owned_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_supplier_name_company1_idx` (`name_company_id`),
  CONSTRAINT `fk_name_supplier_name_company10` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_owned_company`
--

LOCK TABLES `name_owned_company` WRITE;
/*!40000 ALTER TABLE `name_owned_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_owned_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_phone`
--

DROP TABLE IF EXISTS `name_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_contact_id` int(11) NOT NULL,
  `international_prefix` varchar(5) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `function` varchar(45) DEFAULT NULL,
  `label` varchar(45) DEFAULT NULL,
  `do_not_use` tinyint(1) DEFAULT NULL,
  `not_valid` tinyint(1) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_contact_copy1_name_contact1_idx` (`name_contact_id`),
  CONSTRAINT `fk_name_contact_copy1_name_contact10` FOREIGN KEY (`name_contact_id`) REFERENCES `name_contact` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_phone`
--

LOCK TABLES `name_phone` WRITE;
/*!40000 ALTER TABLE `name_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_supplier`
--

DROP TABLE IF EXISTS `name_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `name_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `name_company_id` int(11) NOT NULL,
  `supplier_code` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_name_supplier_name_company1_idx` (`name_company_id`),
  CONSTRAINT `fk_name_supplier_name_company100` FOREIGN KEY (`name_company_id`) REFERENCES `name_company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_supplier`
--

LOCK TABLES `name_supplier` WRITE;
/*!40000 ALTER TABLE `name_supplier` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item`
--

DROP TABLE IF EXISTS `prdc_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `sku` varchar(45) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item`
--

LOCK TABLES `prdc_item` WRITE;
/*!40000 ALTER TABLE `prdc_item` DISABLE KEYS */;
INSERT INTO `prdc_item` VALUES (314,'aa7225dc29ca8dc0e4e8635fb7e39908','5.633-110.0 TANK VK2 GREY',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13',NULL,0,'2021-09-10 08:55:13'),(315,'7c9d1d64706be58a7d534a5a5dd08dd1','5.033-178.0 SAUGLEITUNG TURBINE',1,43,NULL,'2021-09-10 08:23:44',NULL,NULL,0,'2021-09-21 08:59:18'),(316,'c8d798117cdf6634b53022a9a6189c22','5.055-419.0 ABSAUGUNG',43,43,NULL,'2021-09-10 08:27:30',NULL,NULL,0,'2021-09-20 09:46:31'),(317,'7208a7799bab9dd25089e121b68a5f2e','5.033-237.0 SAUGLEITUNG MUFFE TANK',43,43,NULL,'2021-09-10 08:36:36',NULL,NULL,0,'2021-09-20 09:44:53'),(318,'0010feeca8c11bf165bf67f171b6c3b1','5071203 TANK CLEANING AGENTS 300 ML',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(319,'46f0628542c540285943a6c66babb4e4','50712020 TANK CLEANING AGENTS 600 ML',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(320,'efcde0d997ea2cddf34c731771feab4d','5.055-525.0 FC3 CLEAN WATER TANK',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(321,'09d6aa3fbfe44be6e1776f64baa60b98','5.033-179.0 SAUGLEITUNG TANK',43,43,NULL,'2021-09-10 09:31:53',NULL,NULL,0,'2021-09-20 09:43:39'),(322,'236f9a9e0708496b1ca30e9f0f51765f','5.071-414.0 BOTTLE CLEANING AGENTS 1 LT',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(323,'3d4a04499cbfc3ac764753b3fdbdaa3a','5.640-610.0 CANISTER',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(324,'03f452730544388d3e460c6c1f3645b9','5.071-188.0 TANICA 1 LITRO',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(325,'2d3c2868a8f6c2cf511ae58332e7291d','5.633-025.0 TANK VW PP GREY',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(326,'b24c2f304f5f58710a8c2e87d4f4f7b1','5.633.025.0 TANK PP GREY',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(327,'012a396343490608671e862272a592a2','5.633-223.0 TANK VW2 YELLOW',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(328,'2ea85163309efaa630772f44b8e48a06','5.633-223.0 TANK VW2 YELLOW',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,1,'2021-09-10 14:22:00'),(329,'302afe1eb38373aaec54e306e19297ac','5.633.223.0 TANK VW2 YELLOW',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(330,'096ff4bc09d2f63b4b2a55ad7979d170','5.633.110.0 TANK VW2 GREY',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(331,'0bc50c321842a8a9402e0f358083b151','5.055-048.0 FRISCHWASSER TANK',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(332,'44c4d48ef40f078e004d74874d903771','5.633-176.0 BOTTLE SPRAY',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(333,'80b11c7a187687c44d60177de00c0f1f','5.633.176.0 BOTTLE SPRAY',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(334,'b657debddc8e019262dd07e8dcec4131','BO2LHDPESQR122X92 FLAC. 2 LT',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(335,'56fc7365ad56cf243eb8a5e83315cd2d','LPRF2.5A1OP FLAC. 2.5 LT',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(336,'4795d1f497b366b0aaef8f2ba60fdd1f','LN1A.017 CAPSULA ALLUMINIO',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(337,'92e1d7b713b8a6afb409c6fd57f03a81','FLA025 BOTT. CILINDRICA 1 LT',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(338,'b2a39f5d91f1ef46b6a81ec859503380','FLA025 BLUE',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(339,'97420ec4bca2b8e516a927a91654aa0a','FLA011.BOTT. 1 LT FEEDER',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(340,'4322ad9714dded0884c7f00672a48306','FLA025SPC B.CILINDRICA NEUTRA',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(341,'c11ad8150bbe22039fd2763224381611','LPCF1.09A1BI LITRO NEUTRO',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(342,'ced2acbe656a11c3879d8bb515448795','LPCF1.09A10PP LITRO NEUTRO',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(343,'ae1575b2f1b80dc073b822a7e508fce1','LF1G.017A7 TAPPO GRIGIO',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23');
/*!40000 ALTER TABLE `prdc_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_attributes`
--

DROP TABLE IF EXISTS `prdc_item_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `order_index` int(11) DEFAULT NULL,
  `it_IT_field` varchar(45) DEFAULT NULL,
  `en_UK_field` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `maxlength` int(11) DEFAULT NULL,
  `minlength` int(11) DEFAULT NULL,
  `form_name` varchar(45) DEFAULT NULL,
  `form_id` varchar(45) DEFAULT NULL,
  `form_class` varchar(60) DEFAULT NULL,
  `form_inputType` varchar(45) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_name_UNIQUE` (`form_name`),
  UNIQUE KEY `form_id_UNIQUE` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_attributes`
--

LOCK TABLES `prdc_item_attributes` WRITE;
/*!40000 ALTER TABLE `prdc_item_attributes` DISABLE KEYS */;
INSERT INTO `prdc_item_attributes` VALUES (21,'2CC66DCCBC314A2FB3B6C535C74280C4',99,'Codice Interno','Internal Code','hidden',0,64,0,'ean','ean','form-control col-md-7 col-xs-12 clearable','hidden',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(22,'CB93B0C654EB408AAA621F131BEFAD7F',1,'Descrizione','Description','string',1,1000,0,'description','description','form-control col-md-7 col-xs-12 clearable','text',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(23,'D84F2B571916430784B681DA70F10C14',3,'Pezzi per Confezione','Pieces Per Pack','float',1,1000,0,'pieces_per_pack','pieces_per_pack','form-control col-md-7 col-xs-12 clearable','number',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(24,'68BD676B11E94149B76039D2CB6C8C95',9,'Note','Notes','text',0,1000,0,'note','note','form-control col-md-7 col-xs-12 clearable','text',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(35,'499937D46747D6311FB1E17B8A76B1DD',9,'Aliquota Iva','Tax Rate','select',0,1000,0,'tax_rate_id','tax_rate_id','form-control col-md-7 col-xs-12 clearable','select',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(36,'499937D46747D6311FB1E17B8A76B1DE',10,'Unità di Misura','Measure Unit','select',1,1000,0,'measure_unit_id','measure_unit_id','form-control col-md-7 col-xs-12 clearable','select',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(37,'499937D46747D6311FB1E17B8A76B1DF',4,'Confezioni per Bancale','Pack Per Pallet','float',1,1000,0,'pack_per_pallet','pack_per_pallet','form-control col-md-7 col-xs-12 clearable','number',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(38,'499937D46747D6311FB1E17B8A76B1DL',4,'Gestione Lotti','Batch Management','checkbox',0,1,0,'batch_management','batch_management','form-control col-md-7 col-xs-12 clearable','checboxk',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(39,'D4FA9ADC0DB9CAA66D73BCEA9417069F',5,'Cliente/Fornitore','Customer/Supplier','string',0,1000,0,'clifor','clifor','form-control col-md-7 col-xs-12 clearable','text',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL);
/*!40000 ALTER TABLE `prdc_item_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_attributes_has_item`
--

DROP TABLE IF EXISTS `prdc_item_attributes_has_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_attributes_has_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(1000) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_attributes_has_item_fk` (`item_id`),
  KEY `fk_item_attributes_has_attribute_fk` (`attribute_id`),
  KEY `index_valid_from` (`valid_from`),
  KEY `index_valid_to` (`valid_to`),
  KEY `index_erased` (`erased`),
  CONSTRAINT `fk_item_attributes_has_attribute_fk` FOREIGN KEY (`attribute_id`) REFERENCES `prdc_item_attributes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_attributes_has_item_fk` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2727 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_attributes_has_item`
--

LOCK TABLES `prdc_item_attributes_has_item` WRITE;
/*!40000 ALTER TABLE `prdc_item_attributes_has_item` DISABLE KEYS */;
INSERT INTO `prdc_item_attributes_has_item` VALUES (2307,'5a0f785c2781c2f3d32a21a1827d7dec',314,22,NULL,43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2308,'123c6bfcea9a9e4fe77f988a07bd96b6',314,23,'240',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2309,'fc3af2270a819aabb4614a5ec9b8a230',314,37,'12',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2310,'d5d31c05ab3c6cf2e71fca0be87e23d5',314,38,'0',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2311,'d3acb813d2d20b6cb626958fc89c2add',314,24,NULL,43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2312,'fabe981cfa9e46366d38fe6050174076',314,35,NULL,43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2313,'33055a8199db0cfc79a51ec98d7dae25',314,36,'UPC',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2314,'7607ead62336c5a2ac9c912f87e6d68f',314,21,'KARCHER LAVAVETRI NUOVA GRIGIA',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2315,'b64c8aef946ee593c493dbf00ad1e8ab',314,22,'KARCHER LAVAVETRI NUOVA GRIGIA',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2316,'6d63a78185d3f6e42ce231a14858d751',314,23,'240',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2317,'ca68ef879cdca6779f8a84b4598a2be5',314,37,'12',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2318,'00a43a53a0d204f65b597bc02d82963a',314,38,'0',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2319,'2a7be68da1399b1d45a858824d8a058f',314,24,NULL,43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2320,'726e9f94c53ad3f5a043eddc37e4c721',314,35,'Array',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2321,'d11e46646991bbd96e95fafd089c2347',314,36,'UPC',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2322,'2d4c8b5aa2edf2b0436fd6b525fb5620',314,21,'KARCHER',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2323,'60c8f9c703c4c1b10778d5f6e90d8fca',314,22,'TANICA LAVAVETRI PP NUOVA GRIGIA',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2324,'d2b5ba5fe6aefd33f5ba824159e85f88',314,23,'240',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2325,'6f54058880fd8dea7f19954ede76e02d',314,37,'12',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2326,'1263e6e5b620b86c15162509d7b71143',314,38,'0',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2327,'255b31ea9538a22f624533f5ecd11476',314,24,NULL,43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2328,'f3f56cab9ee169a699076ac42a4c7ad2',314,35,'Array',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2329,'78d0f93e56ed18be58527571bc976215',314,36,'UPC',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2330,'401c43a45a403985ba8b27205e910064',314,21,'KARCHER',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2331,'08038149a5781e7c53e4fcfee33d7a4e',314,22,'TANICA LAVAVETRI NUOVA PP GRIGIO',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2332,'d4b4b97354a072586d1a07e0521afbf5',314,23,'240',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2333,'516e340cb332c28de0dd01bde3bdfad1',314,37,'12',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2334,'8b6346d6fe7904c5d08c34a69926a1aa',314,38,'0',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2335,'b5a2d54d212cd677461ea2cf15e09ada',314,24,NULL,43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2336,'e20deff3385914b3380637d4e0ea581e',314,35,'Array',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2337,'eb30ccbb872ef9927be94ae0c3c13386',314,36,'UPC',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2338,'a50009dd4db0a6bb4b39128a44e66c52',314,21,'KARCHER',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2339,'aae1c6e8d83643a000a9d0bce624821c',314,22,'TANICA LAVAVETRI NUOVA PP GRIGIO',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2340,'358ecd32bc14f55cee7106679d761406',314,23,'240',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2341,'2e223af28e65fdfde3ef6216337555c9',314,37,'12',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2342,'4c1a7e7986473a57bb0284352d59ef99',314,38,'0',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2343,'d1d62100514285196a7f8c84fea23009',314,24,NULL,43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2344,'d88442b328fafda9bdf6aa7846aefe95',314,35,'Array',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2345,'97e12e805c71fb6d9c665c9a6e363b0e',314,36,'UPC',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2346,'5938135b7280f3452c377ee36a784b07',314,21,NULL,43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2347,'95d60879aa42fbb98ba968778036a4df',315,22,'BASTONE HDPE NERO',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2348,'c8ea72a6a4bc3400af7f63d3170d5f9d',315,23,'70',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2349,'c8ebdc3de0f38c82987e35426bc24e67',315,37,'6',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2350,'011c8849bb298de21d202d7751cd8121',315,38,'0',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2351,'acf85369dc75f7553453c8d0bd119e31',315,24,NULL,1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2352,'3f76015c7112af480c9e656e52f9c068',315,35,NULL,1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2353,'cc463483db3de969489acb62a2cdb481',315,36,'UPC',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2354,'6b09c3844ba0e89c89bad9300b911e88',315,21,'KARCHER',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2355,'3b75a8305569762ac51ae0db5292097b',316,22,'CORNINO PP NERO',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2356,'7f9d59e4428923696137c84518abf4bc',316,23,'750',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2357,'ae96e772f16fcd6305646c4a149e2521',316,37,'20',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2358,'f80b670b0bc4d38f703df115cd05ce29',316,38,'0',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2359,'296773320624e5b9c6a290ae05aae6df',316,24,NULL,43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2360,'aea47fe8d1c4a15cc6544ea1911d4076',316,35,NULL,43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2361,'dfa19a823f8d25b1298ee125cddb189d',316,36,'UPC',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2362,'836fbc3a24edfd4cf48e24a5661baf70',316,21,'KARCHER',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2363,'b036fa114f819dbdf4f0f3c7ce8893ed',317,22,'CURVA HDPE NERO',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2364,'b4157467358d16205809e16f689a1eec',317,23,'500',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2365,'458a134953222cd69931abe4df3cb51d',317,37,'12',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2366,'cbbd66b7e1baf3b2ea8cd1281046544c',317,38,'0',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2367,'e1e64546ae734b7202ddbd99dc1b0437',317,24,NULL,43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2368,'59c5ae452a48ea23f4a5cce1a0a915dc',317,35,NULL,43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2369,'2e3f826d7d0cdf52ac4e40243ac2f6a0',317,36,'UPC',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2370,'aecb09154a90b1133535373e0db3587c',317,21,'KARCHER',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2371,'924cb98ffb6b2c19b01896c71ae93488',318,22,'SERBATOIO 300 ML HDPE GRIGIO',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2372,'ed5bc1e07f84164910eac0238f738af6',318,23,'380',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2373,'fb171e0c9deaeb19f647f170b30c4c16',318,37,'8',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2374,'a2f847744fcd4f281cc72f4165868218',318,38,'0',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2375,'77ac76f1c0a705b8a0202f95a98b2ea9',318,24,NULL,43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2376,'bbbdc5182100992b9c48d345b81f3fb4',318,35,NULL,43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2377,'ff4b5ef89c77ca929b5c8fd2340d562d',318,36,'UPC',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2378,'e9d4b2a8cf0e99e290278ba2630a65a8',318,21,NULL,43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2379,'818c72fabb17617dd75549ee84878a8a',319,22,'SERBATOIO 600 ML HDPE GRIGIO',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2380,'88a7e5b76d86aa5ae512daf8a4ffdce7',319,23,'210',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2381,'b2afc558f85d6cc0a01f47f02ba0ac93',319,37,'8',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2382,'b3961b3c537dbef2b17e1db34f1790ec',319,38,'0',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2383,'0ca8f633bcae8e91c972217a86ad615f',319,24,NULL,43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2384,'7eaa0863d4976ca5d05ed8c425e99757',319,35,NULL,43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2385,'47850cc2d40ab30f4d78c7b75abedc6b',319,36,'UPC',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2386,'68271614148b973e4266e7e54aca51bf',319,21,NULL,43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2387,'e26ed4773499ab813ae4f441dc7baf5d',320,22,'TANICA LAVAPAVIMENTI NUOVA FC3 HDPE GRIGIO',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2388,'d0f9299871b0f85aa52c20155b7ee2f4',320,23,'236',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2389,'32e120df3fd62e187e44af3c0518ff68',320,37,'12',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2390,'009aa85aaca5d1cd7c65951e6ec9e080',320,38,'0',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2391,'f431a7fcdd0c69ebc8846fb63f813520',320,24,NULL,43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2392,'ca3f40e87b4f793268eca25cceac13be',320,35,NULL,43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2393,'a06dd56001b17c80ece0db75b09076e1',320,36,'UPC',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2394,'a9cf97fb78aef90c0ec53ba223b18b07',320,21,NULL,43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2395,'210b26160699274141274ea4e7c2ef0d',321,22,'OMBRELLO HDPE NERO',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2396,'f1990f64797243389a8e62018fcc5ac5',321,23,'130',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2397,'a35d39e1c044594e9ac220272f4ca069',321,37,'6',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2398,'6697658b56d9bb35ac26da3f61d865e1',321,38,'0',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2399,'1414f2f0810586ed9d08b4c2932fa4cc',321,24,NULL,43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2400,'03822396735d4600aaaef25e26b88952',321,35,NULL,43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2401,'aa8eddf1266a4012dd87d5be0263f156',321,36,'UPC',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2402,'c338fcd7bbc90d62d1017d902876f937',321,21,'KARCHER',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2403,'3db074e1765e7933b608ce6afb38c39f',322,22,'BASE QUADRATA HDPE GRIGIO',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2404,'1502c87ec877d4be00654c06c5952bdf',322,23,'70',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2405,'0a6ab9b22d270ace036d90abf4ccd39a',322,37,'12',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2406,'e2d20414480d2948e5fa7a7d6766797d',322,38,'0',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2407,'0b434c572123e8979a3cf3f4c5b967aa',322,24,NULL,43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2408,'6974553bddb245fecfd8be7c6fe9303e',322,35,NULL,43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2409,'1f113f6a4901f9ad76bbaab60aa97c13',322,36,'UPC',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2410,'e027eb097490af661597c26c6f905b64',322,21,NULL,43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2411,'6b1f32db1fd90c6cc0954a507d31f21f',323,22,'SERBATOIO 12 LT HDPE NEUTRO',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2412,'be55cce213c7bd8c2146c1e536509816',323,23,'38',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2413,'a108518cf196b880ee55a8461c7dd882',323,37,'1',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2414,'c7c6aed05cc329b2a850224ee469783f',323,38,'0',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2415,'b5d8414d825fe93691daa94947bb9b95',323,24,NULL,43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2416,'cdbf6bf3d5106d4c64062a47121e1a82',323,35,NULL,43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2417,'5f9f280525b17ff0f4f5b7184769690b',323,36,'UPC',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2418,'ee20fa13033233d1db2a30b9c018ce3e',323,21,NULL,43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2419,'ad19abb0bc2bd16cd7b08724dfb17dd4',324,22,'SERBATOIO 1 LT HDPE GRIGIO',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2420,'71b1822e06971416bcf2ed2bdb062ddd',324,23,'120',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2421,'71eb6d804ccdc0ac55548c8c6ff525e1',324,37,'8',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2422,'9dbf6a67bc0dfebb4308bbc178624663',324,38,'0',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2423,'ec34f0c2a50b970e53adfa00a97f57fc',324,24,NULL,43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2424,'252bf83553f4ad3cfd5fd4a8d8872342',324,35,NULL,43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2425,'fe58a3e98e16e7c3212cb321345a6ba6',324,36,'UPC',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2426,'40026cefe190079aaa944616e0073987',324,21,NULL,43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2427,'100860cdaf5c6eda88e1db860273fd41',325,22,'TANICA LAVAVETRI VECCHIA PP GRIGIO',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2428,'ce8217e681d502f3173d6b6fd8dd80a8',325,23,'320',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2429,'4e8cd23059c66e3717c5ae09eb329a1a',325,37,'12',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2430,'2ebbccad09eb5e8011c8d67fd3fc77da',325,38,'0',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2431,'24dac11412b08fec241f28f21b81c406',325,24,NULL,43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2432,'a0ada597be7faf74fe4360d5036b3171',325,35,NULL,43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2433,'ca0513de4df54a53af492a37128b621f',325,36,'UPC',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2434,'ccf0b1b21bebb8fed88518ae3d9e6035',325,21,NULL,43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2435,'c3d88e338488ac46c42543d4fe5cbae0',326,22,'TANICA LAVAVETRI VECCHIA PP GRIGIO PER CEM',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2436,'177097ca9cfb269832636a5e03d21ee7',326,23,'320',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2437,'2006d8a1f2c97e3c3967f87ce824c678',326,37,'9',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2438,'717161418f4a7e9ca5d979c8b7c766ff',326,38,'0',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2439,'3251480df8bda3089f30dfd8b8881bc7',326,24,NULL,43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2440,'eb3056a448b8c9c7a1c59083468d38fc',326,35,NULL,43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2441,'065c9718914031fd58e0000de4c42b33',326,36,'UPC',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2442,'6e69b1150ee3c9458db759ee80772c7e',326,21,NULL,43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2443,'34a2115ced546889c8adbee90c3bb0e6',327,22,'TANICA LAVAVETRI NUOVA PP GIALLO',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2444,'f578bca6996a9a6984f55b997ead3ff9',327,23,'240',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2445,'49b6b11951d2c1aade9e933e6f962b9b',327,37,'12',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2446,'db21e02cd6c6ba82d099b2f43c6be906',327,38,'0',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2447,'695349f21a396f44bd8f60df14b1851d',327,24,NULL,43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2448,'4cef7cf43544d9b021eec0327769ea57',327,35,NULL,43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2449,'6476e284dceac559f9525196f7c464d1',327,36,'UPC',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2450,'1fea6d5798c011556761ec460ae34383',327,21,NULL,43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2451,'46e5b2cd749c5658a0b34db3739ff5a2',328,22,'TANICA LAVAVETRI NUOVA PP GIALLO CEM',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2452,'ff8b813387a3a5aa986f7e3ace3786d5',328,23,'240',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2453,'fd26ef6248fcd9e065491f294b9eab6f',328,37,'9',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2454,'af7b346313505ea33c3bc929c0fd3688',328,38,'0',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2455,'6cb2b9e152b4c75d13a30915d0b7f720',328,24,NULL,43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2456,'7245671b1bd21bd8e3c728975c4947cd',328,35,NULL,43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2457,'d47a4c0e74f25cfc5dfd79a0d8809264',328,36,'UPC',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2458,'c3b3aab5e92ff04958ba7b6d65ed13d5',328,21,NULL,43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2459,'157061fd4592913671bfed0acf8da2ae',329,22,'TANICA LAVAVETRI NUOVA PP GIALLO CEM',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2460,'6ea1dc8588a62f749beb991d3cffc0a8',329,23,'240',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2461,'d69ee2db473d2cc2a20d75e29ea94a47',329,37,'9',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2462,'87e0681f54fee3128e0917d6b0f0ec59',329,38,'0',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2463,'3a5a08c997dc3ba62c22aae492f2c603',329,24,NULL,43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2464,'7736f1e1396deb7b76c19a2f2e154ea5',329,35,NULL,43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2465,'521584f96af63d021a07a62b4ab5a20c',329,36,'UPC',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2466,'72404c0cda1db64a5a7458a766e1d27a',329,21,NULL,43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2467,'81c390285a14bc7dc64b4bb3671a73a5',330,22,'TANICA LAVAVETRI NUOVA PP GRIGIO PER CEM',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2468,'010de7e99218ab4aa13342605fea8cc6',330,23,'240',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2469,'cc9ca96396534e4042b1aba2fa370da1',330,37,'9',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2470,'92f1d2cc86f39c6a9882aa9e535f8a12',330,38,'0',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2471,'7e7655206c46f1fe60e7606bf675d20e',330,24,NULL,43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2472,'1df1a8c431e180a7642308b1a9023ee2',330,35,NULL,43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2473,'c5237fdaf067b2f5d7fd4cbc55996e93',330,36,'UPC',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2474,'ebf9e7e52b7355f3be021e1c4c3c0e40',330,21,NULL,43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2475,'e80120615a1c8668ca0fbd0809048d99',331,22,'TANICA LAVAPAVIMENTI FRISCHWASSER',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2476,'894f280623820aa8fc2e67c2fe8fe510',331,23,'210',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2477,'95fb476976414d15289c5a15b51b40a9',331,37,'12',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2478,'03298b0b4d4df678b0a9e975da3417c5',331,38,'0',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2479,'8f8098fc0614989092106a4a28737c46',331,24,NULL,43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2480,'558cf09b94de340a2f204ac7e22e5233',331,35,NULL,43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2481,'823d0ebdc3acf985945f8ea268196188',331,36,'UPC',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2482,'d022935c536df6aed8374a11ff6ebc08',331,21,NULL,43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2483,'81ebe6cf690629630c234a32b53f1b5d',332,22,'SPRUZZINI HDPE GRIGIO KARCHER',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2484,'23eab8f9e90e77dea2a47c66a613ca3b',332,23,'280',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2485,'2491227785b0c391359ec10b775d4a45',332,37,'12',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2486,'f1f203b8c29422da963abdb0f0332cee',332,38,'0',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2487,'a9fd00ed633aa2043f26ccad22c190a9',332,24,NULL,43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2488,'55d4db919cae16b0afef6ee5b35f9626',332,35,NULL,43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2489,'01d6bc8150ff1bb8e9b2c4dedbb16274',332,36,'UPC',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2490,'8ab5cf93b4833454cc5e9f1def756361',332,21,NULL,43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2491,'01aa8118d6f42c02d5eaeb2721a5962b',333,22,'SPRUZZINI HDPE GRIGIO CEM',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2492,'bf4c3137bfc2c958e0cc30ea0673dbae',333,23,'220',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2493,'638cbec7ec785939f015a3f6d8215ae9',333,37,'8',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2494,'708a202cb7a9e48e52e34b7e85f7999c',333,38,'0',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2495,'e738ba1e157e7250571f1ceb22bc54fe',333,24,NULL,43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2496,'c5f86f182e03cdfbdd0b602cbd66a5c4',333,35,NULL,43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2497,'23796c55723ffcc9c183863b475ba3be',333,36,'UPC',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2498,'96e63fe79ccb112550473309dff199bf',333,21,NULL,43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2499,'e7120f97e6661b3f5079022b8c91a290',334,22,'FLACONE 2 LT HDPE NEUTRO',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2500,'92c44bc12c8bafd20cd56f28a5f1747b',334,23,'55',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2501,'bd333fef1b37281f7791769929176094',334,37,'6',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2502,'025fbdce0debfd2c71cc2b40031c029a',334,38,'0',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2503,'ed5845cea752f5e5776a9709b389c1b3',334,24,NULL,43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2504,'2adbebede35da3c62ffe315a2351c454',334,35,NULL,43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2505,'f2714263ba2086cd2b65fca42d576161',334,36,'UPC',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2506,'c1674554f9187afbd431a6c1002085b2',334,21,NULL,43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2507,'07ff59f8cb9d03fe8c0a79663ff639fc',335,22,'FLACONE 2.5 LT HDPE NEUTRO',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2508,'08cac2c61240e00c166d80370f04b1da',335,23,'64',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2509,'ae30bdfad85451f68950d2e40a477a32',335,37,'6',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2510,'92fb3d9253014314c74d1b7b93cd93b4',335,38,'0',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2511,'9b606b247d4dd92fd7601a72fa72e469',335,24,NULL,43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2512,'fd7e1e046495b27aec942f17a1a64c83',335,35,NULL,43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2513,'7feb026c5995cdbcfa9e69533aff9e89',335,36,'UPC',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2514,'4c79f617a0633f6b414a0449600fc89f',335,21,NULL,43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2515,'33e2b234ef8d539454dfabd07c05f987',336,22,'CAPSULA IN ALLUMINIO',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2516,'a2c4c3ede1fa93e637fab93fa8f78cc6',336,23,'10000',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2517,'2f4e94ac71b36fbf8f662af8a5600d9f',336,37,NULL,43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2518,'cc48b72cabbb6fb915eea2405eaebd48',336,38,'0',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2519,'382d95e737adcbd88a3f6ed6a53acc36',336,24,NULL,43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2520,'1be3ddf0861b34e7c06f6cf25a9ffc33',336,35,NULL,43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2521,'5ad66dd559b348c8a2488ca6a96f6bfb',336,36,'UPC',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2522,'5a36f96efc8a41e96fc3dec2bde91ab3',336,21,NULL,43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2523,'057d30ee9042f6e0621491b41d1df2b9',337,22,'FLAC.JBR 1 LT HDPE BIANCO',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2524,'e6bad64b9099e31894dd8628a4a2aed9',337,23,'110',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2525,'c638ae849c7f65eeef29dd6aac838fc9',337,37,'8',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2526,'fc40a52742d7bc03964752faa2dca01b',337,38,'0',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2527,'88383519e91650c0e28777330e38de44',337,24,NULL,43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2528,'2c64fdea81e9c6c9443fb2a12e3d6b46',337,35,NULL,43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2529,'74ae04dadc705c83a5ec7493dc682760',337,36,'UPC',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2530,'cffc7fdf01d60b711bbe66170252e0dd',337,21,NULL,43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2531,'1d3a5fd11b5e617523ed3f4714c69fe2',338,22,'FLAC.JBR 1 LT HDPE BLU',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2532,'8f7d8b8da7261812b3a9c03149c30e57',338,23,'110',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2533,'0ec3f6f9063cd561f3371fb456885957',338,37,'8',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2534,'719d2e90fa6ec0de5d18a937938ebba9',338,38,'0',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2535,'fed05091b7d5d2321b20056603378704',338,24,NULL,43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2536,'be6d1e5a1cac81935e072a66000c1d82',338,35,NULL,43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2537,'5c555b33d864adf2a3be3a59d8e11586',338,36,'UPC',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2538,'ddf3b1a3b79376fda5e1b33da02bc005',338,21,NULL,43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2539,'062671320cd58aacaa5d852c58dab79c',339,22,'FLAC. 1 LT FEEDER HDPE NEUTRO',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2540,'2ed0e17bec48b92593896920630495b8',339,23,'110',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2541,'dd296a9a616a99d4d160051775791f8e',339,37,'8',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2542,'f3d437027541be3f8f5d1a3d6cc0cf23',339,38,'0',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2543,'338240bf6ef1fbd9ae20386bcc645b23',339,24,NULL,43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2544,'a794d5457186a91e49c7381463d2401d',339,35,NULL,43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2545,'5aaed38ad7fc51a156bc8a4af96fd44e',339,36,'UPC',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2546,'e37ef2cd11ad1e8b0acdc540ae143151',339,21,NULL,43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2547,'b1bcab91b07f3a50f1feb30442fab65d',340,22,'FLAC. JBR 1 LT HDPE NEUTRO',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2548,'90fdfefbaf0ee006cb1efeb2b88a9188',340,23,'110',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2549,'626a018b489a29e28d2c6348cc59a2d6',340,37,'8',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2550,'c7e71bc6832a0a72aba5c06e1fe32209',340,38,'0',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2551,'e2370bc644abea1ca7bb53aa2ee0fc77',340,24,NULL,43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2552,'8223aeac311949703ceaa04a3effe3e5',340,35,NULL,43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2553,'d4a7122fe6b5aee8e347dadf70f05f11',340,36,'UPC',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2554,'cab080c14cb4fda92c8b7f2bb54b6982',340,21,NULL,43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2555,'a48be9a2ecf402a1785c7da65d5f9012',341,22,'FLAC. 1 LT RIGATO HDPE NEUTRO',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2556,'112f3405085c5dcf2eefc967e58762ff',341,23,'110',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2557,'f024a0376ea831e943904b0822a6819d',341,37,'8',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2558,'f45b183d3552e5df7e3febf41e119f8c',341,38,'0',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2559,'6d96b3f51b1a2ea1bcb490fc29ae42f5',341,24,NULL,43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2560,'8190ebb29e7a54ad5bb01464e3a9e0e9',341,35,NULL,43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2561,'0ea55b14c0d02d774fb479d130e0ff03',341,36,'UPC',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2562,'0e8c0ae4c6f5dba7525ba8c6a7752ad8',341,21,NULL,43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2563,'191867b7df1f53a0dea21bf7a17fe3a9',342,22,'FLAC. 1 LT MOD. VECCHIO HDPE NEUTRO',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2564,'1aa898b05408d0537961b713de5cf8f2',342,23,'110',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2565,'153725f58e7a1ad6d8524ac6c0852a8f',342,37,'8',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2566,'56525ad25b56fdaaf4b408bd33b2ff21',342,38,'0',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2567,'51f4254c6d5caf3b4b230b7857191132',342,24,NULL,43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2568,'966bddadadc59577a6c4074578b9427e',342,35,NULL,43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2569,'ab2f0ca3078c89ed161f925f9646f6f2',342,36,'UPC',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2570,'1c198d39a2e601f6c7c501b9a3bf3306',342,21,NULL,43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2571,'880cc6847dbb7a251b1153787d1060e8',343,22,'TAPPO A PRESSIONE GRIGIO',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2572,'d0407ce0da1956a58b7f884e79445316',343,23,'4000',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2573,'98fbab1fc0fb8c91501d34ce135e5d9e',343,37,NULL,43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2574,'df4b5533dbe0a95328058eb8fc1a5222',343,38,'0',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2575,'6f350d16a4b610a47bd6618ee925106d',343,24,NULL,43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2576,'c424501f897006702a9d48411e04b586',343,35,NULL,43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2577,'4d90fb7d79b3849fef908be589002262',343,36,'UPC',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2578,'2c40dddf08d53e0b5eba3546da1f4d14',343,21,NULL,43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2579,'2657002f28ee6b42e2c2c093fd2adb40',315,22,'BASTONE HDPE NERO',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2580,'6bc894eb13427d07b66829cb5d75a455',315,23,'70',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2581,'3a61464eb8d33ec67677bef324af2c61',315,37,'6',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2582,'8f577af71398395723134e462ce165b6',315,38,'0',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2583,'5da2041767f07d4c123af63a410eee26',315,24,NULL,1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2584,'5fb29ffaf06f08f041c3fddf00638758',315,35,'Array',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2585,'4d4b81061369c4bee7f4097ea2b5ae7e',315,36,'UPC',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2586,'ae070d2106bca2fa231f3b3564171d3e',315,21,'KARCHER',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2587,'57836a23365a566b25166914386d482e',315,22,'SAUGLEITUNG TURBINE',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2588,'d59fabb784a9ca7fe634ecfa9992ff4c',315,23,'70',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2589,'a5c88c06baf67aacea96cdbd35aed1a7',315,37,'6',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2590,'be730a23d922348282fa9213420c3f89',315,38,'0',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2591,'0e81338df534581cf8944953762a6831',315,24,NULL,1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2592,'3e730645fd3b5000a24e6e77e4348035',315,35,'Array',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2593,'b34664d80eaf326b35243dfc705564db',315,36,'UPC',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2594,'fb7d2dc1992725e83a1895f0e490a622',315,21,NULL,1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2595,'6dd6e77eaa846d14c2ea43a6a1a8aaa9',321,22,'SAUGLEITUNG TANK',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2596,'2f3149ec20a3051481a5021ad61978a7',321,23,'130',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2597,'3b10d7bd7969acfbb28b4cbadcae3508',321,37,'6',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2598,'4b70380f17b0d27c44177fb52e4a8e4d',321,38,'0',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2599,'6aa6d57819c212c739d9a41ad682fda5',321,24,NULL,43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2600,'62db0fdcf6209c1064cd4490bf14ebc3',321,35,'Array',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2601,'9b73a311ed56c00def5d8a2f0cf237da',321,36,'UPC',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2602,'6a45416875329b21be6d5ffa35e3b799',321,21,NULL,43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2603,'9ae7f3004d20347ad8c4247e085128b8',317,22,'SAUGLEITUNG MUFFE TANK',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2604,'6b104935822e9cdf20643c98e09c799f',317,23,'500',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2605,'3af2bbd12ab75147bf6aa869a311e79c',317,37,'12',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2606,'69dfbcd8a2d92c4605ccf8038159a4b6',317,38,'0',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2607,'4364840777666d3717f26d2d7d5e8a47',317,24,NULL,43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2608,'7f262a9ebdf17051eb84a316d3ad4b1a',317,35,'Array',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2609,'9f25f3a75e646f8a40104000d5aabe79',317,36,'UPC',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2610,'8b4c0a1c479bcf301252bc36d9278712',317,21,NULL,43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2611,'7c34b9d278102cac88b5163cc6516e98',316,22,'ABSAUGUNG',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2612,'49b63f398cebe9a626a49e676c7fc427',316,23,'750',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2613,'58cb5a739387c721748fbe5d463bcb80',316,37,'20',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2614,'77665ecce5bdd258222e658a462a6d95',316,38,'0',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2615,'f9696aa2ef56e619d58b3cdf5e80a56f',316,24,NULL,43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2616,'9c2a4b1bd20f330a5c8803b465aaaa02',316,35,'Array',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2617,'0a4cc10c24f239d4daaaeb6ed7ae033e',316,36,'UPC',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2618,'551fe7993e09897d22fdf4bb8d44b9ae',316,21,NULL,43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2619,'E02519383D25828FA525DA2E57941166',314,39,'KARCHER LAVAVETRI NUOVA GRIGIA',43,43,43,'2021-09-10 06:55:13','2021-09-10 06:55:13','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2620,'E004D3B73806D730ECCD8E19697AC8A7',314,39,'KARCHER',43,43,43,'2021-09-10 06:55:51','2021-09-10 06:55:51','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2621,'76C1C44C6C24C00C5BD1E6E31BC6FBF3',314,39,'KARCHER',43,43,43,'2021-09-10 06:56:12','2021-09-10 06:56:12','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2622,'B7580C365275E0050D069C327097867D',314,39,'KARCHER',43,43,43,'2021-09-10 06:56:33','2021-09-10 06:56:33','2021-09-10 09:15:11',0,'2021-09-10 09:15:12'),(2623,'E271FCBE807C59CF912432FE08F2B9B8',314,39,'KARCHER',43,43,43,'2021-09-10 07:15:12','2021-09-10 07:15:12',NULL,0,'2021-09-10 09:15:12'),(2624,'D0AA88D34ABA6F0772E3891E8C17FBC5',315,39,'KARCHER',1,43,43,'2021-09-10 08:23:44','2021-09-10 08:23:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2625,'E9AB1A542BA7E731B2E783E226F8D312',316,39,'KARCHER',43,43,43,'2021-09-10 08:27:30','2021-09-10 08:27:30','2021-09-20 09:46:30',0,'2021-09-20 09:46:31'),(2626,'FE47ED4F53E88F40CB4F6A0BFABFB075',317,39,'KARCHER',43,43,43,'2021-09-10 08:36:36','2021-09-10 08:36:36','2021-09-20 09:44:52',0,'2021-09-20 09:44:53'),(2627,'001B600910F78325BB060885508A1BE2',318,39,'LAMPIA ROM',43,43,43,'2021-09-10 09:06:05','2021-09-10 09:06:05',NULL,0,'2021-09-10 11:06:05'),(2628,'DAF4157B759E5C79A91E0562516BC65A',319,39,'LAMPIA ROM',43,43,43,'2021-09-10 09:24:58','2021-09-10 09:24:58',NULL,0,'2021-09-10 11:24:58'),(2629,'00661761B5B782C4BD3A7754D37E33FA',320,39,'KARCHER',43,43,43,'2021-09-10 09:29:00','2021-09-10 09:29:00',NULL,0,'2021-09-10 11:29:00'),(2630,'38CEC7263C67CCF44F87DCC3D58D8B87',321,39,'KARCHER',43,43,43,'2021-09-10 09:31:53','2021-09-10 09:31:53','2021-09-20 09:43:38',0,'2021-09-20 09:43:39'),(2631,'2E0143A01FD78C23AC845BD7B4E4F8DB',322,39,'KARCHER',43,43,43,'2021-09-10 09:37:27','2021-09-10 09:37:27',NULL,0,'2021-09-10 11:37:27'),(2632,'2473228CE272A098B43506A5F7776075',323,39,'KARCHER',43,43,43,'2021-09-10 09:44:35','2021-09-10 09:44:35',NULL,0,'2021-09-10 11:44:35'),(2633,'277738050D0C51CD77FA50FDD9A64A5F',324,39,'KARCHER',43,43,43,'2021-09-10 09:58:19','2021-09-10 09:58:19',NULL,0,'2021-09-10 11:58:19'),(2634,'4DA0561226217039A541FD99589BCF3E',325,39,'KARCHER',43,43,43,'2021-09-10 10:01:37','2021-09-10 10:01:37',NULL,0,'2021-09-10 12:01:37'),(2635,'2BA2F1A3371BACE1C281D3CAE94D2530',326,39,'CEM',43,43,43,'2021-09-10 10:27:40','2021-09-10 10:27:40',NULL,0,'2021-09-10 12:27:40'),(2636,'436DC185A293F6EED3A0FC3B6E9FD66F',327,39,'KARCHER',43,43,43,'2021-09-10 10:47:41','2021-09-10 10:47:41',NULL,0,'2021-09-10 12:47:41'),(2637,'5DDD2B34DE17D7583A84F660D2C3A5A1',328,39,'CEM',43,43,43,'2021-09-10 12:22:00','2021-09-10 12:22:00',NULL,0,'2021-09-10 14:22:00'),(2638,'D5DA76A4D781001865A5CD4A93FB5CCD',329,39,'CEM',43,43,43,'2021-09-10 12:24:58','2021-09-10 12:24:58',NULL,0,'2021-09-10 14:24:58'),(2639,'7EE780BCDCFB2CE8581EF41F49B9F4DE',330,39,'CEM',43,43,43,'2021-09-10 12:49:58','2021-09-10 12:49:58',NULL,0,'2021-09-10 14:49:58'),(2640,'CB7B00D6EFF0955245ED7CADBD2332C9',331,39,'KARCHER',43,43,43,'2021-09-10 12:56:16','2021-09-10 12:56:16',NULL,0,'2021-09-10 14:56:16'),(2641,'6C452F79EA667B5876C6D5A859ADEC69',332,39,'KARCHER',43,43,43,'2021-09-10 13:08:48','2021-09-10 13:08:48',NULL,0,'2021-09-10 15:08:48'),(2642,'54BD1E0573282BEF9F6CE02DAA2447C5',333,39,'CEM',43,43,43,'2021-09-10 13:17:13','2021-09-10 13:17:13',NULL,0,'2021-09-10 15:17:13'),(2643,'E68DFFF902CAEC438D3C2D264ECDB486',334,39,'JK GROUP',43,43,43,'2021-09-10 14:03:23','2021-09-10 14:03:23',NULL,0,'2021-09-10 16:03:23'),(2644,'0E0C89D4A9F3A311B26225463E1A6391',335,39,'JK GROUP',43,43,43,'2021-09-10 14:21:37','2021-09-10 14:21:37',NULL,0,'2021-09-10 16:21:37'),(2645,'CCB896C00DAF78ADEB51774A39E3240A',336,39,'JK GROUP',43,43,43,'2021-09-10 14:24:12','2021-09-10 14:24:12',NULL,0,'2021-09-10 16:24:12'),(2646,'EE20AEF70A688695DA1A15D37C8C9635',337,39,'JK GROUP',43,43,43,'2021-09-10 14:28:17','2021-09-10 14:28:17',NULL,0,'2021-09-10 16:28:17'),(2647,'4246D99033FFC8456EB7A9789A227A2E',338,39,'JK GROUP',43,43,43,'2021-09-10 14:31:36','2021-09-10 14:31:36',NULL,0,'2021-09-10 16:31:36'),(2648,'BA4F12EAE0EEE0DC680F10F0C1007BD7',339,39,'JK GROUP',43,43,43,'2021-09-10 14:46:29','2021-09-10 14:46:29',NULL,0,'2021-09-10 16:46:29'),(2649,'A18C95BB9E502E4D35B932317610407E',340,39,'JK GROUP',43,43,43,'2021-09-10 14:48:30','2021-09-10 14:48:30',NULL,0,'2021-09-10 16:48:30'),(2650,'16483C30E14CA775EB865E51F477D1DF',341,39,'JK GROUP',43,43,43,'2021-09-10 14:50:40','2021-09-10 14:50:40',NULL,0,'2021-09-10 16:50:40'),(2651,'B97A1A2D94F2F6CE1EBE8300E38D28A5',342,39,'JK GROUP',43,43,43,'2021-09-10 14:53:39','2021-09-10 14:53:39',NULL,0,'2021-09-10 16:53:39'),(2652,'0813010838EAE7C718B41FE9B6066650',343,39,'JK GROUP',43,43,43,'2021-09-10 14:56:23','2021-09-10 14:56:23',NULL,0,'2021-09-10 16:56:23'),(2653,'DEF5C05E1087B75782729E9B3A3286A6',315,39,'KARCHER',1,43,43,'2021-09-15 12:58:00','2021-09-15 12:58:00','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2654,'D34A765B886D49EFB7C37079B527EE35',315,39,'KARCHER',1,43,43,'2021-09-20 07:42:11','2021-09-20 07:42:11','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2655,'2278C8A979494E325EA4CB8E37BA3F2C',321,39,'KARCHER',43,43,43,'2021-09-20 07:43:39','2021-09-20 07:43:39',NULL,0,'2021-09-20 09:43:39'),(2656,'D823DD864F038D19D06915E48B6C19BC',317,39,'KARCHER',43,43,43,'2021-09-20 07:44:53','2021-09-20 07:44:53',NULL,0,'2021-09-20 09:44:53'),(2657,'2491FAA6110D8938188EB65F752DD864',316,39,'KARCHER',43,43,43,'2021-09-20 07:46:31','2021-09-20 07:46:31',NULL,0,'2021-09-20 09:46:31'),(2682,'5e82c794d963f7518271b7143ddf2392',315,22,'SAUGLEITUNG TURBINE',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2683,'af1c0a57d265bba07ca208c99f5212bf',315,23,'70',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2684,'c92a81885a349b8f4a17b79adafbb78e',315,37,'6',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2685,'3ca074d89bc7290938fbbe6d148107ad',315,38,'0',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2686,'e7c1ce8052b687d36e438afa1e310ea8',315,39,NULL,1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2687,'3325d617e28e598ea22660fc203a289e',315,24,NULL,1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2688,'4b66366d76eab69da39647677af2ebff',315,35,'Array',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2689,'6afa3f51b047ec8023af1f5f6c8ad26a',315,36,'UPC',1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2690,'f5a4f24dd009874dc9533daf9a2a0b5d',315,21,NULL,1,1,1,'2021-09-21 08:52:44','2021-09-21 08:52:44','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2691,'e065e041e4df569da41d309321332d37',315,22,'SAUGLEITUNG TURBINE',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2692,'bc2ec3d78c40c0db2b798d65c66e5be0',315,23,'70',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2693,'64ad4c70cd38453204e51416f95ac1b9',315,37,'6',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2694,'32c903b78969b4f92ddd0da930f21f45',315,38,'0',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2695,'5a5696ad4fe057710239f89236ac39d9',315,39,NULL,1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2696,'0063e7954d5d4c65b3820beb1a68976d',315,24,NULL,1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2697,'2ab01afdf00c5c28cc262378a464d7bf',315,35,'Array',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2698,'e4b4b49f5a4e8249e88c07cc0163cc7d',315,36,'UPC',1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2699,'339dfc5d10c7bce433f6353863195fec',315,21,NULL,1,1,1,'2021-09-21 08:55:09','2021-09-21 08:55:09','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2700,'d242f20e8279205d63b36c5db4c6fe79',315,22,'SAUGLEITUNG TURBINE',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2701,'f7e25fb30204568d76e7cc6c6ae58637',315,23,'70',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2702,'d7fbdae7727f60a185ea01cce6d06db5',315,37,'6',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2703,'b004450d97fde3608744063fed5a47ea',315,38,'0',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2704,'0c435cbaee89eda600dc4f60719603dd',315,39,NULL,1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2705,'ea69a6d511eddfb039bb6ababed119e6',315,24,NULL,1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2706,'cd9992e1512e69bc81b697ab054d701c',315,35,'Array',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2707,'bda9ad99c1a72d4e26e49a08c05a65bd',315,36,'UPC',1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2708,'7d4f9d10cce3cbaad02a6403f30c4ac7',315,21,NULL,1,1,1,'2021-09-21 08:55:50','2021-09-21 08:55:50','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2709,'f576005b16ce9e8e056bf3fb49962540',315,22,'SAUGLEITUNG TURBINE',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2710,'521822f381586ef9e16f56e0f78ba06a',315,23,'70',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2711,'6a056bd1b7828f94296dc684edceaffa',315,37,'6',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2712,'f49e90a3c0db9ace8dae36bc96c484f4',315,38,'0',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2713,'0b7d23b1a7dbef344feec23dbd9cda0f',315,39,NULL,1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2714,'42f389418594bd849da22bfc291adb80',315,24,NULL,1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2715,'c3de60592d9cceab6609e178706702ca',315,35,'Array',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2716,'b2ac4d9f930ceaeec73efd9dc0e2972b',315,36,'UPC',1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2717,'23920a4dd67ed420fb83a13f14f565e6',315,21,NULL,1,1,1,'2021-09-21 08:56:17','2021-09-21 08:56:17','2021-09-21 08:59:17',0,'2021-09-21 08:59:18'),(2718,'845faa9b8ed351ccf958ca1e8e789fb1',315,22,'SAUGLEITUNG TURBINE',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2719,'746b2af2fa100a6c0a39b0d8d2475b43',315,23,'70',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2720,'7b44c8ffa1312078a68edc614eac3dce',315,37,'6',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2721,'75169d1d0a596e6e22e2a7dd61b8dd1e',315,38,'0',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2722,'44754d396d2bd3be0fb0d8cf3e5d5bb7',315,39,'KARCHER_TEST',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2723,'f9e3ed27133e6baa5e131d8602db5757',315,24,NULL,1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2724,'15108b660b382d5fb2aa3ca582dc03bc',315,35,'Array',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2725,'7ce9db720fe7cd9e35b2f8231317cc36',315,36,'UPC',1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18'),(2726,'e056ff51aaec5e08e2c186b7284bd624',315,21,NULL,1,1,1,'2021-09-21 08:59:18','2021-09-21 08:59:18',NULL,0,'2021-09-21 08:59:18');
/*!40000 ALTER TABLE `prdc_item_attributes_has_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_category`
--

DROP TABLE IF EXISTS `prdc_item_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_category`
--

LOCK TABLES `prdc_item_category` WRITE;
/*!40000 ALTER TABLE `prdc_item_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_has_category`
--

DROP TABLE IF EXISTS `prdc_item_has_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_has_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_has_category_category1_idx` (`category_id`),
  KEY `fk_item_has_category_item_idx` (`item_id`),
  CONSTRAINT `fk_item_has_category_category1` FOREIGN KEY (`category_id`) REFERENCES `prdc_item_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_category_item` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_has_category`
--

LOCK TABLES `prdc_item_has_category` WRITE;
/*!40000 ALTER TABLE `prdc_item_has_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_has_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_has_price_list`
--

DROP TABLE IF EXISTS `prdc_item_has_price_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_has_price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price_list_id` int(11) NOT NULL,
  `price` decimal(17,2) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `discount` decimal(17,2) DEFAULT NULL,
  `discount_percent` decimal(4,2) DEFAULT NULL,
  `surcharge` decimal(17,2) DEFAULT NULL,
  `surcharge_percent` decimal(4,2) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_prdc_item_has_price_list_idx` (`item_id`),
  KEY `fk_prdc_item_has_price_list_price_list_idx` (`price_list_id`),
  KEY `fk_prdc_item_has_price_list_tax_rate1_idx` (`tax_rate_id`),
  CONSTRAINT `prdc_item_has_price_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `prdc_item_has_price_list_ibfk_2` FOREIGN KEY (`price_list_id`) REFERENCES `prdc_item_price_list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prdc_item_has_price_list_ibfk_3` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_has_price_list`
--

LOCK TABLES `prdc_item_has_price_list` WRITE;
/*!40000 ALTER TABLE `prdc_item_has_price_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_has_price_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `prdc_item_has_price_list_v`
--

DROP TABLE IF EXISTS `prdc_item_has_price_list_v`;
/*!50001 DROP VIEW IF EXISTS `prdc_item_has_price_list_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `prdc_item_has_price_list_v` AS SELECT 
 1 AS `id`,
 1 AS `oid`,
 1 AS `item_id`,
 1 AS `price_list_id`,
 1 AS `price`,
 1 AS `tax_rate_id`,
 1 AS `discount`,
 1 AS `discount_percent`,
 1 AS `surcharge`,
 1 AS `surcharge_percent`,
 1 AS `modified_by`,
 1 AS `created_by`,
 1 AS `assigned`,
 1 AS `create_date`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `erased`,
 1 AS `last_update`,
 1 AS `item_description`,
 1 AS `tax_rate`,
 1 AS `price_list`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `prdc_item_has_type`
--

DROP TABLE IF EXISTS `prdc_item_has_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_has_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_has_type_type_idx` (`type_id`),
  KEY `fk_item_has_type_item_idx` (`item_id`),
  CONSTRAINT `fk_item_has_type_item_idx` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_type_type_idx` FOREIGN KEY (`type_id`) REFERENCES `prdc_item_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_has_type`
--

LOCK TABLES `prdc_item_has_type` WRITE;
/*!40000 ALTER TABLE `prdc_item_has_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_has_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_price_list`
--

DROP TABLE IF EXISTS `prdc_item_price_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_price_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `type` varchar(2) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_price_list`
--

LOCK TABLES `prdc_item_price_list` WRITE;
/*!40000 ALTER TABLE `prdc_item_price_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_price_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdc_item_type`
--

DROP TABLE IF EXISTS `prdc_item_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdc_item_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `type_code` varchar(4) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdc_item_type`
--

LOCK TABLES `prdc_item_type` WRITE;
/*!40000 ALTER TABLE `prdc_item_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `prdc_item_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progressive`
--

DROP TABLE IF EXISTS `progressive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progressive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipologia` varchar(10) NOT NULL,
  `progressivo` int(11) NOT NULL,
  `anno` varchar(4) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`tipologia`,`progressivo`,`anno`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progressive`
--

LOCK TABLES `progressive` WRITE;
/*!40000 ALTER TABLE `progressive` DISABLE KEYS */;
INSERT INTO `progressive` VALUES (1,'I',8,'2020',0,'2020-09-08 13:50:28'),(79,'N',0,'2020',0,'2020-09-10 15:41:15');
/*!40000 ALTER TABLE `progressive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_dcmt_has_einv`
--

DROP TABLE IF EXISTS `rel_dcmt_has_einv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_dcmt_has_einv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `einv_id` int(11) NOT NULL,
  `erased` tinyint(1) DEFAULT '0',
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_document_has_einv_document_id_idx` (`document_id`),
  KEY `fk_document_has_einv_einv_id_idx` (`einv_id`),
  CONSTRAINT `fk_document_has_einv_document_id` FOREIGN KEY (`document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_document_has_einv_einv_id` FOREIGN KEY (`einv_id`) REFERENCES `einv_inv_header` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_dcmt_has_einv`
--

LOCK TABLES `rel_dcmt_has_einv` WRITE;
/*!40000 ALTER TABLE `rel_dcmt_has_einv` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_dcmt_has_einv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_dcmt_has_name`
--

DROP TABLE IF EXISTS `rel_dcmt_has_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_dcmt_has_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `name_company_id` int(11) DEFAULT NULL,
  `dcmt_document_id` int(11) NOT NULL,
  `name_address_id` int(11) DEFAULT NULL,
  `role` varchar(1) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dcmt_document_has_company_dcmt_document1_idx` (`dcmt_document_id`),
  KEY `fk_dcmt_document_has_address_dcmt_idx` (`name_address_id`),
  CONSTRAINT `fk_dcmt_document_has_address_dcmt` FOREIGN KEY (`name_address_id`) REFERENCES `name_address` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_dcmt_document_has_company_dcmt_document1` FOREIGN KEY (`dcmt_document_id`) REFERENCES `dcmt_document` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_dcmt_has_name`
--

LOCK TABLES `rel_dcmt_has_name` WRITE;
/*!40000 ALTER TABLE `rel_dcmt_has_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_dcmt_has_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_dcmt_row_has_prdc`
--

DROP TABLE IF EXISTS `rel_dcmt_row_has_prdc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_dcmt_row_has_prdc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `document_row_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price_list_id` int(11) DEFAULT NULL,
  `tax_rate_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(4) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rel_dcmt_row_has_prdc_dcmt_document_row1_idx` (`document_row_id`),
  KEY `fk_rel_dcmt_row_has_prdc_tax_ratet_row1_idx` (`tax_rate_id`),
  KEY `fk_rel_dcmt_row_has_prdc_prdc_item_row1_idx` (`item_id`),
  KEY `fk_rel_dcmt_row_has_prdc_price_list_row1_idx` (`price_list_id`),
  CONSTRAINT `fk_rel_dcmt_row_has_prdc_dcmt_document_row1` FOREIGN KEY (`document_row_id`) REFERENCES `dcmt_document_row` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_dcmt_row_has_prdc_prdc_item_row1` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_dcmt_row_has_prdc_price_list_row1` FOREIGN KEY (`price_list_id`) REFERENCES `prdc_item_has_price_list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_dcmt_row_has_prdc_tax_ratet_row1` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rate` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=756 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_dcmt_row_has_prdc`
--

LOCK TABLES `rel_dcmt_row_has_prdc` WRITE;
/*!40000 ALTER TABLE `rel_dcmt_row_has_prdc` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_dcmt_row_has_prdc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_prdc_has_name`
--

DROP TABLE IF EXISTS `rel_prdc_has_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_prdc_has_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `name_customer_id` int(11) DEFAULT NULL,
  `name_supplier_id` int(11) DEFAULT NULL,
  `prdc_price_list_id` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_price_list_has_name_name_customer1_idx` (`name_customer_id`),
  KEY `fk_price_list_has_name_name_supplier_id_idx` (`name_supplier_id`),
  KEY `fk_price_list_has_name_price_list_idx` (`prdc_price_list_id`),
  CONSTRAINT `rel_prdc_has_name_ibfk_1` FOREIGN KEY (`prdc_price_list_id`) REFERENCES `prdc_item_price_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `rel_prdc_has_name_ibfk_2` FOREIGN KEY (`name_supplier_id`) REFERENCES `name_supplier` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `rel_prdc_has_name_ibfk_3` FOREIGN KEY (`name_customer_id`) REFERENCES `name_customer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_prdc_has_name`
--

LOCK TABLES `rel_prdc_has_name` WRITE;
/*!40000 ALTER TABLE `rel_prdc_has_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_prdc_has_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `rel_prdc_has_name_v`
--

DROP TABLE IF EXISTS `rel_prdc_has_name_v`;
/*!50001 DROP VIEW IF EXISTS `rel_prdc_has_name_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `rel_prdc_has_name_v` AS SELECT 
 1 AS `id`,
 1 AS `oid`,
 1 AS `name_customer_id`,
 1 AS `name_supplier_id`,
 1 AS `prdc_price_list_id`,
 1 AS `price_list_code`,
 1 AS `price_list_description`,
 1 AS `price_list_type`,
 1 AS `price_list_erased`,
 1 AS `customer_code`,
 1 AS `customer_business_name`,
 1 AS `customer_description`,
 1 AS `supplier_code`,
 1 AS `supplier_business_name`,
 1 AS `supplier_description`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `rel_wrhs_has_prdc`
--

DROP TABLE IF EXISTS `rel_wrhs_has_prdc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_wrhs_has_prdc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `mov_row_id` int(11) DEFAULT NULL,
  `wrhs_lot_id` int(11) DEFAULT NULL,
  `wrhs_status_id` int(11) DEFAULT NULL,
  `wrhs_status_year_id` int(11) DEFAULT NULL,
  `wrhs_stocktaking_id` int(11) DEFAULT NULL,
  `wrhs_warehouse_id` int(11) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_warehouse_has_product_prdc_item1_idx` (`item_id`),
  KEY `fk_warehouse_has_product_wrhs_mov_row1_idx` (`mov_row_id`),
  KEY `fk_warehouse_has_product_wrhs_lot1_idx` (`wrhs_lot_id`),
  KEY `fk_rel_wrhs_has_prdc_wrhs_status1_idx` (`wrhs_status_id`),
  KEY `fk_rel_wrhs_has_prdc_wrhs_status_year1_idx` (`wrhs_status_year_id`),
  KEY `fk_rel_wrhs_has_prdc_wrhs_stocktaking1_idx` (`wrhs_stocktaking_id`),
  KEY `fk_rel_wrhs_has_prdc_wrhs_warehouse1_idx` (`wrhs_warehouse_id`),
  CONSTRAINT `fk_rel_wrhs_has_prdc_wrhs_status1` FOREIGN KEY (`wrhs_status_id`) REFERENCES `wrhs_status` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_wrhs_has_prdc_wrhs_status_year1` FOREIGN KEY (`wrhs_status_year_id`) REFERENCES `wrhs_status_year` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_wrhs_has_prdc_wrhs_stocktaking1` FOREIGN KEY (`wrhs_stocktaking_id`) REFERENCES `wrhs_stocktaking` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_rel_wrhs_has_prdc_wrhs_warehouse1` FOREIGN KEY (`wrhs_warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_warehouse_has_product_prdc_item1` FOREIGN KEY (`item_id`) REFERENCES `prdc_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_warehouse_has_product_wrhs_lot1` FOREIGN KEY (`wrhs_lot_id`) REFERENCES `wrhs_lot` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_warehouse_has_product_wrhs_mov_row1` FOREIGN KEY (`mov_row_id`) REFERENCES `wrhs_mov_row` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_wrhs_has_prdc`
--

LOCK TABLES `rel_wrhs_has_prdc` WRITE;
/*!40000 ALTER TABLE `rel_wrhs_has_prdc` DISABLE KEYS */;
INSERT INTO `rel_wrhs_has_prdc` VALUES (190,314,173,NULL,NULL,NULL,NULL,4,0,'2021-11-19 10:12:30'),(191,314,NULL,NULL,55,NULL,NULL,4,0,'2021-11-19 10:12:30'),(192,314,174,NULL,NULL,NULL,NULL,4,0,'2021-11-19 10:45:51');
/*!40000 ALTER TABLE `rel_wrhs_has_prdc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_wrhs_row_has_dcmt_row`
--

DROP TABLE IF EXISTS `rel_wrhs_row_has_dcmt_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_wrhs_row_has_dcmt_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dcmt_document_row_id` int(11) NOT NULL,
  `wrhs_mov_row_id` int(11) NOT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wrhs_mov_row_id_UNIQUE` (`wrhs_mov_row_id`),
  KEY `fk_table1_dcmt_document_row1_idx` (`dcmt_document_row_id`),
  KEY `fk_table1_wrhs_mov_row1_idx` (`wrhs_mov_row_id`),
  CONSTRAINT `fk_table1_dcmt_document_row1` FOREIGN KEY (`dcmt_document_row_id`) REFERENCES `dcmt_document_row` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_wrhs_mov_row1` FOREIGN KEY (`wrhs_mov_row_id`) REFERENCES `wrhs_mov_row` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_wrhs_row_has_dcmt_row`
--

LOCK TABLES `rel_wrhs_row_has_dcmt_row` WRITE;
/*!40000 ALTER TABLE `rel_wrhs_row_has_dcmt_row` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_wrhs_row_has_dcmt_row` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(100) DEFAULT NULL,
  `smtp_port` varchar(4) DEFAULT NULL,
  `smtp_auth` tinyint(1) DEFAULT NULL,
  `smtp_username` varchar(100) DEFAULT NULL,
  `smtp_password` varchar(100) DEFAULT NULL,
  `smtp_from` varchar(100) DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_rate`
--

DROP TABLE IF EXISTS `tax_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) NOT NULL,
  `code` varchar(16) DEFAULT NULL,
  `note` text,
  `rate` decimal(6,2) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `kind_id` int(11) DEFAULT NULL,
  `normative_reference` varchar(100) DEFAULT NULL,
  `default_amazon` tinyint(1) DEFAULT '0',
  `deductibility_percent` decimal(6,2) NOT NULL DEFAULT '100.00',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modified_bt_idx` (`modified_by`),
  KEY `created_by_idx` (`created_by`),
  KEY `assigned_idx` (`assigned`),
  KEY `fk_taxrate_taxratekind_id_idx` (`kind_id`),
  CONSTRAINT `fk_taxrate_taxratekind_id` FOREIGN KEY (`kind_id`) REFERENCES `tax_rate_kind` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_rate`
--

LOCK TABLES `tax_rate` WRITE;
/*!40000 ALTER TABLE `tax_rate` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_rate_kind`
--

DROP TABLE IF EXISTS `tax_rate_kind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_rate_kind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(4) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_rate_kind`
--

LOCK TABLES `tax_rate_kind` WRITE;
/*!40000 ALTER TABLE `tax_rate_kind` DISABLE KEYS */;
INSERT INTO `tax_rate_kind` VALUES (1,'N1','escluse ex art. 15',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2019-05-22 09:00:00'),(2,'N2','non soggette',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-23 14:41:36'),(3,'N3','non imponibili',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:22'),(4,'N4','esenti',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-23 14:41:44'),(5,'N5','regime del margine / IVA non esposta in fattura',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2019-05-22 09:00:00'),(6,'N6','inversione contabile (per le operazioni in reverse charge ovvero nei casi di autofatturazione per acquisti extra UE di servizi ovvero per importazioni di beni nei soli casi previsti)',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2019-05-22 09:00:00'),(7,'N7','IVA assolta in altro stato UE (vendite a distanza ex art. 40 c. 3 e 4 e art. 41 c. 1 lett. b, DL 331/93; prestazione di servizi di telecomunicazioni, tele-radiodiffusione ed elettronici ex art. 7-sexies lett f,g, art. 74-sexies DPR 633/72)',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-09-24 07:37:23'),(8,'N2.1','non soggette ad IVA ai sensi degli artt. da 7 a 7-septies del DPR 633/72',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(9,'N2.2','non soggette - altri casi',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(10,'N3.3','non imponibili - esportazioni',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-09-11 07:12:12'),(11,'N3.2','non imponibili - cessioni intracomunitarie',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(12,'N3.3','non imponibili - cessioni verso San Marino',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(13,'N3.4','non imponibili - operazioni assimilate alle cessioni all\'esportazione',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(14,'N3.5','non imponibili - a seguito di dichiarazioni d\'intento',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(15,'N3.6','non imponibili - altre operazioni che non concorrono alla formazione del plafond',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(16,'N6.1','inversione contabile - cessione di rottami e altri materiali di recupero',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(17,'N6.2','inversione contabile - cessione di oro e argento puro',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(18,'N6.3','inversione contabile - subappalto nel settore edile',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(19,'N6.4','inversione contabile - cessione di fabbricati',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(20,'N6.5','inversione contabile - cessione di telefoni cellulari',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(21,'N6.6','inversione contabile - cessione di prodotti elettronici',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(22,'N6.7','inversione contabile - prestazioni comparto edile e settori connessi',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(23,'N6.8','inversione contabile - operazioni settore energetico',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-09-24 07:37:32'),(24,'N6.9','inversione contabile - altri casi',1,NULL,NULL,'2019-05-22 09:00:00','2019-05-22 09:00:00',NULL,0,'2020-07-24 16:06:18'),(26,'test','test',1,1,NULL,'2020-09-24 07:36:26','2020-09-24 07:36:26',NULL,1,'2020-09-24 07:38:07'),(27,'test','test\'test nat\'ure',1,1,NULL,'2020-10-12 15:45:46','2020-10-12 15:45:46',NULL,1,'2020-10-12 15:46:05');
/*!40000 ALTER TABLE `tax_rate_kind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wrhs_lot`
--

DROP TABLE IF EXISTS `wrhs_lot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_lot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `item_code` text,
  `item_description` text,
  `lot` text,
  `description` text,
  `expiration_date` date DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `existence` decimal(18,6) DEFAULT NULL,
  `prepared` decimal(18,6) DEFAULT NULL,
  `engaged` decimal(18,6) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lot_create_by_idx` (`created_by`),
  KEY `fk_lot_modified_by_idx` (`modified_by`),
  KEY `fk_lot_assigned_idx` (`assigned`),
  CONSTRAINT `fk_lot_assigned` FOREIGN KEY (`assigned`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lot_create_by` FOREIGN KEY (`created_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lot_modified_by` FOREIGN KEY (`modified_by`) REFERENCES `core_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_lot`
--

LOCK TABLES `wrhs_lot` WRITE;
/*!40000 ALTER TABLE `wrhs_lot` DISABLE KEYS */;
INSERT INTO `wrhs_lot` VALUES (22,'BAA34C1EC0994C10B3B26C14004EE27E','20001562','Boccette 55ml','202101BOCC',NULL,NULL,0,-72000.000000,NULL,NULL,1,1,1,'2021-06-07 15:45:35','2021-06-07 15:45:35',NULL,0,'2021-06-07 16:06:55'),(23,'0D6CA5A1C0344DA5AB40FC2F69526167','20001562','Boccette 55ml','202101BOCC',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-06-07 15:46:26','2021-06-07 15:46:26',NULL,0,'2021-06-07 16:18:17'),(24,'e8088a5dfbb3b609c00a640aadd489ff','20001562','Boccette 55ml','1566',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 07:51:47','2021-07-08 07:51:47',NULL,0,'2021-07-08 07:52:24'),(25,'787b95c1c5909ddd002e9dfde84ba17e','20001562','Boccette 55ml','1566',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 07:52:24','2021-07-08 07:52:24',NULL,0,'2021-07-08 07:52:31'),(26,'295ce9e4a91d5e779765a8ab5c491ee5','20001562','Boccette 55ml','1566',NULL,NULL,0,1200.000000,NULL,NULL,1,1,1,'2021-07-08 07:52:31','2021-07-08 07:52:31',NULL,0,'2021-07-08 07:52:31'),(27,'ec697cf80b6b3f583e850b6ddb2b51b8','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','123',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 08:33:14','2021-07-08 08:33:14',NULL,0,'2021-07-08 08:33:35'),(28,'9855deb8b2f5cc3900733e4ecb8e5964','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','1234',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 08:36:14','2021-07-08 08:36:14',NULL,0,'2021-07-08 08:36:21'),(29,'26bc4b37d98eb6d806fc7dda2d4cf36c','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','1234',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 08:36:21','2021-07-08 08:36:21',NULL,0,'2021-07-08 08:36:27'),(30,'b463d72f68ee5c62914336fa50d5046b','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','132',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 08:39:14','2021-07-08 08:39:14',NULL,0,'2021-07-08 08:39:31'),(31,'73f987bb3713ee8e103cfebd50bf8997','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','123',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 08:40:48','2021-07-08 08:40:48',NULL,0,'2021-07-08 09:15:39'),(32,'d706c156c18980839fbc7117faa124cd','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','123',NULL,NULL,0,0.000000,NULL,NULL,1,1,1,'2021-07-08 09:15:39','2021-07-08 09:15:39',NULL,0,'2021-07-08 09:15:59'),(33,'07578972446a11d73cfdcc327e55b71c','000_431-abc/B','Bottiglia 0.5 Lt Tritan Verde - ri-Modificata - Bianco','123',NULL,NULL,0,2025000000.000000,NULL,NULL,1,1,1,'2021-07-08 09:15:59','2021-07-08 09:15:59',NULL,0,'2021-07-08 09:15:59'),(34,'9f9944311fdbd267ab31adc81b97cd8f','GEL250PVC/N','Gel 250 ml PVC Neutro','1985236',NULL,NULL,0,955.000000,NULL,NULL,1,1,1,'2021-07-14 15:49:59','2021-07-14 15:49:59',NULL,0,'2021-07-14 15:50:48');
/*!40000 ALTER TABLE `wrhs_lot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wrhs_mov_causal`
--

DROP TABLE IF EXISTS `wrhs_mov_causal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_mov_causal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `code` varchar(4) NOT NULL,
  `description` varchar(40) DEFAULT NULL,
  `existence_multiplier` int(11) DEFAULT NULL COMMENT 'Valore: \n1 : Incremente\n-1 : Decremente\n0: Ignora',
  `mov_causal_linked_id` int(11) DEFAULT NULL,
  `warehouse_linked_id` int(11) DEFAULT NULL,
  `movement_type` int(11) DEFAULT NULL COMMENT '0 normale\n10 apertura inventario\n20 chiusura inventario\n',
  `proposed_price` int(11) DEFAULT NULL COMMENT 'Valori:\n10: Costo standard\n20: Listino acq\n30: Listino vend\n40: Prezzo acq\n50: Ultimo prezzo vend',
  `third_parties` tinyint(1) DEFAULT NULL,
  `update_price_list` int(11) DEFAULT NULL COMMENT 'Valori:\n0: Nessuno\n10: Standard\n20: Listino acquisti\n30: Listino fornitori\n\n\n',
  `existence_check` tinyint(1) DEFAULT NULL,
  `statistical_movement_type` varchar(45) DEFAULT NULL COMMENT 'Valori:\napertura inventario, \nacquisti, \nresi clienti. \ncariohi produzione, \nentrate varie, \nchiusura inventario, \nvendite, \nresi fornitori, \nscarichi produzione,\nuscite varie, \nNessuno',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_mov_causal_wrhs_warehouse1_idx` (`warehouse_id`),
  KEY `fk_wrhs_mov_causal_wrhs_warehouse2_idx` (`warehouse_linked_id`),
  KEY `fk_wrhs_mov_causal_wrhs_mov_causal1_idx` (`mov_causal_linked_id`),
  CONSTRAINT `fk_wrhs_mov_causal_wrhs_mov_causal1` FOREIGN KEY (`mov_causal_linked_id`) REFERENCES `wrhs_mov_causal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_causal_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_causal_wrhs_warehouse2` FOREIGN KEY (`warehouse_linked_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_mov_causal`
--

LOCK TABLES `wrhs_mov_causal` WRITE;
/*!40000 ALTER TABLE `wrhs_mov_causal` DISABLE KEYS */;
INSERT INTO `wrhs_mov_causal` VALUES (7,'1D6A5F70603B473A8A190AA4A52AB6B6',4,'CAR','Carico Magazzino',1,NULL,NULL,0,0,0,NULL,0,NULL,1,1,1,'2021-06-07 12:54:22',NULL,0,'2021-06-07 12:54:22'),(8,'48B8E0BC24EE46DAB507A47BCCB3BD2C',4,'SCA','Scarico Magazzino',-1,NULL,NULL,0,0,0,NULL,0,NULL,1,1,1,'2021-06-07 12:54:22',NULL,0,'2021-06-07 12:54:22'),(9,'8D975574861B423289BDC79FB456E932',5,'CAR','Carico Magazzino',1,NULL,NULL,0,0,0,NULL,0,NULL,1,1,1,'2021-06-07 12:54:29',NULL,0,'2021-06-07 12:54:29'),(10,'1CFFD4C2FF8A40E7A42A9759B7A73615',5,'SCA','Scarico Magazzino',-1,NULL,NULL,0,0,0,NULL,0,NULL,1,1,1,'2021-06-07 12:54:29',NULL,0,'2021-06-07 12:54:29');
/*!40000 ALTER TABLE `wrhs_mov_causal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wrhs_mov_head`
--

DROP TABLE IF EXISTS `wrhs_mov_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_mov_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `mov_causal_id` int(11) NOT NULL,
  `progressive` int(11) NOT NULL,
  `warehouse_linked_id` int(11) DEFAULT NULL,
  `mov_causal_linked` int(11) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `document_number` varchar(20) DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `customer_supplier_type` varchar(1) DEFAULT NULL COMMENT 'S = Supplier; C=Customer',
  `customer_supplier_code` varchar(8) DEFAULT NULL,
  `newspaper_print` tinyint(1) DEFAULT NULL,
  `year_operation` varchar(4) DEFAULT NULL,
  `currency_code` varchar(4) DEFAULT NULL,
  `currency_change` decimal(18,6) DEFAULT NULL,
  `document_type` varchar(20) DEFAULT NULL,
  `total_quantity` decimal(18,6) DEFAULT NULL,
  `total_document_amount` decimal(18,6) DEFAULT NULL,
  `source_document` varchar(30) DEFAULT NULL COMMENT 'Descrizione tipologia documento',
  `progressive_source_document` int(11) DEFAULT NULL,
  `note` mediumtext,
  `movement_type` varchar(20) DEFAULT NULL COMMENT 'Chiusura inventario; Normale; Apertura inventario',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_mov_head_wrhs_warehouse1_idx` (`warehouse_id`),
  KEY `fk_wrhs_mov_head_wrhs_mov_causal1_idx` (`mov_causal_id`),
  KEY `fk_wrhs_mov_head_wrhs_mov_causal2_idx` (`mov_causal_linked`),
  KEY `fk_wrhs_mov_head_wrhs_warehouse2_idx` (`warehouse_linked_id`),
  CONSTRAINT `fk_wrhs_mov_head_wrhs_mov_causal1` FOREIGN KEY (`mov_causal_id`) REFERENCES `wrhs_mov_causal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_head_wrhs_mov_causal2` FOREIGN KEY (`mov_causal_linked`) REFERENCES `wrhs_mov_causal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_head_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_head_wrhs_warehouse2` FOREIGN KEY (`warehouse_linked_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_mov_head`
--

LOCK TABLES `wrhs_mov_head` WRITE;
/*!40000 ALTER TABLE `wrhs_mov_head` DISABLE KEYS */;
INSERT INTO `wrhs_mov_head` VALUES (72,'75dc531f1dec2824d4d88a9c2e506ff2',4,7,210910,NULL,NULL,'2021-09-10',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,43,43,43,'2021-09-10 07:02:59','2021-09-10 07:02:59',NULL,0,'2021-09-10 09:02:59'),(73,'d2fc7bbc9482493e623639f79daec4c2',4,7,211119,NULL,NULL,'2021-11-19',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2021-11-19 10:11:35','2021-11-19 10:11:35',NULL,0,'2021-11-19 10:11:35'),(74,'5da10cdf170556c648b66d52fe08f8f0',4,8,211119,NULL,NULL,'2021-11-19',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2021-11-19 10:45:34','2021-11-19 10:45:34',NULL,0,'2021-11-19 10:45:34');
/*!40000 ALTER TABLE `wrhs_mov_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `wrhs_mov_head_v`
--

DROP TABLE IF EXISTS `wrhs_mov_head_v`;
/*!50001 DROP VIEW IF EXISTS `wrhs_mov_head_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `wrhs_mov_head_v` AS SELECT 
 1 AS `id`,
 1 AS `oid`,
 1 AS `warehouse_id`,
 1 AS `mov_causal_id`,
 1 AS `existence_multiplier`,
 1 AS `warehouse_name`,
 1 AS `causal_description`,
 1 AS `progressive`,
 1 AS `warehouse_linked_id`,
 1 AS `mov_causal_linked`,
 1 AS `registration_date`,
 1 AS `document_number`,
 1 AS `document_date`,
 1 AS `customer_supplier_type`,
 1 AS `customer_supplier_code`,
 1 AS `newspaper_print`,
 1 AS `year_operation`,
 1 AS `currency_code`,
 1 AS `currency_change`,
 1 AS `document_type`,
 1 AS `total_quantity`,
 1 AS `total_document_amount`,
 1 AS `source_document`,
 1 AS `progressive_source_document`,
 1 AS `note`,
 1 AS `movement_type`,
 1 AS `modified_by`,
 1 AS `created_by`,
 1 AS `assigned`,
 1 AS `create_date`,
 1 AS `valid_from`,
 1 AS `valid_to`,
 1 AS `erased`,
 1 AS `last_update`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wrhs_mov_row`
--

DROP TABLE IF EXISTS `wrhs_mov_row`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_mov_row` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `mov_head_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `progressive` int(11) NOT NULL,
  `row` int(11) DEFAULT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `item_description` text,
  `quantity` decimal(18,6) DEFAULT NULL,
  `unit_price` decimal(18,6) DEFAULT NULL,
  `incoming_quantity` decimal(18,6) DEFAULT NULL,
  `outgoing_quantity` decimal(18,6) DEFAULT NULL,
  `movement_type` varchar(45) DEFAULT NULL,
  `note` mediumtext,
  `source_document_row` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_mov_row_wrhs_mov_head1_idx` (`mov_head_id`),
  KEY `fk_wrhs_mov_row_wrhs_warehouse1_idx` (`warehouse_id`),
  CONSTRAINT `fk_wrhs_mov_row_wrhs_mov_head1` FOREIGN KEY (`mov_head_id`) REFERENCES `wrhs_mov_head` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_wrhs_mov_row_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_mov_row`
--

LOCK TABLES `wrhs_mov_row` WRITE;
/*!40000 ALTER TABLE `wrhs_mov_row` DISABLE KEYS */;
INSERT INTO `wrhs_mov_row` VALUES (173,'69a59213b76551b0ee37b5ff1705388c',73,4,211119,NULL,NULL,NULL,1.000000,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2021-11-19 10:12:30','2021-11-19 10:12:30',NULL,0,'2021-11-19 10:12:30'),(174,'57edd224c2567b0dd329e99c679405ac',74,4,211119,NULL,NULL,NULL,1.000000,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2021-11-19 10:45:50','2021-11-19 10:45:50',NULL,0,'2021-11-19 10:45:50');
/*!40000 ALTER TABLE `wrhs_mov_row` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `wrhs_mov_row_v`
--

DROP TABLE IF EXISTS `wrhs_mov_row_v`;
/*!50001 DROP VIEW IF EXISTS `wrhs_mov_row_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `wrhs_mov_row_v` AS SELECT 
 1 AS `mov_row_id`,
 1 AS `note`,
 1 AS `quantity`,
 1 AS `item_id`,
 1 AS `pieces_per_pack`,
 1 AS `pack_per_pallet`,
 1 AS `item_description`,
 1 AS `erased`,
 1 AS `mov_head_id`,
 1 AS `lot_code`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wrhs_status`
--

DROP TABLE IF EXISTS `wrhs_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `item_description` text,
  `minimum_stock` decimal(18,6) DEFAULT NULL,
  `maximum_stock` decimal(18,6) DEFAULT NULL,
  `engaged` decimal(18,6) DEFAULT NULL COMMENT 'quantita ordinata da un cliente che impegna la merce',
  `ordered` decimal(18,6) DEFAULT NULL COMMENT 'quantita ordinata al fornitore che dovrebbe arrivare',
  `existence` decimal(18,6) DEFAULT NULL,
  `reorder_point` decimal(18,6) DEFAULT NULL COMMENT 'https://it.wikipedia.org/wiki/Reorder_point',
  `prepared` decimal(18,6) DEFAULT NULL COMMENT 'stato approntato per conferma la merce per la spedizione, la confera della quantoità approntta genera un ddt in stato ancora da evadere ',
  `load_price` decimal(18,6) DEFAULT NULL,
  `unload_price` decimal(18,6) DEFAULT NULL,
  `gross_load_price` decimal(18,6) DEFAULT NULL,
  `gross_unload_price` decimal(18,6) DEFAULT NULL,
  `vat_load_percentage` decimal(18,6) DEFAULT NULL,
  `var_unload_percentage` decimal(18,6) DEFAULT NULL,
  `load_date` datetime DEFAULT NULL,
  `unload_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_status_wrhs_warehouse1_idx` (`warehouse_id`),
  CONSTRAINT `fk_wrhs_status_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_status`
--

LOCK TABLES `wrhs_status` WRITE;
/*!40000 ALTER TABLE `wrhs_status` DISABLE KEYS */;
INSERT INTO `wrhs_status` VALUES (55,'748962c3114f10800005857fb9e1179f',4,NULL,NULL,0.000000,NULL,NULL,NULL,0.000000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2021-11-19 10:12:30','2021-11-19 10:12:30',NULL,0,'2021-11-19 10:45:51');
/*!40000 ALTER TABLE `wrhs_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `wrhs_status_v`
--

DROP TABLE IF EXISTS `wrhs_status_v`;
/*!50001 DROP VIEW IF EXISTS `wrhs_status_v`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `wrhs_status_v` AS SELECT 
 1 AS `warehouse_id`,
 1 AS `warehouse_name`,
 1 AS `item_id`,
 1 AS `item_description`,
 1 AS `status_id`,
 1 AS `item_existence`,
 1 AS `lot`,
 1 AS `tot_existence`,
 1 AS `measure_unit_id`,
 1 AS `pieces_per_pack`,
 1 AS `pack_per_pallet`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wrhs_status_year`
--

DROP TABLE IF EXISTS `wrhs_status_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_status_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `wrhs_status_yearcol` varchar(45) DEFAULT NULL,
  `year` int(11) DEFAULT NULL COMMENT 'ANNO ESERCIZIO',
  `opening_quantity` decimal(18,6) DEFAULT NULL,
  `opening_value` decimal(18,6) DEFAULT NULL,
  `closing_quantity` decimal(18,6) DEFAULT NULL,
  `closing_value` decimal(18,6) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_status_year_wrhs_warehouse1_idx` (`warehouse_id`),
  CONSTRAINT `fk_wrhs_status_year_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_status_year`
--

LOCK TABLES `wrhs_status_year` WRITE;
/*!40000 ALTER TABLE `wrhs_status_year` DISABLE KEYS */;
/*!40000 ALTER TABLE `wrhs_status_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wrhs_stocktaking`
--

DROP TABLE IF EXISTS `wrhs_stocktaking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_stocktaking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `existence` decimal(18,6) DEFAULT NULL COMMENT 'QUANTITA CHE OPERATORE DEVE SPECIFICARE DURANTE L''INVENTARIO',
  `theoretical_existence` decimal(18,6) DEFAULT NULL,
  `managed` tinyint(1) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wrhs_stocktaking_wrhs_warehouse1_idx` (`warehouse_id`),
  CONSTRAINT `fk_wrhs_stocktaking_wrhs_warehouse1` FOREIGN KEY (`warehouse_id`) REFERENCES `wrhs_warehouse` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_stocktaking`
--

LOCK TABLES `wrhs_stocktaking` WRITE;
/*!40000 ALTER TABLE `wrhs_stocktaking` DISABLE KEYS */;
/*!40000 ALTER TABLE `wrhs_stocktaking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wrhs_warehouse`
--

DROP TABLE IF EXISTS `wrhs_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wrhs_warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(32) DEFAULT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `address` varchar(60) DEFAULT NULL,
  `zip` varchar(5) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `province` varchar(2) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `property` varchar(2) DEFAULT NULL COMMENT 'Se è di proprietà oppure di altra azienda',
  `property_business_name` varchar(100) DEFAULT NULL,
  `property_vat` varchar(28) DEFAULT NULL,
  `property_fiscal_code` varchar(16) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `erased` tinyint(1) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wrhs_warehouse`
--

LOCK TABLES `wrhs_warehouse` WRITE;
/*!40000 ALTER TABLE `wrhs_warehouse` DISABLE KEYS */;
INSERT INTO `wrhs_warehouse` VALUES (4,'F068F8F5259C4D8CB6CD0B30EC3BBB49','CASELLE01','Magazzino 1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-06-07 12:54:22','2021-06-07 12:54:22',NULL,0,'2021-06-07 13:08:38'),(5,'C137E1C00509472DA0795A57445527C4','CASELLE02','Magazzino 2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2021-06-07 12:54:29','2021-06-07 12:54:29',NULL,0,'2021-06-07 13:08:45');
/*!40000 ALTER TABLE `wrhs_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'inaf'
--

--
-- Dumping routines for database 'inaf'
--
/*!50003 DROP PROCEDURE IF EXISTS `dcmt_electronic_invoicing` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `dcmt_electronic_invoicing`(IN invoice_id INT)
BEGIN


declare documentCode VARCHAR(8) DEFAULT '';
declare discountAmount DECIMAL(20,4) DEFAULT NULL;
declare surchargeAmount DECIMAL(20,4) DEFAULT NULL;
declare bodyId INT DEFAULT 0;
declare headerId INT DEFAULT 0;
declare documentTotal DECIMAL (15,4) DEFAULT NULL;
declare documentCausal TEXT DEFAULT '';
declare tmpCausal TEXT DEFAULT '';
declare purchaseOrder VARCHAR(20) DEFAULT NULL;
declare receiverType VARCHAR(5) DEFAULT '';
declare receiverSdi  VARCHAR(7) DEFAULT NULL;
declare receiverPec  VARCHAR(256) DEFAULT NULL;
declare itIdPaese VARCHAR(2);
declare itIdCodice VARCHAR(28);
declare cpRegimeFiscale VARCHAR(4);
declare senderVatNumber VARCHAR(28);
declare senderFiscalCode VARCHAR(16);
declare senderBusinessName VARCHAR(128);
declare senderName VARCHAR(128);
declare senderLastname VARCHAR(128);
declare senderRegisteredStreet VARCHAR(60);
declare senderRegisteredNumber VARCHAR(8);
declare senderRegisteredZip VARCHAR(5);
declare senderRegisteredCity VARCHAR(60);
declare senderRegisteredProvince VARCHAR(2);
declare senderRegisteredCountry VARCHAR(2);
declare receiverVatNumber VARCHAR(28);
declare receiverFiscalCode VARCHAR(16);
declare receiverBusinessName VARCHAR(128);
declare receiverName VARCHAR(128);
declare receiverLastname VARCHAR(128);
declare receiverRegisteredStreet VARCHAR(60);
declare receiverRegisteredNumber VARCHAR(8);
declare receiverRegisteredZip VARCHAR(5);
declare receiverRegisteredCity VARCHAR(60);
declare receiverRegisteredProvince VARCHAR(2);
declare receiverRegisteredCountry VARCHAR(2);
declare documentCurrency VARCHAR(3);
declare documentDate DATETIME;
declare documentNumber VARCHAR(20);

declare rowId INT;
declare rowDiscount DECIMAL(17,2);
declare rowDiscountPercentage DECIMAL(6,2);
declare rowAddedDiscount DECIMAL(17,2);
declare rowAddedDiscountPercentage DECIMAL(6,2);
declare rowSurcharge DECIMAL(17,2);
declare rowSurchargePercentage DECIMAL(6,2);
declare rowAddedSurcharge DECIMAL(17,2);
declare rowAddedSurchargePercentage DECIMAL(6,2);

declare rowNumber INT DEFAULT 0;
declare rowDescription VARCHAR(1000) DEFAULT '';
declare rowQuantity DECIMAL(20,8) DEFAULT 0;
declare rowUnitMeasure VARCHAR(10) DEFAULT '';
declare rowUnitPrice DECIMAL(23, 8) DEFAULT 0;
declare taxableAmount DECIMAL(23,8);
declare sellTaxRate DECIMAL(8,2);
declare sellTaxKind VARCHAR(2);
declare externalCode VARCHAR(35);
declare administrationReference VARCHAR(20);

declare summaryTaxRate DECIMAL(8,2);
declare summaryTaxKind VARCHAR(2);
declare summaryTaxableAmount DECIMAL(17,2);
declare summaryTaxAmount DECIMAL(17,2);
declare summaryCollectability VARCHAR(1);
declare summaryNormativeReference VARCHAR(100);

declare paymentMode VARCHAR(4);
declare paymentAmount DECIMAL(17,2);
declare paymentRows INT;
declare paymentId INT;

declare parentInvoice VARCHAR(20);

declare documentYear VARCHAR(4);
declare documentType VARCHAR(1);
declare progressiveNumber INT;

DECLARE purchaseOrderId INT DEFAULT 0;
DECLARE poRow INT DEFAULT 0;

DECLARE linkedInvoiceId INT DEFAULT 0;
DECLARE linkedInvoiceRow INT DEFAULT 0;

DECLARE transmitter_country	VARCHAR(2);
DECLARE transmitter_code	VARCHAR(28);
DECLARE fiscal_regime	VARCHAR(4);


DECLARE rowCursor CURSOR FOR
SELECT row_number, description, quantity, measure_unit, unit_price, discount, discount_percentage, added_discount, added_discount_percentage, surcharge,
surcharge_percentage, added_surcharge, added_surcharge_percentage, taxable_amount, sell_tax_rate, sell_tax_kind, external_code, IF(document_type = 'N', IF(code = 'INVCA4',return_number,credit_application), NULL)
FROM dcmt_document_row WHERE document_id = invoice_id and erased = 0;

DECLARE taxCursor CURSOR FOR
SELECT tax_rate, tax_kind, taxable_amount, tax_amount, collectability, normative_reference
FROM dcmt_document_total_tax WHERE document_id = invoice_id;

DECLARE paymentCursor CURSOR FOR
SELECT payment_mode, amount
FROM dcmt_document_installment WHERE document_id = invoice_id;


DECLARE purchaseOrderCursor CURSOR FOR
SELECT DISTINCT IF(code = 'INVCA3',credit_application,document_purchase_order)
FROM dcmt_document_row WHERE document_id = invoice_id;

DECLARE parentInvoiceCursor CURSOR FOR
SELECT DISTINCT d.number
FROM dcmt_document_row r JOIN dcmt_document d on r.parent_invoice_id = d.id WHERE r.document_id = invoice_id;



DELETE FROM einv_inv_header WHERE id IN (SELECT einv_id FROM rel_dcmt_has_einv WHERE document_id = invoice_id);


/*

Travaso name_company_custom_parameter -> owned company

*/



SELECT parameter_value INTO transmitter_country
FROM name_company_custom_parameter 
WHERE parameter_name = 'transmitter_country' and company_role = 'O' and erased = 0;

SELECT parameter_value INTO transmitter_code
FROM name_company_custom_parameter 
WHERE parameter_name = 'transmitter_code' and company_role = 'O' and erased = 0;

SELECT parameter_value INTO fiscal_regime
FROM name_company_custom_parameter 
WHERE parameter_name = 'fiscal_regime' and company_role = 'O' and erased = 0;



delete from einv_config;
insert into einv_config values(null,null,'it_idpaese',transmitter_country,now());
insert into einv_config values(null,null,'it_idcodice',transmitter_code,now());
insert into einv_config values(null,null,'cp_regime_fiscale',fiscal_regime,now());


SELECT COUNT(*) INTO paymentRows
FROM dcmt_document_installment WHERE document_id = invoice_id;



SELECT code, discount_amount, surcharge_amount, document_total, IF(code = 'INVCA3','QPD',IF(code = 'INVCA4','VRET',IF(code = 'INVCA5','CCOGS',causal))), purchase_order, receiver_type, receiver_sdi, receiver_pec, 
sender_registered_country, sender_vat_number, sendet_fiscal_code, sender_business_name, sender_name, sender_lastname, sender_registered_street,
sender_registered_number, sender_registered_zip, sender_registered_city, sender_registered_province, receiver_vat_number, receiver_fiscal_code, 
receiver_business_name, receiver_name, receiver_lastname, receiver_registered_street, receiver_registered_number, receiver_registered_zip, 
receiver_registered_city, receiver_registered_province, receiver_registered_country, currency, date, number, year, document_type
into documentCode, discountAmount, surchargeAmount, documentTotal, documentCausal, purchaseOrder, receiverType, receiverSdi, receiverPec, 
senderRegisteredCountry, senderVatNumber, senderFiscalCode, senderBusinessName, senderName, senderLastname, senderRegisteredStreet, 
senderRegisteredNumber, senderRegisteredZip, senderRegisteredCity, senderRegisteredProvince, receiverVatNumber, receiverFiscalCode, 
receiverBusinessName, receiverName, receiverLastname, receiverRegisteredStreet, receiverRegisteredNumber, receiverRegisteredZip, 
receiverRegisteredCity, receiverRegisteredProvince, receiverRegisteredCountry, documentCurrency, documentDate, documentNumber, documentYear, documentType
from dcmt_document where id = invoice_id;



SELECT progressivo INTO progressiveNumber
FROM progressive 
WHERE tipologia = documentType and anno = documentYear;



select parameter_value INTO itIdPaese from einv_config where parameter_name = 'it_idpaese';
select parameter_value INTO itIdCodice from einv_config where parameter_name = 'it_idcodice';
select parameter_value INTO cpRegimeFiscale from einv_config where parameter_name = 'cp_regime_fiscale';

INSERT INTO einv_inv_header (dt_it_IdPaese, dt_it_IdCodice, dt_ProgressivoInvio, dt_FormatoTrasmissione, dt_CodiceDestinatario, dt_PECDestinatario, 
cp_da_ifi_IdPaese, cp_da_ifi_IdCodice, cp_da_CodiceFiscale, cp_da_a_Denominazione, cp_da_a_Nome, cp_da_a_Cognome, cp_da_RegimeFiscale, cp_s_Indirizzo, cp_s_NumeroCivico, 
cp_s_CAP, cp_s_Comune, cp_s_Provincia, cp_s_Nazione, cc_da_ifi_IdPaese, cc_da_ifi_IdCodice, cc_da_CodiceFiscale, cc_da_a_Denominazione, cc_da_a_Nome, cc_da_a_Cognome, 
cc_s_Indirizzo, cc_s_NumeroCivico, cc_s_CAP, cc_s_Comune, cc_s_Provincia, cc_s_Nazione, erased, last_update)
 SELECT
itIdPaese, itIdCodice, IFNULL(progressiveNumber + 1, 1), receiverType, IFNULL(receiverSdi, '0000000'), IF(receiverSdi IS NULL, receiverPec, NULL), senderRegisteredCountry, 
senderVatNumber, senderFiscalCode, senderBusinessName, senderName, senderLastname, cpRegimeFiscale, senderRegisteredStreet, 
senderRegisteredNumber, senderRegisteredZip, senderRegisteredCity, senderRegisteredProvince, senderRegisteredCountry, SUBSTR(receiverVatNumber, 1, 2 ), 
SUBSTR(receiverVatNumber, 3, length(receiverVatNumber) - 1 ), receiverFiscalCode, receiverBusinessName, IF(receiverBusinessName is null, receiverName, null), IF(receiverBusinessName is null, receiverLastname, null), receiverRegisteredStreet, receiverRegisteredNumber, 
receiverRegisteredZip, receiverRegisteredCity, receiverRegisteredProvince, receiverRegisteredCountry, 0, now();

SELECT LAST_INSERT_ID() INTO headerId;


INSERT INTO progressive (tipologia, progressivo, anno, erased, last_update)
VALUES (documentType, IFNULL(progressiveNumber, 1), documentYear, 0, now())
ON DUPLICATE KEY UPDATE progressivo = IFNULL(progressiveNumber, 0) + 1;



INSERT INTO rel_dcmt_has_einv (document_id, einv_id) VALUES (invoice_id, headerId);


INSERT INTO einv_inv_body (id_einv_inv_header, dg_dgd_TipoDocumento, dg_dgd_Divisa, dg_dgd_Data, dg_dgd_Numero, dg_dgd_ImportoTotaleDocumento, erased, 
last_update)
SELECT headerId,(CASE 
    WHEN documentCode = 'INVS' THEN 'TD01'
    WHEN documentType = 'N' THEN 'TD04'
    ELSE null
    END
    ),
    documentCurrency,
    documentDate,
    documentNumber,
    documentTotal, 
    0, 
    now();
SELECT LAST_INSERT_ID() INTO bodyId;


OPEN rowCursor;
BEGIN
DECLARE empty_cursor INT DEFAULT 0;
DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET empty_cursor = 1;

get_row: LOOP
FETCH rowCursor INTO rowNumber, rowDescription, rowQuantity, rowUnitMeasure, rowUnitPrice, rowDiscount, rowDiscountPercentage, rowAddedDiscount, 
rowAddedDiscountPercentage, rowSurcharge, rowSurchargePercentage, rowAddedSurcharge, rowAddedSurchargePercentage, taxableAmount, sellTaxRate, sellTaxKind, 
externalCode, administrationReference;

IF empty_cursor = 1 THEN 
 LEAVE get_row;
 END IF;





INSERT INTO einv_inv_body_dati_beni_servizi (einv_inv_body_id, dl_NumeroLinea, dl_Descrizione, dl_Quantita, dl_UnitaMisura, dl_PrezzoUnitario, 
dl_PrezzoTotale, dl_AliquotaIVA, dl_Natura, dl_RiferimentoAmministrazione, erased, last_update)
SELECT bodyId, rowNumber, rowDescription, rowQuantity, rowUnitMeasure, rowUnitPrice, taxableAmount, sellTaxRate, sellTaxKind, administrationReference, 0, now();

SELECT LAST_INSERT_ID() INTO rowId;


INSERT INTO einv_inv_body_dbs_sconto_maggiorazione (einv_inv_body_dbs_id, Tipo, Importo, Percentuale, erased, last_update)
select rowId, 'SC', rowDiscount, rowDiscountPercentage, 0, now()
where (rowDiscount is not null and rowDiscountPercentage is not null) AND (rowDiscount <> 0 and rowDiscountPercentage <> 0); 
INSERT INTO einv_inv_body_dbs_sconto_maggiorazione (einv_inv_body_dbs_id, Tipo, Importo, Percentuale, erased, last_update)
select rowId, 'SC', rowAddedDiscount, rowAddedDiscountPercentage, 0, now()
where (rowAddedDiscount is not null and rowAddedDiscountPercentage is not null) AND (rowAddedDiscount <> 0 and rowAddedDiscountPercentage <> 0);
INSERT INTO einv_inv_body_dbs_sconto_maggiorazione (einv_inv_body_dbs_id, Tipo, Importo, Percentuale, erased, last_update)
select rowId, 'MG', rowSurcharge, rowSurchargePercentage, 0, now()
where (rowSurcharge is not null and rowSurchargePercentage is not null) AND (rowSurcharge <> 0 and rowSurchargePercentage <> 0);
INSERT INTO einv_inv_body_dbs_sconto_maggiorazione (einv_inv_body_dbs_id, Tipo, Importo, Percentuale, erased, last_update)
select rowId, 'MG', rowAddedSurcharge, rowAddedSurchargePercentage, 0, now()
where (rowAddedSurcharge is not null and rowAddedSurchargePercentage is not null) AND (rowAddedSurcharge <> 0 and rowAddedSurchargePercentage <> 0);




INSERT INTO einv_inv_body_dbs_codice_articolo (einv_inv_body_dbs_id, CodiceTipo, CodiceValore, erased, last_update)
SELECT rowId, 'ASIN', externalCode, 0, now()
WHERE externalCode is not null and (documentCode = 'INVS' or documentCode = 'INVCA2' or documentCode = 'INVCA4');


END LOOP get_row;
END;
CLOSE rowCursor;




INSERT INTO einv_inv_body_sconto_maggiorazione (einv_inv_body_id, Tipo, Importo, erased, last_update)
select bodyId, 'SC', discountAmount, 0, now()
where discountAmount is not null; 
INSERT INTO einv_inv_body_sconto_maggiorazione (einv_inv_body_id, Tipo, Importo, erased, last_update)
select bodyId, 'MG', surchargeAmount, 0, now()
where surchargeAmount is not null;



SELECT documentCausal INTO tmpCausal;
WHILE LENGTH(tmpCausal) > 0 DO		
	INSERT INTO einv_inv_body_causali(einv_inv_body_id, Causale, erased, last_update) VALUES(bodyId, SUBSTRING(tmpCausal, 1, 200), 0, now());
    SELECT SUBSTRING(tmpCausal, 201) INTO tmpCausal;
END WHILE;


OPEN taxCursor;
BEGIN
DECLARE empty_cursor INT DEFAULT 0;
DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET empty_cursor = 1;
get_tax: LOOP
FETCH taxCursor INTO summaryTaxRate, summaryTaxKind, summaryTaxableAmount, summaryTaxAmount, summaryCollectability, summaryNormativeReference;

IF empty_cursor = 1 THEN 
 LEAVE get_tax;
 END IF;



INSERT INTO einv_inv_body_dati_riepilogo (einv_inv_body_id, AliquotaIVA, Natura, ImponibileImporto, Imposta, EsigibilitaIVA,RiferimentoNormativo, erased, last_update)
SELECT bodyId, summaryTaxRate, summaryTaxKind, summaryTaxableAmount, summaryTaxAmount, summaryCollectability,summaryNormativeReference, 0, now();

END LOOP get_tax;
END;
CLOSE taxCursor;




INSERT INTO einv_inv_body_dati_pagamento (einv_inv_body_id, CondizioniPagamento, erased, last_update)
SELECT bodyId, IF(paymentRows = 1, 'TP02', IF(paymentRows > 1, 'TP01', '')), 0, now()
where paymentRows > 0;
SELECT LAST_INSERT_ID() INTO paymentId;


INSERT INTO einv_inv_dp_dettaglio_pagamento (einv_inv_body_dp_id, ModalitaPagamento, ImportoPagamento, DataScadenzaPagamento,IstitutoFinanziario, iban, abi, cab,bic, erased, last_update)
SELECT paymentId, payment_mode, amount,expiry_date, bank, iban, abi,cab,bic, 0,now()
FROM dcmt_document_installment WHERE document_id = invoice_id;




OPEN purchaseOrderCursor;
BEGIN
DECLARE empty_cursor INT DEFAULT 0;
DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET empty_cursor = 1;


get_purchase_order: LOOP
FETCH purchaseOrderCursor INTO purchaseOrder;

IF empty_cursor = 1 THEN 
 LEAVE get_purchase_order;
 END IF;
 
 



INSERT INTO einv_inv_body_dati_ordine_acquisto (einv_inv_body_id, IdDocumento, erased, last_update)
SELECT bodyId, purchaseOrder, 0, now()
WHERE purchaseOrder is not null and (documentCode = 'INVS' or documentCode = 'INVCA1' or documentCode = 'INVCA2' or documentCode = 'INVCA3');

SELECT LAST_INSERT_ID() INTO purchaseOrderId;



BLOCK2: BEGIN
		DECLARE empty_line INT DEFAULT 0;
        DECLARE poRowCursor CURSOR FOR SELECT row_number FROM dcmt_document_row where document_id = invoice_id and ((code = 'INVCA3' and credit_application = purchaseOrder) or document_purchase_order = purchaseOrder);
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET empty_line = 1;
        OPEN poRowCursor; 
        get_po_row: LOOP
        FETCH poRowCursor INTO poRow;   
            IF empty_line = 1 THEN 
 LEAVE get_po_row;
 END IF;
            INSERT INTO einv_inv_body_doa_riferimento_numero_linea(einv_inv_body_doa_id, RiferimentoNumeroLinea, erased, last_update)
                SELECT purchaseOrderId, poRow, 0, now()
                WHERE purchaseOrder is not null and (documentCode = 'INVS' or documentCode = 'INVCA1' or documentCode = 'INVCA2' or documentCode = 'INVCA3'); 
        END LOOP get_po_row;
        END BLOCK2;




END LOOP get_purchase_order;
END;
CLOSE purchaseOrderCursor;



OPEN parentInvoiceCursor;
BEGIN
DECLARE empty_cursor INT DEFAULT 0;
DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET empty_cursor = 1;
get_parent_invoice: LOOP
FETCH parentInvoiceCursor INTO parentInvoice;

IF empty_cursor = 1 THEN 
 LEAVE get_parent_invoice;
 END IF;



INSERT INTO einv_inv_body_dati_fatture_collegate (einv_inv_body_id, IdDocumento, erased, last_update)
SELECT bodyId, parentInvoice, 0, now()
WHERE parentInvoice is not null and (documentCode = 'INVCA1' or documentCode = 'INVCA2' or documentCode = 'INVCA3');

SELECT LAST_INSERT_ID() INTO linkedInvoiceId;

 
BLOCK2: BEGIN
		DECLARE empty_line INT DEFAULT 0;
        DECLARE invoiceRowCursor CURSOR FOR SELECT r.row_number FROM dcmt_document_row r JOIN dcmt_document d on r.parent_invoice_id = d.id WHERE r.document_id = invoice_id and d.number = parentInvoice;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET empty_line = 1;
        OPEN invoiceRowCursor; 
        get_li_row: LOOP
        FETCH invoiceRowCursor INTO linkedInvoiceRow;   
        		
            IF empty_line = 1 THEN 
 LEAVE get_li_row;
 END IF;
 
 
            INSERT INTO einv_inv_body_dfc_riferimento_numero_linea(einv_inv_body_dfc_id, RiferimentoNumeroLinea, erased, last_update)
                SELECT linkedInvoiceId, linkedInvoiceRow, 0, now()
                WHERE parentInvoice is not null and (documentCode = 'INVCA1' or documentCode = 'INVCA2' or documentCode = 'INVCA3'); 
        END LOOP get_li_row;
        END BLOCK2;

END LOOP get_parent_invoice;
END;
CLOSE parentInvoiceCursor;







END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `import_articoli_partial` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `import_articoli_partial`()
BEGIN
set @sql_safe_updates = 0;
drop table if exists import_articoli_temp;
create temporary table import_articoli_temp select * from IMPORT_ARTICOLI;
-- ALTER TABLE import_articoli_temp ADD INDEX `Codice` (`Codice` ASC);

set @count = (select count(*) from import_articoli_temp);
-- delete from `prdc_item_has_category`;

while (@count > 0) do

	set @codice = (select Codice from import_articoli_temp limit 1);
    
    set @ean = (select Codice_Produttore from import_articoli_temp where Codice = @codice limit 1);
    set @ean_att_id = 21;
    
	set @descrizione = (select Descrizione_Breve from import_articoli_temp where Codice = @codice limit 1);
    set @descrizione_att_id = 22;
    
    set @unita_misura = (select Unita_Misura from import_articoli_temp where Codice = @codice limit 1);
    set @unita_misura_att_id = 36;
    
    set @pezzi_conf = (select Pezzi_Confezione from import_articoli_temp where Codice = @codice limit 1);
    set @pezzi_conf_att_id = 23;
    
    set @conf_bancale = (select Confezioni_Bancale from import_articoli_temp where Codice = @codice limit 1);
    set @bancale_att_id = 37;
    
    set @gestione_lotti = (select Gestione_Lotti from import_articoli_temp where Codice = @codice limit 1);
    set @lotti_att_id = 38;
    
    set @note = (select Ragione_Sociale from import_articoli_temp where Codice = @codice limit 1);
    set @note_att_id = 24;
    
    set @erased = 0;
	set @last_update=now();
	set @oid = upper(md5(rand()));

	set @edit_id = (select id from `prdc_item` where sku = @codice limit 1);
    if @edit_id > 0 then
		-- update
        # attributes descrizione - OK
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @descrizione_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @descrizione_att_id and erased = 0 and valid_to is null;
		end if;
        
		# attributes ean - OK
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @ean_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @ean_att_id and erased = 0  and valid_to is null;
		end if;

        # attributes unita misura
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @unita_misura_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @unita_misura_att_id and erased = 0  and valid_to is null;
		end if;
        
        # attributes pezzi confezione
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @pezzi_conf_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @pezzi_conf_att_id and erased = 0  and valid_to is null;
		end if;
        
        # attributes bancale
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @bancale_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @bancale_att_id and erased = 0  and valid_to is null;
		end if;
        
        # attributes gestione lotti
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @lotti_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @lotti_att_id and erased = 0  and valid_to is null;
		end if;
        
		# attributes note
        set @att_id = (select id from `prdc_item_attributes_has_item` where `item_id` = @edit_id and `attribute_id` = @note_att_id and erased = 0 limit 1);
        if(@att_id > 0) then
            UPDATE `prdc_item_attributes_has_item` set valid_to = now(), last_update = now() where `item_id` = @edit_id and `attribute_id` = @note_att_id and erased = 0  and valid_to is null;
		end if;
		
        
    end if;
		-- insert new

		# item
		INSERT INTO `prdc_item`(`id`,`oid`,`sku`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@codice,33,33,null,now(),now(),null,0,now());
		set @id = last_insert_id();

		-- update
        # attributes descrizione - OK
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@descrizione_att_id,@descrizione,33,33,null,now(),now(),null,0,now());
        
        

		# attributes ean - OK
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@ean_att_id,@ean,33,33,null,now(),now(),null,0,now());
		
        # attributes unita misura
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@unita_misura_att_id,@unita_misura,33,33,null,now(),now(),null,0,now());

        
        # attributes pezzi confezione
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@pezzi_conf_att_id,@pezzi_conf,33,33,null,now(),now(),null,0,now());
        
        # attributes bancale
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@bancale_att_id,@conf_bancale,33,33,null,now(),now(),null,0,now());

        
        # attributes gestione lotti
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@lotti_att_id,@gestione_lotti,33,33,null,now(),now(),null,0,now());
        
		# attributes note
		set @oid = upper(md5(rand()));
		INSERT INTO `prdc_item_attributes_has_item`(`id`,`oid`,`item_id`,`attribute_id`,`value`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
		VALUES
		(null,@oid,@id,@note_att_id,@note,33,33,null,now(),now(),null,0,now());
    -- end if;
    
    delete from import_articoli_temp where Codice = @codice;
	set @count = (select count(*) from import_articoli_temp);
end while;
set @sql_safe_updates = 1;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `dcmt_document_export_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_document_export_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_document_export_v` AS select `dcmt_document`.`status_description` AS `status_description`,`dcmt_document`.`code` AS `code`,`dcmt_document`.`progressive` AS `progressive`,`dcmt_document`.`number` AS `number`,`dcmt_document`.`receiver_business_name` AS `receiver_business_name`,`dcmt_document`.`receiver_name` AS `receiver_name`,`dcmt_document`.`receiver_lastname` AS `receiver_lastname`,date_format(`dcmt_document`.`date`,'%d/%m/%Y') AS `date`,date_format(`dcmt_document`.`delivery_window_start`,'%d/%m/%Y') AS `delivery_window_start`,date_format(`dcmt_document`.`delivery_window_end`,'%d/%m/%Y') AS `delivery_window_end`,`dcmt_document`.`causal` AS `causal`,`dcmt_document`.`document_total` AS `document_total` from `dcmt_document` where (`dcmt_document`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_document_installment_payment_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_document_installment_payment_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_document_installment_payment_v` AS select `ip`.`id` AS `id`,`ip`.`installment_id` AS `installment_id`,`ip`.`date` AS `date`,`ip`.`amount` AS `amount`,`ip`.`code` AS `code`,upper(`pc`.`value`) AS `payment_code_description`,`ip`.`erased` AS `erased`,`ip`.`last_update` AS `last_update` from (`dcmt_document_installment_payment` `ip` left join `dcmt_document_payment_code` `pc` on(((`ip`.`code` = `pc`.`code`) and (`pc`.`erased` = 0)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_document_payment_schedule_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_document_payment_schedule_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_document_payment_schedule_v` AS select `i`.`id` AS `id`,`i`.`document_id` AS `document_id`,`d`.`document_type` AS `document_type`,`d`.`code` AS `document_code`,(case when (`d`.`code` = 'INVS') then ifnull(`d`.`receiver_business_name`,concat(concat(ifnull(`d`.`receiver_name`,''),' ',ifnull(`d`.`receiver_lastname`,'')))) else ifnull(`d`.`sender_business_name`,concat(concat(ifnull(`d`.`sender_name`,''),' ',ifnull(`d`.`sender_lastname`,'')))) end) AS `company_name`,`d`.`document_total` AS `document_total`,`d`.`totally_paid` AS `totally_paid`,`d`.`number` AS `document_number`,`d`.`date` AS `document_date`,`i`.`payment_mode` AS `payment_mode`,concat(upper(left(`pc`.`value`,1)),lower(substr(`pc`.`value`,2))) AS `payent_mode_description`,`i`.`amount` AS `installment_amount`,sum(ifnull(`ip`.`amount`,0)) AS `paied_amount`,(ifnull(`i`.`amount`,0) - sum(ifnull(`ip`.`amount`,0))) AS `unpaid_amount`,cast(`i`.`expiry_date` as date) AS `expiry_date`,`i`.`paid` AS `installment_paid` from (((`dcmt_document_installment` `i` left join `dcmt_document` `d` on(((`i`.`document_id` = `d`.`id`) and (`i`.`erased` = 0) and (`d`.`erased` = 0)))) left join `dcmt_document_installment_payment` `ip` on(((`i`.`id` = `ip`.`installment_id`) and (`i`.`erased` = 0) and (`ip`.`erased` = 0)))) left join `dcmt_document_payment_code` `pc` on(((`i`.`payment_mode` = `pc`.`code`) and (`i`.`erased` = 0) and (`pc`.`erased` = 0)))) where (`d`.`document_type` = 'I') group by `i`.`id`,`i`.`document_id`,`d`.`document_type`,`document_code`,`company_name`,`d`.`document_total`,`d`.`totally_paid`,`document_number`,`i`.`document_date`,`i`.`payment_mode`,`payent_mode_description`,`installment_amount`,`i`.`expiry_date`,`installment_paid` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_document_row_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_document_row_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_document_row_v` AS select `ddr`.`row_number` AS `row_number`,`ddr`.`description` AS `description`,`ddr`.`external_code` AS `external_code`,`ddr`.`internal_code` AS `internal_code`,`ddr`.`sell_tax_rate` AS `sell_tax_rate`,`ddr`.`unit_price` AS `unit_price`,`ddr`.`quantity` AS `quantity`,`ddr`.`measure_unit` AS `measure_unit`,`ddr`.`total_price` AS `total_price`,`ddr`.`id` AS `id`,`ddr`.`credit_application` AS `credit_application`,`ddr`.`return_number` AS `return_number`,`ddr`.`commission_1` AS `commission_1`,`ddr`.`commission_2` AS `commission_2`,`ddr`.`commission_1_percentage` AS `commission_1_percentage`,`ddr`.`commission_2_percentage` AS `commission_2_percentage`,date_format(`ddr`.`delivery_date`,'%d/%m/%Y') AS `delivery_date`,`ddr`.`note` AS `note`,`ddr`.`free_gift` AS `free_gift`,`ddr`.`status_code` AS `status_code`,`ddr`.`net_weight` AS `net_weight`,`ddr`.`gross_weight` AS `gross_weight`,`ddr`.`volume` AS `volume`,`ddr`.`weight_unit_measure` AS `weight_unit_measure`,`ddr`.`volume_unit_measure` AS `volume_unit_measure`,`ddr`.`oid` AS `oid`,`ddr`.`document_id` AS `document_id`,`ddr`.`parent_invoice_id` AS `parent_invoice_id`,`ddr`.`parent_row_id` AS `parent_row_id`,`ddr`.`document_type` AS `document_type`,`ddr`.`document_purchase_order` AS `document_purchase_order`,`ddr`.`code` AS `code`,`ddr`.`buy_tax_rate` AS `buy_tax_rate`,`ddr`.`buy_tax_kind` AS `buy_tax_kind`,`ddr`.`buy_tax_normative_reference` AS `buy_tax_normative_reference`,`ddr`.`sell_tax_kind` AS `sell_tax_kind`,`ddr`.`sell_tax_normative_reference` AS `sell_tax_normative_reference`,`ddr`.`lot_code` AS `lot_code`,`ddr`.`processable_quantity` AS `processable_quantity`,`ddr`.`processed_quantity` AS `processed_quantity`,`ddr`.`discount` AS `discount`,`ddr`.`discount_percentage` AS `discount_percentage`,`ddr`.`added_discount` AS `added_discount`,`ddr`.`added_discount_percentage` AS `added_discount_percentage`,`ddr`.`surcharge` AS `surcharge`,`ddr`.`surcharge_percentage` AS `surcharge_percentage`,`ddr`.`added_surcharge` AS `added_surcharge`,`ddr`.`added_surcharge_percentage` AS `added_surcharge_percentage`,`ddr`.`total_discount` AS `total_discount`,`ddr`.`total_discount_percentage` AS `total_discount_percentage`,`ddr`.`total_surcharge` AS `total_surcharge`,`ddr`.`total_surcharge_percentage` AS `total_surcharge_percentage`,`ddr`.`unit_price_discounted` AS `unit_price_discounted`,`ddr`.`discounted_unit_price_surcharged` AS `discounted_unit_price_surcharged`,`ddr`.`taxable_amount` AS `taxable_amount`,`ddr`.`tax_amount` AS `tax_amount`,`ddr`.`taxed_amount` AS `taxed_amount`,`ddr`.`causal` AS `causal`,`ddr`.`erased` AS `erased`,`dd`.`id` AS `parent_document_id`,`dd`.`number` AS `parent_document_number` from ((`dcmt_document_row` `ddr` left join `dcmt_document_row` `ddr1` on((`ddr`.`parent_row_id` = `ddr1`.`id`))) left join `dcmt_document` `dd` on((`dd`.`id` = `ddr1`.`document_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_document_tax_calculation_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_document_tax_calculation_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_document_tax_calculation_v` AS select `t`.`year` AS `year`,`t`.`month` AS `month`,(case when (`t`.`month` = 1) then 'Gennaio' when (`t`.`month` = 2) then 'Febbraio' when (`t`.`month` = 3) then 'Marzo' when (`t`.`month` = 4) then 'Aprile' when (`t`.`month` = 5) then 'Maggio' when (`t`.`month` = 6) then 'Giugno' when (`t`.`month` = 7) then 'Luglio' when (`t`.`month` = 8) then 'Agosto' when (`t`.`month` = 9) then 'Settembre' when (`t`.`month` = 10) then 'Ottobre' when (`t`.`month` = 11) then 'Novembre' when (`t`.`month` = 12) then 'Dicembre' else '' end) AS `month_description`,cast(`t`.`sales_tax` as decimal(21,2)) AS `sales_tax`,cast(`t`.`purchases_tax` as decimal(21,2)) AS `purchases_tax`,cast((`t`.`sales_tax` - `t`.`purchases_tax`) as decimal(21,2)) AS `to_be_paid_tax` from (select `d`.`year` AS `year`,month(`d`.`date`) AS `month`,(sum((case when (`d`.`code` = 'INVS') then `dt`.`tax_amount` else 0 end)) - sum((case when (`d`.`code` = 'NCRE') then `dt`.`tax_amount` else 0 end))) AS `sales_tax`,sum((case when (`d`.`code` = 'INVB') then ((`dt`.`tax_amount` * `dt`.`tax_deductibility_percent`) / 100) else 0 end)) AS `purchases_tax` from (`inaf`.`dcmt_document` `d` join `inaf`.`dcmt_document_total_tax` `dt` on((`d`.`id` = `dt`.`document_id`))) where ((`d`.`erased` = 0) and (`dt`.`erased` = 0) and (`d`.`date` is not null)) group by `d`.`year`,`month`) `t` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_payment_solution_row_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_payment_solution_row_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_payment_solution_row_v` AS select concat(`dp`.`code`,' - ',`dp`.`value`) AS `payment_sol`,`dpsr`.`days` AS `days`,`dpsr`.`taxable` AS `taxable`,`dpsr`.`tax` AS `tax`,`dpsr`.`payment_solution_id` AS `payment_solution_id`,`dpsr`.`id` AS `id` from (`dcmt_document_payment_solution_row` `dpsr` left join `dcmt_document_payment_code` `dp` on((`dpsr`.`payment_code_id` = `dp`.`id`))) where (`dpsr`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `dcmt_payment_solution_v`
--

/*!50001 DROP VIEW IF EXISTS `dcmt_payment_solution_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `dcmt_payment_solution_v` AS select `dps`.`id` AS `id`,`dps`.`code` AS `code`,`dps`.`description` AS `description`,`dps`.`discount` AS `discount`,`dps`.`deposit_percentage` AS `deposit_percentage`,`dps`.`deposit_fixed` AS `deposit_fixed`,`dps`.`collection_bank` AS `collection_bank`,`dps`.`invoice_date` AS `invoice_date`,`dps`.`days` AS `days`,`dps`.`erased` AS `erased`,`dps`.`last_update` AS `last_update`,`dpsr`.`rate` AS `rate`,`dpsr`.`payment_solution_id` AS `payment_solution_id` from (`inaf`.`dcmt_document_payment_solution` `dps` left join (select count(0) AS `rate`,`inaf`.`dcmt_document_payment_solution_row`.`payment_solution_id` AS `payment_solution_id` from `inaf`.`dcmt_document_payment_solution_row` where (`inaf`.`dcmt_document_payment_solution_row`.`erased` = 0) group by `inaf`.`dcmt_document_payment_solution_row`.`payment_solution_id`) `dpsr` on((`dpsr`.`payment_solution_id` = `dps`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `item_archive_v`
--

/*!50001 DROP VIEW IF EXISTS `item_archive_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_archive_v` AS select distinct `pi`.`id` AS `id`,`pi`.`sku` AS `sku`,`a`.`value` AS `ean`,`b`.`value` AS `description`,`c`.`value` AS `pieces_per_pack`,`g`.`value` AS `pack_per_pallet`,ifnull(`h`.`value`,0) AS `batch_management`,`d`.`value` AS `note`,`e`.`value` AS `tax_rate_id`,`f`.`value` AS `measure_unit_id`,`pi`.`valid_from` AS `valid_from`,`a`.`valid_to` AS `valid_to`,`a`.`erased` AS `item_erased` from ((((((((`prdc_item` `pi` left join `prdc_item_attributes_has_item` `a` on(((`a`.`item_id` = `pi`.`id`) and (`a`.`attribute_id` = 21)))) left join `prdc_item_attributes_has_item` `b` on(((`b`.`item_id` = `pi`.`id`) and (`b`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`b`.`attribute_id` = 22)))) left join `prdc_item_attributes_has_item` `c` on(((`c`.`item_id` = `pi`.`id`) and (`c`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`c`.`attribute_id` = 23)))) left join `prdc_item_attributes_has_item` `d` on(((`d`.`item_id` = `pi`.`id`) and (`d`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`d`.`attribute_id` = 24)))) left join `prdc_item_attributes_has_item` `e` on(((`e`.`item_id` = `pi`.`id`) and (`e`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`e`.`attribute_id` = 35)))) left join `prdc_item_attributes_has_item` `f` on(((`f`.`item_id` = `pi`.`id`) and (`f`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`f`.`attribute_id` = 36)))) left join `prdc_item_attributes_has_item` `g` on(((`g`.`item_id` = `pi`.`id`) and (`g`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`g`.`attribute_id` = 37)))) left join `prdc_item_attributes_has_item` `h` on(((`h`.`item_id` = `pi`.`id`) and (`h`.`valid_from` between `a`.`valid_from` and ifnull(`a`.`valid_to`,now())) and (`h`.`attribute_id` = 38)))) where (`pi`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `item_attribute_light_v`
--

/*!50001 DROP VIEW IF EXISTS `item_attribute_light_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_attribute_light_v` AS select `prdc_item_attributes_has_item`.`id` AS `id`,`prdc_item_attributes_has_item`.`oid` AS `oid`,`prdc_item_attributes_has_item`.`item_id` AS `item_id`,`prdc_item_attributes_has_item`.`attribute_id` AS `attribute_id`,`prdc_item_attributes_has_item`.`value` AS `value`,`prdc_item_attributes_has_item`.`modified_by` AS `modified_by`,`prdc_item_attributes_has_item`.`created_by` AS `created_by`,`prdc_item_attributes_has_item`.`assigned` AS `assigned`,`prdc_item_attributes_has_item`.`create_date` AS `create_date`,`prdc_item_attributes_has_item`.`valid_from` AS `valid_from`,`prdc_item_attributes_has_item`.`valid_to` AS `valid_to`,`prdc_item_attributes_has_item`.`erased` AS `erased`,`prdc_item_attributes_has_item`.`last_update` AS `last_update` from `prdc_item_attributes_has_item` where isnull(`prdc_item_attributes_has_item`.`valid_to`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `item_light_v`
--

/*!50001 DROP VIEW IF EXISTS `item_light_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_light_v` AS select `pi`.`id` AS `id`,`pi`.`sku` AS `sku`,`a`.`value` AS `ean`,`b`.`value` AS `description`,`c`.`value` AS `pieces_per_pack`,`g`.`value` AS `pack_per_pallet`,ifnull(`h`.`value`,0) AS `batch_management`,`d`.`value` AS `note`,`e`.`value` AS `tax_rate_id`,`f`.`value` AS `measure_unit_id`,`i`.`value` AS `clifor`,`pi`.`valid_from` AS `valid_from`,`a`.`valid_to` AS `valid_to`,`a`.`erased` AS `item_erased` from (((((((((`prdc_item` `pi` left join `item_attribute_light_v` `a` on(((`a`.`item_id` = `pi`.`id`) and (`a`.`attribute_id` = 21)))) left join `item_attribute_light_v` `b` on(((`b`.`item_id` = `pi`.`id`) and (`b`.`attribute_id` = 22)))) left join `item_attribute_light_v` `c` on(((`c`.`item_id` = `pi`.`id`) and (`c`.`attribute_id` = 23)))) left join `item_attribute_light_v` `d` on(((`d`.`item_id` = `pi`.`id`) and (`d`.`attribute_id` = 24)))) left join `item_attribute_light_v` `e` on(((`e`.`item_id` = `pi`.`id`) and (`e`.`attribute_id` = 35)))) left join `item_attribute_light_v` `f` on(((`f`.`item_id` = `pi`.`id`) and (`f`.`attribute_id` = 36)))) left join `item_attribute_light_v` `g` on(((`g`.`item_id` = `pi`.`id`) and (`g`.`attribute_id` = 37)))) left join `item_attribute_light_v` `h` on(((`h`.`item_id` = `pi`.`id`) and (`h`.`attribute_id` = 38)))) left join `item_attribute_light_v` `i` on(((`i`.`item_id` = `pi`.`id`) and (`i`.`attribute_id` = 39)))) where (`pi`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `item_light_v_OLD`
--

/*!50001 DROP VIEW IF EXISTS `item_light_v_OLD`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `item_light_v_OLD` AS select `pi`.`id` AS `id`,`pi`.`sku` AS `sku`,`ean`.`ean` AS `ean`,`description`.`description` AS `description`,`pieces_per_pack`.`pieces_per_pack` AS `pieces_per_pack`,`note`.`note` AS `note`,`tax_rate_id`.`tax_rate_id` AS `tax_rate_id`,`measure_unit_id`.`measure_unit_id` AS `measure_unit_id`,`pack_per_pallet`.`pack_per_pallet` AS `pack_per_pallet`,ifnull(`batch_management`.`batch_management`,0) AS `batch_management`,`clifor`.`clifor` AS `clifor`,`pi`.`valid_from` AS `valid_from`,`pi`.`valid_to` AS `valid_to`,`pi`.`erased` AS `item_erased` from (((((((((`inaf`.`prdc_item` `pi` join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `ean` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 21) and isnull(`ai`.`valid_to`))) `ean` on((`pi`.`id` = `ean`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `description` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 22) and isnull(`ai`.`valid_to`))) `description` on((`pi`.`id` = `description`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `pieces_per_pack` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 23) and isnull(`ai`.`valid_to`))) `pieces_per_pack` on((`pi`.`id` = `pieces_per_pack`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `note` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 24) and isnull(`ai`.`valid_to`))) `note` on((`pi`.`id` = `note`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `tax_rate_id` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 35) and isnull(`ai`.`valid_to`))) `tax_rate_id` on((`pi`.`id` = `tax_rate_id`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `measure_unit_id` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 36) and isnull(`ai`.`valid_to`))) `measure_unit_id` on((`pi`.`id` = `measure_unit_id`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `pack_per_pallet` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 37) and isnull(`ai`.`valid_to`))) `pack_per_pallet` on((`pi`.`id` = `pack_per_pallet`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `batch_management` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 38) and isnull(`ai`.`valid_to`))) `batch_management` on((`pi`.`id` = `batch_management`.`item_id`))) join (select `ai`.`item_id` AS `item_id`,`ai`.`value` AS `clifor` from `inaf`.`prdc_item_attributes_has_item` `ai` where ((`ai`.`attribute_id` = 39) and isnull(`ai`.`valid_to`))) `clifor` on((`pi`.`id` = `clifor`.`item_id`))) where (`pi`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `name_company_export_v`
--

/*!50001 DROP VIEW IF EXISTS `name_company_export_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `name_company_export_v` AS select `name_company`.`id` AS `id`,`name_company`.`name` AS `name`,`name_company`.`last_name` AS `last_name`,`name_company`.`business_name` AS `business_name`,`name_company`.`description` AS `description`,`name_company`.`vat_number` AS `vat_number`,`name_company`.`fiscal_code` AS `fiscal_code` from `name_company` where (`name_company`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prdc_item_has_price_list_v`
--

/*!50001 DROP VIEW IF EXISTS `prdc_item_has_price_list_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `prdc_item_has_price_list_v` AS select `p`.`id` AS `id`,`p`.`oid` AS `oid`,`p`.`item_id` AS `item_id`,`p`.`price_list_id` AS `price_list_id`,`p`.`price` AS `price`,`p`.`tax_rate_id` AS `tax_rate_id`,`p`.`discount` AS `discount`,`p`.`discount_percent` AS `discount_percent`,`p`.`surcharge` AS `surcharge`,`p`.`surcharge_percent` AS `surcharge_percent`,`p`.`modified_by` AS `modified_by`,`p`.`created_by` AS `created_by`,`p`.`assigned` AS `assigned`,`p`.`create_date` AS `create_date`,`p`.`valid_from` AS `valid_from`,`p`.`valid_to` AS `valid_to`,`p`.`erased` AS `erased`,`p`.`last_update` AS `last_update`,concat(ifnull(`it`.`sku`,''),' - ',ifnull(`it`.`description`,'')) AS `item_description`,concat(ifnull(`tx`.`code`,''),' [ ',convert(ifnull(`tx`.`rate`,'') using utf8),'% ] ',ifnull(`tx`.`description`,'')) AS `tax_rate`,concat(ifnull(`pl`.`code`,''),' - ',ifnull(`pl`.`description`,'')) AS `price_list` from (((`prdc_item_has_price_list` `p` left join `item_light_v` `it` on(((`p`.`item_id` = `it`.`id`) and isnull(`it`.`valid_to`)))) left join `tax_rate` `tx` on((`p`.`tax_rate_id` = `tx`.`id`))) left join `prdc_item_price_list` `pl` on((`p`.`price_list_id` = `pl`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rel_prdc_has_name_v`
--

/*!50001 DROP VIEW IF EXISTS `rel_prdc_has_name_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `rel_prdc_has_name_v` AS select `rphn`.`id` AS `id`,`rphn`.`oid` AS `oid`,`rphn`.`name_customer_id` AS `name_customer_id`,`rphn`.`name_supplier_id` AS `name_supplier_id`,`rphn`.`prdc_price_list_id` AS `prdc_price_list_id`,`pipl`.`code` AS `price_list_code`,`pipl`.`description` AS `price_list_description`,`pipl`.`type` AS `price_list_type`,`pipl`.`erased` AS `price_list_erased`,`nc`.`customer_code` AS `customer_code`,`ncc`.`business_name` AS `customer_business_name`,concat('[',`nc`.`customer_code`,'] ',`ncc`.`business_name`) AS `customer_description`,`ns`.`supplier_code` AS `supplier_code`,`ncs`.`business_name` AS `supplier_business_name`,concat('[',ifnull(`ns`.`supplier_code`,''),'] ',ifnull(`ncs`.`business_name`,'')) AS `supplier_description` from (((((`rel_prdc_has_name` `rphn` left join `name_customer` `nc` on((`rphn`.`name_customer_id` = `nc`.`id`))) left join `name_supplier` `ns` on((`rphn`.`name_supplier_id` = `ns`.`id`))) left join `name_company` `ncc` on((`ncc`.`id` = `nc`.`name_company_id`))) left join `name_company` `ncs` on((`ncs`.`id` = `ns`.`name_company_id`))) left join `prdc_item_price_list` `pipl` on((`rphn`.`prdc_price_list_id` = `pipl`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `wrhs_mov_head_v`
--

/*!50001 DROP VIEW IF EXISTS `wrhs_mov_head_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `wrhs_mov_head_v` AS select `wh`.`id` AS `id`,`wh`.`oid` AS `oid`,`wh`.`warehouse_id` AS `warehouse_id`,`wh`.`mov_causal_id` AS `mov_causal_id`,`wc`.`existence_multiplier` AS `existence_multiplier`,concat(ifnull(concat('[ ',`ww`.`code`,' ] '),''),ifnull(concat(' - ',`ww`.`name`),'')) AS `warehouse_name`,`wc`.`description` AS `causal_description`,`wh`.`progressive` AS `progressive`,`wh`.`warehouse_linked_id` AS `warehouse_linked_id`,`wh`.`mov_causal_linked` AS `mov_causal_linked`,`wh`.`registration_date` AS `registration_date`,`wh`.`document_number` AS `document_number`,`wh`.`document_date` AS `document_date`,`wh`.`customer_supplier_type` AS `customer_supplier_type`,`wh`.`customer_supplier_code` AS `customer_supplier_code`,`wh`.`newspaper_print` AS `newspaper_print`,`wh`.`year_operation` AS `year_operation`,`wh`.`currency_code` AS `currency_code`,`wh`.`currency_change` AS `currency_change`,`wh`.`document_type` AS `document_type`,`wh`.`total_quantity` AS `total_quantity`,`wh`.`total_document_amount` AS `total_document_amount`,`wh`.`source_document` AS `source_document`,`wh`.`progressive_source_document` AS `progressive_source_document`,`wh`.`note` AS `note`,`wh`.`movement_type` AS `movement_type`,`wh`.`modified_by` AS `modified_by`,`wh`.`created_by` AS `created_by`,`wh`.`assigned` AS `assigned`,`wh`.`create_date` AS `create_date`,`wh`.`valid_from` AS `valid_from`,`wh`.`valid_to` AS `valid_to`,`wh`.`erased` AS `erased`,`wh`.`last_update` AS `last_update` from ((`wrhs_mov_head` `wh` left join `wrhs_warehouse` `ww` on((`wh`.`warehouse_id` = `ww`.`id`))) left join `wrhs_mov_causal` `wc` on((`wh`.`mov_causal_id` = `wc`.`id`))) where (`wh`.`erased` = 0) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `wrhs_mov_row_v`
--

/*!50001 DROP VIEW IF EXISTS `wrhs_mov_row_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `wrhs_mov_row_v` AS select `wr`.`id` AS `mov_row_id`,`wr`.`note` AS `note`,`wr`.`quantity` AS `quantity`,`iav`.`id` AS `item_id`,`iav`.`pieces_per_pack` AS `pieces_per_pack`,`iav`.`pack_per_pallet` AS `pack_per_pallet`,concat(`iav`.`sku`,' - ',`iav`.`description`) AS `item_description`,`wr`.`erased` AS `erased`,`wr`.`mov_head_id` AS `mov_head_id`,`wl`.`lot` AS `lot_code` from ((((`wrhs_mov_row` `wr` left join `rel_wrhs_has_prdc` `whp` on((`whp`.`mov_row_id` = `wr`.`id`))) left join `item_light_v` `iav` on(((`iav`.`id` = `whp`.`item_id`) and isnull(`iav`.`valid_to`)))) left join `wrhs_mov_head` `wh` on((`wr`.`mov_head_id` = `wh`.`id`))) left join `wrhs_lot` `wl` on((`wl`.`id` = `whp`.`wrhs_lot_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `wrhs_status_v`
--

/*!50001 DROP VIEW IF EXISTS `wrhs_status_v`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `wrhs_status_v` AS select distinct `ww`.`id` AS `warehouse_id`,`ww`.`name` AS `warehouse_name`,ifnull(`rwp_ia`.`id`,`rwhp_ia`.`id`) AS `item_id`,concat(ifnull(`rwp_ia`.`sku`,`rwhp_ia`.`sku`),' - ',ifnull(`rwp_ia`.`description`,`rwhp_ia`.`description`)) AS `item_description`,`s`.`id` AS `status_id`,(case when ((`rwhp_ia`.`batch_management` is not null) and (`rwhp_ia`.`batch_management` = 1)) then ifnull(`wl`.`existence`,0) else `s`.`existence` end) AS `item_existence`,`wl`.`lot` AS `lot`,`s`.`existence` AS `tot_existence`,`rwp_ia`.`measure_unit_id` AS `measure_unit_id`,`rwp_ia`.`pieces_per_pack` AS `pieces_per_pack`,`rwp_ia`.`pack_per_pallet` AS `pack_per_pallet` from ((((((`wrhs_status` `s` left join `rel_wrhs_has_prdc` `rwp` on(((`rwp`.`wrhs_status_id` = `s`.`id`) and (`rwp`.`wrhs_warehouse_id` = `s`.`warehouse_id`) and (not(`rwp`.`item_id` in (select `rel_wrhs_has_prdc`.`item_id` from `rel_wrhs_has_prdc` where (`rel_wrhs_has_prdc`.`wrhs_lot_id` is not null))))))) left join `item_light_v` `rwp_ia` on((isnull(`rwp_ia`.`valid_to`) and (`rwp_ia`.`id` = `rwp`.`item_id`)))) left join `wrhs_warehouse` `ww` on((`ww`.`id` = `s`.`warehouse_id`))) left join `rel_wrhs_has_prdc` `rwhp` on((`rwhp`.`item_id` in (select `rel_wrhs_has_prdc`.`item_id` from `rel_wrhs_has_prdc` where (`rel_wrhs_has_prdc`.`wrhs_lot_id` is not null)) and (`rwhp`.`wrhs_lot_id` is not null) and (`rwhp`.`wrhs_lot_id` is not null) and (`rwhp`.`wrhs_warehouse_id` = `s`.`warehouse_id`)))) left join `item_light_v` `rwhp_ia` on((isnull(`rwhp_ia`.`valid_to`) and (`rwhp_ia`.`id` = `rwhp`.`item_id`)))) left join `wrhs_lot` `wl` on((`wl`.`id` = `rwhp`.`wrhs_lot_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-03  9:34:23
