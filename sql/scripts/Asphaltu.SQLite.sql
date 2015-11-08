DROP TABLE __app_document;
DROP TABLE __dbconn_app;
DROP TABLE __document_form;
DROP TABLE __form_block;
DROP TABLE __member_newletter;
DROP TABLE __user_app;
DROP TABLE _block_type;
DROP TABLE _bug_status;
DROP TABLE _dbserver_type;
DROP TABLE _document_type;
DROP TABLE _form_type;
DROP TABLE _protocol_type;
DROP TABLE applications;
DROP TABLE blocks;
DROP TABLE bugreport;
DROP TABLE changelog;
DROP TABLE dbconn;
DROP TABLE dictionary;
DROP TABLE forms;
DROP TABLE groups;
DROP TABLE members;
DROP TABLE menus;
DROP TABLE newsletter;
DROP TABLE queries;
DROP TABLE storage;
DROP TABLE todo;
DROP TABLE users;
DROP TABLE documents;

CREATE TABLE __app_document (
  "app_id" INTEGER NOT NULL,
  "doc_id" INTEGER NOT NULL,
  PRIMARY KEY ("app_id","doc_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("doc_id") REFERENCES documents ("doc_id")
);
CREATE TABLE __dbconn_app (
  "dbc_id" INTEGER NOT NULL,
  "app_id" INTEGER NOT NULL,
  PRIMARY KEY ("dbc_id","app_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("dbc_id") REFERENCES dbconn ("dbc_id")
);
CREATE TABLE __document_form (
  "doc_id" INTEGER NOT NULL,
  "frm_id" INTEGER NOT NULL,
  PRIMARY KEY ("doc_id","frm_id"),
  FOREIGN KEY ("doc_id") REFERENCES documents ("doc_id"),
  FOREIGN KEY ("frm_id") REFERENCES forms ("frm_id")
);
CREATE TABLE __form_block (
  "frm_id" INTEGER NOT NULL,
  "bl_id" INTEGER NOT NULL,
  PRIMARY KEY ("frm_id","bl_id"),
  FOREIGN KEY ("bl_id") REFERENCES blocks ("bl_id"),
  FOREIGN KEY ("frm_id") REFERENCES forms ("frm_id")
);
CREATE TABLE __member_newletter (
  "mbr_id" INTEGER NOT NULL,
  "nl_id" INTEGER NOT NULL,
  PRIMARY KEY ("mbr_id","nl_id"),
  FOREIGN KEY ("mbr_id") REFERENCES members ("mbr_id"),
  FOREIGN KEY ("nl_id") REFERENCES newsletter ("nl_id")
);
CREATE TABLE __user_app (
  "usr_id" INTEGER NOT NULL,
  "app_id" INTEGER NOT NULL,
  PRIMARY KEY ("usr_id","app_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("usr_id") REFERENCES users ("usr_id")
);
CREATE TABLE _block_type (
  "bt_id" INTEGER NOT NULL,
  "bt_type" TEXT NOT NULL,
  PRIMARY KEY ("bt_id")
);
CREATE TABLE _bug_status (
  "bs_id" INTEGER NOT NULL,
  "bs_status" TEXT,
  PRIMARY KEY ("bs_id")
);
CREATE TABLE _dbserver_type (
  "dbs_id" INTEGER NOT NULL,
  "dbs_type" TEXT NOT NULL,
  PRIMARY KEY ("dbs_id")
);
CREATE TABLE _document_type (
  "dt_id" INTEGER NOT NULL,
  "dt_type" TEXT NOT NULL,
  PRIMARY KEY ("dt_id")
);
CREATE TABLE _form_type (
  "ft_id" INTEGER NOT NULL,
  "ft_type" TEXT NOT NULL,
  PRIMARY KEY ("ft_id")
);
CREATE TABLE _protocol_type (
  "prt_id" INTEGER NOT NULL,
  "prt_type" TEXT,
  PRIMARY KEY ("prt_id")
);
CREATE TABLE blocks (
  "bl_id" INTEGER NOT NULL,
  "bl_column" TEXT,
  "bt_id" INTEGER NOT NULL,
  "di_id" INTEGER NOT NULL,
  PRIMARY KEY ("bl_id"),
  FOREIGN KEY ("di_id") REFERENCES dictionary ("di_id"),
  FOREIGN KEY ("bt_id") REFERENCES _block_type ("bt_id")
);
CREATE TABLE bugreport (
  "br_id" INTEGER NOT NULL,
  "br_title" TEXT,
  "br_text" TEXT,
  "br_importance" INTEGER,
  "br_date" INTEGER,
  "br_time" INTEGER,
  "bs_id" INTEGER,
  "usr_id" INTEGER,
  "app_id" INTEGER,
  PRIMARY KEY ("br_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("bs_id") REFERENCES _bug_status ("bs_id"),
  FOREIGN KEY ("usr_id") REFERENCES users ("usr_id")
);
CREATE TABLE changelog (
  "cl_id" INTEGER NOT NULL,
  "cl_title" TEXT,
  "cl_text" TEXT,
  "cl_date" INTEGER,
  "cl_time" INTEGER,
  "app_id" INTEGER,
  "usr_id" INTEGER,
  PRIMARY KEY ("cl_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("usr_id") REFERENCES users ("usr_id")
);
CREATE TABLE dbconn (
  "dbc_id" INTEGER NOT NULL,
  "dbc_host" TEXT NOT NULL,
  "dbc_database" TEXT NOT NULL,
  "dbc_login" TEXT NOT NULL,
  "dbc_passwd" TEXT NOT NULL,
  "dbs_id" INTEGER NOT NULL,
  PRIMARY KEY ("dbc_id"),
  FOREIGN KEY ("dbs_id") REFERENCES _dbserver_type ("dbs_id")
);
CREATE TABLE dictionary (
  "di_id" INTEGER NOT NULL,
  "di_name" TEXT,
  "di_fr_short" TEXT,
  "di_fr_long" TEXT,
  "di_en_short" TEXT,
  "di_en_long" TEXT,
  "di_ru_short" TEXT,
  "di_ru_long" TEXT,
  PRIMARY KEY ("di_id")
);
CREATE TABLE forms (
  "frm_id" INTEGER NOT NULL,
  "frm_filename" TEXT,
  "frm_directory" TEXT,
  "frm_url" TEXT,
  "di_id" INTEGER,
  "ft_id" INTEGER,
  "app_id" INTEGER,
  PRIMARY KEY ("frm_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("di_id") REFERENCES dictionary ("di_id"),
  FOREIGN KEY ("ft_id") REFERENCES _form_type ("ft_id")
);
CREATE TABLE groups (
  "grp_id" INTEGER NOT NULL,
  "grp_name" TEXT NOT NULL,
  "grp_members_priv" TEXT NOT NULL,
  "grp_menu_priv" TEXT NOT NULL,
  "grp_page_priv" TEXT NOT NULL,
  "grp_news_priv" TEXT NOT NULL,
  "grp_items_priv" TEXT NOT NULL,
  "grp_database_priv" TEXT NOT NULL,
  "grp_images_priv" TEXT NOT NULL,
  "grp_calendar_priv" TEXT NOT NULL,
  "grp_newsletter_priv" TEXT NOT NULL,
  "grp_forum_priv" TEXT NOT NULL,
  "grp_users_priv" TEXT NOT NULL,
  PRIMARY KEY ("grp_id")
);
CREATE TABLE members (
  "mbr_id" INTEGER NOT NULL,
  "mbr_name" TEXT,
  "mbr_adr1" TEXT,
  "mbr_adr2" TEXT,
  "mbr_cp" TEXT,
  "mbr_email" TEXT,
  "mbr_login" TEXT,
  "mbr_password" TEXT,
  PRIMARY KEY ("mbr_id")
);
CREATE TABLE menus (
  "me_id" INTEGER NOT NULL,
  "me_level" TEXT,
  "me_target" TEXT,
  "frm_id" INTEGER,
  "bl_id" INTEGER,
  PRIMARY KEY ("me_id"),
  FOREIGN KEY ("bl_id") REFERENCES blocks ("bl_id"),
  FOREIGN KEY ("frm_id") REFERENCES forms ("frm_id")
);
CREATE TABLE newsletter (
  "nl_id" INTEGER NOT NULL,
  "nl_title" TEXT,
  "nl_author" TEXT,
  "nl_header" TEXT,
  "nl_image" TEXT,
  "nl_comment" TEXT,
  "nl_body" TEXT,
  "nl_links" TEXT,
  "nl_footer" TEXT,
  "nl_file" TEXT,
  "nl_date" INTEGER,
  PRIMARY KEY ("nl_id")
);
CREATE TABLE queries (
  "qy_id" INTEGER NOT NULL,
  "qy_name" TEXT NOT NULL,
  "qy_text" TEXT NOT NULL,
  "dbc_id" INTEGER NOT NULL,
  PRIMARY KEY ("qy_id"),
  FOREIGN KEY ("dbc_id") REFERENCES dbconn ("dbc_id")
);
CREATE TABLE storage (
  "sto_id" INTEGER NOT NULL,
  "sto_root_dir" TEXT NOT NULL,
  "sto_host" TEXT NOT NULL,
  "sto_port" INTEGER,
  "usr_id" INTEGER,
  "prt_id" INTEGER,
  PRIMARY KEY ("sto_id"),
  FOREIGN KEY ("prt_id") REFERENCES _protocol_type ("prt_id"),
  FOREIGN KEY ("usr_id") REFERENCES users ("usr_id")
);
CREATE TABLE todo (
  "td_id" INTEGER NOT NULL,
  "td_title" TEXT,
  "td_text" TEXT,
  "td_priority" INTEGER,
  "td_expiry" INTEGER,
  "td_status" TEXT,
  "td_date" INTEGER,
  "td_time" INTEGER,
  "app_id" INTEGER,
  "usr_id" INTEGER,
  "usr_id2" INTEGER,
  PRIMARY KEY ("td_id"),
  FOREIGN KEY ("app_id") REFERENCES applications ("app_id"),
  FOREIGN KEY ("usr_id") REFERENCES users ("usr_id"),
  FOREIGN KEY ("usr_id2") REFERENCES users ("usr_id")
);
CREATE TABLE users (
  "usr_id" INTEGER NOT NULL,
  "mbr_id" INTEGER NOT NULL,
  "grp_id" INTEGER NOT NULL,
  PRIMARY KEY ("usr_id"),
  FOREIGN KEY ("grp_id") REFERENCES groups ("grp_id"),
  FOREIGN KEY ("mbr_id") REFERENCES members ("mbr_id")
);
CREATE TABLE documents (
    "doc_id" INT NOT NULL,
    "doc_name" TEXT NOT NULL,
    "doc_title" TEXT NOT NULL,
    "doc_content" TEXT,
    "doc_dir" TEXT NOT NULL,
    "doc_url" TEXT NOT NULL,
    "dt_id" INTEGER NOT NULL,
  PRIMARY KEY ("doc_id"),
  FOREIGN KEY ("dt_id") REFERENCES _document_type ("dt_id")
);
CREATE TABLE applications (
    "app_id" INTEGER NOT NULL,
    "app_name" TEXT,
    "di_id" INTEGER,
    "sto_id" INTEGER
);
INSERT INTO applications (app_id, app_name, di_id, sto_id) VALUES(1, NULL, 19, NULL);
INSERT INTO blocks (bl_id, bl_column, bt_id, di_id) VALUES(1, '1', 2, 0);
INSERT INTO dbconn (dbc_id, dbc_host, dbc_database, dbc_login, dbc_passwd, dbs_id) VALUES(1, 'localhost', 'asphaltu', 'root', '', 1);
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(0, 'na', 'N/A', 'N/A', 'N/A', 'N/A', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(1, 'applicat', 'Applications', 'Liste des applications', 'Applications', 'List of applications', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(2, 'blocks', 'Blocs', 'Liste des blocs', 'Blocks', 'List of blocks', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(3, 'bugrepor', 'Bugs', 'Rapport de bugs', 'Bugs', 'Bug reports', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(4, 'changelo', 'Changements', 'Notes de changements', 'Changes', 'Change log', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(5, 'dictiona', 'Dictionnaire', '', 'Dictionary', '', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(6, 'editor', 'Editer', 'Editer les attributs du script', 'Edit', 'Edit script attributes', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(7, 'forums', 'Forums', 'Forums disponibles', 'Forums', 'Available forums', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(8, 'groups', 'Groupes', 'Liste des groupes', 'Groups', 'List of groups', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(9, 'home', 'Accueil', 'Page d''accueil', 'Home', 'Home page', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(10, 'members', 'Accès membres', 'Gérez votre profil membre', 'Members area', 'Manage your data', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(11, 'menus', 'Menus', 'Entrées de menus', 'Menus', 'Menu items', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(12, 'mkblock', 'Créer un bloc', 'Créer un nouveau bloc', 'Create a block', 'Create a new block', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(13, 'mkfields', 'Champs', 'Champs de la table', 'Fields', 'Table fields', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(14, 'mkfile', 'Fichier', 'Création du fichier', 'File', 'Creation of the file', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(15, 'mkmenu', 'Créer un menu', 'Créer une nouvelle entrée de menu', 'Create a menu', 'Create a new menu item', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(16, 'mkscript', 'Créer un script', 'Créer un script à partir d''une table', 'Create a script', 'Create a script from a table', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(17, 'pages', 'Pages', 'Liste des pages', 'Pages', 'List of pages', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(18, 'todo', 'A faire', 'Liste des tâches', 'To do', 'Tasks to do', '', '');
INSERT INTO dictionary (di_id, di_name, di_fr_short, di_fr_long, di_en_short, di_en_long, di_ru_short, di_ru_long) VALUES(19, 'webfacto', 'WebFactory', 'WebFactory', 'WebFactory', 'WebFactory', '', '');
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(17, 'mkmain.php', '.', '', 9, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(18, 'menus.php', '.', '', 11, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(19, 'pages.php', '.', '', 17, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(20, 'blocks.php', '.', '', 2, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(21, 'dictionary.php', '.', '', 5, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(22, 'applications.php', '.', '', 1, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(23, 'forums.php', '.', '', 7, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(24, 'changelog.php', '.', '', 4, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(25, 'todo.php', '.', '', 18, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(26, 'bugreport.php', '.', '', 3, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(27, 'groups.php', '.', '', 8, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(28, 'newsletter.php', '.', '', 0, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(29, 'mkscript.php', '.', '', 16, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(30, 'mkmenu.php', '.', '', 15, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(31, 'mkblock.php', '.', '', 12, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(32, 'mkfields.php', '.', '', 13, 2, 1);
INSERT INTO forms (frm_id, frm_filename, frm_directory, frm_url, di_id, ft_id, app_id) VALUES(33, 'mkfile.php', '.', '', 14, 2, 1);
INSERT INTO groups (grp_id, grp_name, grp_members_priv, grp_menu_priv, grp_page_priv, grp_news_priv, grp_items_priv, grp_database_priv, grp_images_priv, grp_calendar_priv, grp_newsletter_priv, grp_forum_priv, grp_users_priv) VALUES(1, 'root', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y');
INSERT INTO members (mbr_id, mbr_name, mbr_adr1, mbr_adr2, mbr_cp, mbr_email, mbr_login, mbr_password) VALUES(1, 'David BLANCHARD', 'Pas d''adresse', '', '76000', 'davidbl@wanadoo.fr', 'dpjb', '1p2+ar');
INSERT INTO members (mbr_id, mbr_name, mbr_adr1, mbr_adr2, mbr_cp, mbr_email, mbr_login, mbr_password) VALUES(2, 'Pierre-Yves Le Bihan', 'Pas d''adresse', '', '92800', 'pylb@wanadoo.fr', 'pylb', 'K3r1v31');
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(17, '1', 'page', 17, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(18, '1', 'page', 18, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(19, '1', 'page', 19, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(20, '1', 'page', 20, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(21, '1', 'page', 21, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(22, '2', 'page', 22, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(23, '0', 'page', 23, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(24, '1', 'page', 24, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(25, '1', 'page', 25, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(26, '1', 'page', 26, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(27, '1', 'page', 27, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(29, '0', 'page', 29, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(30, '0', 'page', 30, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(31, '0', 'page', 31, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(32, '0', 'page', 32, 1);
INSERT INTO menus (me_id, me_level, me_target, frm_id, bl_id) VALUES(33, '0', 'page', 33, 1);
INSERT INTO `storage` (sto_id, sto_root_dir, sto_host, sto_port, usr_id, prt_id) VALUES(1, 'admin', 'http://localhost', NULL, NULL, NULL);
INSERT INTO users (usr_id, mbr_id, grp_id) VALUES(1, 1, 1);
INSERT INTO users (usr_id, mbr_id, grp_id) VALUES(2, 2, 1);
INSERT INTO _block_type (bt_id, bt_type) VALUES(1, 'form');
INSERT INTO _block_type (bt_id, bt_type) VALUES(2, 'menu');
INSERT INTO _bug_status (bs_id, bs_status) VALUES(1, 'à fixer');
INSERT INTO _bug_status (bs_id, bs_status) VALUES(2, 'en cours');
INSERT INTO _bug_status (bs_id, bs_status) VALUES(3, 'fixé');
INSERT INTO _bug_status (bs_id, bs_status) VALUES(4, 'suspendu');
INSERT INTO _bug_status (bs_id, bs_status) VALUES(5, 'abandonné');
INSERT INTO _dbserver_type (dbs_id, dbs_type) VALUES(1, 'MySQL');
INSERT INTO _dbserver_type (dbs_id, dbs_type) VALUES(2, 'SQL Server');
INSERT INTO _dbserver_type (dbs_id, dbs_type) VALUES(3, 'SQLite');
INSERT INTO _form_type (ft_id, ft_type) VALUES(1, 'html');
INSERT INTO _form_type (ft_id, ft_type) VALUES(2, 'php');
INSERT INTO _form_type (ft_id, ft_type) VALUES(3, 'aspx');
