/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`(50)),
  UNIQUE KEY `abbreviation` (`abbreviation`(10))
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `countries` VALUES (1,'- other -','00',1),(2,'United Arab Emirates','AE',1),(3,'Afghanistan','AF',1),(4,'Albania','AL',1),(5,'Armenia','AM',1),(6,'Netherlands Antilles','AN',1),(7,'Angola','AO',1),(8,'Argentina','AR',1),(9,'Austria','AT',1),(10,'Australia','AU',1),(11,'Aruba','AW',1),(12,'Azerbaijan','AZ',1),(13,'Bosnia & Herzegovina','BA',1),(14,'Barbados','BB',1),(15,'Bangladesh','BD',1),(16,'Belgium','BE',1),(17,'Burkino Faso','BF',1),(18,'Bulgaria','BG',1),(19,'Bahrain','BH',1),(20,'Burundi','BI',1),(21,'Benin','BJ',1),(22,'Bermuda','BM',1),(23,'Brunei Darussalam','BN',1),(24,'Bolivia','BO',1),(25,'Brazil','BR',1),(26,'Bahamas','BS',1),(27,'Bhutan','BT',1),(28,'Botswana','BW',1),(29,'Belarus','BY',1),(30,'Belize','BZ',1),(31,'Canada','CA',1),(32,'Central African Republic','CF',1),(33,'Republic of the Congo','CG',1),(34,'Switzerland','CH',1),(35,'Cote d\'lvoire','CI',1),(36,'Cook Islands','CK',1),(37,'Chile','CL',1),(38,'Cameroon','CM',1),(39,'China','CN',1),(40,'Colombia','CO',1),(41,'Costa Rica','CR',1),(42,'Cuba','CU',1),(43,'Cape Verde','CV',1),(44,'Cyprus','CY',1),(45,'Czech Republic','CZ',1),(46,'Germany','DE',1),(47,'Denmark','DK',1),(48,'Algeria','DZ',1),(49,'Ecuador','EC',1),(50,'Estonia','EE',1),(51,'Egypt','EG',1),(52,'Eritrea','ER',1),(53,'Spain','ES',1),(54,'Ethiopia','ET',1),(55,'Finland','FI',1),(56,'Fiji','FJ',1),(57,'Faroe Islands','FO',1),(58,'France','FR',1),(59,'Gabon','GA',1),(60,'Grenada','GD',1),(61,'Georgia','GE',1),(62,'Gibraltar','GI',1),(63,'Greenland','GL',1),(64,'Greece','GR',1),(65,'Guatemala','GT',1),(66,'Guam','GU',1),(67,'Guinea','GY',1),(68,'Hong Kong','HK',1),(69,'Croatia','HR',1),(70,'Haiti','HT',1),(71,'Hungary','HU',1),(72,'Indonesia','ID',1),(73,'Ireland','IE',1),(74,'Israel','IL',1),(75,'India','IN',1),(76,'Iraq','IQ',1),(77,'Iran','IR',1),(78,'Iceland','IS',1),(79,'Italy','IT',1),(80,'Jamaica','JM',1),(81,'Jordan','JO',1),(82,'Japan','JP',1),(83,'Kenya','KE',1),(84,'Kyrgyzstan','KG',1),(85,'Combodia','KH',1),(86,'Kiribati','KI',1),(87,'Korea (North)','KP',1),(88,'Korea (South)','KR',1),(89,'Cayman Islands','KY',1),(90,'Kazakhstan','KZ',1),(91,'Laos','LA',1),(92,'Lebanon','LB',1),(93,'Saint Lucia','LC',1),(94,'Sri Lanka','LK',1),(95,'Lithuania','LT',1),(96,'Luxembourg','LU',1),(97,'Latvia','LV',1),(98,'Libya','LY',1),(99,'Morocco','MA',1),(100,'Monaco','MC',1),(101,'Moldova','MD',1),(102,'Madagascar','MG',1),(103,'Mongolia','MN',1),(104,'Northern Mariana','MP',1),(105,'Martinique','MQ',1),(106,'Montserrat','MS',1),(107,'Mexico','MX',1),(108,'Malaysia','MY',1),(109,'Mozambique','MZ',1),(110,'Namibia','NA',1),(111,'Norfolk Island','NC',1),(112,'Netherlands','NL',1),(113,'Norway','NO',1),(114,'Nepal','NP',1),(115,'Nauru','NR',1),(116,'New Zealand','NZ',1),(117,'Oman','OM',1),(118,'Panama','PA',1),(119,'Peru','PE',1),(120,'French Polynesia','PF',1),(121,'Philippines','PH',1),(122,'Pakistan','PK',1),(123,'Poland','PL',1),(124,'St.Pierre and Miquelon','PM',1),(125,'Puerto Rico','PR',1),(126,'Portugal','PT',1),(127,'Paraguay','PY',1),(128,'Qatar','QA',1),(129,'Reunion','RE',1),(130,'Romania','RO',1),(131,'Russian Federation','RU',1),(132,'Saudi Arabia','SA',1),(133,'Solomon Islands','SB',1),(134,'Sudan','SD',1),(135,'Sweden','SE',1),(136,'Singapore','SG',1),(137,'Slovenia','SI',1),(138,'Slovak Republic','SK',1),(139,'Sierra Leone','SL',1),(140,'Somalia','SO',1),(141,'St.Helena','TC',1),(142,'Togo','TG',1),(143,'Thailand','TH',1),(144,'Tunisia','TN',1),(145,'Tonga','TO',1),(147,'Turkey','TR',1),(148,'Trinidad and Tobago','TT',1),(149,'Tuvalu','TV',1),(150,'Taiwan','TW',1),(151,'Tanzania','TZ',1),(152,'Ukraine','UA',1),(153,'Uganda','UG',1),(154,'United Kingdom','UK',1),(155,'United States','US',1),(156,'Uruguay','UY',1),(157,'Vativan City State','VA',1),(158,'Venezuela','VE',1),(159,'Virgin Islands (British)','VG',1),(160,'Virgin Islands (U.S.)','VI',1),(161,'Vietnam','VN',1),(162,'Samoa','WS',1),(163,'Yemen','YE',1),(164,'Yugoslavia','YU',1),(165,'South Africa','ZA',1),(166,'Zimbabwe','ZW',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deadlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `round` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `deadline` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demos` (
  `id` int(11) NOT NULL DEFAULT 0,
  `id_match` int(11) NOT NULL DEFAULT 0,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mappool` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `map` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_match` int(11) NOT NULL DEFAULT 0,
  `id_map` int(11) NOT NULL DEFAULT 0,
  `score_p1` int(11) NOT NULL DEFAULT 0,
  `score_p2` int(11) NOT NULL DEFAULT 0,
  `comment_p1` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_p2` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_admin` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_map` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_match` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `bracket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `round` int(11) NOT NULL DEFAULT 0,
  `match` int(11) NOT NULL DEFAULT 0,
  `wo` int(11) NOT NULL DEFAULT 0,
  `out` tinyint(4) NOT NULL DEFAULT 0,
  `bye` tinyint(4) NOT NULL DEFAULT 0,
  `id_player1` int(11) NOT NULL DEFAULT 0,
  `id_player2` int(11) NOT NULL DEFAULT 0,
  `num_winmaps` int(11) NOT NULL DEFAULT 0,
  `score_p1` int(11) NOT NULL DEFAULT 0,
  `score_p2` int(11) NOT NULL DEFAULT 0,
  `comment_admin` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `submitter` int(11) NOT NULL DEFAULT 0,
  `confirmed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `confirmer` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_news_group` int(11) NOT NULL DEFAULT 0,
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_news` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `season_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_season` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usertype_root` tinyint(4) NOT NULL DEFAULT 0,
  `usertype_headadmin` tinyint(4) NOT NULL DEFAULT 0,
  `usertype_admin` tinyint(4) NOT NULL DEFAULT 0,
  `usertype_player` tinyint(4) NOT NULL DEFAULT 0,
  `seedgroup` int(11) NOT NULL DEFAULT 0,
  `seedlevel` int(11) NOT NULL DEFAULT 0,
  `rejected` tinyint(4) NOT NULL DEFAULT 0,
  `invited` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci PACK_KEYS=1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `season_users` VALUES (1,0,1,'','0000-00-00 00:00:00',1,0,0,0,0,0,0,0);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `qualification` tinyint(4) NOT NULL DEFAULT 0,
  `single_elimination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `double_elimination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `winmaps` int(11) NOT NULL DEFAULT 0,
  `status` enum('signups','bracket','running','finished') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`(50))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL DEFAULT 0,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `irc_channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `notify` tinyint(4) NOT NULL DEFAULT 1,
  `submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `new_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`(50))
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `users` VALUES (1,0,'admin','ieL4JGsSS/Ljo','','',0,'0000-00-00 00:00:00','');
