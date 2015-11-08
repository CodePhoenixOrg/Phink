USE [Asphaltu]
GO
/****** Object:  Table [dbo].[members]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[members](
	[mbr_id] [int] IDENTITY(1,1) NOT NULL,
	[mbr_name] [varchar](50) NULL,
	[mbr_adr1] [varchar](50) NULL,
	[mbr_adr2] [varchar](50) NULL,
	[mbr_cp] [varchar](5) NULL,
	[mbr_email] [varchar](50) NULL,
	[mbr_login] [varchar](50) NULL,
	[mbr_password] [varchar](50) NULL,
 CONSTRAINT [PK_members] PRIMARY KEY CLUSTERED 
(
	[mbr_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[groups]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[groups](
	[grp_id] [int] IDENTITY(1,1) NOT NULL,
	[grp_name] [varchar](15) NOT NULL,
	[grp_members_priv] [char](1) NOT NULL,
	[grp_menu_priv] [char](1) NOT NULL,
	[grp_page_priv] [char](1) NOT NULL,
	[grp_news_priv] [char](1) NOT NULL,
	[grp_items_priv] [char](1) NOT NULL,
	[grp_database_priv] [char](1) NOT NULL,
	[grp_images_priv] [char](1) NOT NULL,
	[grp_calendar_priv] [char](1) NOT NULL,
	[grp_newsletter_priv] [char](1) NOT NULL,
	[grp_forum_priv] [char](1) NOT NULL,
	[grp_users_priv] [char](1) NOT NULL,
 CONSTRAINT [PK_groups] PRIMARY KEY CLUSTERED 
(
	[grp_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[newsletter]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[newsletter](
	[nl_id] [int] IDENTITY(1,1) NOT NULL,
	[nl_title] [varchar](255) NULL,
	[nl_author] [varchar](255) NULL,
	[nl_header] [text] NULL,
	[nl_image] [varchar](255) NULL,
	[nl_comment] [varchar](255) NULL,
	[nl_body] [text] NULL,
	[nl_links] [text] NULL,
	[nl_footer] [text] NULL,
	[nl_file] [varchar](255) NULL,
	[nl_date] [datetime] NULL,
 CONSTRAINT [PK_newsletter] PRIMARY KEY CLUSTERED 
(
	[nl_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dictionary]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dictionary](
	[di_id] [int] IDENTITY(1,1) NOT NULL,
	[di_name] [varchar](8) NULL,
	[di_fr_short] [varchar](255) NULL,
	[di_fr_long] [text] NULL,
	[di_en_short] [varchar](255) NULL,
	[di_en_long] [text] NULL,
	[di_ru_short] [varchar](255) NULL,
	[di_ru_long] [text] NULL,
 CONSTRAINT [PK_dictionary] PRIMARY KEY CLUSTERED 
(
	[di_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_protocol_type]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_protocol_type](
	[prt_id] [int] NOT NULL,
	[prt_type] [varchar](10) NULL,
 CONSTRAINT [PK_protocol_type] PRIMARY KEY CLUSTERED 
(
	[prt_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_form_type]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_form_type](
	[ft_id] [int] NOT NULL,
	[ft_type] [varchar](10) NOT NULL,
 CONSTRAINT [PK_form_type] PRIMARY KEY CLUSTERED 
(
	[ft_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_document_type]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_document_type](
	[dt_id] [int] NOT NULL,
	[dt_type] [varchar](15) NOT NULL,
 CONSTRAINT [PK_document_type] PRIMARY KEY CLUSTERED 
(
	[dt_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_dbserver_type]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_dbserver_type](
	[dbs_id] [int] NOT NULL,
	[dbs_type] [varchar](10) NOT NULL,
 CONSTRAINT [PK_dbserver_type] PRIMARY KEY CLUSTERED 
(
	[dbs_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_bug_status]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_bug_status](
	[bs_id] [int] NOT NULL,
	[bs_status] [varchar](10) NULL,
 CONSTRAINT [PK_bug_status] PRIMARY KEY CLUSTERED 
(
	[bs_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[_block_type]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[_block_type](
	[bt_id] [int] NOT NULL,
	[bt_type] [varchar](10) NOT NULL,
 CONSTRAINT [PK_block_type] PRIMARY KEY CLUSTERED 
(
	[bt_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[users]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[users](
	[usr_id] [int] IDENTITY(1,1) NOT NULL,
	[mbr_id] [int] NOT NULL,
	[grp_id] [int] NOT NULL,
 CONSTRAINT [PK_users] PRIMARY KEY CLUSTERED 
(
	[usr_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[__member_newletter]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[__member_newletter](
	[mbr_id] [int] NOT NULL,
	[nl_id] [int] NOT NULL,
 CONSTRAINT [PK_member_newletter] PRIMARY KEY CLUSTERED 
(
	[mbr_id] ASC,
	[nl_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[dbconn]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dbconn](
	[dbc_id] [int] IDENTITY(1,1) NOT NULL,
	[dbc_host] [varchar](50) NOT NULL,
	[dbc_database] [varchar](15) NOT NULL,
	[dbc_login] [varchar](15) NOT NULL,
	[dbc_passwd] [varchar](16) NOT NULL,
	[dbs_id] [int] NOT NULL,
 CONSTRAINT [PK_dbconn] PRIMARY KEY CLUSTERED 
(
	[dbc_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[blocks]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[blocks](
	[bl_id] [int] IDENTITY(1,1) NOT NULL,
	[bl_column] [varchar](1) NULL,
	[bt_id] [int] NOT NULL,
	[di_id] [int] NOT NULL,
 CONSTRAINT [PK_blocks] PRIMARY KEY CLUSTERED 
(
	[bl_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[documents]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[documents](
	[doc_id] [int] IDENTITY(1,1) NOT NULL,
	[doc_name] [varchar](50) NOT NULL,
	[doc_title] [varchar](255) NOT NULL,
	[doc_content] [text] NULL,
	[doc_dir] [varchar](255) NOT NULL,
	[doc_url] [varchar](255) NOT NULL,
	[dt_id] [int] NOT NULL,
 CONSTRAINT [PK_documents] PRIMARY KEY CLUSTERED 
(
	[doc_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[storage]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[storage](
	[sto_id] [int] IDENTITY(1,1) NOT NULL,
	[sto_root_dir] [varchar](255) NOT NULL,
	[sto_host] [varchar](255) NOT NULL,
	[sto_port] [int] NULL,
	[usr_id] [int] NULL,
	[prt_id] [int] NULL,
 CONSTRAINT [PK_storage] PRIMARY KEY CLUSTERED 
(
	[sto_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[queries]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[queries](
	[qy_id] [int] IDENTITY(1,1) NOT NULL,
	[qy_name] [varchar](15) NOT NULL,
	[qy_text] [text] NOT NULL,
	[dbc_id] [int] NOT NULL,
 CONSTRAINT [PK_queries] PRIMARY KEY CLUSTERED 
(
	[qy_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[applications]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[applications](
	[app_id] [int] IDENTITY(1,1) NOT NULL,
	[app_name] [varchar](50) NULL,
	[di_id] [int] NULL,
	[sto_id] [int] NULL,
 CONSTRAINT [PK_applications] PRIMARY KEY CLUSTERED 
(
	[app_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[changelog]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[changelog](
	[cl_id] [int] IDENTITY(1,1) NOT NULL,
	[cl_title] [varchar](255) NULL,
	[cl_text] [text] NULL,
	[cl_date] [datetime] NULL,
	[cl_time] [datetime] NULL,
	[app_id] [int] NULL,
	[usr_id] [int] NULL,
 CONSTRAINT [PK_changelog] PRIMARY KEY CLUSTERED 
(
	[cl_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[bugreport]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[bugreport](
	[br_id] [int] IDENTITY(1,1) NOT NULL,
	[br_title] [varchar](255) NULL,
	[br_text] [text] NULL,
	[br_importance] [int] NULL,
	[br_date] [datetime] NULL,
	[br_time] [datetime] NULL,
	[bs_id] [int] NULL,
	[usr_id] [int] NULL,
	[app_id] [int] NULL,
 CONSTRAINT [PK_bugreport] PRIMARY KEY CLUSTERED 
(
	[br_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[user_app]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[user_app](
	[usr_id] [int] NOT NULL,
	[app_id] [int] NOT NULL,
 CONSTRAINT [PK_user_app] PRIMARY KEY CLUSTERED 
(
	[usr_id] ASC,
	[app_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[__dbconn_app]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[__dbconn_app](
	[dbc_id] [int] NOT NULL,
	[app_id] [int] NOT NULL,
 CONSTRAINT [PK_dbconn_app] PRIMARY KEY CLUSTERED 
(
	[dbc_id] ASC,
	[app_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[__app_document]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[__app_document](
	[app_id] [int] NOT NULL,
	[doc_id] [int] NOT NULL,
 CONSTRAINT [PK_document_app] PRIMARY KEY CLUSTERED 
(
	[app_id] ASC,
	[doc_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[forms]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[forms](
	[frm_id] [int] IDENTITY(1,1) NOT NULL,
	[frm_filename] [varchar](255) NULL,
	[frm_directory] [varchar](1024) NULL,
	[frm_url] [varchar](1024) NULL,
	[di_id] [int] NULL,
	[ft_id] [int] NULL,
	[app_id] [int] NULL,
 CONSTRAINT [PK_forms] PRIMARY KEY CLUSTERED 
(
	[frm_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[todo]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[todo](
	[td_id] [int] IDENTITY(1,1) NOT NULL,
	[td_title] [varchar](255) NULL,
	[td_text] [text] NULL,
	[td_priority] [int] NULL,
	[td_expiry] [datetime] NULL,
	[td_status] [varchar](8) NULL,
	[td_date] [datetime] NULL,
	[td_time] [datetime] NULL,
	[app_id] [int] NULL,
	[usr_id] [int] NULL,
	[usr_id2] [int] NULL,
 CONSTRAINT [PK_todo] PRIMARY KEY CLUSTERED 
(
	[td_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[menus]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[menus](
	[me_id] [int] IDENTITY(1,1) NOT NULL,
	[me_level] [varchar](1) NULL,
	[me_target] [varchar](7) NULL,
	[frm_id] [int] NULL,
	[bl_id] [int] NULL,
 CONSTRAINT [PK_menus] PRIMARY KEY CLUSTERED 
(
	[me_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[__form_block]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[__form_block](
	[frm_id] [int] NOT NULL,
	[bl_id] [int] NOT NULL,
 CONSTRAINT [PK_form_block] PRIMARY KEY CLUSTERED 
(
	[frm_id] ASC,
	[bl_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[__document_form]    Script Date: 08/19/2010 16:48:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[__document_form](
	[doc_id] [int] NOT NULL,
	[frm_id] [int] NOT NULL,
 CONSTRAINT [PK_document_form] PRIMARY KEY CLUSTERED 
(
	[doc_id] ASC,
	[frm_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO


SET IDENTITY_INSERT applications ON
INSERT INTO [dbo].[applications] ([app_id], [app_name], [di_id], [sto_id]) VALUES(1, NULL, 19, NULL)
GO
SET IDENTITY_INSERT applications OFF
GO
SET IDENTITY_INSERT blocks ON
INSERT INTO [dbo].[blocks] ([bl_id], [bl_column], [bt_id], [di_id]) VALUES(1, '1', 2, 0)
GO
SET IDENTITY_INSERT blocks OFF
GO

SET IDENTITY_INSERT dbconn ON
INSERT INTO [dbo].[dbconn] ([dbc_id], [dbc_host], [dbc_database], [dbc_login], [dbc_passwd], [dbs_id]) VALUES(1, 'localhost', 'webfactory', 'root', '1p2+ar', 1)
GO
SET IDENTITY_INSERT dbconn OFF
GO

SET IDENTITY_INSERT dictionary ON
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(0, 'na', 'N/A', 'N/A', 'N/A', 'N/A', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(1, 'applicat', 'Applications', 'Liste des applications', 'Applications', 'List of applications', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(2, 'blocks', 'Blocs', 'Liste des blocs', 'Blocks', 'List of blocks', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(3, 'bugrepor', 'Bugs', 'Rapport de bugs', 'Bugs', 'Bug reports', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(4, 'changelo', 'Changements', 'Notes de changements', 'Changes', 'Change log', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(5, 'dictiona', 'Dictionnaire', '', 'Dictionary', '', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(6, 'editor', 'Editer', 'Editer les attributs du script', 'Edit', 'Edit script attributes', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(7, 'forums', 'Forums', 'Forums disponibles', 'Forums', 'Available forums', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(8, 'groups', 'Groupes', 'Liste des groupes', 'Groups', 'List of groups', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(9, 'home', 'Accueil', 'Page d''accueil', 'Home', 'Home page', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(10, 'members', 'Accès membres', 'Gérez votre profil membre', 'Members area', 'Manage your data', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(11, 'menus', 'Menus', 'Entrées de menus', 'Menus', 'Menu items', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(12, 'mkblock', 'Créer un bloc', 'Créer un nouveau bloc', 'Create a block', 'Create a new block', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(13, 'mkfields', 'Champs', 'Champs de la table', 'Fields', 'Table fields', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(14, 'mkfile', 'Fichier', 'Création du fichier', 'File', 'Creation of the file', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(15, 'mkmenu', 'Créer un menu', 'Créer une nouvelle entrée de menu', 'Create a menu', 'Create a new menu item', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(16, 'mkscript', 'Créer un script', 'Créer un script à partir d''une table', 'Create a script', 'Create a script from a table', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(17, 'pages', 'Pages', 'Liste des pages', 'Pages', 'List of pages', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(18, 'todo', 'A faire', 'Liste des tâches', 'To do', 'Tasks to do', '', '')
INSERT INTO [dbo].[dictionary] ([di_id], [di_name], [di_fr_short], [di_fr_long], [di_en_short], [di_en_long], [di_ru_short], [di_ru_long]) VALUES(19, 'webfacto', 'WebFactory', 'WebFactory', 'WebFactory', 'WebFactory', '', '')
GO
SET IDENTITY_INSERT dictionary OFF
GO

SET IDENTITY_INSERT forms ON
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(17, 'mkmain.php', '.', '', 9, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(18, 'menus.php', '.', '', 11, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(19, 'pages.php', '.', '', 17, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(20, 'blocks.php', '.', '', 2, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(21, 'dictionary.php', '.', '', 5, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(22, 'applications.php', '.', '', 1, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(23, 'forums.php', '.', '', 7, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(24, 'changelog.php', '.', '', 4, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(25, 'todo.php', '.', '', 18, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(26, 'bugreport.php', '.', '', 3, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(27, 'groups.php', '.', '', 8, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(28, 'newsletter.php', '.', '', 0, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(29, 'mkscript.php', '.', '', 16, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(30, 'mkmenu.php', '.', '', 15, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(31, 'mkblock.php', '.', '', 12, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(32, 'mkfields.php', '.', '', 13, 2, 1)
INSERT INTO [dbo].[forms] ([frm_id], [frm_filename], [frm_directory], [frm_url], [di_id], [ft_id], [app_id]) VALUES(33, 'mkfile.php', '.', '', 14, 2, 1)
GO
SET IDENTITY_INSERT forms OFF
GO

SET IDENTITY_INSERT groups ON
INSERT INTO [dbo].[groups] ([grp_id], [grp_name], [grp_members_priv], [grp_menu_priv], [grp_page_priv], [grp_news_priv], [grp_items_priv], [grp_database_priv], [grp_images_priv], [grp_calendar_priv], [grp_newsletter_priv], [grp_forum_priv], [grp_users_priv]) VALUES(1, 'root', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')
GO
SET IDENTITY_INSERT groups OFF
GO

SET IDENTITY_INSERT members ON
INSERT INTO [dbo].[members] ([mbr_id], [mbr_name], [mbr_adr1], [mbr_adr2], [mbr_cp], [mbr_email], [mbr_login], [mbr_password]) VALUES(1, 'David BLANCHARD', 'Pas d''adresse', '', '76000', 'davidbl@wanadoo.fr', 'dpjb', '1p2+ar')
INSERT INTO [dbo].[members] ([mbr_id], [mbr_name], [mbr_adr1], [mbr_adr2], [mbr_cp], [mbr_email], [mbr_login], [mbr_password]) VALUES(2, 'Pierre-Yves Le Bihan', 'Pas d''adresse', '', '92800', 'pylb@wanadoo.fr', 'pylb', 'K3r1v31')
GO
SET IDENTITY_INSERT members OFF
GO

SET IDENTITY_INSERT menus ON
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(17, '1', 'pages', 17, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(18, '1', 'page', 18, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(19, '1', 'page', 19, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(20, '1', 'page', 20, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(21, '1', 'page', 21, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(22, '2', 'page', 22, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(23, '0', 'page', 23, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(24, '1', 'page', 24, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(25, '1', 'page', 25, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(26, '1', 'page', 26, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(27, '1', 'page', 27, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(29, '0', 'page', 29, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(30, '0', 'page', 30, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(31, '0', 'page', 31, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(32, '0', 'page', 32, 1)
INSERT INTO [dbo].[menus] ([me_id], [me_level], [me_target], [frm_id], [bl_id]) VALUES(33, '0', 'page', 33, 1)
GO
SET IDENTITY_INSERT menus OFF
GO

SET IDENTITY_INSERT storage ON
INSERT INTO [dbo].[storage] ([sto_id], [sto_root_dir], [sto_host], [sto_port], [usr_id], [prt_id]) VALUES(1, 'admin', 'http://localhost', NULL, NULL, NULL)
GO
SET IDENTITY_INSERT storage OFF
GO


SET IDENTITY_INSERT users ON
INSERT INTO [dbo].[users] ([usr_id], [mbr_id], [grp_id]) VALUES(1, 1, 1)
INSERT INTO [dbo].[users] ([usr_id], [mbr_id], [grp_id]) VALUES(2, 2, 1)
GO
SET IDENTITY_INSERT users OFF
GO

INSERT INTO [dbo].[_block_type] ([bt_id], [bt_type]) VALUES(1, 'form')
GO
INSERT INTO [dbo].[_block_type] ([bt_id], [bt_type]) VALUES(2, 'menu')
GO

INSERT INTO [dbo].[_bug_status] ([bs_id], [bs_status]) VALUES(1, 'à fixer')
GO
INSERT INTO [dbo].[_bug_status] ([bs_id], [bs_status]) VALUES(2, 'en cours')
GO
INSERT INTO [dbo].[_bug_status] ([bs_id], [bs_status]) VALUES(3, 'fixé')
GO
INSERT INTO [dbo].[_bug_status] ([bs_id], [bs_status]) VALUES(4, 'suspendu')
GO
INSERT INTO [dbo].[_bug_status] ([bs_id], [bs_status]) VALUES(5, 'abandonné')
GO

INSERT INTO [dbo].[_dbserver_type] ([dbs_id], [dbs_type]) VALUES(1, 'MySQL')
GO
INSERT INTO [dbo].[_dbserver_type] ([dbs_id], [dbs_type]) VALUES(2, 'SQL Server')
GO
INSERT INTO [dbo].[_dbserver_type] ([dbs_id], [dbs_type]) VALUES(3, 'Sybase ASE')
GO
INSERT INTO [dbo].[_dbserver_type] ([dbs_id], [dbs_type]) VALUES(4, 'Oracle')
GO

INSERT INTO [dbo].[_form_type] ([ft_id], [ft_type]) VALUES(1, 'html')
GO
INSERT INTO [dbo].[_form_type] ([ft_id], [ft_type]) VALUES(2, 'php')
GO
INSERT INTO [dbo].[_form_type] ([ft_id], [ft_type]) VALUES(3, 'aspx')
GO


/****** Object:  Default [DF__bug_sta__bs_st__0BC6C43E]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[_bug_status] ADD  DEFAULT (NULL) FOR [bs_status]
GO
/****** Object:  Default [DF__documen__app_i__1367E606]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__app_document] ADD  CONSTRAINT [DF__documen__app_i__1367E606]  DEFAULT (NULL) FOR [app_id]
GO
/****** Object:  Default [DF__documen__doc_i__1273C1CD]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__app_document] ADD  CONSTRAINT [DF__documen__doc_i__1273C1CD]  DEFAULT (NULL) FOR [doc_id]
GO
/****** Object:  Default [DF__documen__doc_i__15502E78]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__document_form] ADD  CONSTRAINT [DF__documen__doc_i__15502E78]  DEFAULT (NULL) FOR [doc_id]
GO
/****** Object:  Default [DF__documen__frm_i__164452B1]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__document_form] ADD  CONSTRAINT [DF__documen__frm_i__164452B1]  DEFAULT (NULL) FOR [frm_id]
GO
/****** Object:  Default [DF__form_bl__frm_i__182C9B23]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__form_block] ADD  CONSTRAINT [DF__form_bl__frm_i__182C9B23]  DEFAULT (NULL) FOR [frm_id]
GO
/****** Object:  Default [DF__form_bl__bl_id__1920BF5C]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__form_block] ADD  CONSTRAINT [DF__form_bl__bl_id__1920BF5C]  DEFAULT (NULL) FOR [bl_id]
GO
/****** Object:  Default [DF__applica__app_n__1CF15040]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[applications] ADD  DEFAULT (NULL) FOR [app_name]
GO
/****** Object:  Default [DF__applica__di_id__1DE57479]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[applications] ADD  DEFAULT (NULL) FOR [di_id]
GO
/****** Object:  Default [DF__applica__se_id__1ED998B2]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[applications] ADD  DEFAULT (NULL) FOR [sto_id]
GO
/****** Object:  Default [DF__blocks__bl_col__21B6055D]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[blocks] ADD  DEFAULT (NULL) FOR [bl_column]
GO
/****** Object:  Default [DF__bugrepo__br_ti__239E4DCF]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [br_title]
GO
/****** Object:  Default [DF__bugrepo__br_im__24927208]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [br_importance]
GO
/****** Object:  Default [DF__bugrepo__br_da__25869641]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [br_date]
GO
/****** Object:  Default [DF__bugrepo__br_ti__267ABA7A]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [br_time]
GO
/****** Object:  Default [DF__bugrepo__bs_id__276EDEB3]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [bs_id]
GO
/****** Object:  Default [DF__bugrepo__usr_i__286302EC]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [usr_id]
GO
/****** Object:  Default [DF__bugrepo__app_i__29572725]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport] ADD  DEFAULT (NULL) FOR [app_id]
GO
/****** Object:  Default [DF__changel__cl_ti__2B3F6F97]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog] ADD  DEFAULT (NULL) FOR [cl_title]
GO
/****** Object:  Default [DF__changel__cl_da__2C3393D0]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog] ADD  DEFAULT (NULL) FOR [cl_date]
GO
/****** Object:  Default [DF__changel__cl_ti__2D27B809]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog] ADD  DEFAULT (NULL) FOR [cl_time]
GO
/****** Object:  Default [DF__changel__app_i__2E1BDC42]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog] ADD  DEFAULT (NULL) FOR [app_id]
GO
/****** Object:  Default [DF__changel__usr_i__2F10007B]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog] ADD  DEFAULT (NULL) FOR [usr_id]
GO
/****** Object:  Default [DF__diction__di_na__31EC6D26]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[dictionary] ADD  DEFAULT (NULL) FOR [di_name]
GO
/****** Object:  Default [DF__diction__di_fr__32E0915F]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[dictionary] ADD  DEFAULT (NULL) FOR [di_fr_short]
GO
/****** Object:  Default [DF__diction__di_en__33D4B598]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[dictionary] ADD  DEFAULT (NULL) FOR [di_en_short]
GO
/****** Object:  Default [DF__diction__di_ru__34C8D9D1]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[dictionary] ADD  DEFAULT (NULL) FOR [di_ru_short]
GO
/****** Object:  Default [DF__forms__frm_fil__37A5467C]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [frm_filename]
GO
/****** Object:  Default [DF__forms__frm_dir__38996AB5]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [frm_directory]
GO
/****** Object:  Default [DF__forms__frm_url__398D8EEE]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [frm_url]
GO
/****** Object:  Default [DF__forms__di_id__3A81B327]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [di_id]
GO
/****** Object:  Default [DF__forms__ft_id__3B75D760]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [ft_id]
GO
/****** Object:  Default [DF__forms__app_id__3C69FB99]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms] ADD  DEFAULT (NULL) FOR [app_id]
GO
/****** Object:  Default [DF__members__mbr_n__3F466844]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_name]
GO
/****** Object:  Default [DF__members__mbr_a__403A8C7D]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_adr1]
GO
/****** Object:  Default [DF__members__mbr_a__412EB0B6]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_adr2]
GO
/****** Object:  Default [DF__members__mbr_c__4222D4EF]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_cp]
GO
/****** Object:  Default [DF__members__mbr__4316F928]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_email]
GO
/****** Object:  Default [DF__members__mbr_l__440B1D61]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_login]
GO
/****** Object:  Default [DF__members__mbr_p__44FF419A]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[members] ADD  DEFAULT (NULL) FOR [mbr_password]
GO
/****** Object:  Default [DF__menus__me_leve__46E78A0C]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus] ADD  DEFAULT (NULL) FOR [me_level]
GO
/****** Object:  Default [DF__menus__me_targ__47DBAE45]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus] ADD  DEFAULT (NULL) FOR [me_target]
GO
/****** Object:  Default [DF__menus__frm_id__48CFD27E]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus] ADD  DEFAULT (NULL) FOR [frm_id]
GO
/****** Object:  Default [DF__menus__bl_id__49C3F6B7]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus] ADD  DEFAULT (NULL) FOR [bl_id]
GO
/****** Object:  Default [DF__newslet__nl_ti__4BAC3F29]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_title]
GO
/****** Object:  Default [DF__newslet__nl_au__4CA06362]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_author]
GO
/****** Object:  Default [DF__newslet__nl_im__4D94879B]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_image]
GO
/****** Object:  Default [DF__newslet__nl_co__4E88ABD4]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_comment]
GO
/****** Object:  Default [DF__newslet__nl_fi__4F7CD00D]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_file]
GO
/****** Object:  Default [DF__newslet__nl_da__5070F446]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[newsletter] ADD  DEFAULT (NULL) FOR [nl_date]
GO
/****** Object:  Default [DF__todo__td_title__5535A963]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_title]
GO
/****** Object:  Default [DF__todo__td_prior__5629CD9C]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_priority]
GO
/****** Object:  Default [DF__todo__td_expir__571DF1D5]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_expiry]
GO
/****** Object:  Default [DF__todo__td_statu__5812160E]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_status]
GO
/****** Object:  Default [DF__todo__td_date__59063A47]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_date]
GO
/****** Object:  Default [DF__todo__td_time__59FA5E80]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [td_time]
GO
/****** Object:  Default [DF__todo__app_id__5AEE82B9]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [app_id]
GO
/****** Object:  Default [DF__todo__usr_id__5BE2A6F2]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [usr_id]
GO
/****** Object:  Default [DF__todo__usr_id2__5CD6CB2B]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo] ADD  DEFAULT (NULL) FOR [usr_id2]
GO
/****** Object:  ForeignKey [FK_users_groups]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[users]  WITH CHECK ADD  CONSTRAINT [FK_users_groups] FOREIGN KEY([grp_id])
REFERENCES [dbo].[groups] ([grp_id])
GO
ALTER TABLE [dbo].[users] CHECK CONSTRAINT [FK_users_groups]
GO
/****** Object:  ForeignKey [FK_users_members]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[users]  WITH CHECK ADD  CONSTRAINT [FK_users_members] FOREIGN KEY([mbr_id])
REFERENCES [dbo].[members] ([mbr_id])
GO
ALTER TABLE [dbo].[users] CHECK CONSTRAINT [FK_users_members]
GO
/****** Object:  ForeignKey [FK_app_document_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__app_document]  WITH CHECK ADD  CONSTRAINT [FK_app_document_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[__app_document] CHECK CONSTRAINT [FK_app_document_applications]
GO
/****** Object:  ForeignKey [FK_app_document_documents]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__app_document]  WITH CHECK ADD  CONSTRAINT [FK_app_document_documents] FOREIGN KEY([doc_id])
REFERENCES [dbo].[documents] ([doc_id])
GO
ALTER TABLE [dbo].[__app_document] CHECK CONSTRAINT [FK_app_document_documents]
GO
/****** Object:  ForeignKey [FK_dbconn_app_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__dbconn_app]  WITH CHECK ADD  CONSTRAINT [FK_dbconn_app_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[__dbconn_app] CHECK CONSTRAINT [FK_dbconn_app_applications]
GO
/****** Object:  ForeignKey [FK_dbconn_app_dbconn]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__dbconn_app]  WITH CHECK ADD  CONSTRAINT [FK_dbconn_app_dbconn] FOREIGN KEY([dbc_id])
REFERENCES [dbo].[dbconn] ([dbc_id])
GO
ALTER TABLE [dbo].[__dbconn_app] CHECK CONSTRAINT [FK_dbconn_app_dbconn]
GO
/****** Object:  ForeignKey [FK_document_form_documents]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__document_form]  WITH CHECK ADD  CONSTRAINT [FK_document_form_documents] FOREIGN KEY([doc_id])
REFERENCES [dbo].[documents] ([doc_id])
GO
ALTER TABLE [dbo].[__document_form] CHECK CONSTRAINT [FK_document_form_documents]
GO
/****** Object:  ForeignKey [FK_document_form_forms]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__document_form]  WITH CHECK ADD  CONSTRAINT [FK_document_form_forms] FOREIGN KEY([frm_id])
REFERENCES [dbo].[forms] ([frm_id])
GO
ALTER TABLE [dbo].[__document_form] CHECK CONSTRAINT [FK_document_form_forms]
GO
/****** Object:  ForeignKey [FK_form_block_blocks]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__form_block]  WITH CHECK ADD  CONSTRAINT [FK_form_block_blocks] FOREIGN KEY([bl_id])
REFERENCES [dbo].[blocks] ([bl_id])
GO
ALTER TABLE [dbo].[__form_block] CHECK CONSTRAINT [FK_form_block_blocks]
GO
/****** Object:  ForeignKey [FK_form_block_forms]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__form_block]  WITH CHECK ADD  CONSTRAINT [FK_form_block_forms] FOREIGN KEY([frm_id])
REFERENCES [dbo].[forms] ([frm_id])
GO
ALTER TABLE [dbo].[__form_block] CHECK CONSTRAINT [FK_form_block_forms]
GO
/****** Object:  ForeignKey [FK_member_newletter_members]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__member_newletter]  WITH CHECK ADD  CONSTRAINT [FK_member_newletter_members] FOREIGN KEY([mbr_id])
REFERENCES [dbo].[members] ([mbr_id])
GO
ALTER TABLE [dbo].[__member_newletter] CHECK CONSTRAINT [FK_member_newletter_members]
GO
/****** Object:  ForeignKey [FK_member_newletter_newsletter]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[__member_newletter]  WITH CHECK ADD  CONSTRAINT [FK_member_newletter_newsletter] FOREIGN KEY([nl_id])
REFERENCES [dbo].[newsletter] ([nl_id])
GO
ALTER TABLE [dbo].[__member_newletter] CHECK CONSTRAINT [FK_member_newletter_newsletter]
GO
/****** Object:  ForeignKey [FK_user_app_users]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[user_app]  WITH CHECK ADD  CONSTRAINT [FK_user_app_users] FOREIGN KEY([usr_id])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[user_app] CHECK CONSTRAINT [FK_user_app_users]
GO
/****** Object:  ForeignKey [FK_user_app_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[user_app]  WITH CHECK ADD  CONSTRAINT [FK_user_app_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[user_app] CHECK CONSTRAINT [FK_user_app_applications]
GO
/****** Object:  ForeignKey [FK_applications_dictionary]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[applications]  WITH CHECK ADD  CONSTRAINT [FK_applications_dictionary] FOREIGN KEY([di_id])
REFERENCES [dbo].[dictionary] ([di_id])
GO
ALTER TABLE [dbo].[applications] CHECK CONSTRAINT [FK_applications_dictionary]
GO
/****** Object:  ForeignKey [FK_applications_storage]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[applications]  WITH CHECK ADD  CONSTRAINT [FK_applications_storage] FOREIGN KEY([sto_id])
REFERENCES [dbo].[storage] ([sto_id])
GO
ALTER TABLE [dbo].[applications] CHECK CONSTRAINT [FK_applications_storage]
GO
/****** Object:  ForeignKey [FK_blocks_block_type]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[blocks]  WITH CHECK ADD  CONSTRAINT [FK_blocks_block_type] FOREIGN KEY([bt_id])
REFERENCES [dbo].[_block_type] ([bt_id])
GO
ALTER TABLE [dbo].[blocks] CHECK CONSTRAINT [FK_blocks_block_type]
GO
/****** Object:  ForeignKey [FK_blocks_dictionary]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[blocks]  WITH CHECK ADD  CONSTRAINT [FK_blocks_dictionary] FOREIGN KEY([di_id])
REFERENCES [dbo].[dictionary] ([di_id])
GO
ALTER TABLE [dbo].[blocks] CHECK CONSTRAINT [FK_blocks_dictionary]
GO
/****** Object:  ForeignKey [FK_bugreport_bug_status]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport]  WITH CHECK ADD  CONSTRAINT [FK_bugreport_bug_status] FOREIGN KEY([bs_id])
REFERENCES [dbo].[_bug_status] ([bs_id])
GO
ALTER TABLE [dbo].[bugreport] CHECK CONSTRAINT [FK_bugreport_bug_status]
GO
/****** Object:  ForeignKey [FK_bugreport_users]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport]  WITH CHECK ADD  CONSTRAINT [FK_bugreport_users] FOREIGN KEY([usr_id])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[bugreport] CHECK CONSTRAINT [FK_bugreport_users]
GO
/****** Object:  ForeignKey [FK_bugreport_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[bugreport]  WITH CHECK ADD  CONSTRAINT [FK_bugreport_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[bugreport] CHECK CONSTRAINT [FK_bugreport_applications]
GO
/****** Object:  ForeignKey [FK_changelog_users]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog]  WITH CHECK ADD  CONSTRAINT [FK_changelog_users] FOREIGN KEY([usr_id])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[changelog] CHECK CONSTRAINT [FK_changelog_users]
GO
/****** Object:  ForeignKey [FK_changelog_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[changelog]  WITH CHECK ADD  CONSTRAINT [FK_changelog_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[changelog] CHECK CONSTRAINT [FK_changelog_applications]
GO
/****** Object:  ForeignKey [FK_dbconn_dbserver_type]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[dbconn]  WITH CHECK ADD  CONSTRAINT [FK_dbconn_dbserver_type] FOREIGN KEY([dbs_id])
REFERENCES [dbo].[_dbserver_type] ([dbs_id])
GO
ALTER TABLE [dbo].[dbconn] CHECK CONSTRAINT [FK_dbconn_dbserver_type]
GO
/****** Object:  ForeignKey [FK_documents_document_type]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[documents]  WITH CHECK ADD  CONSTRAINT [FK_documents_document_type] FOREIGN KEY([dt_id])
REFERENCES [dbo].[_document_type] ([dt_id])
GO
ALTER TABLE [dbo].[documents] CHECK CONSTRAINT [FK_documents_document_type]
GO
/****** Object:  ForeignKey [FK_forms_form_type]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms]  WITH CHECK ADD  CONSTRAINT [FK_forms_form_type] FOREIGN KEY([ft_id])
REFERENCES [dbo].[_form_type] ([ft_id])
GO
ALTER TABLE [dbo].[forms] CHECK CONSTRAINT [FK_forms_form_type]
GO
/****** Object:  ForeignKey [FK_forms_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms]  WITH CHECK ADD  CONSTRAINT [FK_forms_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[forms] CHECK CONSTRAINT [FK_forms_applications]
GO
/****** Object:  ForeignKey [FK_forms_dictionary]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[forms]  WITH CHECK ADD  CONSTRAINT [FK_forms_dictionary] FOREIGN KEY([di_id])
REFERENCES [dbo].[dictionary] ([di_id])
GO
ALTER TABLE [dbo].[forms] CHECK CONSTRAINT [FK_forms_dictionary]
GO
/****** Object:  ForeignKey [FK_menus_blocks]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus]  WITH CHECK ADD  CONSTRAINT [FK_menus_blocks] FOREIGN KEY([bl_id])
REFERENCES [dbo].[blocks] ([bl_id])
GO
ALTER TABLE [dbo].[menus] CHECK CONSTRAINT [FK_menus_blocks]
GO
/****** Object:  ForeignKey [FK_menus_forms]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[menus]  WITH CHECK ADD  CONSTRAINT [FK_menus_forms] FOREIGN KEY([frm_id])
REFERENCES [dbo].[forms] ([frm_id])
GO
ALTER TABLE [dbo].[menus] CHECK CONSTRAINT [FK_menus_forms]
GO
/****** Object:  ForeignKey [FK_queries_dbconn]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[queries]  WITH CHECK ADD  CONSTRAINT [FK_queries_dbconn] FOREIGN KEY([dbc_id])
REFERENCES [dbo].[dbconn] ([dbc_id])
GO
ALTER TABLE [dbo].[queries] CHECK CONSTRAINT [FK_queries_dbconn]
GO
/****** Object:  ForeignKey [FK_storage_protocol_type]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[storage]  WITH CHECK ADD  CONSTRAINT [FK_storage_protocol_type] FOREIGN KEY([prt_id])
REFERENCES [dbo].[_protocol_type] ([prt_id])
GO
ALTER TABLE [dbo].[storage] CHECK CONSTRAINT [FK_storage_protocol_type]
GO
/****** Object:  ForeignKey [FK_storage_users]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[storage]  WITH CHECK ADD  CONSTRAINT [FK_storage_users] FOREIGN KEY([usr_id])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[storage] CHECK CONSTRAINT [FK_storage_users]
GO
/****** Object:  ForeignKey [FK_todo_users]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo]  WITH CHECK ADD  CONSTRAINT [FK_todo_users] FOREIGN KEY([usr_id])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[todo] CHECK CONSTRAINT [FK_todo_users]
GO
/****** Object:  ForeignKey [FK_todo_users1]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo]  WITH CHECK ADD  CONSTRAINT [FK_todo_users1] FOREIGN KEY([usr_id2])
REFERENCES [dbo].[users] ([usr_id])
GO
ALTER TABLE [dbo].[todo] CHECK CONSTRAINT [FK_todo_users1]
GO
/****** Object:  ForeignKey [FK_todo_applications]    Script Date: 08/19/2010 16:48:54 ******/
ALTER TABLE [dbo].[todo]  WITH CHECK ADD  CONSTRAINT [FK_todo_applications] FOREIGN KEY([app_id])
REFERENCES [dbo].[applications] ([app_id])
GO
ALTER TABLE [dbo].[todo] CHECK CONSTRAINT [FK_todo_applications]
GO
