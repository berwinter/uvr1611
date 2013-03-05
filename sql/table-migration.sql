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


ALTER TABLE `uvr1611`.`t_names` CHANGE COLUMN `type` `type` ENUM('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2') NOT NULL  ;
ALTER TABLE `uvr1611`.`t_schema` CHANGE COLUMN `type` `type` ENUM('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2') NOT NULL  ;

ALTER TABLE `uvr1611`.`t_names_of_charts` ADD COLUMN `frame` ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8') NOT NULL  AFTER `type` , ADD COLUMN `order` INT NULL  AFTER `frame` , CHANGE COLUMN `chart_id` `chart_id` INT(11) NOT NULL  FIRST , CHANGE COLUMN `name_id` `type` ENUM('analog1','analog2','analog3','analog4','analog5','analog6','analog7','analog8','analog9','analog10','analog11','analog12','analog13','analog14','analog15','analog16','digital1','digital2','digital3','digital4','digital5','digital6','digital7','digital8','digital9','digital10','digital11','digital12','digital13','digital14','digital15','digital16','speed1','speed2','speed3','speed4','energy1','energy2','power1','power2') NOT NULL  
, DROP PRIMARY KEY 
, ADD PRIMARY KEY (`chart_id`,`frame`,`type`) 
, DROP INDEX `chart_id` 
, DROP INDEX `name_id` ;

