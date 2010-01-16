-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 01, 2006 at 12:36 AM
-- Server version: 5.0.20
-- PHP Version: 5.0.4
-- 
-- Database: `phptourney`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_bans`
-- 

CREATE TABLE `phpt_bans` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_bans`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_countries`
-- 

CREATE TABLE `phpt_countries` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `abbreviation` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `country` (`name`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- 
-- Dumping data for table `phpt_countries`
-- 

INSERT INTO `phpt_countries` (`id`, `name`, `abbreviation`, `active`) VALUES (1, '- other -', '00', 1),
(2, 'United Arab Emirates', 'AE', 0),
(3, 'Afghanistan', 'AF', 0),
(4, 'Albania', 'AL', 0),
(5, 'Armenia', 'AM', 0),
(6, 'Netherlands Antilles', 'AN', 0),
(7, 'Angola', 'AO', 0),
(8, 'Argentina', 'AR', 0),
(9, 'Austria', 'AT', 1),
(10, 'Australia', 'AU', 1),
(11, 'Aruba', 'AW', 0),
(12, 'Azerbaijan', 'AZ', 0),
(13, 'Bosnia & Herzegovina', 'BA', 0),
(14, 'Barbados', 'BB', 0),
(15, 'Bangladesh', 'BD', 0),
(16, 'Belgium', 'BE', 1),
(17, 'Burkino Faso', 'BF', 0),
(18, 'Bulgaria', 'BG', 0),
(19, 'Bahrain', 'BH', 0),
(20, 'Burundi', 'BI', 0),
(21, 'Benin', 'BJ', 0),
(22, 'Bermuda', 'BM', 0),
(23, 'Brunei Darussalam', 'BN', 0),
(24, 'Bolivia', 'BO', 0),
(25, 'Brazil', 'BR', 1),
(26, 'Bahamas', 'BS', 0),
(27, 'Bhutan', 'BT', 0),
(28, 'Botswana', 'BW', 0),
(29, 'Belarus', 'BY', 0),
(30, 'Belize', 'BZ', 0),
(31, 'Canada', 'CA', 1),
(32, 'Central African Republic', 'CF', 0),
(33, 'Republic of the Congo', 'CG', 0),
(34, 'Switzerland', 'CH', 1),
(35, 'Cote d''lvoire', 'CI', 0),
(36, 'Cook Islands', 'CK', 0),
(37, 'Chile', 'CL', 0),
(38, 'Cameroon', 'CM', 0),
(39, 'China', 'CN', 0),
(40, 'Colombia', 'CO', 0),
(41, 'Costa Rica', 'CR', 0),
(42, 'Cuba', 'CU', 0),
(43, 'Cape Verde', 'CV', 0),
(44, 'Cyprus', 'CY', 0),
(45, 'Czech Republic', 'CZ', 1),
(46, 'Germany', 'DE', 1),
(47, 'Denmark', 'DK', 1),
(48, 'Algeria', 'DZ', 0),
(49, 'Ecuador', 'EC', 0),
(50, 'Estonia', 'EE', 1),
(51, 'Egypt', 'EG', 0),
(52, 'Eritrea', 'ER', 0),
(53, 'Spain', 'ES', 1),
(54, 'Ethiopia', 'ET', 0),
(55, 'Finland', 'FI', 1),
(56, 'Fiji', 'FJ', 0),
(57, 'Faroe Islands', 'FO', 0),
(58, 'France', 'FR', 1),
(59, 'Gabon', 'GA', 0),
(60, 'Grenada', 'GD', 0),
(61, 'Georgia', 'GE', 0),
(62, 'Gibraltar', 'GI', 0),
(63, 'Greenland', 'GL', 0),
(64, 'Greece', 'GR', 1),
(65, 'Guatemala', 'GT', 0),
(66, 'Guam', 'GU', 0),
(67, 'Guinea', 'GY', 0),
(68, 'Hong Kong', 'HK', 0),
(69, 'Croatia', 'HR', 0),
(70, 'Haiti', 'HT', 0),
(71, 'Hungary', 'HU', 1),
(72, 'Indonesia', 'ID', 0),
(73, 'Ireland', 'IE', 1),
(74, 'Israel', 'IL', 0),
(75, 'India', 'IN', 0),
(76, 'Iraq', 'IQ', 0),
(77, 'Iran', 'IR', 0),
(78, 'Iceland', 'IS', 1),
(79, 'Italy', 'IT', 1),
(80, 'Jamaica', 'JM', 0),
(81, 'Jordan', 'JO', 0),
(82, 'Japan', 'JP', 0),
(83, 'Kenya', 'KE', 0),
(84, 'Kyrgyzstan', 'KG', 0),
(85, 'Combodia', 'KH', 0),
(86, 'Kiribati', 'KI', 0),
(87, 'Korea (North)', 'KP', 0),
(88, 'Korea (South)', 'KR', 0),
(89, 'Cayman Islands', 'KY', 0),
(90, 'Kazakhstan', 'KZ', 0),
(91, 'Laos', 'LA', 0),
(92, 'Lebanon', 'LB', 0),
(93, 'Saint Lucia', 'LC', 0),
(94, 'Sri Lanka', 'LK', 0),
(95, 'Lithuania', 'LT', 0),
(96, 'Luxembourg', 'LU', 1),
(97, 'Latvia', 'LV', 0),
(98, 'Libya', 'LY', 0),
(99, 'Morocco', 'MA', 0),
(100, 'Monaco', 'MC', 0),
(101, 'Moldova', 'MD', 0),
(102, 'Madagascar', 'MG', 0),
(103, 'Mongolia', 'MN', 0),
(104, 'Northern Mariana', 'MP', 0),
(105, 'Martinique', 'MQ', 0),
(106, 'Montserrat', 'MS', 0),
(107, 'Mexico', 'MX', 1),
(108, 'Malaysia', 'MY', 0),
(109, 'Mozambique', 'MZ', 0),
(110, 'Namibia', 'NA', 0),
(111, 'Norfolk Island', 'NC', 0),
(112, 'Netherlands', 'NL', 1),
(113, 'Norway', 'NO', 1),
(114, 'Nepal', 'NP', 0),
(115, 'Nauru', 'NR', 0),
(116, 'New Zealand', 'NZ', 0),
(117, 'Oman', 'OM', 0),
(118, 'Panama', 'PA', 0),
(119, 'Peru', 'PE', 0),
(120, 'French Polynesia', 'PF', 0),
(121, 'Philippines', 'PH', 0),
(122, 'Pakistan', 'PK', 0),
(123, 'Poland', 'PL', 1),
(124, 'St.Pierre and Miquelon', 'PM', 0),
(125, 'Puerto Rico', 'PR', 0),
(126, 'Portugal', 'PT', 1),
(127, 'Paraguay', 'PY', 0),
(128, 'Qatar', 'QA', 0),
(129, 'Reunion', 'RE', 0),
(130, 'Romania', 'RO', 1),
(131, 'Russian Federation', 'RU', 1),
(132, 'Saudi Arabia', 'SA', 0),
(133, 'Solomon Islands', 'SB', 0),
(134, 'Sudan', 'SD', 0),
(135, 'Sweden', 'SE', 1),
(136, 'Singapore', 'SG', 0),
(137, 'Slovenia', 'SI', 0),
(138, 'Slovak Republic', 'SK', 1),
(139, 'Sierra Leone', 'SL', 0),
(140, 'Somalia', 'SO', 0),
(141, 'St.Helena', 'TC', 0),
(142, 'Togo', 'TG', 0),
(143, 'Thailand', 'TH', 0),
(144, 'Tunisia', 'TN', 0),
(145, 'Tonga', 'TO', 0),
(147, 'Turkey', 'TR', 0),
(148, 'Trinidad and Tobago', 'TT', 0),
(149, 'Tuvalu', 'TV', 0),
(150, 'Taiwan', 'TW', 0),
(151, 'Tanzania', 'TZ', 0),
(152, 'Ukraine', 'UA', 1),
(153, 'Uganda', 'UG', 0),
(154, 'United Kingdom', 'UK', 1),
(155, 'United States', 'US', 1),
(156, 'Uruguay', 'UY', 0),
(157, 'Vativan City State', 'VA', 0),
(158, 'Venezuela', 'VE', 0),
(159, 'Virgin Islands (British)', 'VG', 0),
(160, 'Virgin Islands (U.S.)', 'VI', 0),
(161, 'Vietnam', 'VN', 0),
(162, 'Samoa', 'WS', 0),
(163, 'Yemen', 'YE', 0),
(164, 'Yugoslavia', 'YU', 0),
(165, 'South Africa', 'ZA', 0),
(166, 'Zimbabwe', 'ZW', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_deadlines`
-- 

CREATE TABLE `phpt_deadlines` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `round` varchar(255) NOT NULL default '',
  `deadline` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_deadlines`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_demos`
-- 

CREATE TABLE `phpt_demos` (
  `id` int(11) NOT NULL default '0',
  `id_match` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_demos`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_mappool`
-- 

CREATE TABLE `phpt_mappool` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `map` varchar(255) NOT NULL default '',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_mappool`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_maps`
-- 

CREATE TABLE `phpt_maps` (
  `id` int(11) NOT NULL auto_increment,
  `id_match` int(11) NOT NULL default '0',
  `id_map` int(11) NOT NULL default '0',
  `score_p1` int(11) NOT NULL default '0',
  `score_p2` int(11) NOT NULL default '0',
  `comment_p1` text NOT NULL,
  `comment_p2` text NOT NULL,
  `comment_admin` text NOT NULL,
  `num_map` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_maps`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_match_comments`
-- 

CREATE TABLE `phpt_match_comments` (
  `id` int(11) NOT NULL auto_increment,
  `id_match` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_match_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_matches`
-- 

CREATE TABLE `phpt_matches` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `bracket` varchar(255) NOT NULL default '',
  `round` int(11) NOT NULL default '0',
  `match` int(11) NOT NULL default '0',
  `wo` int(11) NOT NULL default '0',
  `out` tinyint(4) NOT NULL default '0',
  `bye` tinyint(4) NOT NULL default '0',
  `id_player1` int(11) NOT NULL default '0',
  `id_player2` int(11) NOT NULL default '0',
  `num_winmaps` int(11) NOT NULL default '0',
  `score_p1` int(11) NOT NULL default '0',
  `score_p2` int(11) NOT NULL default '0',
  `comment_admin` text NOT NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `submitter` int(11) NOT NULL default '0',
  `confirmed` datetime NOT NULL default '0000-00-00 00:00:00',
  `confirmer` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_matches`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_news`
-- 

CREATE TABLE `phpt_news` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `id_news_group` int(11) NOT NULL default '0',
  `heading` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_news`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_news_comments`
-- 

CREATE TABLE `phpt_news_comments` (
  `id` int(11) NOT NULL auto_increment,
  `id_news` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_news_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_rules`
-- 

CREATE TABLE `phpt_rules` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- 
-- Dumping data for table `phpt_rules`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_season_users`
-- 

CREATE TABLE `phpt_season_users` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `usertype_root` tinyint(4) NOT NULL default '0',
  `usertype_headadmin` tinyint(4) NOT NULL default '0',
  `usertype_admin` tinyint(4) NOT NULL default '0',
  `usertype_player` tinyint(4) NOT NULL default '0',
  `seedgroup` int(11) NOT NULL default '0',
  `seedlevel` int(11) NOT NULL default '0',
  `rejected` tinyint(4) NOT NULL default '0',
  `invited` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- 
-- Dumping data for table `phpt_season_users`
-- 

INSERT INTO `phpt_season_users` (`id`, `id_season`, `id_user`, `ip`, `submitted`, `usertype_root`, `usertype_headadmin`, `usertype_admin`, `usertype_player`, `seedgroup`, `seedlevel`, `rejected`, `invited`) VALUES (1, 0, 1, '', '0000-00-00 00:00:00', 1, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_seasons`
-- 

CREATE TABLE `phpt_seasons` (
  `id` int(11) NOT NULL auto_increment,
  `id_section` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `qualification` tinyint(4) NOT NULL default '0',
  `single_elimination` varchar(255) NOT NULL default '',
  `double_elimination` varchar(255) NOT NULL default '',
  `winmaps` int(11) NOT NULL default '0',
  `status` enum('signups','bracket','running','finished') default NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_seasons`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_sections`
-- 

CREATE TABLE `phpt_sections` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `abbreviation` varchar(255) NOT NULL default '',
  `charset` varchar(255) NOT NULL default 'ISO-8859-1',
  `bot_host` varchar(255) NOT NULL default '',
  `bot_port` varchar(255) NOT NULL default '',
  `bot_password` varchar(255) NOT NULL default '',
  `admin_irc_channels` varchar(255) NOT NULL default '',
  `public_irc_channels` varchar(255) NOT NULL default '',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- 
-- Dumping data for table `phpt_sections`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_signup_polls`
-- 

CREATE TABLE `phpt_signup_polls` (
  `id` int(11) NOT NULL auto_increment,
  `id_season` int(11) NOT NULL default '0',
  `choices` varchar(255) NOT NULL default '',
  `heading` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_signup_polls`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_signup_votes`
-- 

CREATE TABLE `phpt_signup_votes` (
  `id` int(11) NOT NULL default '0',
  `id_poll` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `vote` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `phpt_signup_votes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `phpt_users`
-- 

CREATE TABLE `phpt_users` (
  `id` int(11) NOT NULL auto_increment,
  `id_country` int(11) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `irc_channel` varchar(255) NOT NULL default '',
  `notify` tinyint(4) NOT NULL default '1',
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `new_password` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- 
-- Dumping data for table `phpt_users`
-- 

INSERT INTO `phpt_users` (`id`, `id_country`, `username`, `password`, `email`, `irc_channel`, `notify`, `submitted`, `new_password`) VALUES (1, 0, 'admin', 'ieL4JGsSS/Ljo', '', '', 0, '0000-00-00 00:00:00', '');
