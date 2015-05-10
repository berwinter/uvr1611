ALTER TABLE `t_menu` 
ADD COLUMN `view` VARCHAR(50) NULL AFTER `schema`;

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `t_users` (`id`,`username`,`password`,`salt`) VALUES (0,'admin','40e2776165475d893e923da0fc9039569bad50e7f88e0ff07e11ad8bffd51019c7c0ab2709395c3599f4bebd6a6bd927e9c9470a638e1eef8e8cb971061d7412','80125411a211653c6b76a9c5b9b12b6406a4a53ab61543d31abf626cda4a58ba5c8d2411a5011ad2b61de2cecd07e02b6ec9ad7a2513299d977e34b5c3f76df0');
