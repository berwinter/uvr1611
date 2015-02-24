CREATE DATABASE  IF NOT EXISTS `uvr1611`;
USE `uvr1611`;

------------------------------------------------------------------
--  TABLE t_data
------------------------------------------------------------------

CREATE TABLE t_data
(
    id           int(11),
    `date`       datetime,
    frame        ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    analog1      float,
    analog2      float,
    analog3      float,
    analog4      float,
    analog5      float,
    analog6      float,
    analog7      float,
    analog8      float,
    analog9      float,
    analog10     float,
    analog11     float,
    analog12     float,
    analog13     float,
    analog14     float,
    analog15     float,
    analog16     float,
    digital1     char(1),
    digital2     char(1),
    digital3     char(1),
    digital4     char(1),
    digital5     char(1),
    digital6     char(1),
    digital7     char(1),
    digital8     char(1),
    digital9     char(1),
    digital10    char(1),
    digital11    char(1),
    digital12    char(1),
    digital13    char(1),
    digital14    char(1),
    digital15    char(1),
    digital16    char(1),
    speed1       int(2),
    speed2       int(2),
    speed3       int(2),
    speed4       int(2),
    power1       float,
    power2       float,
    energy1      decimal(10,1),
    energy2      decimal(10,1)
);


------------------------------------------------------------------
--  TABLE t_energies
------------------------------------------------------------------

CREATE TABLE t_energies
(
    id         int(11),
    `date`     datetime,
    frame      ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    energy1    decimal(10,1),
    energy2    decimal(10,1)
);


------------------------------------------------------------------
--  TABLE t_hg_data
------------------------------------------------------------------

CREATE TABLE t_hg_data
(
   id       int(11),
   `date`   datetime,
   `1`      float,
   `2`      float,
   `3`      float,
   `4`      float,
   `5`      float,
   `6`      float,
   `7`      float,
   `8`      float,
   `9`      float,
   `10`     float,
   `11`     float,
   `12`     float,
   `13`     float,
   `14`     float,
   `15`     float,
   `16`     float,
   `17`     float,
   `18`     float,
   `19`     float,
   `20`     float,
   `21`     float,
   `22`     float,
   `23`     float,
   `24`     float,
   `25`     float,
   `26`     float,
   `27`     float,
   `28`     float,
   `29`     float,
   `30`     float,
   `31`     float,
   `32`     float,
   `33`     float,
   `34`     float,
   `35`     float,
   `36`     float,
   `37`     float,
   `38`     float,
   `39`     float,
   `40`     float,
   `41`     float,
   `42`     float,
   `43`     float,
   `44`     float,
   `45`     float,
   `46`     float,
   `47`     float,
   `48`     float,
   `49`     float,
   `50`     float,
   `51`     float,
   `52`     float,
   `53`     float,
   `54`     float,
   `55`     float,
   `56`     float,
   `57`     float,
   `58`     float,
   `59`     float,
   `60`     float,
   `61`     float,
   `62`     float,
   `63`     float,
   `64`     float,
   `65`     float,
   `66`     float,
   `67`     float,
   `68`     float,
   `69`     float,
   `70`     float,
   `71`     float,
   `72`     float,
   `73`     float,
   `74`     float,
   `75`     float,
   `76`     float,
   `77`     float,
   `78`     float,
   `79`     float,
   `80`     float,
   `81`     float,
   `82`     float,
   `83`     float,
   `84`     float,
   `85`     float,
   `86`     float,
   `87`     float,
   `88`     float,
   `89`     float,
   `90`     float,
   `91`     float,
   `92`     float,
   `93`     float,
   `94`     float,
   `95`     float,
   `96`     float,
   `97`     float,
   `98`     float,
   `99`     float,
   `100`    float,
   `101`    float,
   `102`    float,
   `103`    float,
   `104`    float,
   `105`    float,
   `106`    float,
   `107`    float,
   `108`    float,
   `109`    float,
   `110`    float,
   `111`    float,
   `112`    float,
   `113`    float,
   `114`    float,
   `115`    float,
   `116`    float,
   `117`    float,
   `118`    float,
   `119`    float,
   `120`    float,
   `121`    float,
   `122`    float,
   `123`    float,
   `124`    float,
   `125`    float,
   `126`    float,
   `127`    float,
   `128`    float,
   `129`    float,
   `130`    float,
   `131`    float,
   `132`    float,
   `133`    float,      
   `134`    float,
   `135`    float,
   `136`    float,
   `137`    float,
   `138`    float,
   `139`    float,
   `140`    float,
   `141`    float,
   `142`    float,
   `143`    float,
   `144`    float,
   `145`    float,
   `146`    float,
   `147`    float,
   `148`    float,
   `149`    binary(2),
   `150`    binary(2),
   `151`    binary(2),
   `152`    binary(2),
   `153`    binary(2),
   `154`    binary(2),
   `155`    binary(2),
   `156`    binary(2),
   frame    varchar(20) DEFAULT 'frame1'
);


------------------------------------------------------------------
--  TABLE t_max
------------------------------------------------------------------

CREATE TABLE t_max
(
    id          int(11),
    `date`      datetime,
    frame       ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    analog1     float,
    analog2     float,
    analog3     float,
    analog4     float,
    analog5     float,
    analog6     float,
    analog7     float,
    analog8     float,
    analog9     float,
    analog10    float,
    analog11    float,
    analog12    float,
    analog13    float,
    analog14    float,
    analog15    float,
    analog16    float,
    speed1      int(2),
    speed2      int(2),
    speed3      int(2),
    speed4      int(2),
    power1      float,
    power2      float,
    `1`         float,
    `2`         float,
    `3`         float,
    `4`         float,
    `5`         float,
    `6`         float,
    `7`         float,
    `8`         float,
    `9`         float,
    `10`        float,
    `11`        float,
    `12`        float,
    `13`        float,
    `14`        float,
    `15`        float,
    `16`        float,
    `17`        float,
    `18`        float,
    `19`        float,
    `20`        float,
    `21`        float,
    `22`        float,
    `23`        float,
    `24`        float,
    `25`        float,
    `26`        float,
    `27`        float,
    `28`        float,
    `29`        float,
    `30`        float,
    `31`        float,
    `32`        float,
    `33`        float,
    `34`        float,
    `35`        float,
    `36`        float,
    `37`        float,
    `38`        float,
    `39`        float,
    `40`        float,
    `41`        float,
    `42`        float,
    `43`        float,
    `44`        float,
    `45`        float,
    `46`        float,
    `47`        float,
    `48`        float,
    `49`        float,
    `50`        float,
    `51`        float,
    `52`        float,
    `53`        float,
    `54`        float,
    `55`        float,
    `56`        float,
    `57`        float,
    `58`        float,
    `59`        float,
    `60`        float,
    `61`        float,
    `62`        float,
    `63`        float,
    `64`        float,
    `65`        float,
    `66`        float,
    `67`        float,
    `68`        float,
    `69`        float,
    `70`        float,
    `71`        float,
    `72`        float,
    `73`        float,
    `74`        float,
    `75`        float,
    `76`        float,
    `77`        float,
    `78`        float,
    `79`        float,
    `80`        float,
    `81`        float,
    `82`        float,
    `83`        float,
    `84`        float,
    `85`        float,
    `86`        float,
    `87`        float,
    `88`        float,
    `89`        float,
    `90`        float,
    `91`        float,
    `92`        float,
    `93`        float,
    `94`        float,
    `95`        float,
    `96`        float,
    `97`        float,
    `98`        float,
    `99`        float,
    `100`       float,
    `101`       float,
    `102`       float,
    `103`       float,
    `104`       float,
    `105`       float,
    `106`       float,
    `107`       float,
    `108`       float,
    `109`       float,
    `110`       float,
    `111`       float,
    `112`       float,
    `113`       float,
    `114`       float,
    `115`       float,
    `116`       float,
    `117`       float,
    `118`       float,
    `119`       float,
    `120`       float,
    `121`       float,
    `122`       float,
    `123`       float,
    `124`       float,
    `125`       float,
    `126`       float,
    `127`       float,
    `128`       float,
    `129`       float,
    `130`       float,
   `131`    float,
   `132`    float,
   `133`    float,      
   `134`    float,
   `135`    float,
   `136`    float,
   `137`    float,
   `138`    float,
   `139`    float,
   `140`    float,
   `141`    float,
   `142`    float,
   `143`    float,
   `144`    float,
   `145`    float,
   `146`    float,
   `147`    float,
   `148`    float,
   `149`    binary(2),
   `150`    binary(2),
   `151`    binary(2),
   `152`    binary(2),
   `153`    binary(2),
   `154`    binary(2),
   `155`    binary(2),
   `156`    binary(2)
);


------------------------------------------------------------------
--  TABLE t_menu
------------------------------------------------------------------

CREATE TABLE t_menu
(
    id        int(11),
    name      varchar(200),
    unit      varchar(10),
    type      ENUM('schema','line','power','energy'),
    `order`   tinyint(4),
    `schema`  varchar(200)
);


------------------------------------------------------------------
--  TABLE t_min
------------------------------------------------------------------

CREATE TABLE t_min
(
    id          int(11),
    `date`      datetime,
    frame       ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    analog1     float,
    analog2     float,
    analog3     float,
    analog4     float,
    analog5     float,
    analog6     float,
    analog7     float,
    analog8     float,
    analog9     float,
    analog10    float,
    analog11    float,
    analog12    float,
    analog13    float,
    analog14    float,
    analog15    float,
    analog16    float,
    speed1      int(2),
    speed2      int(2),
    speed3      int(2),
    speed4      int(2),
    power1      float,
    power2      float,
    `1`         float,
    `2`         float,
    `3`         float,
    `4`         float,
    `5`         float,
    `6`         float,
    `7`         float,
    `8`         float,
    `9`         float,
    `10`        float,
    `11`        float,
    `12`        float,
    `13`        float,
    `14`        float,
    `15`        float,
    `16`        float,
    `17`        float,
    `18`        float,
    `19`        float,
    `20`        float,
    `21`        float,
    `22`        float,
    `23`        float,
    `24`        float,
    `25`        float,
    `26`        float,
    `27`        float,
    `28`        float,
    `29`        float,
    `30`        float,
    `31`        float,
    `32`        float,
    `33`        float,
    `34`        float,
    `35`        float,
    `36`        float,
    `37`        float,
    `38`        float,
    `39`        float,
    `40`        float,
    `41`        float,
    `42`        float,
    `43`        float,
    `44`        float,
    `45`        float,
    `46`        float,
    `47`        float,
    `48`        float,
    `49`        float,
    `50`        float,
    `51`        float,
    `52`        float,
    `53`        float,
    `54`        float,
    `55`        float,
    `56`        float,
    `57`        float,
    `58`        float,
    `59`        float,
    `60`        float,
    `61`        float,
    `62`        float,
    `63`        float,
    `64`        float,
    `65`        float,
    `66`        float,
    `67`        float,
    `68`        float,
    `69`        float,
    `70`        float,
    `71`        float,
    `72`        float,
    `73`        float,
    `74`        float,
    `75`        float,
    `76`        float,
    `77`        float,
    `78`        float,
    `79`        float,
    `80`        float,
    `81`        float,
    `82`        float,
    `83`        float,
    `84`        float,
    `85`        float,
    `86`        float,
    `87`        float,
    `88`        float,
    `89`        float,
    `90`        float,
    `91`        float,
    `92`        float,
    `93`        float,
    `94`        float,
    `95`        float,
    `96`        float,
    `97`        float,
    `98`        float,
    `99`        float,
    `100`       float,
    `101`       float,
    `102`       float,
    `103`       float,
    `104`       float,
    `105`       float,
    `106`       float,
    `107`       float,
    `108`       float,
    `109`       float,
    `110`       float,
    `111`       float,
    `112`       float,
    `113`       float,
    `114`       float,
    `115`       float,
    `116`       float,
    `117`       float,
    `118`       float,
    `119`       float,
    `120`       float,
    `121`       float,
    `122`       float,
    `123`       float,
    `124`       float,
    `125`       float,
    `126`       float,
    `127`       float,
    `128`       float,
    `129`       float,
    `130`       float,
   `131`    float,
   `132`    float,
   `133`    float,      
   `134`    float,
   `135`    float,
   `136`    float,
   `137`    float,
   `138`    float,
   `139`    float,
   `140`    float,
   `141`    float,
   `142`    float,
   `143`    float,
   `144`    float,
   `145`    float,
   `146`    float,
   `147`    float,
   `148`    float,
   `149`    binary(2),
   `150`    binary(2),
   `151`    binary(2),
   `152`    binary(2),
   `153`    binary(2),
   `154`    binary(2),
   `155`    binary(2),
   `156`    binary(2)
);


------------------------------------------------------------------
--  TABLE t_names
------------------------------------------------------------------

CREATE TABLE t_names
(
   id      int(11),
   frame   varchar(20) DEFAULT 'frame1',
   type    varchar(20),
   name    varchar(200),
   unit    varchar(20)
);


------------------------------------------------------------------
--  TABLE t_names_of_charts
------------------------------------------------------------------

CREATE TABLE t_names_of_charts
(
    chart_id    int(11),
    type        varchar(20),
    frame       ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    `order`     int(11)
);


------------------------------------------------------------------
--  TABLE t_schema
------------------------------------------------------------------

CREATE TABLE t_schema
(
    id        int(11),
    path      varchar(200),
    frame     ENUM('frame1','frame2','frame3','frame4','frame5','frame6','frame7','frame8'),
    type      varchar(20),
    format    varchar(200)
);


------------------------------------------------------------------
--  VIEW v_minmaxdate
------------------------------------------------------------------
create or replace view v_minmaxdate as 
  SELECT `uvr1611`.`t_data`.`date` AS `date`,
         min(`uvr1611`.`t_data`.`date`) AS `min`,
         max(`uvr1611`.`t_data`.`date`) AS `max`,
         `uvr1611`.`t_data`.`frame` AS `frame`
    FROM `uvr1611`.`t_data`
   WHERE (   (`uvr1611`.`t_data`.`date` >=
                 cast(
                    (SELECT max(`uvr1611`.`t_energies`.`date`)
                       FROM `uvr1611`.`t_energies`) AS date))
          OR ((SELECT count(0) FROM `uvr1611`.`t_energies`) = 0))
GROUP BY cast(`uvr1611`.`t_data`.`date` AS date), `uvr1611`.`t_data`.`frame`;

------------------------------------------------------------------
--  VIEW v_data
------------------------------------------------------------------
DROP VIEW IF EXISTS `v_data`;
CREATE VIEW v_data as
SELECT ifnull(`u`.`id`, `h`.`id`) AS `id`,
         ifnull(`u`.`date`, `h`.`date`) AS `date`,
         ifnull(`u`.`frame`, `h`.`frame`) AS `frame`,
         `u`.`analog1` AS `analog1`,
         `u`.`analog2` AS `analog2`,
         `u`.`analog3` AS `analog3`,
         `u`.`analog4` AS `analog4`,
         `u`.`analog5` AS `analog5`,
         `u`.`analog6` AS `analog6`,
         `u`.`analog7` AS `analog7`,
         `u`.`analog8` AS `analog8`,
         `u`.`analog9` AS `analog9`,
         `u`.`analog10` AS `analog10`,
         `u`.`analog11` AS `analog11`,
         `u`.`analog12` AS `analog12`,
         `u`.`analog13` AS `analog13`,
         `u`.`analog14` AS `analog14`,
         `u`.`analog15` AS `analog15`,
         `u`.`analog16` AS `analog16`,
         `u`.`digital1` AS `digital1`,
         `u`.`digital2` AS `digital2`,
         `u`.`digital3` AS `digital3`,
         `u`.`digital4` AS `digital4`,
         `u`.`digital5` AS `digital5`,
         `u`.`digital6` AS `digital6`,
         `u`.`digital7` AS `digital7`,
         `u`.`digital8` AS `digital8`,
         `u`.`digital9` AS `digital9`,
         `u`.`digital10` AS `digital10`,
         `u`.`digital11` AS `digital11`,
         `u`.`digital12` AS `digital12`,
         `u`.`digital13` AS `digital13`,
         `u`.`digital14` AS `digital14`,
         `u`.`digital15` AS `digital15`,
         `u`.`digital16` AS `digital16`,
         `u`.`speed1` AS `speed1`,
         `u`.`speed2` AS `speed2`,
         `u`.`speed3` AS `speed3`,
         `u`.`speed4` AS `speed4`,
         `u`.`power1` AS `power1`,
         `u`.`power2` AS `power2`,
         `u`.`energy1` AS `energy1`,
         `u`.`energy2` AS `energy2`,
         `h`.`1` AS `1`,
         `h`.`2` AS `2`,
         `h`.`3` AS `3`,
         `h`.`4` AS `4`,
         `h`.`5` AS `5`,
         `h`.`6` AS `6`,
         `h`.`7` AS `7`,
         `h`.`8` AS `8`,
         `h`.`9` AS `9`,
         `h`.`10` AS `10`,
         `h`.`11` AS `11`,
         `h`.`12` AS `12`,
         `h`.`13` AS `13`,
         `h`.`14` AS `14`,
         `h`.`15` AS `15`,
         `h`.`16` AS `16`,
         `h`.`17` AS `17`,
         `h`.`18` AS `18`,
         `h`.`19` AS `19`,
         `h`.`20` AS `20`,
         `h`.`21` AS `21`,
         `h`.`22` AS `22`,
         `h`.`23` AS `23`,
         `h`.`24` AS `24`,
         `h`.`25` AS `25`,
         `h`.`26` AS `26`,
         `h`.`27` AS `27`,
         `h`.`28` AS `28`,
         `h`.`29` AS `29`,
         `h`.`30` AS `30`,
         `h`.`31` AS `31`,
         `h`.`32` AS `32`,
         `h`.`33` AS `33`,
         `h`.`34` AS `34`,
         `h`.`35` AS `35`,
         `h`.`36` AS `36`,
         `h`.`37` AS `37`,
         `h`.`38` AS `38`,
         `h`.`39` AS `39`,
         `h`.`40` AS `40`,
         `h`.`41` AS `41`,
         `h`.`42` AS `42`,
         `h`.`43` AS `43`,
         `h`.`44` AS `44`,
         `h`.`45` AS `45`,
         `h`.`46` AS `46`,
         `h`.`47` AS `47`,
         `h`.`48` AS `48`,
         `h`.`49` AS `49`,
         `h`.`50` AS `50`,
         `h`.`51` AS `51`,
         `h`.`52` AS `52`,
         `h`.`53` AS `53`,
         `h`.`54` AS `54`,
         `h`.`55` AS `55`,
         `h`.`56` AS `56`,
         `h`.`57` AS `57`,
         `h`.`58` AS `58`,
         `h`.`59` AS `59`,
         `h`.`60` AS `60`,
         `h`.`61` AS `61`,
         `h`.`62` AS `62`,
         `h`.`63` AS `63`,
         `h`.`64` AS `64`,
         `h`.`65` AS `65`,
         `h`.`66` AS `66`,
         `h`.`67` AS `67`,
         `h`.`68` AS `68`,
         `h`.`69` AS `69`,
         `h`.`70` AS `70`,
         `h`.`71` AS `71`,
         `h`.`72` AS `72`,
         `h`.`73` AS `73`,
         `h`.`74` AS `74`,
         `h`.`75` AS `75`,
         `h`.`76` AS `76`,
         `h`.`77` AS `77`,
         `h`.`78` AS `78`,
         `h`.`79` AS `79`,
         `h`.`80` AS `80`,
         `h`.`81` AS `81`,
         `h`.`82` AS `82`,
         `h`.`83` AS `83`,
         `h`.`84` AS `84`,
         `h`.`85` AS `85`,
         `h`.`86` AS `86`,
         `h`.`87` AS `87`,
         `h`.`88` AS `88`,
         `h`.`89` AS `89`,
         `h`.`90` AS `90`,
         `h`.`91` AS `91`,
         `h`.`92` AS `92`,
         `h`.`93` AS `93`,
         `h`.`94` AS `94`,
         `h`.`95` AS `95`,
         `h`.`96` AS `96`,
         `h`.`97` AS `97`,
         `h`.`98` AS `98`,
         `h`.`99` AS `99`,
         `h`.`100` AS `100`,
         `h`.`101` AS `101`,
         `h`.`102` AS `102`,
         `h`.`103` AS `103`,
         `h`.`104` AS `104`,
         `h`.`105` AS `105`,
         `h`.`106` AS `106`,
         `h`.`107` AS `107`,
         `h`.`108` AS `108`,
         `h`.`109` AS `109`,
         `h`.`110` AS `110`,
         `h`.`111` AS `111`,
         `h`.`112` AS `112`,
         `h`.`113` AS `113`,
         `h`.`114` AS `114`,
         `h`.`115` AS `115`,
         `h`.`116` AS `116`,
         `h`.`117` AS `117`,
         `h`.`118` AS `118`,
         `h`.`119` AS `119`,
         `h`.`120` AS `120`,
         `h`.`121` AS `121`,
         `h`.`122` AS `122`,
         `h`.`123` AS `123`,
         `h`.`124` AS `124`,
         `h`.`125` AS `125`,
         `h`.`126` AS `126`,
         `h`.`127` AS `127`,
         `h`.`128` AS `128`,
         `h`.`129` AS `129`,
         `h`.`130` AS `130`,
         `h`.`131` AS `131`,
         `h`.`132` AS `132`,
         `h`.`133` AS `133`,
         `h`.`134` AS `134`,
         `h`.`135` AS `135`,
         `h`.`136` AS `136`,
         `h`.`137` AS `137`,
         `h`.`138` AS `138`,
         `h`.`139` AS `139`,
         `h`.`140` AS `140`,
         `h`.`141` AS `141`,
		 `h`.`142` AS `142`,
         `h`.`143` AS `143`,
         `h`.`144` AS `144`,
         `h`.`145` AS `145`,
         `h`.`146` AS `146`,
         `h`.`147` AS `147`,
         `h`.`148` AS `148`,
         `h`.`149` AS `149`,
         `h`.`150` AS `150`,
         `h`.`151` AS `151`,
         `h`.`152` AS `152`,
         `h`.`153` AS `153`,
         `h`.`154` AS `154`,
         `h`.`155` AS `155`,
         `h`.`156` AS `156`
    FROM (`uvr1611`.`t_data` `u`
          LEFT JOIN `uvr1611`.`t_hg_data` `h` ON ((`u`.`date` = `h`.`date`)))
ORDER BY `u`.`date` DESC;




------------------------------------------------------------------
--  VIEW v_energies
------------------------------------------------------------------
create or replace view v_energies as
SELECT cast(`v_minmaxdate`.`date` AS date) AS `date`,
       (`max`.`energy1` - `min`.`energy1`) AS `energy1`,
       (`max`.`energy2` - `min`.`energy2`) AS `energy2`,
       `v_minmaxdate`.`frame` AS `frame`
  FROM ((`uvr1611`.`v_minmaxdate`
         LEFT JOIN `uvr1611`.`t_data` `min`
            ON ((    (`min`.`date` = `v_minmaxdate`.`min`)
                 AND (`min`.`frame` = `v_minmaxdate`.`frame`))))
        LEFT JOIN `uvr1611`.`t_data` `max`
           ON ((    (`max`.`date` = `v_minmaxdate`.`max`)
                AND (`max`.`frame` = `v_minmaxdate`.`frame`))));


------------------------------------------------------------------
--  VIEW v_max
------------------------------------------------------------------
Create or replace view v_max as 
  SELECT cast(`v_data`.`date` AS date) AS `date`,
         max(`v_data`.`analog1`) AS `analog1`,
         max(`v_data`.`analog2`) AS `analog2`,
         max(`v_data`.`analog3`) AS `analog3`,
         max(`v_data`.`analog4`) AS `analog4`,
         max(`v_data`.`analog5`) AS `analog5`,
         max(`v_data`.`analog6`) AS `analog6`,
         max(`v_data`.`analog7`) AS `analog7`,
         max(`v_data`.`analog8`) AS `analog8`,
         max(`v_data`.`analog9`) AS `analog9`,
         max(`v_data`.`analog10`) AS `analog10`,
         max(`v_data`.`analog11`) AS `analog11`,
         max(`v_data`.`analog12`) AS `analog12`,
         max(`v_data`.`analog13`) AS `analog13`,
         max(`v_data`.`analog14`) AS `analog14`,
         max(`v_data`.`analog15`) AS `analog15`,
         max(`v_data`.`analog16`) AS `analog16`,
         max(`v_data`.`speed1`) AS `speed1`,
         max(`v_data`.`speed2`) AS `speed2`,
         max(`v_data`.`speed3`) AS `speed3`,
         max(`v_data`.`speed4`) AS `speed4`,
         max(`v_data`.`power1`) AS `power1`,
         max(`v_data`.`power2`) AS `power2`,
         `v_data`.`frame` AS `frame`,
         max(`v_data`.`1`) AS `1`,
         max(`v_data`.`2`) AS `2`,
         max(`v_data`.`3`) AS `3`,
         max(`v_data`.`4`) AS `4`,
         max(`v_data`.`5`) AS `5`,
         max(`v_data`.`6`) AS `6`,
         max(`v_data`.`7`) AS `7`,
         max(`v_data`.`8`) AS `8`,
         max(`v_data`.`9`) AS `9`,
         max(`v_data`.`10`) AS `10`,
         max(`v_data`.`11`) AS `11`,
         max(`v_data`.`12`) AS `12`,
         max(`v_data`.`13`) AS `13`,
         max(`v_data`.`14`) AS `14`,
         max(`v_data`.`15`) AS `15`,
         max(`v_data`.`16`) AS `16`,
         max(`v_data`.`17`) AS `17`,
         max(`v_data`.`18`) AS `18`,
         max(`v_data`.`19`) AS `19`,
         max(`v_data`.`20`) AS `20`,
         max(`v_data`.`21`) AS `21`,
         max(`v_data`.`22`) AS `22`,
         max(`v_data`.`23`) AS `23`,
         max(`v_data`.`24`) AS `24`,
         max(`v_data`.`25`) AS `25`,
         max(`v_data`.`26`) AS `26`,
         max(`v_data`.`27`) AS `27`,
         max(`v_data`.`28`) AS `28`,
         max(`v_data`.`29`) AS `29`,
         max(`v_data`.`30`) AS `30`,
         max(`v_data`.`31`) AS `31`,
         max(`v_data`.`32`) AS `32`,
         max(`v_data`.`33`) AS `33`,
         max(`v_data`.`34`) AS `34`,
         max(`v_data`.`35`) AS `35`,
         max(`v_data`.`36`) AS `36`,
         max(`v_data`.`37`) AS `37`,
         max(`v_data`.`38`) AS `38`,
         max(`v_data`.`39`) AS `39`,
         max(`v_data`.`40`) AS `40`,
         max(`v_data`.`41`) AS `41`,
         max(`v_data`.`42`) AS `42`,
         max(`v_data`.`43`) AS `43`,
         max(`v_data`.`44`) AS `44`,
         max(`v_data`.`45`) AS `45`,
         max(`v_data`.`46`) AS `46`,
         max(`v_data`.`47`) AS `47`,
         max(`v_data`.`48`) AS `48`,
         max(`v_data`.`49`) AS `49`,
         max(`v_data`.`50`) AS `50`,
         max(`v_data`.`51`) AS `51`,
         max(`v_data`.`52`) AS `52`,
         max(`v_data`.`53`) AS `53`,
         max(`v_data`.`54`) AS `54`,
         max(`v_data`.`55`) AS `55`,
         max(`v_data`.`56`) AS `56`,
         max(`v_data`.`57`) AS `57`,
         max(`v_data`.`58`) AS `58`,
         max(`v_data`.`59`) AS `59`,
         max(`v_data`.`60`) AS `60`,
         max(`v_data`.`61`) AS `61`,
         max(`v_data`.`62`) AS `62`,
         max(`v_data`.`63`) AS `63`,
         max(`v_data`.`64`) AS `64`,
         max(`v_data`.`65`) AS `65`,
         max(`v_data`.`66`) AS `66`,
         max(`v_data`.`67`) AS `67`,
         max(`v_data`.`68`) AS `68`,
         max(`v_data`.`69`) AS `69`,
         max(`v_data`.`70`) AS `70`,
         max(`v_data`.`71`) AS `71`,
         max(`v_data`.`72`) AS `72`,
         max(`v_data`.`73`) AS `73`,
         max(`v_data`.`74`) AS `74`,
         max(`v_data`.`75`) AS `75`,
         max(`v_data`.`76`) AS `76`,
         max(`v_data`.`77`) AS `77`,
         max(`v_data`.`78`) AS `78`,
         max(`v_data`.`79`) AS `79`,
         max(`v_data`.`80`) AS `80`,
         max(`v_data`.`81`) AS `81`,
         max(`v_data`.`82`) AS `82`,
         max(`v_data`.`83`) AS `83`,
         max(`v_data`.`84`) AS `84`,
         max(`v_data`.`85`) AS `85`,
         max(`v_data`.`86`) AS `86`,
         max(`v_data`.`87`) AS `87`,
         max(`v_data`.`88`) AS `88`,
         max(`v_data`.`89`) AS `89`,
         max(`v_data`.`90`) AS `90`,
         max(`v_data`.`91`) AS `91`,
         max(`v_data`.`92`) AS `92`,
         max(`v_data`.`93`) AS `93`,
         max(`v_data`.`94`) AS `94`,
         max(`v_data`.`95`) AS `95`,
         max(`v_data`.`96`) AS `96`,
         max(`v_data`.`97`) AS `97`,
         max(`v_data`.`98`) AS `98`,
         max(`v_data`.`99`) AS `99`,
         max(`v_data`.`100`) AS `100`,
         max(`v_data`.`101`) AS `101`,
         max(`v_data`.`102`) AS `102`,
         max(`v_data`.`103`) AS `103`,
         max(`v_data`.`104`) AS `104`,
         max(`v_data`.`105`) AS `105`,
         max(`v_data`.`106`) AS `106`,
         max(`v_data`.`107`) AS `107`,
         max(`v_data`.`108`) AS `108`,
         max(`v_data`.`109`) AS `109`,
         max(`v_data`.`110`) AS `110`,
         max(`v_data`.`111`) AS `111`,
         max(`v_data`.`112`) AS `112`,
         max(`v_data`.`113`) AS `113`,
         max(`v_data`.`114`) AS `114`,
         max(`v_data`.`115`) AS `115`,
         max(`v_data`.`116`) AS `116`,
         max(`v_data`.`117`) AS `117`,
         max(`v_data`.`118`) AS `118`,
         max(`v_data`.`119`) AS `119`,
         max(`v_data`.`120`) AS `120`,
         max(`v_data`.`121`) AS `121`,
         max(`v_data`.`122`) AS `122`,
         max(`v_data`.`123`) AS `123`,
         max(`v_data`.`124`) AS `124`,
         max(`v_data`.`125`) AS `125`,
         max(`v_data`.`126`) AS `126`,
         max(`v_data`.`127`) AS `127`,
         max(`v_data`.`128`) AS `128`,
         max(`v_data`.`129`) AS `129`,
         max(`v_data`.`130`) AS `130`,
         max(`v_data`.`131`) AS `131`,
         max(`v_data`.`132`) AS `132`,
         max(`v_data`.`133`) AS `133`,
         max(`v_data`.`134`) AS `134`,
         max(`v_data`.`135`) AS `135`,
         max(`v_data`.`136`) AS `136`,
         max(`v_data`.`137`) AS `137`,
         max(`v_data`.`138`) AS `138`,
         max(`v_data`.`139`) AS `139`,
         max(`v_data`.`140`) AS `140`,
         max(`v_data`.`141`) AS `141`,
		 
		 max(`v_data`.`142`) AS `142`,
         max(`v_data`.`143`) AS `143`,
         max(`v_data`.`144`) AS `144`,
         max(`v_data`.`145`) AS `145`,
         max(`v_data`.`146`) AS `146`,
         max(`v_data`.`147`) AS `147`,
         max(`v_data`.`148`) AS `148`,
         max(`v_data`.`149`) AS `149`,
         max(`v_data`.`150`) AS `150`,
         max(`v_data`.`151`) AS `151`,
         max(`v_data`.`152`) AS `152`,
         max(`v_data`.`153`) AS `153`,
         max(`v_data`.`154`) AS `154`,
         max(`v_data`.`155`) AS `155`,
         max(`v_data`.`156`) AS `156`
    FROM `uvr1611`.`v_data`
   WHERE (   (`v_data`.`date` >=
                 cast(
                    (SELECT max(`uvr1611`.`t_max`.`date`)
                       FROM `uvr1611`.`t_max`) AS date))
          OR ((SELECT count(0) FROM `uvr1611`.`t_max`) = 0))
GROUP BY cast(`v_data`.`date` AS date), `v_data`.`frame`;


------------------------------------------------------------------
--  VIEW v_min
------------------------------------------------------------------
Create or replace view v_min as 
  SELECT cast(`v_data`.`date` AS date) AS `date`,
         min(`v_data`.`analog1`) AS `analog1`,
         min(`v_data`.`analog2`) AS `analog2`,
         min(`v_data`.`analog3`) AS `analog3`,
         min(`v_data`.`analog4`) AS `analog4`,
         min(`v_data`.`analog5`) AS `analog5`,
         min(`v_data`.`analog6`) AS `analog6`,
         min(`v_data`.`analog7`) AS `analog7`,
         min(`v_data`.`analog8`) AS `analog8`,
         min(`v_data`.`analog9`) AS `analog9`,
         min(`v_data`.`analog10`) AS `analog10`,
         min(`v_data`.`analog11`) AS `analog11`,
         min(`v_data`.`analog12`) AS `analog12`,
         min(`v_data`.`analog13`) AS `analog13`,
         min(`v_data`.`analog14`) AS `analog14`,
         min(`v_data`.`analog15`) AS `analog15`,
         min(`v_data`.`analog16`) AS `analog16`,
         min(`v_data`.`speed1`) AS `speed1`,
         min(`v_data`.`speed2`) AS `speed2`,
         min(`v_data`.`speed3`) AS `speed3`,
         min(`v_data`.`speed4`) AS `speed4`,
         min(`v_data`.`power1`) AS `power1`,
         min(`v_data`.`power2`) AS `power2`,
         `v_data`.`frame` AS `frame`,
         min(`v_data`.`1`) AS `1`,
         min(`v_data`.`2`) AS `2`,
         min(`v_data`.`3`) AS `3`,
         min(`v_data`.`4`) AS `4`,
         min(`v_data`.`5`) AS `5`,
         min(`v_data`.`6`) AS `6`,
         min(`v_data`.`7`) AS `7`,
         min(`v_data`.`8`) AS `8`,
         min(`v_data`.`9`) AS `9`,
         min(`v_data`.`10`) AS `10`,
         min(`v_data`.`11`) AS `11`,
         min(`v_data`.`12`) AS `12`,
         min(`v_data`.`13`) AS `13`,
         min(`v_data`.`14`) AS `14`,
         min(`v_data`.`15`) AS `15`,
         min(`v_data`.`16`) AS `16`,
         min(`v_data`.`17`) AS `17`,
         min(`v_data`.`18`) AS `18`,
         min(`v_data`.`19`) AS `19`,
         min(`v_data`.`20`) AS `20`,
         min(`v_data`.`21`) AS `21`,
         min(`v_data`.`22`) AS `22`,
         min(`v_data`.`23`) AS `23`,
         min(`v_data`.`24`) AS `24`,
         min(`v_data`.`25`) AS `25`,
         min(`v_data`.`26`) AS `26`,
         min(`v_data`.`27`) AS `27`,
         min(`v_data`.`28`) AS `28`,
         min(`v_data`.`29`) AS `29`,
         min(`v_data`.`30`) AS `30`,
         min(`v_data`.`31`) AS `31`,
         min(`v_data`.`32`) AS `32`,
         min(`v_data`.`33`) AS `33`,
         min(`v_data`.`34`) AS `34`,
         min(`v_data`.`35`) AS `35`,
         min(`v_data`.`36`) AS `36`,
         min(`v_data`.`37`) AS `37`,
         min(`v_data`.`38`) AS `38`,
         min(`v_data`.`39`) AS `39`,
         min(`v_data`.`40`) AS `40`,
         min(`v_data`.`41`) AS `41`,
         min(`v_data`.`42`) AS `42`,
         min(`v_data`.`43`) AS `43`,
         min(`v_data`.`44`) AS `44`,
         min(`v_data`.`45`) AS `45`,
         min(`v_data`.`46`) AS `46`,
         min(`v_data`.`47`) AS `47`,
         min(`v_data`.`48`) AS `48`,
         min(`v_data`.`49`) AS `49`,
         min(`v_data`.`50`) AS `50`,
         min(`v_data`.`51`) AS `51`,
         min(`v_data`.`52`) AS `52`,
         min(`v_data`.`53`) AS `53`,
         min(`v_data`.`54`) AS `54`,
         min(`v_data`.`55`) AS `55`,
         min(`v_data`.`56`) AS `56`,
         min(`v_data`.`57`) AS `57`,
         min(`v_data`.`58`) AS `58`,
         min(`v_data`.`59`) AS `59`,
         min(`v_data`.`60`) AS `60`,
         min(`v_data`.`61`) AS `61`,
         min(`v_data`.`62`) AS `62`,
         min(`v_data`.`63`) AS `63`,
         min(`v_data`.`64`) AS `64`,
         min(`v_data`.`65`) AS `65`,
         min(`v_data`.`66`) AS `66`,
         min(`v_data`.`67`) AS `67`,
         min(`v_data`.`68`) AS `68`,
         min(`v_data`.`69`) AS `69`,
         min(`v_data`.`70`) AS `70`,
         min(`v_data`.`71`) AS `71`,
         min(`v_data`.`72`) AS `72`,
         min(`v_data`.`73`) AS `73`,
         min(`v_data`.`74`) AS `74`,
         min(`v_data`.`75`) AS `75`,
         min(`v_data`.`76`) AS `76`,
         min(`v_data`.`77`) AS `77`,
         min(`v_data`.`78`) AS `78`,
         min(`v_data`.`79`) AS `79`,
         min(`v_data`.`80`) AS `80`,
         min(`v_data`.`81`) AS `81`,
         min(`v_data`.`82`) AS `82`,
         min(`v_data`.`83`) AS `83`,
         min(`v_data`.`84`) AS `84`,
         min(`v_data`.`85`) AS `85`,
         min(`v_data`.`86`) AS `86`,
         min(`v_data`.`87`) AS `87`,
         min(`v_data`.`88`) AS `88`,
         min(`v_data`.`89`) AS `89`,
         min(`v_data`.`90`) AS `90`,
         min(`v_data`.`91`) AS `91`,
         min(`v_data`.`92`) AS `92`,
         min(`v_data`.`93`) AS `93`,
         min(`v_data`.`94`) AS `94`,
         min(`v_data`.`95`) AS `95`,
         min(`v_data`.`96`) AS `96`,
         min(`v_data`.`97`) AS `97`,
         min(`v_data`.`98`) AS `98`,
         min(`v_data`.`99`) AS `99`,
         min(`v_data`.`100`) AS `100`,
         min(`v_data`.`101`) AS `101`,
         min(`v_data`.`102`) AS `102`,
         min(`v_data`.`103`) AS `103`,
         min(`v_data`.`104`) AS `104`,
         min(`v_data`.`105`) AS `105`,
         min(`v_data`.`106`) AS `106`,
         min(`v_data`.`107`) AS `107`,
         min(`v_data`.`108`) AS `108`,
         min(`v_data`.`109`) AS `109`,
         min(`v_data`.`110`) AS `110`,
         min(`v_data`.`111`) AS `111`,
         min(`v_data`.`112`) AS `112`,
         min(`v_data`.`113`) AS `113`,
         min(`v_data`.`114`) AS `114`,
         min(`v_data`.`115`) AS `115`,
         min(`v_data`.`116`) AS `116`,
         min(`v_data`.`117`) AS `117`,
         min(`v_data`.`118`) AS `118`,
         min(`v_data`.`119`) AS `119`,
         min(`v_data`.`120`) AS `120`,
         min(`v_data`.`121`) AS `121`,
         min(`v_data`.`122`) AS `122`,
         min(`v_data`.`123`) AS `123`,
         min(`v_data`.`124`) AS `124`,
         min(`v_data`.`125`) AS `125`,
         min(`v_data`.`126`) AS `126`,
         min(`v_data`.`127`) AS `127`,
         min(`v_data`.`128`) AS `128`,
         min(`v_data`.`129`) AS `129`,
         min(`v_data`.`130`) AS `130`,
         min(`v_data`.`131`) AS `131`,
         min(`v_data`.`132`) AS `132`,
         min(`v_data`.`133`) AS `133`,
         min(`v_data`.`134`) AS `134`,
         min(`v_data`.`135`) AS `135`,
         min(`v_data`.`136`) AS `136`,
         min(`v_data`.`137`) AS `137`,
         min(`v_data`.`138`) AS `138`,
         min(`v_data`.`139`) AS `139`,
         min(`v_data`.`140`) AS `140`,
         min(`v_data`.`141`) AS `141`,
		 
		 min(`v_data`.`142`) AS `142`,
         min(`v_data`.`143`) AS `143`,
         min(`v_data`.`144`) AS `144`,
         min(`v_data`.`145`) AS `145`,
         min(`v_data`.`146`) AS `146`,
         min(`v_data`.`147`) AS `147`,
         min(`v_data`.`148`) AS `148`,
         min(`v_data`.`149`) AS `149`,
         min(`v_data`.`150`) AS `150`,
         min(`v_data`.`151`) AS `151`,
         min(`v_data`.`152`) AS `152`,
         min(`v_data`.`153`) AS `153`,
         min(`v_data`.`154`) AS `154`,
         min(`v_data`.`155`) AS `155`,
         min(`v_data`.`156`) AS `156`
    FROM `uvr1611`.`v_data`
   WHERE (   (`v_data`.`date` >=
                 cast(
                    (SELECT max(`uvr1611`.`t_min`.`date`)
                       FROM `uvr1611`.`t_min`) AS date))
          OR ((SELECT count(0) FROM `uvr1611`.`t_min`) = 0))
GROUP BY cast(`v_data`.`date` AS date), `v_data`.`frame`;




DROP PROCEDURE IF EXISTS `p_energies`;
DROP PROCEDURE IF EXISTS `p_minmax`;
------------------------------------------------------------------
--  PROCEDURE p_energies
------------------------------------------------------------------


CREATE PROCEDURE p_energies()
BEGIN
REPLACE INTO t_energies (date, energy1, energy2, frame) SELECT * FROM v_energies;
END;


------------------------------------------------------------------
--  PROCEDURE p_minmax
------------------------------------------------------------------

CREATE  PROCEDURE `p_minmax`()
BEGIN
REPLACE INTO t_min (date, analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8,
analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16, speed1, speed2, speed3, speed4, power1, power2, frame ,
t_min.1,
t_min.2, t_min.3, t_min.4, t_min.5, t_min.6, t_min.7, t_min.8, t_min.9, t_min.10, t_min.11, t_min.12, t_min.13, t_min.14, t_min.15, t_min.16, t_min.17, t_min.18, t_min.19, t_min.20, t_min.21, t_min.22, t_min.23, t_min.24, t_min.25, t_min.26, t_min.27, t_min.28, t_min.29, t_min.30, t_min.31, t_min.32, t_min.33, t_min.34, t_min.35, t_min.36, t_min.37, t_min.38, t_min.39, t_min.40, t_min.41, t_min.42, t_min.43, t_min.44, t_min.45, t_min.46, t_min.47, t_min.48, t_min.49, t_min.50, t_min.51, t_min.52, t_min.53, t_min.54, t_min.55, t_min.56, t_min.57, t_min.58, t_min.59, t_min.60, t_min.61, t_min.62, t_min.63, t_min.64, t_min.65, t_min.66, t_min.67, t_min.68, t_min.69, t_min.70, t_min.71, t_min.72, t_min.73, t_min.74, t_min.75, t_min.76, t_min.77, t_min.78, t_min.79, t_min.80,t_min.81, t_min.82, t_min.83, t_min.84, t_min.85, t_min.86, t_min.87, t_min.88, t_min.89, t_min.90, t_min.91, t_min.92, t_min.93, t_min.94, t_min.95, t_min.96, t_min.97, t_min.98, t_min.99, t_min.100, t_min.101, t_min.102, t_min.103, t_min.104, t_min.105, t_min.106, t_min.107, t_min.108, t_min.109, t_min.110, t_min.111, t_min.112, t_min.113, t_min.114, t_min.115, t_min.116, t_min.117, t_min.118, t_min.119, t_min.120, t_min.121, t_min.122, t_min.123, t_min.124, t_min.125, t_min.126, t_min.127, t_min.128, t_min.129, t_min.130, t_min.131, t_min.132, t_min.133, t_min.134, t_min.135, t_min.136, t_min.137, t_min.138, t_min.139, t_min.140, t_min.141, t_min.142,t_min.143, t_min.144, t_min.145, t_min.146, t_min.147, t_min.148, t_min.149, t_min.150, t_min.151, t_min.152, t_min.153, t_min.154,t_min.155, t_min.156) SELECT * FROM v_min;
REPLACE INTO t_max (date, analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8,
analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16, speed1, speed2, speed3, speed4, power1, power2, frame,
t_max.1,
t_max.2, t_max.3, t_max.4, t_max.5, t_max.6, t_max.7, t_max.8, t_max.9, t_max.10, t_max.11, t_max.12, t_max.13, t_max.14, t_max.15, t_max.16, t_max.17, t_max.18, t_max.19, t_max.20, t_max.21, t_max.22, t_max.23, t_max.24, t_max.25, t_max.26, t_max.27, t_max.28, t_max.29, t_max.30, t_max.31, t_max.32, t_max.33, t_max.34, t_max.35, t_max.36, t_max.37, t_max.38, t_max.39, t_max.40, t_max.41, t_max.42, t_max.43, t_max.44, t_max.45, t_max.46, t_max.47, t_max.48, t_max.49, t_max.50, t_max.51, t_max.52, t_max.53, t_max.54, t_max.55, t_max.56, t_max.57, t_max.58, t_max.59, t_max.60, t_max.61, t_max.62, t_max.63, t_max.64, t_max.65, t_max.66, t_max.67, t_max.68, t_max.69, t_max.70, t_max.71, t_max.72, t_max.73, t_max.74, t_max.75, t_max.76, t_max.77, t_max.78, t_max.79, t_max.80,t_max.81, t_max.82, t_max.83, t_max.84, t_max.85, t_max.86, t_max.87, t_max.88, t_max.89, t_max.90, t_max.91, t_max.92, t_max.93, t_max.94, t_max.95, t_max.96, t_max.97, t_max.98, t_max.99, t_max.100, t_max.101, t_max.102, t_max.103, t_max.104, t_max.105, t_max.106, t_max.107, t_max.108, t_max.109, t_max.110, t_max.111, t_max.112, t_max.113, t_max.114, t_max.115, t_max.116, t_max.117, t_max.118, t_max.119, t_max.120, t_max.121, t_max.122, t_max.123, t_max.124, t_max.125, t_max.126, t_max.127, t_max.128, t_max.129, t_max.130, t_max.131, t_max.132, t_max.133, t_max.134, t_max.135, t_max.136, t_max.137, t_max.138, t_max.139, t_max.140, t_max.141, t_max.142,t_max.143, t_max.144, t_max.145, t_max.146, t_max.147, t_max.148, t_max.149, t_max.150, t_max.151, t_max.152, t_max.153, t_max.154,t_max.155, t_max.156) SELECT * FROM v_max;
END;


------------------------------------------------------------------
--  INDEX frame
------------------------------------------------------------------

ALTER TABLE t_names
ADD UNIQUE KEY frame (frame, type);


------------------------------------------------------------------
--  INDEX index
------------------------------------------------------------------

ALTER TABLE t_schema
ADD KEY `index` (frame, type);


------------------------------------------------------------------
--  INDEX name
------------------------------------------------------------------

ALTER TABLE t_names
ADD UNIQUE KEY name (name);


------------------------------------------------------------------
--  INDEX PRIMARY
------------------------------------------------------------------

ALTER TABLE t_data
ADD UNIQUE KEY `primaryId` (id);


------------------------------------------------------------------
--  INDEX UNIQUE
------------------------------------------------------------------

ALTER TABLE t_data
ADD UNIQUE KEY `unique` (`date`, frame);

------------------------------------------------------------------
--  TRIGGER TR_SECOND_STRIP
------------------------------------------------------------------

CREATE TRIGGER `TR_SECOND_STRIP`
   BEFORE INSERT
   ON uvr1611.t_hg_data
   FOR EACH ROW
BEGIN
   SET new.date = Date_format(new.date, '%Y-%m-%d %H:%i');
END;

CREATE TRIGGER `TR_SECOND_STRIP_1`
   BEFORE INSERT
   ON uvr1611.t_data
   FOR EACH ROW
BEGIN
   SET new.date = Date_format(new.date, '%Y-%m-%d %H:%i');
END;

-- INSERTS
INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (1,
             'frame1',
             'analog1',
             'Kollektor',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (4,
             'frame1',
             'analog2',
             'Solar RL PU',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (5,
             'frame1',
             'analog3',
             'Puffer unten',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (6,
             'frame1',
             'analog4',
             'Puffer oben',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (7,
             'frame1',
             'analog5',
             'Solar RL WW',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (8,
             'frame1',
             'analog6',
             'Boiler oben',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (9,
             'frame1',
             'analog7',
             'HKL 2 VL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (10,
             'frame1',
             'analog8',
             'Solar RL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (11,
             'frame1',
             'analog9',
             'HKL 1 VL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (12,
             'frame1',
             'analog10',
             'Boiler unten',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (13,
             'frame1',
             'analog11',
             'Außen',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (14,
             'frame1',
             'analog12',
             'Kessel RL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (15,
             'frame1',
             'analog13',
             'Kessel VL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (16,
             'frame1',
             'analog14',
             'Solar VL',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (17,
             'frame1',
             'analog15',
             'DFM Kessel',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (18,
             'frame1',
             'analog16',
             'DFM Solar',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (19,
             'frame1',
             'power1',
             'Leistung Kessel',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (20,
             'frame1',
             'power2',
             'Leistung Kollektor',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (21,
             'frame1',
             'energy1',
             'Kessel',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (22,
             'frame1',
             'energy2',
             'Flächenkollektor',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (197,
             'frame1',
             '1',
             'ZK',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (198,
             'frame1',
             '2',
             'O2',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (199,
             'frame1',
             '3',
             'O2soll',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (200,
             'frame1',
             '4',
             'TK',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (201,
             'frame1',
             '5',
             'TKsoll',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (202,
             'frame1',
             '6',
             'TRG',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (203,
             'frame1',
             '7',
             'SZ',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (204,
             'frame1',
             '8',
             'SZsoll',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (205,
             'frame1',
             '9',
             'PLK',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (206,
             'frame1',
             '10',
             'PLKsoll',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (207,
             'frame1',
             '11',
             'Leistung',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (208,
             'frame1',
             '12',
             'Förder',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (209,
             'frame1',
             '13',
             'EsRostPos',
             '°');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (210,
             'frame1',
             '14',
             'UD',
             'Pa');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (211,
             'frame1',
             '15',
             'GBF',
             '°');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (212,
             'frame1',
             '16',
             'I Es',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (213,
             'frame1',
             '17',
             'I Ra',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (214,
             'frame1',
             '18',
             'I Aa',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (215,
             'frame1',
             '19',
             'I EsRost',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (216,
             'frame1',
             '20',
             'I VS',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (217,
             'frame1',
             '21',
             'Taus',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (218,
             'frame1',
             '22',
             'TA Gem.',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (219,
             'frame1',
             '23',
             'TPo',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (220,
             'frame1',
             '24',
             'TPm',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (221,
             'frame1',
             '25',
             'TPu',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (222,
             'frame1',
             '26',
             'TFW',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (223,
             'frame1',
             '27',
             'TRL',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (224,
             'frame1',
             '28',
             'TRLsoll',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (225,
             'frame1',
             '29',
             'RLP',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (226,
             'frame1',
             '30',
             'Tplat',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (227,
             'frame1',
             '31',
             'TVL_A',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (228,
             'frame1',
             '32',
             'TVLs_A',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (229,
             'frame1',
             '33',
             'TRA',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (230,
             'frame1',
             '34',
             'TBA',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (231,
             'frame1',
             '35',
             'TBs_A',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (232,
             'frame1',
             '36',
             'TVL_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (233,
             'frame1',
             '37',
             'TVL_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (234,
             'frame1',
             '38',
             'TVLs_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (235,
             'frame1',
             '39',
             'TVLs_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (236,
             'frame1',
             '40',
             'TR1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (237,
             'frame1',
             '41',
             'TR2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (238,
             'frame1',
             '42',
             'TB1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (239,
             'frame1',
             '43',
             'TBs_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (240,
             'frame1',
             '44',
             'TVL_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (241,
             'frame1',
             '45',
             'TVL_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (242,
             'frame1',
             '46',
             'TVLs_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (243,
             'frame1',
             '47',
             'TVLs_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (244,
             'frame1',
             '48',
             'TR3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (245,
             'frame1',
             '49',
             'TR4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (246,
             'frame1',
             '50',
             'TB2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (247,
             'frame1',
             '51',
             'TBs_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (248,
             'frame1',
             '52',
             'TVL_5',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (249,
             'frame1',
             '53',
             'TVL_6',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (250,
             'frame1',
             '54',
             'TVLs_5',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (251,
             'frame1',
             '55',
             'TVLs_6',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (252,
             'frame1',
             '56',
             'TR5',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (253,
             'frame1',
             '57',
             'TR6',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (254,
             'frame1',
             '58',
             'TB3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (255,
             'frame1',
             '59',
             'TBs_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (256,
             'frame1',
             '60',
             'TRs_A',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (257,
             'frame1',
             '61',
             'TRs_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (258,
             'frame1',
             '62',
             'TRs_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (259,
             'frame1',
             '63',
             'TRs_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (260,
             'frame1',
             '64',
             'TRs_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (261,
             'frame1',
             '65',
             'TRs_5',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (262,
             'frame1',
             '66',
             'TRs_6',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (263,
             'frame1',
             '67',
             'WMZ Vorl.',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (264,
             'frame1',
             '68',
             'WMZ Rueckl.',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (265,
             'frame1',
             '69',
             'WMZ Durchf.',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (266,
             'frame1',
             '70',
             'WMZ Leist.',
             'KW');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (267,
             'frame1',
             '71',
             'VFS Flow',
             'l/min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (268,
             'frame1',
             '72',
             'VFS Temp',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (269,
             'frame1',
             '73',
             'IO32 VL',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (270,
             'frame1',
             '74',
             'KaskSollTmp_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (271,
             'frame1',
             '75',
             'KaskSollTmp_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (272,
             'frame1',
             '76',
             'KaskSollTmp_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (273,
             'frame1',
             '77',
             'KaskSollTmp_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (274,
             'frame1',
             '78',
             'KaskIstTmp_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (275,
             'frame1',
             '79',
             'KaskIstTmp_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (276,
             'frame1',
             '80',
             'KaskIstTmp_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (277,
             'frame1',
             '81',
             'KaskIstTmp_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (278,
             'frame1',
             '82',
             'UsePos',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (279,
             'frame1',
             '83',
             'UseMotSoll',
             'mm');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (280,
             'frame1',
             '84',
             'UseMotIst',
             'mm');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (281,
             'frame1',
             '85',
             'HKZustand_A',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (282,
             'frame1',
             '86',
             'HKZustand_1',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (283,
             'frame1',
             '87',
             'HKZustand_2',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (284,
             'frame1',
             '88',
             'HKZustand_3',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (285,
             'frame1',
             '89',
             'HKZustand_4',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (286,
             'frame1',
             '90',
             'HKZustand_5',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (287,
             'frame1',
             '91',
             'HKZustand_6',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (288,
             'frame1',
             '92',
             'BoiZustand_A',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (289,
             'frame1',
             '93',
             'BoiZustand_1',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (290,
             'frame1',
             '94',
             'BoiZustand_2',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (291,
             'frame1',
             '95',
             'BoiZustand_3',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (292,
             'frame1',
             '96',
             'PuffZustand',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (293,
             'frame1',
             '97',
             'Puffer_soll',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (294,
             'frame1',
             '98',
             'Mode Fw',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (295,
             'frame1',
             '99',
             'I AschRost',
             'mA');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (296,
             'frame1',
             '100',
             'AschRostPos',
             '°');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (297,
             'frame1',
             '101',
             'ETÜ',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (298,
             'frame1',
             '102',
             'TÜB',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (299,
             'frame1',
             '103',
             'IO32 521',
             'Pa');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (300,
             'frame1',
             '104',
             'IO32 522',
             'Pa');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (301,
             'frame1',
             '105',
             'IO32 509',
             'Pa');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (302,
             'frame1',
             '106',
             'IO32 510',
             'Pa');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (303,
             'frame1',
             '107',
             'NICRNI Res',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (304,
             'frame1',
             '108',
             'GBF soll',
             '°');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (305,
             'frame1',
             '109',
             'UD rel',
             '%');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (306,
             'frame1',
             '110',
             'U_Lambda',
             'mV');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (307,
             'frame1',
             '111',
             'Max Leistung',
             NULL);

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (308,
             'frame1',
             '112',
             'Solltmp. ExtHK',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (309,
             'frame1',
             '113',
             'BSZ Einschub',
             'h');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (310,
             'frame1',
             '114',
             'KaskLZLeisMin_1',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (311,
             'frame1',
             '115',
             'KaskLZLeisMin_2',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (312,
             'frame1',
             '116',
             'KaskLZLeisMin_3',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (313,
             'frame1',
             '117',
             'KaskLZLeisMin_4',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (314,
             'frame1',
             '118',
             'KaskLZLeisMax_1',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (315,
             'frame1',
             '119',
             'KaskLZLeisMax_2',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (316,
             'frame1',
             '120',
             'KaskLZLeisMax_3',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (317,
             'frame1',
             '121',
             'KaskLZLeisMax_4',
             'Min');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (318,
             'frame1',
             '122',
             'Kask LZLeist_1',
             'h');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (319,
             'frame1',
             '123',
             'Kask LZLeist_2',
             'h');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (320,
             'frame1',
             '124',
             'Kask LZLeist_3',
             'h');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (321,
             'frame1',
             '125',
             'Kask LZLeist_4',
             'h');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (322,
             'frame1',
             '126',
             'TÜB2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (323,
             'frame1',
             '127',
             'TRA_A',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (324,
             'frame1',
             '128',
             'TRA_1',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (325,
             'frame1',
             '129',
             'TRA_2',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (326,
             'frame1',
             '130',
             'TRA_3',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (327,
             'frame1',
             '131',
             'TRA_4',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (328,
             'frame1',
             '132',
             'TRA_5',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (329,
             'frame1',
             '133',
             'TRA_6',
             '°C');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (352,
             'frame1',
             'd010',
             'HKP2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (351,
             'frame1',
             'd09',
             'M1Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (350,
             'frame1',
             'd08',
             'M1A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (349,
             'frame1',
             'd07',
             'HKP1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (348,
             'frame1',
             'd06',
             'MAZ',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (347,
             'frame1',
             'd05',
             'MAA',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (346,
             'frame1',
             'd04',
             'HKPA',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (345,
             'frame1',
             'd02',
             'TKS',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (344,
             'frame1',
             'd01',
             'NotAus',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (343,
             'frame1',
             'd00',
             'Stb',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (353,
             'frame1',
             'd011',
             'M2A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (354,
             'frame1',
             'd012',
             'M2Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (355,
             'frame1',
             'd013',
             'Störung',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (356,
             'frame1',
             'd10',
             'L Heiz.',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (357,
             'frame1',
             'd11',
             'Z Heiz.',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (358,
             'frame1',
             'd12',
             'Z Geb.',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (359,
             'frame1',
             'd13',
             'AA Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (360,
             'frame1',
             'd14',
             'AA Dir',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (361,
             'frame1',
             'd15',
             'AA Saug',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (362,
             'frame1',
             'd16',
             'ES Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (363,
             'frame1',
             'd17',
             'ES Dir',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (364,
             'frame1',
             'd18',
             'RA Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (365,
             'frame1',
             'd19',
             'RA Dir',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (366,
             'frame1',
             'd110',
             'Deckel',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (367,
             'frame1',
             'd111',
             'FS Asche',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (368,
             'frame1',
             'd112',
             'INI Asche',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (369,
             'frame1',
             'd113',
             'ER Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (370,
             'frame1',
             'd114',
             'AR Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (371,
             'frame1',
             'd20',
             'BPA',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (372,
             'frame1',
             'd21',
             'BP1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (373,
             'frame1',
             'd22',
             'BP2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (374,
             'frame1',
             'd23',
             'BP3',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (375,
             'frame1',
             'd24',
             'BZP0',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (376,
             'frame1',
             'd25',
             'BZP1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (377,
             'frame1',
             'd26',
             'BZP2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (378,
             'frame1',
             'd27',
             'BZP3',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (379,
             'frame1',
             'd28',
             'RLm_auf',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (380,
             'frame1',
             'd29',
             'RLm_zu',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (381,
             'frame1',
             'd210',
             'RL Pumpe',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (382,
             'frame1',
             'd211',
             'SK Aschebox',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (383,
             'frame1',
             'd212',
             'SK RaDeckel',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (384,
             'frame1',
             'd213',
             'SK VsDeckel',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (385,
             'frame1',
             'd214',
             'SK Lager',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (386,
             'frame1',
             'd30',
             'HKP3',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (387,
             'frame1',
             'd31',
             'M3A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (388,
             'frame1',
             'd32',
             'M3Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (389,
             'frame1',
             'd33',
             'HKP4',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (390,
             'frame1',
             'd34',
             'M4A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (391,
             'frame1',
             'd35',
             'M4Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (392,
             'frame1',
             'd36',
             'HKP5',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (393,
             'frame1',
             'd37',
             'M5A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (394,
             'frame1',
             'd38',
             'M5Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (395,
             'frame1',
             'd39',
             'HKP6',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (396,
             'frame1',
             'd310',
             'M6A',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (397,
             'frame1',
             'd311',
             'M6Z',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (398,
             'frame1',
             'd312',
             'FLP1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (399,
             'frame1',
             'd313',
             'FLP2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (400,
             'frame1',
             'd314',
             'VS/RA2 Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (401,
             'frame1',
             'd315',
             'VS/RA2 Dir',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (402,
             'frame1',
             'd41',
             'Entasch gesp.',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (403,
             'frame1',
             'd42',
             'ATW',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (404,
             'frame1',
             'd43',
             'SK Bruecke',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (405,
             'frame1',
             'd44',
             'ExtHK',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (406,
             'frame1',
             'd45',
             'ExtHK_1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (407,
             'frame1',
             'd46',
             'ExtHK_2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (408,
             'frame1',
             'd47',
             'ExtHK_3',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (409,
             'frame1',
             'd48',
             'HKP Ext',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (410,
             'frame1',
             'd49',
             'HKP Ext_1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (411,
             'frame1',
             'd410',
             'HKP Ext_2',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (412,
             'frame1',
             'd411',
             'PuffP',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (413,
             'frame1',
             'd412',
             'FW Freig.',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (414,
             'frame1',
             'd414',
             'HKV',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (415,
             'frame1',
             'd415',
             'DIN_E1',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (416,
             'frame1',
             'd50',
             'VS Deckel',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (417,
             'frame1',
             'd51',
             'DIN_E13',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (418,
             'frame1',
             'd52',
             'DIN_E14',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (419,
             'frame1',
             'd53',
             'DIN_E35',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (420,
             'frame1',
             'd54',
             'KASK1 MinLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (421,
             'frame1',
             'd55',
             'KASK2 MinLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (422,
             'frame1',
             'd56',
             'KASK3 MinLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (423,
             'frame1',
             'd57',
             'KASK4 MinLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (424,
             'frame1',
             'd58',
             'KASK1 MaxLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (425,
             'frame1',
             'd59',
             'KASK2 MaxLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (426,
             'frame1',
             'd510',
             'KASK3 MaxLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (427,
             'frame1',
             'd511',
             'KASK4 MaxLeist',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (428,
             'frame1',
             'd512',
             'KASK1 Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (429,
             'frame1',
             'd513',
             'KASK2 Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (430,
             'frame1',
             'd514',
             'KASK3 Run',
             'digital');

INSERT INTO t_names(id,
                    frame,
                    type,
                    name,
                    unit)
     VALUES (431,
             'frame1',
             'd515',
             'KASK4 Run',
             'digital');
             

insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'analog1','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'21','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'analog10','frame1',4);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'analog3','frame1',6);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (3,'analog9','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (3,'analog7','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'analog4','frame1',5);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (4,'analog13','frame1',4);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (3,'21','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (4,'21','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (4,'analog14','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (4,'analog8','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (4,'analog12','frame1',5);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'analog1','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'analog14','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'analog8','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'analog2','frame1',4);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (6,'power1','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (6,'power2','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (7,'energy1','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (7,'energy2','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (2,'analog6','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'analog5','frame1',5);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (5,'21','frame1',6);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (10,'4','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (10,'11','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (10,'12','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (10,'9','frame1',4);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (10,'21','frame1',5);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (11,'86','frame1',1);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (11,'87','frame1',2);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (11,'96','frame1',3);
insert into `t_names_of_charts`(`chart_id`,`type`,`frame`,`order`) values (11,'93','frame1',4);


insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (2,'Speicher','#.# �C','line',3,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (3,'Heizkreise','#.# �C','line',4,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (4,'Sonstige','#.# �C','line',5,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (5,'Solar','#.# �C','line',6,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (6,'Leistung','#.## kW','power',7,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (7,'Ertr�ge','#.# kWh','energy',8,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (8,'Schema',null,'schema',1,'schema.svg');
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (9,'Kollektoren',null,'schema',2,'kollektoren.svg');
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (10,'Kessel','# �C','line',9,null);
insert into `t_menu`(`id`,`name`,`unit`,`type`,`order`,`schema`) values (11,'Zust�nde','#','line',10,null);


insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (1,'#speicher1_oben > tspan','frame1','analog1','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (2,'#speicher1_unten > tspan','frame1','analog2','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (3,'#speicher2_oben > tspan','frame1','analog3','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (4,'#speicher2_unten > tspan','frame1','analog4','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (5,'#innen_temp > tspan','frame1','analog5','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (6,'#aussen_temp > tspan','frame1','analog11','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (7,'#vl1_temp > tspan','frame1','analog7','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (8,'#vl2_temp > tspan','frame1','analog8','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (9,'#rl3_temp > tspan','frame1','analog9','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (10,'#kessel_temp > tspan','frame1','analog12','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (11,'#roehren_temp > tspan','frame1','analog15','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (12,'#flach_temp > tspan','frame1','analog16','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (13,'#solarrl_temp > tspan','frame1','analog14','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (14,'#solarvl_temp > tspan','frame1','analog13','#.# �C');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (15,'#fb_pump > tspan','frame1','digital2','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (16,'#hz_pump > tspan','frame1','digital1','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (17,'#ww_pump > tspan','frame1','digital6','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (18,'#flach_pumpe > tspan','frame1','digital11','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (19,'#roehren_pumpe > tspan','frame1','digital10','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (20,'#lade_pumpe > tspan','frame1','digital7','DIGITAL(#)');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (21,'#flach_info > tspan:eq(1)','frame1','energy1','Gesamt: MWH(#) MWh KWH(#.#) kWh');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (22,'#flach_info > tspan:eq(2)','frame1','power1','Aktuell: #.### kW');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (23,'#roehren_info > tspan:eq(1)','frame1','energy2','Gesamt: MWH(#) MWh KWH(#.#) kWh');
insert into `t_schema`(`id`,`path`,`frame`,`type`,`format`) values (24,'#roehren_info > tspan:eq(2)','frame1','power2','Aktuell: #.### kW');


