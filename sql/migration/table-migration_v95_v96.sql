ALTER TABLE `t_schema` 
DROP INDEX `UNIQUE`;

ALTER TABLE `t_menu` 
CHANGE COLUMN `type` `type` VARCHAR(20) NOT NULL ;

CREATE TABLE `t_chartoptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chard_id` int(11) NOT NULL,
  `property` varchar(120) NOT NULL,
  `value` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
