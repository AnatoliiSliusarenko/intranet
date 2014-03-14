CREATE DATABASE  IF NOT EXISTS `intranet` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `intranet`;
-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: 127.0.0.1    Database: intranet
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.13.10.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `officeid` int(11) DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,4,1,'Office 14','Developers'),(2,4,1,'Office London','Traders'),(3,4,2,'Office Spain','Lee'),(4,0,0,'Public Office','In this place you can browse your own offices and offices where you in. Also can create new offices.');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topicid` int(11) NOT NULL,
  `userid` varchar(45) NOT NULL,
  `message` longtext NOT NULL,
  `posted` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (62,6,'1','So lets talk about SMA...','2014-03-03 16:01:14'),(63,6,'2','Okay. Start please, friend!','2014-03-03 16:01:35'),(64,6,'2','I am wating...','2014-03-03 16:01:58'),(65,6,'1','One sec please!','2014-03-03 16:14:30'),(66,6,'2','So, say me something interesting :-)','2014-03-03 16:15:23'),(67,6,'1','What exactly?:-)','2014-03-03 16:15:42'),(68,6,'2','Hello','2014-03-03 16:48:09'),(69,1,'1','Our main topic :-)','2014-03-07 16:36:32'),(70,1,'16','Hello, here I am ;-)','2014-03-07 17:02:58'),(71,1,'1','Cool :-)','2014-03-07 17:03:27'),(72,1,'16','Hey, friend!','2014-03-07 18:04:25'),(73,1,'1','Hello, Popov !','2014-03-07 18:04:44'),(74,1,'16','Hi','2014-03-07 18:22:22'),(75,1,'19','Всем привет, я Иван и я с вами ;-)','2014-03-11 09:55:13'),(76,1,'16','И с вами снова Попов!Ура :-)','2014-03-11 16:19:23'),(77,1,'16','уууу','2014-03-11 16:21:07'),(78,1,'16','ааааа','2014-03-11 16:23:31'),(79,1,'16','сссссс','2014-03-11 16:27:49'),(80,1,'16','ммммм','2014-03-11 16:28:59'),(81,1,'16','йес)','2014-03-11 16:35:02'),(82,1,'16','прокрутка рулить)','2014-03-11 16:36:09'),(83,1,'16','ммммммиииии сми м','2014-03-11 16:37:10'),(84,1,'1','Привет мен)','2014-03-11 16:37:43'),(85,1,'16','Как ты?','2014-03-11 16:37:51'),(86,1,'1','Что у тебя нового?','2014-03-11 16:38:03'),(87,1,'16','ниче','2014-03-11 16:38:12'),(88,1,'20','Всем привет!!!Вай вай...теперь Базиль с вами в чате :-)','2014-03-12 07:24:09'),(89,1,'1','Ну привет, брО','2014-03-12 07:27:37'),(90,1,'20','Что там у вас?','2014-03-12 07:27:55'),(91,1,'1','альо','2014-03-12 07:37:37'),(92,1,'21','Теперь и Олечка с вами :-)','2014-03-12 10:02:27'),(93,1,'1','привет красавица)','2014-03-12 10:03:27'),(94,1,'1','смисми','2014-03-12 10:16:23'),(95,1,'21','xcvxcv','2014-03-12 11:16:21'),(96,1,'21','xcvxcv','2014-03-12 11:16:25'),(97,1,'21','xcvxc','2014-03-12 11:16:28'),(98,1,'21','xcvxc','2014-03-12 11:16:30'),(99,1,'21','xcvxc','2014-03-12 11:16:34'),(100,2,'21','hhhh','2014-03-12 11:26:10'),(101,3,'21','cvbcvbcv','2014-03-12 11:31:56'),(102,4,'21','sdfsdfsd','2014-03-12 11:39:37'),(103,4,'21','fghfghfghf','2014-03-12 11:39:55'),(104,16,'21','uuuuu','2014-03-12 11:40:31'),(105,1,'1','gfgh!!!!','2014-03-12 15:48:01'),(106,1,'21','vvvvvvvvv','2014-03-12 15:48:10'),(107,1,'21','проверяем!','2014-03-13 09:34:04'),(108,1,'22','Всем привет!','2014-03-13 10:23:02'),(109,1,'1','галоу','2014-03-13 10:23:15'),(110,1,'21','sfdfdsds','2014-03-13 12:29:50'),(111,1,'1','xcvxcv','2014-03-13 12:30:21'),(112,1,'21','Ку ку 678','2014-03-13 12:35:31'),(113,1,'21','ііііі','2014-03-13 12:36:17'),(114,1,'21','ііі','2014-03-13 12:36:49'),(115,1,'1','та привет)','2014-03-13 12:37:13'),(116,18,'21','Я первый!!!','2014-03-13 12:38:49'),(117,26,'21','dfsdfsd','2014-03-13 12:45:20'),(118,26,'1','чсмчсм','2014-03-13 12:45:37'),(119,11,'21','cccccc','2014-03-13 12:50:55'),(120,11,'1','ааааа','2014-03-13 12:51:03'),(121,1,'21','vvvvv','2014-03-13 15:47:20'),(122,1,'21','bbbbbb','2014-03-13 15:47:41'),(123,1,'21','bbbbmnm','2014-03-13 15:48:08');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ROLE_ADMIN',''),(2,'ROLE_USER',''),(3,'ROLE_GUEST','');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_91F64639D823E37A` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (1,0,'Public Topics',''),(2,1,'Software',''),(3,1,'Strategies','You should know it!'),(4,1,'General',''),(5,1,'Trading',''),(7,2,'DataNet',''),(8,2,'TickNet',''),(9,2,'DataSync',''),(10,3,'SMA',''),(11,3,'Stochastic',''),(12,10,'SMA1',''),(13,12,'SMA1.1',''),(14,4,'Daily Procedures','Lunch, break and etc.'),(16,4,'Timesheet',''),(17,4,'Timesheet2','dsfsdfsd'),(18,1,'Products','Our Products'),(19,5,'How to start trading?','First steps...'),(20,5,'Professional level','For cool boys!'),(21,1,'Movies','Relax'),(22,10,'SMA2','Just test SMA'),(23,2,'Intranet','Main web service'),(24,18,'Trader System','Just test it :-)'),(25,1,'New One','Some description'),(26,1,'Topic2','y'),(27,16,'Just sheet',':-)))');
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_office`
--

DROP TABLE IF EXISTS `user_office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_office` (
  `user_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_office`
--

LOCK TABLES `user_office` WRITE;
/*!40000 ALTER TABLE `user_office` DISABLE KEYS */;
INSERT INTO `user_office` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `user_office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `registered` datetime NOT NULL,
  `lastactive` datetime NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'oligarch','$2y$12$61p90/.T8S66lpKW46sCRO4fAe9DH3Sxtu8/sHpf9FA8S6blJJdyO','dj.slyusar@rambler.ru','Anatoliy','Slyusarenko','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'eleven.png'),(2,'user','$2y$12$rwD4FYe8aEZRalVHfI4OwuhEoOk2GtQq2g76VYYWHYwqr6rQEQZvu','test@test.com','Oleg','Ivanov','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'one.png'),(16,'popov','$2y$12$9if5SF55ZG7J/otZUVLnLu2tgEDHGPo37fEcTz.DHaqC1oNm5O73u','popov@popa.net','Ivan','Popov','2014-03-07 19:02:22','2014-03-07 19:02:22',1,'eleven.png'),(18,'popov2','$2y$12$bD/S49N/4N1a3Kb3aCBq8O5AsxCtqS36Xp.6iAscSPlXjtfiIYjY2','popov@test.com','Alexander','Popov','2014-03-07 20:19:06','2014-03-07 20:19:06',1,'eleven.png'),(19,'prostoj','$2y$12$jzUEKQsZPP8SMu3gHzxm1.5ERWnsRynsjdr49A.cCH6ATc.zDYAvK','prosto@prosto.com','Иван','Простой','2014-03-11 11:54:51','2014-03-11 11:54:51',1,'eleven.png'),(20,'bazil','$2y$12$h2y7xsLMzWoIXZgIFsF1tOhTSh2GlerwZ4sd6NwmZQDw84w8OCIQy','bazil@gmail.com','Igor','Bazil','2014-03-12 09:23:31','2014-03-12 09:23:31',1,'eleven.png'),(21,'olya','$2y$12$8YN0s4be77/pzyqdTeSh5ebR3Z9MAt6w5HmCQoewAnr8V02eC35Gy','olya@gmail.com','Olya','Mukuta','2014-03-12 12:00:55','2014-03-12 12:00:55',1,'eleven.png'),(22,'rislan','$2y$12$FsxvPwqM//wRPKeK0ogoGeh2uUqzEHcnNoVa8qSa/sLYXAbh93SXq','ruslan@gmail.com','Ruslan','Lyalyko','2014-03-13 12:22:37','2014-03-13 12:22:37',1,'eleven.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-14 21:56:24
