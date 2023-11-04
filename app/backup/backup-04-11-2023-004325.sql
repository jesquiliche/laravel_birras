-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: laravel_birras
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cervezas`
--

DROP TABLE IF EXISTS `cervezas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cervezas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_id` bigint unsigned NOT NULL,
  `graduacion_id` bigint unsigned NOT NULL,
  `tipo_id` bigint unsigned NOT NULL,
  `pais_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `novedad` tinyint(1) NOT NULL DEFAULT '0',
  `oferta` tinyint(1) NOT NULL DEFAULT '0',
  `precio` decimal(8,2) NOT NULL DEFAULT '0.00',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `marca` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cervezas_color_id_foreign` (`color_id`),
  KEY `cervezas_graduacion_id_foreign` (`graduacion_id`),
  KEY `cervezas_tipo_id_foreign` (`tipo_id`),
  KEY `cervezas_pais_id_foreign` (`pais_id`),
  CONSTRAINT `cervezas_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`),
  CONSTRAINT `cervezas_graduacion_id_foreign` FOREIGN KEY (`graduacion_id`) REFERENCES `graduaciones` (`id`),
  CONSTRAINT `cervezas_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`),
  CONSTRAINT `cervezas_tipo_id_foreign` FOREIGN KEY (`tipo_id`) REFERENCES `tipos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cervezas`
--

LOCK TABLES `cervezas` WRITE;
/*!40000 ALTER TABLE `cervezas` DISABLE KEYS */;
INSERT INTO `cervezas` VALUES (1,'POMELO IPA IDA Y VUELTA 24x33cl','**Estilo POMELO IPA. Sin gluten**\n5,8 % ABV\nIBU´S 50 , amarga.\n**INGREDIENTES: **Agua; maltas de trigo Torrefacto y Extra pale; lúpulos Cascade,Columbus y Citra , copos de AVENA,zumo y cascara de pomelo  y levadura Ale. \nGastos de envio España Peninsular incluidos.\n24 botellas  33 cl\n\nExiste la posibilidad de que como realizamos los envíos en cajas de 12 unidades, puedas componer la tuya con los tipos de cerveza de Dougall que desees, para ello debes enviar un correo a info@milcervezas,com explicando lo que desearías. \n\nCerveza elaborada por DouGall\'s desde el 2023 en colaboración con Refu.Valle de Arán',1,4,2,1,'2023-11-03 18:34:58','2023-11-03 18:34:58',0,0,59.81,'https://res.cloudinary.com/dkrew530b/image/upload/v1697309153/pomelo_ipa_ida_y_vuelta_24x33cl_4baeb73584.jpg','DouGall\'s'),(2,'DIPA or Nothing 12x33','Estilo: DDH Doble IPA\nAlcohol: 7,5 % Abv \nIBU’S: 70 Bastante Amarga\nSin gluten\nIngredientes , Agua, maltas y lúpulos  Incognito Mosaic, Azacca y Vic Secret.\nGastos de envio España Peninsular incluidos.\n1\n',8,1,7,2,'2023-11-03 18:34:58','2023-11-03 18:34:58',0,0,46.58,'https://res.cloudinary.com/dkrew530b/image/upload/v1697311032/dipa_or_nothing_12x33_a547d464d5.jpg','DIPA or Nothing ');
/*!40000 ALTER TABLE `cervezas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `copiar_cervezas_after_update` AFTER UPDATE ON `cervezas` FOR EACH ROW BEGIN
                INSERT INTO cervezas_copia (id, nombre, descripcion, color_id, graduacion_id, tipo_id, pais_id, created_at, updated_at, operacion,fecha_operacion)
                SELECT OLD.id, NEW.nombre, OLD.descripcion, OLD.color_id, OLD.graduacion_id, OLD.tipo_id, OLD.pais_id, OLD.created_at, OLD.updated_at, "UPDATE",NOW();
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `copiar_cervezas_before_delete` BEFORE DELETE ON `cervezas` FOR EACH ROW BEGIN
                INSERT INTO cervezas_copia (id, nombre, descripcion, color_id, graduacion_id, tipo_id, pais_id, created_at, updated_at, operacion,fecha_operacion)
                SELECT OLD.id, OLD.nombre, OLD.descripcion, OLD.color_id, OLD.graduacion_id, OLD.tipo_id, OLD.pais_id, OLD.created_at, NOW(), "DELETE",NOW();
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cervezas_copia`
--

DROP TABLE IF EXISTS `cervezas_copia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cervezas_copia` (
  `id` bigint unsigned NOT NULL DEFAULT '0',
  `nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_id` bigint unsigned NOT NULL,
  `graduacion_id` bigint unsigned NOT NULL,
  `tipo_id` bigint unsigned NOT NULL,
  `pais_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `novedad` tinyint(1) NOT NULL DEFAULT '0',
  `oferta` tinyint(1) NOT NULL DEFAULT '0',
  `precio` decimal(8,2) NOT NULL DEFAULT '0.00',
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `marca` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `operacion` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fecha_operacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cervezas_copia`
--

LOCK TABLES `cervezas_copia` WRITE;
/*!40000 ALTER TABLE `cervezas_copia` DISABLE KEYS */;
/*!40000 ALTER TABLE `cervezas_copia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colores`
--

DROP TABLE IF EXISTS `colores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colores`
--

LOCK TABLES `colores` WRITE;
/*!40000 ALTER TABLE `colores` DISABLE KEYS */;
INSERT INTO `colores` VALUES (1,'Amarillo',NULL,NULL),(2,'Ambar',NULL,NULL),(3,'Blanca',NULL,NULL),(4,'Cobrizo',NULL,NULL),(5,'Marrón',NULL,NULL),(6,'Negra',NULL,NULL),(7,'Rubia',NULL,NULL),(8,'Tostada',NULL,NULL);
/*!40000 ALTER TABLE `colores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `graduaciones`
--

DROP TABLE IF EXISTS `graduaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `graduaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `graduaciones`
--

LOCK TABLES `graduaciones` WRITE;
/*!40000 ALTER TABLE `graduaciones` DISABLE KEYS */;
INSERT INTO `graduaciones` VALUES (1,'Alta(7-9',NULL,NULL),(2,'Baja(3-5)',NULL,NULL),(3,'Maxima(12+)',NULL,NULL),(4,'Muy alta(9-12',NULL,NULL),(5,'Sin alcohol(0-2.9)',NULL,NULL);
/*!40000 ALTER TABLE `graduaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2023_10_21_205846_create_colores_table',1),(6,'2023_10_22_210041_create_tipos_table',1),(7,'2023_10_22_210525_create_pais_table',1),(8,'2023_10_23_090901_create_graduacion_table',1),(9,'2023_10_23_091311_create_cervezss_table',1),(10,'2023_10_23_100321_add_fields_cervezas_table',1),(11,'2023_10_23_101813_create_trigger_cervezas',1),(12,'2023_11_03_154308_create_view_cervezas',1),(13,'2023_11_03_171944_create_procedure_cervezas_por_pais',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (1,'España',NULL,NULL),(2,'Alemania',NULL,NULL),(3,'Francia',NULL,NULL),(4,'República checa',NULL,NULL),(5,'Belgica',NULL,NULL),(6,'EEUU',NULL,NULL),(7,'Escocia',NULL,NULL),(8,'Holanda',NULL,NULL),(9,'Inglaterra',NULL,NULL),(10,'Escocia',NULL,NULL),(11,'Holanda',NULL,NULL),(12,'Irlanda',NULL,NULL);
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos`
--

DROP TABLE IF EXISTS `tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos`
--

LOCK TABLES `tipos` WRITE;
/*!40000 ALTER TABLE `tipos` DISABLE KEYS */;
INSERT INTO `tipos` VALUES (1,'Ale',NULL,NULL),(2,'Lager/Pilsner',NULL,NULL),(3,'Stout',NULL,NULL),(4,'Porter',NULL,NULL),(5,'IPA (India Pale Ale)',NULL,NULL),(6,'Wheat Beer',NULL,NULL),(7,'Sour Beer',NULL,NULL),(8,'Belgian Ale',NULL,NULL),(9,'Pale Ale',NULL,NULL),(10,'Brown Ale',NULL,NULL),(11,'Amber Ale',NULL,NULL),(12,'Golden Ale',NULL,NULL),(13,'Blonde Ale',NULL,NULL),(14,'Cream Ale',NULL,NULL),(15,'Kölsch',NULL,NULL),(16,'Pilsner',NULL,NULL),(17,'Bock',NULL,NULL),(18,'Doppelbock',NULL,NULL),(19,'Helles',NULL,NULL),(20,'Vienna Lager',NULL,NULL),(21,'Marzen/Oktoberfest',NULL,NULL),(22,'Kellerbier',NULL,NULL),(23,'Dunkel',NULL,NULL),(24,'Schwarzbier',NULL,NULL),(25,'Barleywine',NULL,NULL),(26,'Saison',NULL,NULL),(27,'Witbier',NULL,NULL),(28,'Gose',NULL,NULL),(29,'Kvass',NULL,NULL),(30,'Rauchbier',NULL,NULL),(31,'Fruit Beer',NULL,NULL),(32,'Cider',NULL,NULL),(33,'Mead',NULL,NULL);
/*!40000 ALTER TABLE `tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@test.com',NULL,'$2y$10$TOVkgpvW.CvXHubK.udkTeErs00/H2l1GdlEjzCL8xvxfduWqc6Nq',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(2,'Dr. Royce Block','ettie78@example.com',NULL,'$2y$10$lKvJ1oE3Meu1Hrep4pv85eEiuba/Xih3TrXpNf1k1mKkw47b8QvES',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(3,'Khalil Spencer','dovie02@example.net',NULL,'$2y$10$0nqJjsLpp7SkU4AirDs04.2KQJve0KSrg1HteUncNpRJhyJHz3A6K',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(4,'Yolanda Osinski','feil.pearlie@example.com',NULL,'$2y$10$Ev0.2.78yKcgyCCnCcFiGOQQFyFP12UhwN1aHKrOmwLn7cisQyUHm',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(5,'Verna Maggio','tyler.goyette@example.com',NULL,'$2y$10$p.C0.b4XXp5tGz37dRUDLe75M.d3OCy8muIx7kvzAFqWSU/.uY2DS',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(6,'Araceli Fadel DVM','grant.cummings@example.com',NULL,'$2y$10$hPBoFjrPNeEA5gbE0WcYKO8j6mwbwUqssFVV405EUBdcYW73PAH5e',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(7,'Mr. Hadley Lehner III','otto.greenfelder@example.com',NULL,'$2y$10$OvOXh0cYx5NxUQ9w13Omx.UiLcWyvsLKewtsN.3ReVsRyIMeQxota',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(8,'Russell O\'Keefe V','okon.brennon@example.com',NULL,'$2y$10$N6zyrh5DAKubEOF7Kcbcy.sbPsn5ASd2DKziyCJ8iskrigMaspkEG',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(9,'Gianni Schumm','kertzmann.jevon@example.com',NULL,'$2y$10$dFVj8IIuBE5Nf4F/v4fGu.odSL/vEi7FSzfjeY49J40i7Mpj2FSKS',NULL,'2023-11-03 18:34:58','2023-11-03 18:34:58'),(10,'Jamaal Schinner','medhurst.franco@example.com',NULL,'$2y$10$.nr447hoGMfResaHgFRXEu96qPmkMW.lUoyD2.h1kI5LnnCFaAVea',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(11,'Reina Spinka DVM','ethompson@example.net',NULL,'$2y$10$yFwefpf.PqzP4SUov0nmZ.VazLpnJuTKCbtUrrcrsfg8tcLjEyCDC',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(12,'Mrs. Nichole Dickens','yolson@example.org',NULL,'$2y$10$qM5LnwQGs6t4Gn9VE/mXme4kGRg2CyXMdoaO3FYzofwtntUgPNKZq',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(13,'Brendon Hirthe','xhand@example.org',NULL,'$2y$10$yWwn2IJu1heVfbhGyFNluOIq29cdGHp8n0qxNb35cQxbls1QoMqeW',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(14,'Marcelino Blick','jadon07@example.net',NULL,'$2y$10$SKp5KPXTYEAPFq/6PXW7luGbmein2b7K6liEVwhIFXZqyIwoWUHia',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(15,'Morgan Hegmann','olson.zack@example.org',NULL,'$2y$10$mk4j4CrXZbG2nnhQ3Xk1EuNAdXVZZKNn.OT8chyJ3P7bQUgJXoPIq',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(16,'Miss Velda Friesen','dickens.maryjane@example.com',NULL,'$2y$10$SCsmRr5QG1Gvn.UotswyN.Td.gmKQbg0PVza6/hXiwUsUGB6JlXyW',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(17,'Jaqueline Schowalter','jgerhold@example.org',NULL,'$2y$10$8J1Di1gNfzUstnvKXFFpRuZNnT2QvHU5A6XBESUQLlEsjk0URyA2u',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(18,'Miss Lydia Jacobson Jr.','haylee17@example.org',NULL,'$2y$10$7GHpx8ggnbRYBcTDh9rHWuBzSAkcYA1.C6Z61DoUNpZ1zest0ddQe',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(19,'Aric Wehner','justice.hickle@example.net',NULL,'$2y$10$cj7fGsO2IAz/qaPYy7uXf.KyqxPGoP7.ul6SXcNNI2yOwZJQndFb2',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(20,'Everardo Ullrich IV','michael.little@example.net',NULL,'$2y$10$6hDbuTseGUyceC5vG3qI..VePhR9H/ejxYC5236XNPp0yQQKIpGqe',NULL,'2023-11-03 18:34:59','2023-11-03 18:34:59'),(21,'Jadon Greenfelder','kreiger.yesenia@example.net',NULL,'$2y$10$jC2/siirryIZUMXHkDn4aeIcb/IuQ.LUMSxmR2p6wzQIwFCiFV7a2',NULL,'2023-11-03 18:35:00','2023-11-03 18:35:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `v_cervezas`
--

DROP TABLE IF EXISTS `v_cervezas`;
/*!50001 DROP VIEW IF EXISTS `v_cervezas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_cervezas` AS SELECT
 1 AS `id`,
  1 AS `nombre`,
  1 AS `descripcion`,
  1 AS `novedad`,
  1 AS `oferta`,
  1 AS `precio`,
  1 AS `foto`,
  1 AS `marca`,
  1 AS `color`,
  1 AS `graduacion`,
  1 AS `tipo`,
  1 AS `pais` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `v_cervezas`
--

/*!50001 DROP VIEW IF EXISTS `v_cervezas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_cervezas` AS select `cer`.`id` AS `id`,`cer`.`nombre` AS `nombre`,`cer`.`descripcion` AS `descripcion`,`cer`.`novedad` AS `novedad`,`cer`.`oferta` AS `oferta`,`cer`.`precio` AS `precio`,`cer`.`foto` AS `foto`,`cer`.`marca` AS `marca`,`col`.`nombre` AS `color`,`g`.`nombre` AS `graduacion`,`t`.`nombre` AS `tipo`,`p`.`nombre` AS `pais` from ((((`cervezas` `cer` join `colores` `col` on((`cer`.`color_id` = `col`.`id`))) join `graduaciones` `g` on((`cer`.`graduacion_id` = `g`.`id`))) join `tipos` `t` on((`t`.`id` = `cer`.`tipo_id`))) join `paises` `p` on((`p`.`id` = `cer`.`pais_id`))) order by `cer`.`nombre` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-04  1:43:25
