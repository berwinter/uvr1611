CREATE DATABASE  IF NOT EXISTS `uvr1611`;
USE `uvr1611`;

DROP TABLE IF EXISTS `t_datasets`;
CREATE TABLE `t_datasets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=133117 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_analogs`;
CREATE TABLE `t_analogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataset` int(11) NOT NULL,
  `value` float NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`frame`,`dataset`,`type`),
  KEY `dataset` (`dataset`),
  KEY `name` (`frame`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=15498505 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_digitals`;
CREATE TABLE `t_digitals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataset` int(11) NOT NULL,
  `value` bit(1) NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`dataset`,`frame`,`type`),
  KEY `dataset` (`dataset`),
  KEY `name` (`frame`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=1233216 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_energies`;
CREATE TABLE `t_energies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataset` int(11) NOT NULL,
  `value` decimal(10,1) NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`dataset`,`frame`,`type`),
  KEY `dataset` (`dataset`),
  KEY `name` (`frame`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=50489 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_powers`;
CREATE TABLE `t_powers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataset` int(11) NOT NULL,
  `value` float NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`frame`,`dataset`,`type`),
  KEY `dataset` (`dataset`),
  KEY `name` (`frame`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=188879 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_speeds`;
CREATE TABLE `t_speeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `digital` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`digital`),
  KEY `digital` (`digital`)
) ENGINE=MyISAM AUTO_INCREMENT=1677 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_menu`;
CREATE TABLE `t_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `type` enum('schema','line','power','energy') NOT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `schema` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_schema`;
CREATE TABLE `t_schema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`frame`,`type`),
  KEY `index` (`frame`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_names`;
CREATE TABLE `t_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','energy1','energy2','power1','power2') NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frame` (`frame`,`type`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_names_of_charts`;
CREATE TABLE `t_names_of_charts` (
  `name_id` int(11) NOT NULL,
  `chart_id` int(11) NOT NULL,
  PRIMARY KEY (`name_id`,`chart_id`),
  KEY `chart_id` (`chart_id`),
  KEY `name_id` (`name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
