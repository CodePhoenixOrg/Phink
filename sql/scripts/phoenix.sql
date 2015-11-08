-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.46


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema phoenix
--

CREATE DATABASE IF NOT EXISTS phoenix;
USE phoenix;

DROP TABLE IF EXISTS `phoenix`.`__app_document`;
CREATE TABLE  `phoenix`.`__app_document` (
  `app_id` int(10) NOT NULL,
  `doc_id` int(10) NOT NULL,
  PRIMARY KEY (`app_id`,`doc_id`),
  UNIQUE KEY `PK_document_app` (`app_id`,`doc_id`),
  KEY `FK_app_document_documents` (`doc_id`),
  CONSTRAINT `FK_app_document_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_app_document_documents` FOREIGN KEY (`doc_id`) REFERENCES `documents` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`__dbconn_app`;
CREATE TABLE  `phoenix`.`__dbconn_app` (
  `dbc_id` int(10) NOT NULL,
  `app_id` int(10) NOT NULL,
  PRIMARY KEY (`dbc_id`,`app_id`),
  UNIQUE KEY `PK_dbconn_app` (`dbc_id`,`app_id`),
  KEY `FK_dbconn_app_applications` (`app_id`),
  CONSTRAINT `FK_dbconn_app_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_dbconn_app_dbconn` FOREIGN KEY (`dbc_id`) REFERENCES `dbconn` (`dbc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`__document_form`;
CREATE TABLE  `phoenix`.`__document_form` (
  `doc_id` int(10) NOT NULL,
  `frm_id` int(10) NOT NULL,
  PRIMARY KEY (`doc_id`,`frm_id`),
  UNIQUE KEY `PK_document_form` (`doc_id`,`frm_id`),
  KEY `FK_document_form_forms` (`frm_id`),
  CONSTRAINT `FK_document_form_documents` FOREIGN KEY (`doc_id`) REFERENCES `documents` (`doc_id`),
  CONSTRAINT `FK_document_form_forms` FOREIGN KEY (`frm_id`) REFERENCES `forms` (`frm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`__form_block`;
CREATE TABLE  `phoenix`.`__form_block` (
  `frm_id` int(10) NOT NULL,
  `bl_id` int(10) NOT NULL,
  PRIMARY KEY (`frm_id`,`bl_id`),
  UNIQUE KEY `PK_form_block` (`frm_id`,`bl_id`),
  KEY `FK_form_block_blocks` (`bl_id`),
  CONSTRAINT `FK_form_block_blocks` FOREIGN KEY (`bl_id`) REFERENCES `blocks` (`bl_id`),
  CONSTRAINT `FK_form_block_forms` FOREIGN KEY (`frm_id`) REFERENCES `forms` (`frm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`__member_newletter`;
CREATE TABLE  `phoenix`.`__member_newletter` (
  `mbr_id` int(10) NOT NULL,
  `nl_id` int(10) NOT NULL,
  PRIMARY KEY (`mbr_id`,`nl_id`),
  UNIQUE KEY `PK_member_newletter` (`mbr_id`,`nl_id`),
  KEY `FK_member_newletter_newsletter` (`nl_id`),
  CONSTRAINT `FK_member_newletter_members` FOREIGN KEY (`mbr_id`) REFERENCES `members` (`mbr_id`),
  CONSTRAINT `FK_member_newletter_newsletter` FOREIGN KEY (`nl_id`) REFERENCES `newsletter` (`nl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`__user_app`;
CREATE TABLE  `phoenix`.`__user_app` (
  `usr_id` int(10) NOT NULL,
  `app_id` int(10) NOT NULL,
  PRIMARY KEY (`usr_id`,`app_id`),
  UNIQUE KEY `PK_user_app` (`usr_id`,`app_id`),
  KEY `FK_user_app_applications` (`app_id`),
  CONSTRAINT `FK_user_app_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_user_app_users` FOREIGN KEY (`usr_id`) REFERENCES `users` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`_block_type`;
CREATE TABLE  `phoenix`.`_block_type` (
  `bt_id` int(10) NOT NULL,
  `bt_type` varchar(10) NOT NULL,
  PRIMARY KEY (`bt_id`),
  UNIQUE KEY `PK_block_type` (`bt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`_block_type` VALUES  (1,'form      '),
 (2,'menu      ');

DROP TABLE IF EXISTS `phoenix`.`_bug_status`;
CREATE TABLE  `phoenix`.`_bug_status` (
  `bs_id` int(10) NOT NULL,
  `bs_status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`bs_id`),
  UNIQUE KEY `PK_bug_status` (`bs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`_bug_status` VALUES  (1,'à fixer'),
 (2,'en cours'),
 (3,'fixé'),
 (4,'suspendu'),
 (5,'abandonné');

DROP TABLE IF EXISTS `phoenix`.`_dbserver_type`;
CREATE TABLE  `phoenix`.`_dbserver_type` (
  `dbs_id` int(10) NOT NULL,
  `dbs_type` varchar(10) NOT NULL,
  PRIMARY KEY (`dbs_id`),
  UNIQUE KEY `PK_dbserver_type` (`dbs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`_dbserver_type` VALUES  (1,'MySQL'),
 (2,'SQL Server'),
 (3,'SQLite');

DROP TABLE IF EXISTS `phoenix`.`_document_type`;
CREATE TABLE  `phoenix`.`_document_type` (
  `dt_id` int(10) NOT NULL,
  `dt_type` varchar(15) NOT NULL,
  PRIMARY KEY (`dt_id`),
  UNIQUE KEY `PK_document_type` (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`_form_type`;
CREATE TABLE  `phoenix`.`_form_type` (
  `ft_id` int(10) NOT NULL,
  `ft_type` varchar(10) NOT NULL,
  PRIMARY KEY (`ft_id`),
  UNIQUE KEY `PK_form_type` (`ft_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`_form_type` VALUES  (1,'html'),
 (2,'php'),
 (3,'aspx');

DROP TABLE IF EXISTS `phoenix`.`_protocol_type`;
CREATE TABLE  `phoenix`.`_protocol_type` (
  `prt_id` int(10) NOT NULL,
  `prt_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`prt_id`),
  UNIQUE KEY `PK_protocol_type` (`prt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`applications`;
CREATE TABLE  `phoenix`.`applications` (
  `app_id` int(10) NOT NULL,
  `app_name` varchar(50) DEFAULT NULL,
  `di_id` int(10) DEFAULT NULL,
  `sto_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `PK_applications` (`app_id`),
  KEY `FK_applications_dictionary` (`di_id`),
  KEY `FK_applications_storage` (`sto_id`),
  CONSTRAINT `FK_applications_dictionary` FOREIGN KEY (`di_id`) REFERENCES `dictionary` (`di_id`),
  CONSTRAINT `FK_applications_storage` FOREIGN KEY (`sto_id`) REFERENCES `storage` (`sto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`applications` VALUES  (1,NULL,19,NULL);

DROP TABLE IF EXISTS `phoenix`.`blocks`;
CREATE TABLE  `phoenix`.`blocks` (
  `bl_id` int(10) NOT NULL,
  `bl_column` varchar(1) DEFAULT NULL,
  `bt_id` int(10) NOT NULL,
  `di_id` int(10) NOT NULL,
  PRIMARY KEY (`bl_id`),
  UNIQUE KEY `PK_blocks` (`bl_id`),
  KEY `FK_blocks_block_type` (`bt_id`),
  KEY `FK_blocks_dictionary` (`di_id`),
  CONSTRAINT `FK_blocks_dictionary` FOREIGN KEY (`di_id`) REFERENCES `dictionary` (`di_id`),
  CONSTRAINT `FK_blocks_block_type` FOREIGN KEY (`bt_id`) REFERENCES `_block_type` (`bt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`blocks` VALUES  (1,'1',2,0);

DROP TABLE IF EXISTS `phoenix`.`bugreport`;
CREATE TABLE  `phoenix`.`bugreport` (
  `br_id` int(10) NOT NULL,
  `br_title` varchar(255) DEFAULT NULL,
  `br_text` longtext,
  `br_importance` int(10) DEFAULT NULL,
  `br_date` datetime DEFAULT NULL,
  `br_time` datetime DEFAULT NULL,
  `bs_id` int(10) DEFAULT NULL,
  `usr_id` int(10) DEFAULT NULL,
  `app_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`br_id`),
  UNIQUE KEY `PK_bugreport` (`br_id`),
  KEY `FK_bugreport_bug_status` (`bs_id`),
  KEY `FK_bugreport_users` (`usr_id`),
  KEY `FK_bugreport_applications` (`app_id`),
  CONSTRAINT `FK_bugreport_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_bugreport_bug_status` FOREIGN KEY (`bs_id`) REFERENCES `_bug_status` (`bs_id`),
  CONSTRAINT `FK_bugreport_users` FOREIGN KEY (`usr_id`) REFERENCES `users` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`changelog`;
CREATE TABLE  `phoenix`.`changelog` (
  `cl_id` int(10) NOT NULL,
  `cl_title` varchar(255) DEFAULT NULL,
  `cl_text` longtext,
  `cl_date` datetime DEFAULT NULL,
  `cl_time` datetime DEFAULT NULL,
  `app_id` int(10) DEFAULT NULL,
  `usr_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`cl_id`),
  UNIQUE KEY `PK_changelog` (`cl_id`),
  KEY `FK_changelog_users` (`usr_id`),
  KEY `FK_changelog_applications` (`app_id`),
  CONSTRAINT `FK_changelog_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_changelog_users` FOREIGN KEY (`usr_id`) REFERENCES `users` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`dbconn`;
CREATE TABLE  `phoenix`.`dbconn` (
  `dbc_id` int(10) NOT NULL,
  `dbc_host` varchar(50) NOT NULL,
  `dbc_database` varchar(15) NOT NULL,
  `dbc_login` varchar(15) NOT NULL,
  `dbc_passwd` varchar(16) NOT NULL,
  `dbs_id` int(10) NOT NULL,
  PRIMARY KEY (`dbc_id`),
  UNIQUE KEY `PK_dbconn` (`dbc_id`),
  KEY `FK_dbconn_dbserver_type` (`dbs_id`),
  CONSTRAINT `FK_dbconn_dbserver_type` FOREIGN KEY (`dbs_id`) REFERENCES `_dbserver_type` (`dbs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`dbconn` VALUES  (1,'localhost','phoenix','root','',1);

DROP TABLE IF EXISTS `phoenix`.`dictionary`;
CREATE TABLE  `phoenix`.`dictionary` (
  `di_id` int(10) NOT NULL,
  `di_name` varchar(8) DEFAULT NULL,
  `di_fr_short` varchar(255) DEFAULT NULL,
  `di_fr_long` longtext,
  `di_en_short` varchar(255) DEFAULT NULL,
  `di_en_long` longtext,
  `di_ru_short` varchar(255) DEFAULT NULL,
  `di_ru_long` longtext,
  PRIMARY KEY (`di_id`),
  UNIQUE KEY `PK_dictionary` (`di_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`dictionary` VALUES  (0,'na','N/A','N/A','N/A','N/A','',''),
 (1,'applicat','Applications','Liste des applications','Applications','List of applications','',''),
 (2,'blocks','Blocs','Liste des blocs','Blocks','List of blocks','',''),
 (3,'bugrepor','Bugs','Rapport de bugs','Bugs','Bug reports','',''),
 (4,'changelo','Changements','Notes de changements','Changes','Change log','',''),
 (5,'dictiona','Dictionnaire','','Dictionary','','',''),
 (6,'editor','Editer','Editer les attributs du script','Edit','Edit script attributes','',''),
 (7,'forums','Forums','Forums disponibles','Forums','Available forums','',''),
 (8,'groups','Groupes','Liste des groupes','Groups','List of groups','',''),
 (9,'home','Accueil','Page d\'accueil','Home','Home page','',''),
 (10,'members','Accès membres','Gérez votre profil membre','Members area','Manage your data','',''),
 (11,'menus','Menus','Entrées de menus','Menus','Menu items','',''),
 (12,'mkblock','Créer un bloc','Créer un nouveau bloc','Create a block','Create a new block','',''),
 (13,'mkfields','Champs','Champs de la table','Fields','Table fields','',''),
 (14,'mkfile','Fichier','Création du fichier','File','Creation of the file','',''),
 (15,'mkmenu','Créer un menu','Créer une nouvelle entrée de menu','Create a menu','Create a new menu item','',''),
 (16,'mkscript','Créer un script','Créer un script à partir d\'une table','Create a script','Create a script from a table','',''),
 (17,'pages','Pages','Liste des pages','Pages','List of pages','',''),
 (18,'todo','A faire','Liste des tâches','To do','Tasks to do','',''),
 (19,'webfacto','WebFactory','WebFactory','WebFactory','WebFactory','','');

DROP TABLE IF EXISTS `phoenix`.`documents`;
CREATE TABLE  `phoenix`.`documents` (
  `doc_id` int(10) NOT NULL,
  `doc_name` varchar(50) NOT NULL,
  `doc_title` varchar(255) NOT NULL,
  `doc_content` longtext,
  `doc_dir` varchar(255) NOT NULL,
  `doc_url` varchar(255) NOT NULL,
  `dt_id` int(10) NOT NULL,
  PRIMARY KEY (`doc_id`),
  UNIQUE KEY `PK_documents` (`doc_id`),
  KEY `FK_documents_document_type` (`dt_id`),
  CONSTRAINT `FK_documents_document_type` FOREIGN KEY (`dt_id`) REFERENCES `_document_type` (`dt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`forms`;
CREATE TABLE  `phoenix`.`forms` (
  `frm_id` int(10) NOT NULL,
  `frm_filename` varchar(255) DEFAULT NULL,
  `frm_directory` varchar(1024) DEFAULT NULL,
  `frm_url` varchar(1024) DEFAULT NULL,
  `di_id` int(10) DEFAULT NULL,
  `ft_id` int(10) DEFAULT NULL,
  `app_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`frm_id`),
  UNIQUE KEY `PK_forms` (`frm_id`),
  KEY `FK_forms_form_type` (`ft_id`),
  KEY `FK_forms_applications` (`app_id`),
  KEY `FK_forms_dictionary` (`di_id`),
  CONSTRAINT `FK_forms_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_forms_dictionary` FOREIGN KEY (`di_id`) REFERENCES `dictionary` (`di_id`),
  CONSTRAINT `FK_forms_form_type` FOREIGN KEY (`ft_id`) REFERENCES `_form_type` (`ft_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`forms` VALUES  (17,'mkmain.php','.','',9,2,1),
 (18,'menus.php','.','',11,2,1),
 (19,'pages.php','.','',17,2,1),
 (20,'blocks.php','.','',2,2,1),
 (21,'dictionary.php','.','',5,2,1),
 (22,'applications.php','.','',1,2,1),
 (23,'forums.php','.','',7,2,1),
 (24,'changelog.php','.','',4,2,1),
 (25,'todo.php','.','',18,2,1),
 (26,'bugreport.php','.','',3,2,1),
 (27,'groups.php','.','',8,2,1),
 (28,'newsletter.php','.','',0,2,1),
 (29,'mkscript.php','.','',16,2,1),
 (30,'mkmenu.php','.','',15,2,1),
 (31,'mkblock.php','.','',12,2,1),
 (32,'mkfields.php','.','',13,2,1),
 (33,'mkfile.php','.','',14,2,1);

DROP TABLE IF EXISTS `phoenix`.`groups`;
CREATE TABLE  `phoenix`.`groups` (
  `grp_id` int(10) NOT NULL,
  `grp_name` varchar(15) NOT NULL,
  `grp_members_priv` char(1) NOT NULL,
  `grp_menu_priv` char(1) NOT NULL,
  `grp_page_priv` char(1) NOT NULL,
  `grp_news_priv` char(1) NOT NULL,
  `grp_items_priv` char(1) NOT NULL,
  `grp_database_priv` char(1) NOT NULL,
  `grp_images_priv` char(1) NOT NULL,
  `grp_calendar_priv` char(1) NOT NULL,
  `grp_newsletter_priv` char(1) NOT NULL,
  `grp_forum_priv` char(1) NOT NULL,
  `grp_users_priv` char(1) NOT NULL,
  PRIMARY KEY (`grp_id`),
  UNIQUE KEY `PK_groups` (`grp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`groups` VALUES  (1,'root           ','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y');

DROP TABLE IF EXISTS `phoenix`.`members`;
CREATE TABLE  `phoenix`.`members` (
  `mbr_id` int(10) NOT NULL,
  `mbr_name` varchar(50) DEFAULT NULL,
  `mbr_adr1` varchar(50) DEFAULT NULL,
  `mbr_adr2` varchar(50) DEFAULT NULL,
  `mbr_cp` varchar(5) DEFAULT NULL,
  `mbr_email` varchar(50) DEFAULT NULL,
  `mbr_login` varchar(50) DEFAULT NULL,
  `mbr_password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mbr_id`),
  UNIQUE KEY `PK_members` (`mbr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`members` VALUES  (1,'David BLANCHARD','Pas d\'adresse','','76000','davidbl@wanadoo.fr','dpjb',''),
 (2,'Pierre-Yves Le Bihan','Pas d\'adresse','','92800','pylb@wanadoo.fr','pylb','');

DROP TABLE IF EXISTS `phoenix`.`menus`;
CREATE TABLE  `phoenix`.`menus` (
  `me_id` int(10) NOT NULL,
  `me_level` varchar(1) DEFAULT NULL,
  `me_target` varchar(7) DEFAULT NULL,
  `frm_id` int(10) DEFAULT NULL,
  `bl_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`me_id`),
  UNIQUE KEY `PK_menus` (`me_id`),
  KEY `FK_menus_blocks` (`bl_id`),
  KEY `FK_menus_forms` (`frm_id`),
  CONSTRAINT `FK_menus_blocks` FOREIGN KEY (`bl_id`) REFERENCES `blocks` (`bl_id`),
  CONSTRAINT `FK_menus_forms` FOREIGN KEY (`frm_id`) REFERENCES `forms` (`frm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`menus` VALUES  (17,'1','page',17,1),
 (18,'1','page',18,1),
 (19,'1','page',19,1),
 (20,'1','page',20,1),
 (21,'1','page',21,1),
 (22,'2','page',22,1),
 (23,'0','page',23,1),
 (24,'1','page',24,1),
 (25,'1','page',25,1),
 (26,'1','page',26,1),
 (27,'1','page',27,1),
 (29,'0','page',29,1),
 (30,'0','page',30,1),
 (31,'0','page',31,1),
 (32,'0','page',32,1),
 (33,'0','page',33,1);

DROP TABLE IF EXISTS `phoenix`.`newsletter`;
CREATE TABLE  `phoenix`.`newsletter` (
  `nl_id` int(10) NOT NULL,
  `nl_title` varchar(255) DEFAULT NULL,
  `nl_author` varchar(255) DEFAULT NULL,
  `nl_header` longtext,
  `nl_image` varchar(255) DEFAULT NULL,
  `nl_comment` varchar(255) DEFAULT NULL,
  `nl_body` longtext,
  `nl_links` longtext,
  `nl_footer` longtext,
  `nl_file` varchar(255) DEFAULT NULL,
  `nl_date` datetime DEFAULT NULL,
  PRIMARY KEY (`nl_id`),
  UNIQUE KEY `PK_newsletter` (`nl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`queries`;
CREATE TABLE  `phoenix`.`queries` (
  `qy_id` int(10) NOT NULL,
  `qy_name` varchar(15) NOT NULL,
  `qy_text` longtext NOT NULL,
  `dbc_id` int(10) NOT NULL,
  PRIMARY KEY (`qy_id`),
  UNIQUE KEY `PK_queries` (`qy_id`),
  KEY `FK_queries_dbconn` (`dbc_id`),
  CONSTRAINT `FK_queries_dbconn` FOREIGN KEY (`dbc_id`) REFERENCES `dbconn` (`dbc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`storage`;
CREATE TABLE  `phoenix`.`storage` (
  `sto_id` int(10) NOT NULL,
  `sto_root_dir` varchar(255) NOT NULL,
  `sto_host` varchar(255) NOT NULL,
  `sto_port` int(10) DEFAULT NULL,
  `usr_id` int(10) DEFAULT NULL,
  `prt_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`sto_id`),
  UNIQUE KEY `PK_storage` (`sto_id`),
  KEY `FK_storage_protocol_type` (`prt_id`),
  KEY `FK_storage_users` (`usr_id`),
  CONSTRAINT `FK_storage_protocol_type` FOREIGN KEY (`prt_id`) REFERENCES `_protocol_type` (`prt_id`),
  CONSTRAINT `FK_storage_users` FOREIGN KEY (`usr_id`) REFERENCES `users` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`storage` VALUES  (1,'admin','http://localhost',NULL,NULL,NULL);

DROP TABLE IF EXISTS `phoenix`.`todo`;
CREATE TABLE  `phoenix`.`todo` (
  `td_id` int(10) NOT NULL,
  `td_title` varchar(255) DEFAULT NULL,
  `td_text` longtext,
  `td_priority` int(10) DEFAULT NULL,
  `td_expiry` datetime DEFAULT NULL,
  `td_status` varchar(8) DEFAULT NULL,
  `td_date` datetime DEFAULT NULL,
  `td_time` datetime DEFAULT NULL,
  `app_id` int(10) DEFAULT NULL,
  `usr_id` int(10) DEFAULT NULL,
  `usr_id2` int(10) DEFAULT NULL,
  PRIMARY KEY (`td_id`),
  UNIQUE KEY `PK_todo` (`td_id`),
  KEY `FK_todo_users` (`usr_id`),
  KEY `FK_todo_users1` (`usr_id2`),
  KEY `FK_todo_applications` (`app_id`),
  CONSTRAINT `FK_todo_applications` FOREIGN KEY (`app_id`) REFERENCES `applications` (`app_id`),
  CONSTRAINT `FK_todo_users` FOREIGN KEY (`usr_id`) REFERENCES `users` (`usr_id`),
  CONSTRAINT `FK_todo_users1` FOREIGN KEY (`usr_id2`) REFERENCES `users` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `phoenix`.`users`;
CREATE TABLE  `phoenix`.`users` (
  `usr_id` int(10) NOT NULL,
  `mbr_id` int(10) NOT NULL,
  `grp_id` int(10) NOT NULL,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `PK_users` (`usr_id`),
  KEY `FK_users_groups` (`grp_id`),
  KEY `FK_users_members` (`mbr_id`),
  CONSTRAINT `FK_users_groups` FOREIGN KEY (`grp_id`) REFERENCES `groups` (`grp_id`),
  CONSTRAINT `FK_users_members` FOREIGN KEY (`mbr_id`) REFERENCES `members` (`mbr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `phoenix`.`users` VALUES  (1,1,1),
 (2,2,1);



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
