ALTER TABLE `uvr1611`.`t_data`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `energy2`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);

ALTER TABLE `uvr1611`.`t_menu`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `view`;

ALTER TABLE `uvr1611`.`t_schema`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `format`;

ALTER TABLE `uvr1611`.`t_digital_counts`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `digital16`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);


ALTER TABLE `uvr1611`.`t_digital_times`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `digital16`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);


ALTER TABLE `uvr1611`.`t_energies`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `energy2`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);

ALTER TABLE `uvr1611`.`t_min`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `power2`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);

ALTER TABLE `uvr1611`.`t_max`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `power2`,
DROP INDEX `UNIQUE` ,
ADD UNIQUE INDEX `UNIQUE` (`date` ASC, `frame` ASC, `logger` ASC);

ALTER TABLE `uvr1611`.`t_names`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `name`,
DROP INDEX `frame` ,
ADD UNIQUE INDEX `frame` (`frame` ASC, `type` ASC, `logger` ASC);

ALTER TABLE `uvr1611`.`t_names_of_charts`
ADD COLUMN `logger` VARCHAR(45) NOT NULL DEFAULT 'uvr1611' AFTER `order`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`chart_id`, `type`, `frame`, `logger`);


/*Update procedures and views*/
