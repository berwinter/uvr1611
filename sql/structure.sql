CREATE DATABASE  IF NOT EXISTS `uvr1611`;
USE `uvr1611`;

DROP TABLE IF EXISTS `t_chartoptions`;
CREATE TABLE `t_chartoptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chard_id` int(11) NOT NULL,
  `property` varchar(120) NOT NULL,
  `value` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `t_users`;
CREATE TABLE `t_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `t_users` (`id`,`username`,`password`,`salt`) VALUES (0,'admin','40e2776165475d893e923da0fc9039569bad50e7f88e0ff07e11ad8bffd51019c7c0ab2709395c3599f4bebd6a6bd927e9c9470a638e1eef8e8cb971061d7412','80125411a211653c6b76a9c5b9b12b6406a4a53ab61543d31abf626cda4a58ba5c8d2411a5011ad2b61de2cecd07e02b6ec9ad7a2513299d977e34b5c3f76df0');

DROP TABLE IF EXISTS `t_data`;
CREATE TABLE `t_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `analog1` float,
  `analog2` float,
  `analog3` float,
  `analog4` float,
  `analog5` float,
  `analog6` float,
  `analog7` float,
  `analog8` float,
  `analog9` float,
  `analog10` float,
  `analog11` float,
  `analog12` float,
  `analog13` float,
  `analog14` float,
  `analog15` float,
  `analog16` float,
  `digital1` char(1),
  `digital2` char(1),
  `digital3` char(1),
  `digital4` char(1),
  `digital5` char(1),
  `digital6` char(1),
  `digital7` char(1),
  `digital8` char(1),
  `digital9` char(1),
  `digital10` char(1),
  `digital11` char(1),
  `digital12` char(1),
  `digital13` char(1),
  `digital14` char(1),
  `digital15` char(1),
  `digital16` char(1),
  `speed1` int(2),
  `speed2` int(2),
  `speed3` int(2),
  `speed4` int(2),
  `power1` float,
  `power2` float,
  `energy1` decimal(10,1),
  `energy2` decimal(10,1),
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`date`,`frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;


DROP TABLE IF EXISTS `t_menu`;
CREATE TABLE `t_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `schema` varchar(200) DEFAULT NULL,
  `view` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_schema`;
CREATE TABLE `t_schema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2','current_energy1','current_energy2') NOT NULL,
  `format` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`frame`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_names`;
CREATE TABLE `t_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2') NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frame` (`frame`,`type`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_names_of_charts`;
CREATE TABLE `t_names_of_charts` (
  `chart_id` int(11) NOT NULL,
  `type` enum('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2') NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`chart_id`,`type`,`frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t_energies`;
CREATE TABLE `t_energies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `energy1` decimal(10,1) DEFAULT NULL,
  `energy2` decimal(10,1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`date`,`frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_max`;
CREATE TABLE `t_max` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `analog1` float DEFAULT NULL,
  `analog2` float DEFAULT NULL,
  `analog3` float DEFAULT NULL,
  `analog4` float DEFAULT NULL,
  `analog5` float DEFAULT NULL,
  `analog6` float DEFAULT NULL,
  `analog7` float DEFAULT NULL,
  `analog8` float DEFAULT NULL,
  `analog9` float DEFAULT NULL,
  `analog10` float DEFAULT NULL,
  `analog11` float DEFAULT NULL,
  `analog12` float DEFAULT NULL,
  `analog13` float DEFAULT NULL,
  `analog14` float DEFAULT NULL,
  `analog15` float DEFAULT NULL,
  `analog16` float DEFAULT NULL,
  `speed1` int(2) DEFAULT NULL,
  `speed2` int(2) DEFAULT NULL,
  `speed3` int(2) DEFAULT NULL,
  `speed4` int(2) DEFAULT NULL,
  `power1` float DEFAULT NULL,
  `power2` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`date`,`frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP TABLE IF EXISTS `t_min`;
CREATE TABLE `t_min` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `frame` enum('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL,
  `analog1` float DEFAULT NULL,
  `analog2` float DEFAULT NULL,
  `analog3` float DEFAULT NULL,
  `analog4` float DEFAULT NULL,
  `analog5` float DEFAULT NULL,
  `analog6` float DEFAULT NULL,
  `analog7` float DEFAULT NULL,
  `analog8` float DEFAULT NULL,
  `analog9` float DEFAULT NULL,
  `analog10` float DEFAULT NULL,
  `analog11` float DEFAULT NULL,
  `analog12` float DEFAULT NULL,
  `analog13` float DEFAULT NULL,
  `analog14` float DEFAULT NULL,
  `analog15` float DEFAULT NULL,
  `analog16` float DEFAULT NULL,
  `speed1` int(2) DEFAULT NULL,
  `speed2` int(2) DEFAULT NULL,
  `speed3` int(2) DEFAULT NULL,
  `speed4` int(2) DEFAULT NULL,
  `power1` float DEFAULT NULL,
  `power2` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`date`,`frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

DROP VIEW IF EXISTS `v_max`;
CREATE VIEW `v_max` AS select cast(`t_data`.`date` as date) AS `date`,max(`t_data`.`analog1`) AS `analog1`,max(`t_data`.`analog2`) AS `analog2`,max(`t_data`.`analog3`) AS `analog3`,max(`t_data`.`analog4`) AS `analog4`,max(`t_data`.`analog5`) AS `analog5`,max(`t_data`.`analog6`) AS `analog6`,max(`t_data`.`analog7`) AS `analog7`,max(`t_data`.`analog8`) AS `analog8`,max(`t_data`.`analog9`) AS `analog9`,max(`t_data`.`analog10`) AS `analog10`,max(`t_data`.`analog11`) AS `analog11`,max(`t_data`.`analog12`) AS `analog12`,max(`t_data`.`analog13`) AS `analog13`,max(`t_data`.`analog14`) AS `analog14`,max(`t_data`.`analog15`) AS `analog15`,max(`t_data`.`analog16`) AS `analog16`,max(`t_data`.`speed1`) AS `speed1`,max(`t_data`.`speed2`) AS `speed2`,max(`t_data`.`speed3`) AS `speed3`,max(`t_data`.`speed4`) AS `speed4`,max(`t_data`.`power1`) AS `power1`,max(`t_data`.`power2`) AS `power2`,`t_data`.`frame` AS `frame` from `t_data` where ((`t_data`.`date` >= cast((select max(`t_max`.`date`) from `t_max`) as date)) or ((select count(0) from `t_max`) = 0)) group by cast(`t_data`.`date` as date),`t_data`.`frame`;

DROP VIEW IF EXISTS `v_min`;
CREATE VIEW `v_min` AS select cast(`t_data`.`date` as date) AS `date`,min(`t_data`.`analog1`) AS `analog1`,min(`t_data`.`analog2`) AS `analog2`,min(`t_data`.`analog3`) AS `analog3`,min(`t_data`.`analog4`) AS `analog4`,min(`t_data`.`analog5`) AS `analog5`,min(`t_data`.`analog6`) AS `analog6`,min(`t_data`.`analog7`) AS `analog7`,min(`t_data`.`analog8`) AS `analog8`,min(`t_data`.`analog9`) AS `analog9`,min(`t_data`.`analog10`) AS `analog10`,min(`t_data`.`analog11`) AS `analog11`,min(`t_data`.`analog12`) AS `analog12`,min(`t_data`.`analog13`) AS `analog13`,min(`t_data`.`analog14`) AS `analog14`,min(`t_data`.`analog15`) AS `analog15`,min(`t_data`.`analog16`) AS `analog16`,min(`t_data`.`speed1`) AS `speed1`,min(`t_data`.`speed2`) AS `speed2`,min(`t_data`.`speed3`) AS `speed3`,min(`t_data`.`speed4`) AS `speed4`,min(`t_data`.`power1`) AS `power1`,min(`t_data`.`power2`) AS `power2`,`t_data`.`frame` AS `frame` from `t_data` where ((`t_data`.`date` >= cast((select max(`t_min`.`date`) from `t_min`) as date)) or ((select count(0) from `t_min`) = 0)) group by cast(`t_data`.`date` as date),`t_data`.`frame`;

DROP VIEW IF EXISTS `v_minmaxdate`;
CREATE VIEW `v_minmaxdate` AS select `t_data`.`date` AS `date`,min(`t_data`.`date`) AS `min`,max(`t_data`.`date`) AS `max`,`t_data`.`frame` AS `frame` from `t_data` where ((`t_data`.`date` >= cast((select max(`t_energies`.`date`) from `t_energies`) as date)) or ((select count(0) from `t_energies`) = 0)) group by cast(`t_data`.`date` as date), `t_data`.`frame`;

DROP VIEW IF EXISTS `v_energies`;
CREATE VIEW `v_energies` AS select cast(`v_minmaxdate`.`date` as date) AS `date`,(`max`.`energy1` - `min`.`energy1`) AS `energy1`,(`max`.`energy2` - `min`.`energy2`) AS `energy2`,`v_minmaxdate`.`frame` AS `frame` from ((`v_minmaxdate` left join `t_data` `min` on(((`min`.`date` = `v_minmaxdate`.`min`) and (`min`.`frame` = `v_minmaxdate`.`frame`)))) left join `t_data` `max` on(((`max`.`date` = `v_minmaxdate`.`max`) and (`max`.`frame` = `v_minmaxdate`.`frame`))));


DROP PROCEDURE IF EXISTS `p_energies`;
DROP PROCEDURE IF EXISTS `p_minmax`;
delimiter $$
CREATE PROCEDURE `p_energies`()
BEGIN
    REPLACE INTO t_energies (date, energy1, energy2, frame) SELECT * FROM v_energies;
END$$

CREATE PROCEDURE `p_minmax`()
BEGIN
    REPLACE INTO t_min (date, analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8,
    analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16, speed1, speed2, speed3, speed4, power1, power2, frame) SELECT * FROM v_min;
    REPLACE INTO t_max (date, analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8,
    analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16, speed1, speed2, speed3, speed4, power1, power2, frame) SELECT * FROM v_max;
END$$








