CREATE DATABASE  IF NOT EXISTS `intranet` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `intranet`;
-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (i686)
--
-- Host: 127.0.0.1    Database: intranet
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.13.10.1

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
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `resourceid` int(11) DEFAULT NULL,
  `destinationid` int(11) DEFAULT NULL,
  `type` enum('task-discussion-start','task-specification-start','task-specification-finished','task-specification-approved','task-assigned','task-opened','task-inprogress-coding','task-inprogress-testing','task-inprogress-research','task-onhold-lunch','task-onhold-home','task-onhold-meeting','task-onhold-suspended','task-resolved','task-resolved-approved','task-closed','task-reopened') NOT NULL,
  `loged` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,1,3,4,'task-discussion-start','2014-04-07 13:02:13'),(2,2,5,7,'task-discussion-start','2014-04-07 13:06:13'),(3,1,9,5,'task-discussion-start','2014-04-07 13:12:13'),(4,2,1,1,'task-opened','2014-04-30 15:00:16'),(5,1,1,1,'task-opened','2014-04-30 15:00:23'),(6,3,1,1,'task-opened','2014-04-30 15:15:03'),(7,3,345,777,'task-closed','2014-04-30 16:04:51'),(8,3,345,777,'task-closed','2014-04-30 16:05:49'),(9,1,345,777,'task-closed','2014-04-30 16:08:49');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` enum('message_office','message_topic','membership_own','membership_user','removed_office','removed_topic','topic_added','membership_own_out','membership_user_out') DEFAULT NULL,
  `destinationid` int(11) DEFAULT NULL,
  `message` text,
  `activated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (11,2,'topic_added',2,'New topic Oligarch topic was added in Oligarch Office','2014-04-07 12:58:46'),(13,2,'message_topic',2,'New message from Anatoliy Slyusarenko in Oligarch topic','2014-04-07 12:59:57'),(15,2,'removed_topic',2,'Oligarch topic was delated!','2014-04-07 13:00:12'),(17,2,'topic_added',3,'New topic Bla Topic was added in Oligarch Office','2014-04-07 13:02:13'),(19,2,'topic_added',4,'New topic Talking about wether was added in Local','2014-04-07 13:05:43'),(20,2,'topic_added',5,'New topic \"Testtopic\" was added in \"Oligarch Office\"','2014-04-07 14:21:34'),(23,2,'topic_added',6,'New topic \"Publictopic\" was added in \"Local\"','2014-04-07 14:22:22'),(24,2,'topic_added',7,'New topic \"TolyaPublic\" was added in \"Public\"','2014-04-07 14:24:25'),(30,2,'topic_added',11,'New subtopic \"Nes public sub\" was added in \"TolyaPublic\"','2014-04-07 14:40:47'),(32,2,'topic_added',13,'New subtopic \"bla sub 1\" was added in \"Bla Topic\"','2014-04-07 16:53:48'),(35,2,'topic_added',15,'New topic \"This is TOPIC in public\" was added in \"Public\"','2014-04-07 17:01:22'),(37,2,'topic_added',16,'New subtopic \"subs\" was added in \"Nes public sub\"','2014-04-07 17:01:50'),(38,2,'topic_added',17,'New subtopic \"weeeee\" was added in \"bla sub 1\"','2014-04-10 11:02:58'),(39,2,'topic_added',18,'New subtopic \"weee 1.1\" was added in \"weeeee\"','2014-04-10 11:03:19'),(40,2,'topic_added',19,'New subtopic \"weee1.1.1\" was added in \"weee 1.1\"','2014-04-10 11:03:40'),(41,2,'topic_added',20,'New topic \"Task\" was added in \"Oligarch Office\"','2014-04-14 17:27:28'),(42,2,'topic_added',21,'New subtopic \"SubTask1\" was added in \"Task\"','2014-04-14 17:27:41'),(43,2,'topic_added',22,'New subtopic \"SubTask2\" was added in \"Task\"','2014-04-14 17:27:56'),(95,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-16 12:43:37'),(96,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-16 12:44:22'),(97,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-16 12:44:34'),(102,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 12:46:45'),(104,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 12:46:47'),(106,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 12:46:49'),(108,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-16 13:34:22'),(109,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 13:47:55'),(111,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 13:52:41'),(113,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-16 14:11:37'),(115,2,'topic_added',23,'New subtopic \"Good wether\" was added in \"Talking about wether\"','2014-04-23 11:33:49'),(118,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-25 15:03:15'),(120,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-25 15:38:43'),(122,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-25 15:40:51'),(135,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-25 19:11:25'),(137,2,'topic_added',25,'New topic \"ffff\" was added in \"Public\"','2014-04-25 19:11:35'),(139,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-25 19:38:03'),(141,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-28 13:55:25'),(143,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-28 19:49:08'),(145,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-28 19:54:23'),(147,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-28 19:55:28'),(149,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-28 20:00:08'),(150,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 14:40:32'),(151,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 14:40:43'),(152,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 14:40:54'),(153,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 14:43:18'),(154,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 14:44:39'),(155,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 15:21:27'),(156,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 15:26:23'),(157,2,'message_topic',1,'New message from Anatoliy Slyusarenko in \"Public\"','2014-04-29 15:26:58'),(159,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 15:29:10'),(160,2,'message_office',2,'New message from Anatoliy Slyusarenko in \"Oligarch Office\"','2014-04-29 15:33:59'),(165,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-29 17:51:55'),(167,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-29 17:54:36'),(169,2,'message_topic',1,'New message from Olya Ivanova in \"Public\"','2014-04-29 18:02:17');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `offices` VALUES (1,0,0,'Local','In this place you can browse your own offices and offices where you in. Also can create new offices.'),(2,1,1,'Oligarch Office','Oligarch Office'),(3,2,1,'SUbOffice1','SUbOffice1'),(4,1,3,'Olya Office','Olya Office');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_office`
--

DROP TABLE IF EXISTS `posts_office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `officeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `posted` datetime NOT NULL,
  `edited` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_office`
--

LOCK TABLES `posts_office` WRITE;
/*!40000 ALTER TABLE `posts_office` DISABLE KEYS */;
INSERT INTO `posts_office` VALUES (1,2,1,'Hello','2014-04-15 08:31:27','0000-00-00 00:00:00'),(2,2,1,'Today is the great day :-)','2014-04-15 08:31:39','0000-00-00 00:00:00'),(3,2,2,'Hi, Anatoliy. How are you?','2014-04-15 08:33:13','0000-00-00 00:00:00'),(4,2,1,'Thanks, great. And you?','2014-04-15 08:33:24','0000-00-00 00:00:00'),(5,2,1,'dgdfgdf','2014-04-15 10:39:58',NULL),(6,2,1,'dsdfsd','2014-04-15 10:49:31',NULL),(7,2,1,'dfgdfg','2014-04-15 11:00:05',NULL),(8,2,1,'смисм','2014-04-15 11:05:11',NULL),(9,2,1,'вап','2014-04-15 11:05:41',NULL),(10,2,1,'Привет','2014-04-15 12:21:06',NULL),(11,2,1,'іваіваіва','2014-04-15 12:34:55',NULL),(12,2,1,'sdfsfsdf111','2014-04-15 14:57:55','0000-00-00 00:00:00'),(13,2,1,'dfgdfgf7771111','2014-04-15 15:00:56','0000-00-00 00:00:00'),(14,2,1,'qqqttyt345','2014-04-15 15:01:27','0000-00-00 00:00:00'),(15,2,2,'dhfghfg','2014-04-15 15:29:17',NULL),(16,2,1,'vcbcvbcv111','2014-04-15 15:48:54','0000-00-00 00:00:00'),(17,2,1,'fffff33333111','2014-04-15 15:49:04','0000-00-00 00:00:00'),(18,2,2,'fgfgfgfg','2014-04-15 15:49:20',NULL),(19,2,1,'hjhjh232323вввв','2014-04-15 15:51:06','0000-00-00 00:00:00'),(20,2,1,'','2014-04-15 16:00:28','0000-00-00 00:00:00'),(21,2,1,'ывавс','2014-04-15 16:02:20','0000-00-00 00:00:00'),(22,2,1,'выаыввввввааааds','2014-04-15 16:06:36','0000-00-00 00:00:00'),(23,2,1,'выаыввввввааааdss111f','2014-04-15 16:08:50','0000-00-00 00:00:00'),(24,2,1,'вапвапв','2014-04-15 16:17:23','0000-00-00 00:00:00'),(25,2,1,'вапвпваввввввв1111','2014-04-15 16:18:19','0000-00-00 00:00:00'),(26,2,2,'skljhglk','2014-04-15 16:22:15',NULL),(27,2,1,'ллл111112222','2014-04-15 16:23:38','0000-00-00 00:00:00'),(28,2,2,'gjgh','2014-04-15 16:44:52',NULL),(29,2,1,'Hello!222','2014-04-16 07:35:05','0000-00-00 00:00:00'),(30,2,1,'cffffpp','2014-04-16 08:33:37','0000-00-00 00:00:00'),(31,2,1,'рррреее','2014-04-16 09:00:24','0000-00-00 00:00:00'),(32,2,1,'Hello','2014-04-16 09:13:25','0000-00-00 00:00:00'),(33,2,1,'вввву','2014-04-16 09:25:28','0000-00-00 00:00:00'),(34,2,1,'ііваівппп','2014-04-16 09:33:22','2014-04-16 12:36:27'),(35,2,1,'прапа','2014-04-16 09:36:33','2014-04-16 12:36:59'),(36,2,1,'апр','2014-04-16 09:36:34',NULL),(37,2,2,'fghfghfg','2014-04-16 09:43:03',NULL),(38,2,1,'авпв11111','2014-04-16 09:43:37','2014-04-16 12:43:46'),(39,2,1,'ллллллл111111','2014-04-16 09:44:22','2014-04-16 12:44:44'),(40,2,1,'ооооооо','2014-04-16 09:44:34',NULL),(41,2,1,'апап333','2014-04-16 10:34:22','2014-04-16 13:34:25'),(42,4,3,'hhhhh','2014-04-25 12:41:18',NULL),(43,4,3,'bbbb','2014-04-25 14:05:20',NULL),(44,4,3,'vbvbv','2014-04-25 14:38:35',NULL),(45,4,3,'vvvvvvv','2014-04-25 14:46:20',NULL),(46,4,3,'xxx','2014-04-25 14:46:43',NULL),(47,4,1,'heeelll','2014-04-25 16:09:59',NULL),(48,4,3,'fvbfbf','2014-04-25 16:11:15',NULL),(49,2,1,'fdfgdf','2014-04-29 11:40:32',NULL),(50,2,1,'kkkkkk','2014-04-29 11:40:43',NULL),(51,2,1,'ggg','2014-04-29 11:40:54',NULL),(52,2,1,'jjjjj','2014-04-29 11:43:18',NULL),(53,2,1,'ruito','2014-04-29 11:44:39',NULL),(54,2,1,'vvv','2014-04-29 12:21:27',NULL),(55,2,1,'kkkk','2014-04-29 12:26:23',NULL),(56,2,1,'kjk','2014-04-29 12:29:10',NULL),(57,2,1,'hghghghg','2014-04-29 12:33:59',NULL),(58,4,3,'bbbb','2014-04-29 13:07:17',NULL),(59,4,3,'vvv','2014-04-29 13:07:54',NULL),(60,4,1,'п','2014-04-29 13:17:24',NULL),(61,4,3,'sdfgdfgdf','2014-04-29 15:58:53',NULL),(62,4,3,'iutiti','2014-04-29 16:05:27',NULL);
/*!40000 ALTER TABLE `posts_office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_task`
--

DROP TABLE IF EXISTS `posts_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` text NOT NULL,
  `posted` datetime DEFAULT NULL,
  `edited` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_task`
--

LOCK TABLES `posts_task` WRITE;
/*!40000 ALTER TABLE `posts_task` DISABLE KEYS */;
INSERT INTO `posts_task` VALUES (1,17,1,'test just','2014-04-15 14:57:55','2014-04-15 14:59:55'),(2,17,1,'dddd','2014-04-29 11:00:11','2014-04-29 14:00:34'),(3,17,1,'gggg','2014-04-29 11:10:10',NULL),(4,17,1,'fgfgf','2014-04-29 11:20:20',NULL),(5,17,1,'fgfgf','2014-04-29 11:22:25',NULL),(6,17,1,'fgfgf','2014-04-29 11:32:46',NULL),(7,17,1,'fgfgf','2014-04-29 11:32:49',NULL),(8,17,1,'fgfgf','2014-04-29 11:32:52',NULL),(9,17,1,'fgfgf','2014-04-29 11:34:13',NULL),(10,17,1,'fgfgf','2014-04-29 11:34:16',NULL),(11,17,1,'fgfgf','2014-04-29 11:34:18',NULL),(12,17,1,'fgfgf','2014-04-29 11:34:36',NULL),(13,17,1,'yuiyyyyuikioi','2014-04-29 12:20:28','2014-04-29 15:20:39'),(14,17,1,'fggf COLL!','2014-04-29 12:20:51','2014-04-29 15:21:02'),(15,17,1,'ghjgh','2014-04-29 12:20:53',NULL),(16,3,1,'hhhfhfgh','2014-04-29 12:22:02',NULL),(17,17,1,'jjjjfrr','2014-04-29 12:24:07','2014-04-29 15:24:12'),(18,17,1,'hjkhjggg','2014-04-29 12:30:11','2014-04-29 15:30:14'),(19,15,1,'ghghjjjjjj','2014-04-29 12:30:20','2014-04-29 15:33:20'),(20,22,1,'hghgh','2014-04-29 12:33:49',NULL),(21,3,1,'крутяк!','2014-04-29 13:18:24',NULL),(22,18,1,'just test555','2014-04-29 14:35:57','2014-04-29 17:36:23'),(23,18,1,'ppp','2014-04-29 14:41:48',NULL),(24,13,1,'just testingbbbb','2014-04-30 10:21:29','2014-04-30 13:21:35'),(25,29,1,'vbnvbnvbjjjjj','2014-04-30 19:19:36','2014-04-30 22:19:44'),(26,53,1,'Chat for t1','2014-05-01 20:32:27',NULL);
/*!40000 ALTER TABLE `posts_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_topic`
--

DROP TABLE IF EXISTS `posts_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topicid` int(11) NOT NULL,
  `userid` varchar(45) NOT NULL,
  `message` longtext NOT NULL,
  `posted` datetime NOT NULL,
  `edited` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_topic`
--

LOCK TABLES `posts_topic` WRITE;
/*!40000 ALTER TABLE `posts_topic` DISABLE KEYS */;
INSERT INTO `posts_topic` VALUES (1,1,'1','bbb','2014-04-15 09:54:52',NULL),(2,1,'1','вапвапва','2014-04-15 16:25:28',NULL),(3,1,'1','впвап','2014-04-15 16:34:04',NULL),(4,1,'1','іва33333','2014-04-15 16:35:17','0000-00-00 00:00:00'),(5,1,'2','іваів','2014-04-15 16:35:44',NULL),(6,1,'1','ооонндд','2014-04-15 16:43:59','0000-00-00 00:00:00'),(7,1,'2','bvnv','2014-04-15 16:45:32',NULL),(8,1,'2','aaaaaaa','2014-04-16 09:46:17',NULL),(9,1,'2','bbbbbbbb','2014-04-16 09:46:20',NULL),(10,1,'1','aaaa1111112222','2014-04-16 09:46:45','2014-04-16 12:47:45'),(11,1,'1','bbbbbbbb','2014-04-16 09:46:47',NULL),(12,1,'1','cccccccc','2014-04-16 09:46:49',NULL),(13,1,'1','роннн','2014-04-16 10:47:55','2014-04-16 13:48:00'),(14,1,'1','ьртиль','2014-04-16 10:52:41',NULL),(15,1,'1','пропроррррььььь','2014-04-16 11:11:37','2014-04-16 14:15:26'),(16,1,'3','Test!','2014-04-25 12:03:15',NULL),(17,1,'3','gggg','2014-04-25 12:38:43',NULL),(18,1,'3','vvvv','2014-04-25 12:40:50',NULL),(19,24,'3','vbvbv','2014-04-25 14:38:43',NULL),(20,24,'1','vvvvvv','2014-04-25 16:10:11',NULL),(21,24,'1','dddd','2014-04-25 16:10:29',NULL),(22,1,'3','dgdg','2014-04-25 16:11:25',NULL),(23,1,'3','gggg','2014-04-25 16:38:02',NULL),(24,1,'3','Hello!!!!','2014-04-28 10:55:24',NULL),(25,1,'3','nnnn','2014-04-28 16:49:07',NULL),(26,1,'3','test','2014-04-28 16:54:23',NULL),(27,1,'3','jjjj','2014-04-28 16:55:28',NULL),(28,1,'3','hhhh','2014-04-28 17:00:07',NULL),(29,1,'1','fghfg','2014-04-29 12:26:58',NULL),(30,1,'3',',m,.m,','2014-04-29 14:51:54',NULL),(31,1,'3','bnmbn','2014-04-29 14:54:36',NULL),(32,1,'3','test now','2014-04-29 15:02:17',NULL);
/*!40000 ALTER TABLE `posts_topic` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ROLE_ADMIN',''),(2,'ROLE_USER',''),(3,'ROLE_DEV',''),(4,'ROLE_CLIENT','');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_status_role`
--

DROP TABLE IF EXISTS `task_status_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_status_role` (
  `statusid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_status_role`
--

LOCK TABLES `task_status_role` WRITE;
/*!40000 ALTER TABLE `task_status_role` DISABLE KEYS */;
INSERT INTO `task_status_role` VALUES (1,1),(1,3),(2,1),(2,3),(3,1),(3,3),(4,1),(4,3),(5,1),(5,4),(6,1),(6,4),(7,1),(7,3),(8,1),(8,3),(9,1),(9,3),(10,1),(10,3),(11,1),(11,3),(12,1),(12,3),(13,1),(13,3),(14,1),(14,3),(15,1),(15,4),(16,1),(16,4),(17,1),(17,3);
/*!40000 ALTER TABLE `task_status_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_status_transitions`
--

DROP TABLE IF EXISTS `task_status_transitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_status_transitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_status_transitions`
--

LOCK TABLES `task_status_transitions` WRITE;
/*!40000 ALTER TABLE `task_status_transitions` DISABLE KEYS */;
INSERT INTO `task_status_transitions` VALUES (1,1,2),(2,1,3),(3,1,4),(4,2,3),(5,2,4),(6,3,2),(7,3,4),(8,4,5),(9,4,6),(10,5,6),(11,5,7),(12,6,2),(13,7,8),(14,7,9),(15,7,14),(16,8,7),(17,8,9),(18,8,14),(19,9,7),(20,9,8),(21,9,14),(22,14,15),(23,14,16),(24,15,16),(25,15,17),(26,16,2),(27,17,2),(28,17,3),(29,17,4),(30,2,10),(31,2,11),(32,2,12),(33,2,13),(34,3,10),(35,3,11),(36,3,12),(37,3,13),(38,7,10),(39,7,11),(40,7,12),(41,7,13),(42,8,10),(43,8,11),(44,8,12),(45,8,13),(46,9,10),(47,9,11),(48,9,12),(49,9,13),(50,10,11),(51,10,12),(52,10,13),(53,10,2),(54,10,3),(55,10,7),(56,10,8),(57,10,9),(58,11,10),(59,11,12),(60,11,13),(61,11,2),(62,11,3),(63,11,7),(64,11,8),(65,11,9),(66,12,10),(67,12,11),(68,12,13),(69,12,2),(70,12,3),(71,12,7),(72,12,8),(73,12,9),(74,13,10),(75,13,11),(76,13,12),(77,13,2),(78,13,3),(79,13,7),(80,13,8),(81,13,9);
/*!40000 ALTER TABLE `task_status_transitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_statuses`
--

DROP TABLE IF EXISTS `task_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#000000',
  `init_startdate` tinyint(1) DEFAULT '0',
  `update_estimate` tinyint(1) DEFAULT '0',
  `update_enddate` tinyint(1) DEFAULT '0',
  `initial` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_statuses`
--

LOCK TABLES `task_statuses` WRITE;
/*!40000 ALTER TABLE `task_statuses` DISABLE KEYS */;
INSERT INTO `task_statuses` VALUES (1,'Opened','#000000',0,0,0,1),(2,'In-discussion','#000000',1,0,0,1),(3,'In-specification','#000000',1,0,0,1),(4,'Specification: finished','#000000',1,1,0,1),(5,'Specification: approved','#000000',0,0,0,0),(6,'Specification: declined','#000000',0,0,0,0),(7,'In-progress: coding','#000000',0,0,0,0),(8,'In-progress: testing','#000000',0,0,0,0),(9,'In-progress: research','#000000',0,0,0,0),(10,'On-hold: home','#000000',0,0,0,0),(11,'On-hold: meeting','#000000',0,0,0,0),(12,'On-hold: suspended','#000000',0,0,0,0),(13,'On-hold: lunch','#000000',0,0,0,0),(14,'Resolved','#000000',0,0,0,0),(15,'Resolved: approved','#000000',0,0,1,0),(16,'Resolved: declined','#000000',0,0,0,0),(17,'Re-opened','#000000',0,0,0,0);
/*!40000 ALTER TABLE `task_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT NULL,
  `officeid` int(11) NOT NULL,
  `userid` int(11) DEFAULT '0',
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'low',
  `name` varchar(255) NOT NULL,
  `statusid` int(11) NOT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (3,NULL,2,1,'high','Third Task222222',1,'2014-04-10 16:30:32',NULL),(8,NULL,4,1,'low','First',1,'2014-04-11 12:11:47',NULL),(11,NULL,4,1,'low','llll',1,'2014-04-11 12:15:54',NULL),(13,NULL,2,1,'low','Task for SubTask1',1,'2014-04-14 17:28:33',NULL),(15,NULL,2,1,'low','Task2 for SubTask1',1,'2014-04-15 11:14:40','2014-04-24 16:34:59'),(16,NULL,4,1,'low','dfddf',1,'2014-04-22 01:38:57',NULL),(17,NULL,2,1,'high','eeeee',1,'2014-04-22 14:20:46',NULL),(18,NULL,1,2,'high','New Taskeeeee',1,'2014-04-23 02:11:09',NULL),(19,NULL,1,NULL,'low','task2',1,'2014-04-23 02:11:53',NULL),(20,NULL,1,1,'high','Good wether task',1,'2014-04-23 17:33:30',NULL),(21,NULL,1,1,'high','Good wether task',1,'2014-04-23 22:07:13',NULL),(22,NULL,2,NULL,'medium','test medium',1,'2014-04-24 15:35:36',NULL),(23,NULL,2,NULL,'high','ккккк',1,NULL,NULL),(24,NULL,2,NULL,'high','test',1,'2014-04-30 21:40:46',NULL),(25,NULL,2,NULL,'high','jjjjjj',1,NULL,NULL),(26,NULL,2,2,'high','ffffg',1,'2014-05-01 22:54:22',NULL),(27,NULL,2,1,'high','wetryretyr',1,NULL,NULL),(30,NULL,1,2,'high','gfgfhgjghjg',1,NULL,NULL),(31,NULL,1,NULL,'high','апвпаапапрап',1,NULL,NULL),(32,NULL,1,2,'high','выапывав',1,NULL,NULL),(33,NULL,1,NULL,'high','ваправпрапр',1,NULL,NULL),(34,NULL,1,NULL,'high','вапвапва',1,NULL,NULL),(35,NULL,1,NULL,'high','папрапроапорап',1,NULL,NULL),(36,NULL,2,3,'high','testSubTask',1,NULL,NULL),(37,NULL,2,2,'low','another SubTask',1,'2014-04-30 21:40:46',NULL),(38,NULL,2,3,'high','dghfhfgfffffff',1,NULL,NULL),(39,NULL,2,NULL,'high','ghkhjhjkhjkj',1,NULL,NULL),(40,NULL,2,NULL,'high','xxxxxx',1,NULL,NULL),(41,NULL,2,1,'high','tttttttt',1,NULL,NULL),(42,NULL,2,NULL,'high','123',1,NULL,NULL),(43,NULL,2,NULL,'high','dfdgd',1,NULL,NULL),(44,NULL,2,2,'high','oooooo',1,NULL,NULL),(45,NULL,2,NULL,'high','ddddddd',1,NULL,NULL),(46,NULL,2,NULL,'high','dgfhdfgfg11111',1,NULL,NULL),(47,NULL,2,3,'low','jojojo',1,'2014-05-01 22:29:42',NULL),(48,NULL,2,NULL,'high','dfgdfgdf',1,NULL,NULL),(49,NULL,2,NULL,'high','ууууііііііп',1,NULL,NULL),(50,NULL,2,NULL,'high','more medium',1,NULL,NULL),(51,NULL,2,NULL,'high','jojo Real',1,NULL,NULL),(52,40,2,3,'high','yyyyyyy for xxxdddd',1,NULL,NULL),(53,41,2,NULL,'high','t1',1,NULL,NULL),(54,47,2,NULL,'high','jo1.1',1,NULL,NULL),(55,24,2,NULL,'high','test1',1,NULL,NULL),(56,42,2,NULL,'high','321',1,NULL,NULL),(57,42,2,NULL,'high','dfdfgdf',1,NULL,NULL),(58,49,2,NULL,'high','tttreyyyyyui',1,NULL,NULL),(59,50,2,1,'high','med',1,NULL,NULL),(60,44,2,2,'medium','ooo2',1,NULL,NULL),(61,NULL,2,NULL,'high','rerterter',1,NULL,NULL),(62,NULL,2,NULL,'high','ggggg',1,NULL,NULL),(63,47,2,NULL,'high','rrtrtr',1,NULL,NULL),(64,54,2,NULL,'high','ggfjkljklyuyrrewrkjmnghtg',1,NULL,NULL),(65,NULL,2,NULL,'high','teettetet',1,NULL,NULL),(66,45,2,NULL,'high','d1',1,NULL,NULL),(67,45,2,NULL,'high','d2',1,NULL,NULL),(68,23,2,NULL,'high','one more',1,NULL,NULL),(69,60,2,NULL,'high','рнрррррр',1,NULL,NULL),(70,51,2,NULL,'high','ппппп',1,NULL,NULL),(71,51,2,3,'high','вввввв',1,NULL,'2014-05-01 23:24:55'),(72,13,2,3,'high','ллллл',1,NULL,NULL),(73,NULL,1,NULL,'high','rrrrrr',1,NULL,NULL),(74,NULL,1,NULL,'high','test disapear',1,NULL,NULL),(75,73,1,3,'high','r1',1,NULL,NULL),(76,18,1,1,'high','fffffff',1,NULL,NULL),(77,35,1,NULL,'high','mmmmmm',1,NULL,NULL),(78,49,2,2,'high','оьоооо',1,NULL,NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic_task`
--

DROP TABLE IF EXISTS `topic_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic_task` (
  `topic_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic_task`
--

LOCK TABLES `topic_task` WRITE;
/*!40000 ALTER TABLE `topic_task` DISABLE KEYS */;
INSERT INTO `topic_task` VALUES (19,13),(5,3),(17,15),(17,13),(18,13),(5,17),(13,17),(6,18),(7,18),(4,19),(15,19),(24,8),(13,26),(4,35),(3,23),(17,23),(22,23),(3,36),(3,42),(13,42),(13,45),(17,45),(3,47),(13,47),(17,50),(18,50),(19,50),(18,59),(17,37),(22,37),(3,26),(5,26),(17,44),(18,44),(19,44),(17,60),(13,24),(17,24),(17,51),(18,51),(19,51),(20,51),(20,70),(19,71),(19,72),(18,72),(4,73),(6,73),(7,76),(7,32),(4,77),(6,35),(7,35),(18,26),(19,26);
/*!40000 ALTER TABLE `topic_task` ENABLE KEYS */;
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
  `officeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_91F64639D823E37A` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (1,0,1,0,'Public','Here you can post topics for all users.'),(3,1,2,1,'Bla Topic','Bla Topic'),(4,1,1,1,'Talking about wether','Talking about wether'),(5,1,2,1,'Testtopic','Testtopic'),(6,1,1,3,'Publictopic','Publictopic'),(7,1,1,1,'TolyaPublic','TolyaPublic'),(8,1,3,1,'Ura!','Ura!'),(9,8,3,1,'Ura Subtopic','Ura Subtopic'),(10,9,3,1,'Ura Sub Sub','Ura Sub Sub'),(11,7,1,1,'Nes public sub','Nes public sub'),(12,10,3,3,'dfsdf','sdfsd'),(13,3,2,3,'bla sub 1','bla sub 1'),(14,1,3,1,'ffff','ffff'),(15,1,1,3,'This is TOPIC in public','This is TOPIC'),(16,11,1,3,'subs','subs'),(17,13,2,1,'weeeee','weeeee'),(18,17,2,1,'weee 1.1','weee 1.1'),(19,18,2,1,'weee1.1.1','weee1.1.1'),(20,1,2,1,'Task','Task'),(21,20,2,1,'SubTask1','SubTask1'),(22,20,2,1,'SubTask2','SubTask2'),(23,4,1,1,'Good wether','Good wether'),(24,1,4,3,'tedtt','dffd'),(25,1,1,3,'ffff','ffff');
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
INSERT INTO `user_office` VALUES (1,1),(2,1),(3,1),(1,2),(2,2),(1,3),(3,3),(3,4),(1,4);
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
INSERT INTO `user_role` VALUES (2,2),(3,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'oligarch','aa777e050bacef1d2d74805b215ce1fa569468017d2db00bc168f4315d891c7bac8c1413ebbb3fc2d1bf6b0e0cb869b39ce0eba1f0fc5a584fff0eb94a6fb0f6','dj.slyusar@rambler.ru','Anatoliy','Slyusarenko','2014-04-07 12:19:11','2014-04-07 12:19:11',1,'eleven.png'),(2,'ruslan','cce07c5fa918838c883849142d589b30d726c7f950ec1ea872ded13582bcc83456395cd972e2eb935be375c96adf2a26b1a1e4b4964e98f9601e36f1de31c1fe','ruslan.lyalko@gmail.com','Ruslan','Lyalko','2014-04-07 12:20:33','2014-04-07 12:20:33',1,'eleven.png'),(3,'olya','cce07c5fa918838c883849142d589b30d726c7f950ec1ea872ded13582bcc83456395cd972e2eb935be375c96adf2a26b1a1e4b4964e98f9601e36f1de31c1fe','olya@gmail.com','Olya','Ivanova','2014-04-07 12:21:04','2014-04-07 12:21:04',1,'eleven.png');
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

-- Dump completed on 2014-05-03  1:11:49
