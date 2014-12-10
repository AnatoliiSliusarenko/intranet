-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.17 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win64
-- HeidiSQL Версия:              9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица zectranet_com.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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
  `country` varchar(2) DEFAULT NULL,
  `lastofficeid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы zectranet_com.users: 28 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT IGNORE INTO `users` (`id`, `username`, `password`, `email`, `name`, `surname`, `registered`, `lastactive`, `active`, `avatar`, `country`, `lastofficeid`) VALUES
	(9, 'oligarch', 'aa777e050bacef1d2d74805b215ce1fa569468017d2db00bc168f4315d891c7bac8c1413ebbb3fc2d1bf6b0e0cb869b39ce0eba1f0fc5a584fff0eb94a6fb0f6', 'dj.slyusaar@gmail.com', 'Anatoliy', 'Slyusarenko', '2014-03-22 17:59:16', '2014-03-22 17:59:16', 1, '9OL8GnDI9zM.jpg', NULL, NULL),
	(13, 'gbsacl', 'b814e4095b9ba19a6a515eb409ddd313c409cfb9b20b3c411e56fd035185ead094ac1f1b73d93f1f1f067be1a01a71500005e30775a4d9002e95f1f6336da334', 'leesacker@gmail.com', 'lee', 'sacker', '2014-03-23 06:19:28', '2014-03-23 06:19:28', 1, 'press stop and error came but see this before at different times.jpg', NULL, NULL),
	(17, 'ruslan', '81cd68e2f920fe2fcdf7af2deb2cdd420cb852c66a8120ed3eac808ef6ba8e62822c6f858ca9d21aa46b8588403b559a6dafb6b3b0a448369ad9556c670bf66c', 'ruslan.lyalko@gmail.com', 'Ruslan', 'Lyalko', '2014-03-23 06:37:44', '2014-03-23 06:37:44', 1, 'TcXqbdsAiYA.jpg', NULL, NULL),
	(21, 'maxmuir', 'b814e4095b9ba19a6a515eb409ddd313c409cfb9b20b3c411e56fd035185ead094ac1f1b73d93f1f1f067be1a01a71500005e30775a4d9002e95f1f6336da334', 'maxmuir2@gmail.com', 'Max', 'Muir', '2014-03-23 07:14:01', '2014-03-23 07:14:01', 1, '519px-Tottenham_Hotspur.svg.png', NULL, NULL),
	(29, 'bill', '8b90bb90bca296191f9edb863eff1e3ec17e5ed0b27a8ee62f1e08bb22fb8b73cc3569b2cdc9c121074c6b5fb2f1e72fa13aed670e5e53fc5a89e043618ad12a', 'bill@ms.com', 'Bill', 'Gates', '2014-03-23 18:02:19', '2014-03-23 18:02:19', 1, 'eleven.png', NULL, NULL),
	(33, 'a', '52defde6c07a95c4e0426c394ff9701c27128f165cbab06cd0f4fa6ac34ff577afbaefb4f1b795e79b65fdda203acaa746c1b2ab5f97d1b9a1bb3bcd7c76f967', 'a@mail.ru', 'a', 'a', '2014-03-26 06:49:49', '2014-03-26 06:49:49', 1, 'eleven.png', NULL, NULL),
	(37, 'muttley', 'b814e4095b9ba19a6a515eb409ddd313c409cfb9b20b3c411e56fd035185ead094ac1f1b73d93f1f1f067be1a01a71500005e30775a4d9002e95f1f6336da334', 'muttley@gmail.com', 'Muttley', 'Muir', '2014-04-01 02:57:57', '2014-04-01 02:57:57', 1, 'eleven.png', NULL, NULL),
	(41, 'b', '40edef0c5af3aef4f02936a2df73ba81608e55120dc41b96faef9bfff2442c4110d520db6a7752ae4a6e80fca9b9f8b57d72470516fcc0ac8edc5466265349df', 'b@mail.ru', 'b', 'b', '2014-04-02 06:23:52', '2014-04-02 06:23:52', 1, 'eleven.png', NULL, NULL),
	(45, 'user', 'cce07c5fa918838c883849142d589b30d726c7f950ec1ea872ded13582bcc83456395cd972e2eb935be375c96adf2a26b1a1e4b4964e98f9601e36f1de31c1fe', 'ddd@ddd.cob', 'User', 'User', '2014-04-04 11:59:28', '2014-04-04 11:59:28', 1, 'eleven.png', NULL, NULL),
	(49, 'MrM', '524d2c2034a2edc315b269773635d43f0c3750fe9d426b3645ab556a5327a70f82b30cb47a6f35a26f67bf98f2cc53171565158f72e25934efc8413f85cc771d', 'marian.melnychuk@gmail.com', 'Marian', 'Melnychuk', '2014-04-14 06:49:15', '2014-04-14 06:49:15', 1, 'g-eYFJiZ0tg.jpg', NULL, NULL),
	(53, 'rmanzo', 'becf8aeddda887a2a862fbf8c0e765aba49f453faff2a0fd3ff69a5caa5dbe602ed9558e7c33a11f4d801eb7877481c720f9b2e30b995334d0207c5dc7f67a69', 'rino.manzo@gmail.com', 'Rino', 'Manzo', '2014-04-17 09:09:41', '2014-04-17 09:09:41', 1, 'eleven.png', NULL, NULL),
	(57, 'leedev', '11def698940ab8a454b903c9eb5384ea419b6bd5b4cad7910724c74552c547ad2a7ed867652b6e75e9635780940a092fe87fdad7dc1d6f9ea22f78756465ee75', 'leesacker@hotmail.com', 'lee', 'sackerdev', '2014-05-03 13:22:51', '2014-05-03 13:22:51', 1, 'eleven.png', NULL, NULL),
	(61, 'carl', '11def698940ab8a454b903c9eb5384ea419b6bd5b4cad7910724c74552c547ad2a7ed867652b6e75e9635780940a092fe87fdad7dc1d6f9ea22f78756465ee75', 'carlsmith158@gmail.com', 'carl', 'smith', '2014-05-03 13:26:19', '2014-05-03 13:26:19', 1, 'eleven.png', NULL, NULL),
	(65, 'adminuser', 'b814e4095b9ba19a6a515eb409ddd313c409cfb9b20b3c411e56fd035185ead094ac1f1b73d93f1f1f067be1a01a71500005e30775a4d9002e95f1f6336da334', 'adminuser@zectranet.com', 'Admin', 'User', '2014-05-08 04:43:26', '2014-05-08 04:43:26', 1, 'eleven.png', NULL, NULL),
	(69, 'Ktullhu', '12814172fff3ae362bf8c66325297b2fe02a47c9a44c016af0e1d4c6861ca2a4ef1bdf3506c065e462c1cea7b132d61f7d3d960f249d2dca69e3c1f9cbf817a5', 'Misha-gajdan@yandex.ru', 'Misha', 'Haydan', '2014-05-08 08:02:40', '2014-05-08 08:02:40', 1, 'eleven.png', NULL, NULL),
	(73, 'Sania', '9c72f264d1545d2cb78059983d9e077abbcd4ea74b4a296dcffa097581d1b145ec7b9154ef5c8743094be4a71989fa110614c5d586df96b64a72da7946f46526', 'sanuha740@gmail.com', 'Alex', 'Kozlov', '2014-05-08 08:02:43', '0000-00-00 00:00:00', 1, 'llll.jpg', NULL, 122),
	(74, 'olya', 'cce07c5fa918838c883849142d589b30d726c7f950ec1ea872ded13582bcc83456395cd972e2eb935be375c96adf2a26b1a1e4b4964e98f9601e36f1de31c1fe', 'olya@gmail.com', 'Olya', 'Ivanova', '2014-05-12 04:37:13', '2014-05-12 04:37:13', 1, 'eleven.png', NULL, NULL),
	(76, 'phil', 'd0322f670ec7f5ab9b36359267a19f55fd9fae408bd5def7364b4cfd8fb58f3d87f282d8dab252823296085bba8aa959caaeaafb2e423b14f0b293e53a94db50', 'philipgold72@gmail.com', 'philip', 'gold', '2014-06-09 17:25:23', '2014-06-09 17:25:23', 1, 'brentDM2504_228x283.jpg', NULL, NULL),
	(78, 'vleonard', '8bafb26b37026c865780a20d44e27c6bfd9adce33a458b99e7d1e37a4ce95a6a928094066d9331cfe4ebc575a09268536e48e9f373b9b9cfa7f0ac801e477d4f', 'vincent.leonard@outlook.com', 'Vincent', 'Leonard', '2014-09-11 13:02:08', '2014-09-11 13:02:08', 1, 'profile VL.jpg', NULL, NULL),
	(79, 'robj', '732131d1a5de34e4cf59f7fbe6980c984e64a898af80b6728a97ccbb3fdf0c4f325a86033fd886f95d84104977c625966203984a2910805118e1f0399f45edc6', 'robjeffers@hotmail.com', 'Rob ', 'Jeffers', '2014-09-22 11:16:36', '2014-09-22 11:16:36', 1, 'eleven.png', NULL, NULL),
	(80, 'tsviklinskyi92', 'eddf63919db617f04398c761bf59f186939c6d5e76db428dec528e68dfaabc38efff52740834d8209fd6a1d4fb0ff875e7fb9457540429b5e97df2a3f7c994d6', 'tsviklinskyi92@gmail.com', 'Vasyl', 'Tsviklinskyi', '2014-10-01 10:33:36', '2014-10-01 10:33:36', 1, 'eleven.png', NULL, NULL),
	(81, 'testuser', 'cce07c5fa918838c883849142d589b30d726c7f950ec1ea872ded13582bcc83456395cd972e2eb935be375c96adf2a26b1a1e4b4964e98f9601e36f1de31c1fe', 'test@gmail.com', 'Test', 'User', '2014-10-07 15:45:40', '2014-10-07 15:45:40', 1, 'eleven.png', NULL, NULL),
	(82, 'gingermare', '06a2872bbc84f44062ce2847682d8ee213b886b79d923a9edbe5b55590bd1440e904724f70520c64e8edf89a6a40433e98fd53dd5af8a3b0d097c52e94001b36', 'jeffscotton63@hotmail.com', 'Jeff', 'Scotton', '2014-10-07 19:41:29', '2014-10-07 19:41:29', 1, 'eleven.png', NULL, NULL),
	(83, '1', '11def698940ab8a454b903c9eb5384ea419b6bd5b4cad7910724c74552c547ad2a7ed867652b6e75e9635780940a092fe87fdad7dc1d6f9ea22f78756465ee75', '111@q.ua', '1', '1', '2014-10-08 12:34:45', '2014-10-08 12:34:45', 1, 'eleven.png', NULL, NULL),
	(84, 'oligarchs', '11def698940ab8a454b903c9eb5384ea419b6bd5b4cad7910724c74552c547ad2a7ed867652b6e75e9635780940a092fe87fdad7dc1d6f9ea22f78756465ee75', 'dj.slyussaar@gmail.com', 'ssss', 'sssss', '2014-10-15 09:19:59', '2014-10-15 09:19:59', 1, 'eleven.png', 'UA', NULL),
	(85, 'humpty12', '094261576e9a31b69f77081d9f7cadd592f31d0c872d251b50f96b80c4629009ebde64f4ae2e9c5a6d945676c8f5bce7d8972e474e04bd50dec04b35cc5bf4fe', 'vincent_leonard@hotmail.com', 'vince', 'Leonard', '2014-10-16 13:53:45', '2014-10-16 13:53:45', 1, 'eleven.png', 'UA', NULL),
	(86, 'tsviklinskyi', 'eddf63919db617f04398c761bf59f186939c6d5e76db428dec528e68dfaabc38efff52740834d8209fd6a1d4fb0ff875e7fb9457540429b5e97df2a3f7c994d6', 'blaster0001@mail.ru', 'Vasyl', 'Tsviklinskyi', '2014-10-01 10:33:36', '2014-10-01 10:33:36', 1, 'eleven.png', NULL, NULL),
	(87, 'Jay@SP', '15836267da32e5fb1e392a6cd2f84da1e0994206e41c5eccb9a2d0ad7d99c01d23208497c96e8cf220edddd187de8002fbcab7d868064e722ff552f0abae07d6', 'admin@sotograndepeople.com', 'Jay', 'Smithson', '2014-11-04 12:48:19', '2014-11-04 12:48:19', 1, 'eleven.png', 'UA', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
