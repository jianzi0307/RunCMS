/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.6.11 : Database - db_tpsite
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_tpsite` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_tpsite`;

/*Table structure for table `app_auth_extend` */

DROP TABLE IF EXISTS `app_auth_extend`;

CREATE TABLE `app_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

/*Data for the table `app_auth_extend` */

LOCK TABLES `app_auth_extend` WRITE;

UNLOCK TABLES;

/*Table structure for table `app_auth_group` */

DROP TABLE IF EXISTS `app_auth_group`;

CREATE TABLE `app_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '组ID',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '组名',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '组描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

/*Data for the table `app_auth_group` */

LOCK TABLES `app_auth_group` WRITE;

insert  into `app_auth_group`(`id`,`module`,`type`,`title`,`description`,`status`,`rules`) values (39,'admin',1,'普通用户组','普通用户所在的权限组',1,'2,26,29'),(38,'admin',1,'管理员组','管理员权限组',1,'1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,26,27,28,29');

UNLOCK TABLES;

/*Table structure for table `app_auth_group_access` */

DROP TABLE IF EXISTS `app_auth_group_access`;

CREATE TABLE `app_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_auth_group_access` */

LOCK TABLES `app_auth_group_access` WRITE;

insert  into `app_auth_group_access`(`uid`,`group_id`) values (1,1),(59,38);

UNLOCK TABLES;

/*Table structure for table `app_auth_rule` */

DROP TABLE IF EXISTS `app_auth_rule`;

CREATE TABLE `app_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` char(80) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `app_auth_rule` */

LOCK TABLES `app_auth_rule` WRITE;

insert  into `app_auth_rule`(`id`,`module`,`type`,`name`,`title`,`status`,`condition`) values (1,'admin',1,'Admin/Sitesetting/index','网站设置',1,''),(2,'admin',2,'Admin/Index/index','系统',1,''),(3,'admin',1,'Admin/Modelsetting/index','模型管理',1,''),(4,'admin',1,'Admin/Confsetting/index','配置管理',1,''),(5,'admin',1,'Admin/Menusetting/index','菜单管理',1,''),(6,'admin',1,'Admin/Usergroup/index','用户组管理',1,''),(7,'admin',1,'Admin/Users/index','用户管理',1,''),(8,'admin',1,'Admin/Logs/index','系统日志',1,''),(9,'admin',1,'Admin/Menusetting/add','增加菜单',1,''),(10,'admin',1,'Admin/Menugroup/index','菜单分组',1,''),(11,'admin',1,'Admin/Menusetting/edit','编辑菜单',1,''),(12,'admin',1,'Admin/Menusetting/del','删除菜单',1,''),(13,'admin',1,'Admin/Confsetting/add','增加配置',1,''),(14,'admin',1,'Admin/Confsetting/edit','编辑配置',1,''),(15,'admin',1,'Admin/Confsetting/del','删除配置',1,''),(16,'admin',1,'Admin/Sitesetting/save','保存设置',1,''),(17,'admin',1,'Admin/Menugroup/add','增加',1,''),(18,'admin',1,'Admin/Menugroup/edit','修改',1,''),(19,'admin',1,'Admin/Menugroup/del','删除',1,''),(20,'admin',1,'Admin/Usergroup/add','增加用户组',1,''),(21,'admin',1,'Admin/Usergroup/edit','编辑用户组',1,''),(22,'admin',1,'Admin/Usergroup/del','删除用户组',1,''),(23,'admin',1,'Admin/Usergroup/priv','权限管理',1,''),(24,'admin',1,'Admin/Usergroup/user','成员管理',1,''),(25,'admin',1,'Admin/Users/add','添加用户',1,''),(26,'admin',1,'Admin/Profile/pwd','修改密码',1,''),(27,'admin',1,'Admin/Users/edit','编辑用户',1,''),(28,'admin',1,'Admin/Users/del','删除用户',1,''),(29,'admin',1,'Admin/Profile/userinfo','个人资料',1,'');

UNLOCK TABLES;

/*Table structure for table `app_config` */

DROP TABLE IF EXISTS `app_config`;

CREATE TABLE `app_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=337 DEFAULT CHARSET=utf8;

/*Data for the table `app_config` */

LOCK TABLES `app_config` WRITE;

insert  into `app_config`(`id`,`name`,`type`,`title`,`group`,`extra`,`remark`,`create_time`,`update_time`,`status`,`value`,`sort`) values (1,'APP_NAME',1,'站点标题',1,'','网站或应用标题,前台显示',1445922713,1445922713,1,'网站管理系统',0),(2,'CONFIG_TYPE_LIST',3,'配置数据类型',3,'','主要用于数据解析和页面表单的生成',1445920122,1445920122,1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片',0),(3,'CONFIG_GROUP_LIST',3,'配置分组',3,'','配置分组',1445920112,1445920112,1,'1:基本设置\r\n2:风格设置\r\n3:系统设置',0),(332,'PAGE_SCREEN_STYLE',4,'页面风格',2,'0:宽屏\n1:窄屏','页面宽窄风格',1445920353,1445920353,1,'0',0),(333,'PAGE_HEADER_FIXED',4,'页面头部',2,'0:默认\n1:固定','页头是否固定悬浮',1445920560,1445920560,1,'0',0),(334,'PAGE_FOOTER_FIXED',4,'页面底部',2,'0:默认\n1:固定','页面底部是否固定悬浮',1445920550,1445920550,1,'0',0),(5,'APP_COPYRIGHT',1,'版权信息',1,'','版权信息，前端显示在页脚',1438219932,1438219932,1,'2015 TpSite',0),(4,'APP_IPC',1,'备案信息',1,'','备案信息，前端显示在页脚',1438666455,1438666455,1,'沪ICP备12007941号-2',0),(335,'SITE_BACKEND_LOGO',5,'后台LOGO',1,'','后台LOGO',1445922919,1445922919,1,'',0),(336,'PAGE_COLOR_STYLE',4,'页面色调',2,'0:暗黑\n1:青淡','后台颜色风格',1445924430,1445924430,1,'1',0);

UNLOCK TABLES;

/*Table structure for table `app_logs` */

DROP TABLE IF EXISTS `app_logs`;

CREATE TABLE `app_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增索引',
  `uname` varchar(50) NOT NULL COMMENT '用户名',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `log` text NOT NULL COMMENT '日志',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`),
  KEY `createtime` (`createtime`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `app_logs` */

LOCK TABLES `app_logs` WRITE;

insert  into `app_logs`(`id`,`uname`,`createtime`,`log`) values (1,'jianzi',1428316362,'阿散发送达飞洒反对萨发'),(2,'admin',1428316362,'1428316362asfddsafsafdsafdasdf3423424'),(3,'jianzi',1428316362,'阿随风倒嚼蜡三大件法律;司机阿法律;就,23242欧io');

UNLOCK TABLES;

/*Table structure for table `app_menu` */

DROP TABLE IF EXISTS `app_menu`;

CREATE TABLE `app_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `groupid` int(10) DEFAULT NULL COMMENT '分组ID',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `icon` varchar(50) DEFAULT NULL COMMENT '菜单图标，文本图标名',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

/*Data for the table `app_menu` */

LOCK TABLES `app_menu` WRITE;

insert  into `app_menu`(`id`,`title`,`pid`,`sort`,`url`,`hide`,`tip`,`groupid`,`is_dev`,`icon`) values (3,'网站设置',2,0,'Sitesetting/index',0,'',6,0,'icon-sitemap'),(2,'系统',0,0,'Index/index',0,'',0,0,''),(4,'模型管理',2,0,'Modelsetting/index',1,'',6,0,'icon-cogs'),(5,'配置管理',2,0,'Confsetting/index',0,'',6,0,'icon-wrench'),(6,'菜单管理',2,0,'Menusetting/index',0,'',6,0,'icon-tasks'),(7,'用户组管理',2,0,'Usergroup/index',0,'',6,0,'icon-group'),(8,'用户管理',2,0,'Users/index',0,'',6,0,'icon-user'),(10,'系统日志',2,0,'Logs/index',1,'',6,0,'icon-warning-sign'),(31,'增加菜单',6,0,'Menusetting/add',0,'',0,0,''),(32,'菜单分组',6,0,'Menugroup/index',0,'',0,0,''),(33,'编辑菜单',6,0,'Menusetting/edit',0,'',0,0,''),(34,'删除菜单',6,0,'Menusetting/del',0,'',0,0,''),(35,'增加配置',5,0,'Confsetting/add',0,'',0,0,''),(36,'编辑配置',5,0,'Confsetting/edit',0,'',0,0,''),(37,'删除配置',5,0,'Confsetting/del',0,'',0,0,''),(38,'保存设置',3,0,'Sitesetting/save',0,'',0,0,''),(39,'增加',32,0,'Menugroup/add',0,'',0,0,''),(40,'修改',32,0,'Menugroup/edit',0,'',0,0,''),(41,'删除',32,0,'Menugroup/del',0,'',0,0,''),(42,'增加用户组',7,0,'Usergroup/add',0,'',0,0,''),(43,'编辑用户组',7,0,'Usergroup/edit',0,'',0,0,''),(44,'删除用户组',7,0,'Usergroup/del',0,'',0,0,''),(45,'权限管理',7,0,'Usergroup/priv',0,'',0,0,''),(46,'成员管理',7,0,'Usergroup/user',0,'',0,0,''),(53,'添加用户',8,0,'Users/add',0,'',0,0,''),(50,'修改密码',2,0,'Profile/pwd',0,'修改个人密码',12,0,'icon-key'),(54,'编辑用户',8,0,'Users/edit',0,'',0,0,''),(55,'删除用户',8,0,'Users/del',0,'',0,0,''),(98,'个人资料',2,0,'Profile/userinfo',0,'修改个人资料',12,0,'icon-user');

UNLOCK TABLES;

/*Table structure for table `app_menu_group` */

DROP TABLE IF EXISTS `app_menu_group`;

CREATE TABLE `app_menu_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单分组ID',
  `menuid` int(11) NOT NULL COMMENT '所属菜单ID',
  `group` char(50) NOT NULL COMMENT '菜单分组名',
  `icon` char(50) DEFAULT NULL COMMENT '菜单组图标',
  `sort` int(10) DEFAULT '0' COMMENT '排序',
  `hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `app_menu_group` */

LOCK TABLES `app_menu_group` WRITE;

insert  into `app_menu_group`(`id`,`menuid`,`group`,`icon`,`sort`,`hide`) values (6,2,'系统设置','icon-cog',0,0),(12,2,'个人资料','icon-edit',0,1);

UNLOCK TABLES;

/*Table structure for table `app_useradmin` */

DROP TABLE IF EXISTS `app_useradmin`;

CREATE TABLE `app_useradmin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理用户ID',
  `uname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
  `passwd` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `factoryname` varchar(50) NOT NULL DEFAULT '' COMMENT '厂名',
  `factoryaddress` varchar(100) NOT NULL DEFAULT '' COMMENT '厂址',
  `factoryscale` varchar(100) NOT NULL DEFAULT '' COMMENT '工厂规模',
  `maintechnology` varchar(100) NOT NULL DEFAULT '' COMMENT '主要工艺',
  `personliable` varchar(100) NOT NULL DEFAULT '' COMMENT '负责人',
  `dutyname` varchar(100) NOT NULL DEFAULT '' COMMENT '负责人职务',
  `personphone` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '负责人联系方式',
  `regname` varchar(100) NOT NULL DEFAULT '' COMMENT '注册人姓名',
  `regdutyname` varchar(100) NOT NULL DEFAULT '' COMMENT '注册人职务',
  `regpersonphone` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册人手机',
  `createtime` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expirtime` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '账户截止时间，0表示无限期',
  `blocked` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

/*Data for the table `app_useradmin` */

LOCK TABLES `app_useradmin` WRITE;

insert  into `app_useradmin`(`id`,`uname`,`nickname`,`passwd`,`factoryname`,`factoryaddress`,`factoryscale`,`maintechnology`,`personliable`,`dutyname`,`personphone`,`regname`,`regdutyname`,`regpersonphone`,`createtime`,`expirtime`,`blocked`) values (1,'admin','','9931a42da138132c04c97a55a6eec855','','','','','','',0,'','',0,0,0,0),(59,'jianzi0307','','5aff92f6169a722b3615e8d641c2a6b7','asdf','asdf','asdf','asdf','sadf','asdf',0,'afsd','asfd',0,1443512786,4553912786,1);

UNLOCK TABLES;

/*Table structure for table `app_userattr` */

DROP TABLE IF EXISTS `app_userattr`;

CREATE TABLE `app_userattr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `app_userattr` */

LOCK TABLES `app_userattr` WRITE;

UNLOCK TABLES;

/* Procedure structure for procedure `app_sp_addnode` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_addnode` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_addnode`(IN NODEID BIGINT, IN TITLE VARCHAR(1000), IN DESCRIPTION TEXT, IN FORMULA TEXT, IN AUTHOR INT, IN LOGICTYPE TINYINT, IN SORTNUM INT)
BEGIN
    
	    DECLARE terror INTEGER DEFAULT 0;
	    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET terror = 1;
	    -- 开始事务
	    START TRANSACTION;
	            -- 插入知识库
		    INSERT INTO `app_exp_knowledge_lib` (`title`, `description`, `create_time`, `author`, `logictype`, `formulaData`)
		    VALUES (TITLE, DESCRIPTION, UNIX_TIMESTAMP(),AUTHOR,LOGICTYPE,FORMULA);
		    
		    SET @last_id = LAST_INSERT_ID();
		    
		    -- 生成关系链
		    INSERT INTO `app_exp_tree_paths` (parent, child, depth)
			SELECT t.parent ,@last_id, depth + 1 
			FROM `app_exp_tree_paths` AS t
			WHERE t.child = NODEID
		    UNION ALL
			SELECT @last_id, @last_id, 0;
		    
		    -- 更新排序
		    UPDATE `app_exp_tree_paths` 
		    SET `sort` = SORTNUM
		    WHERE `parent` = NODEID and `child` = @last_id;
	    IF terror = 1 THEN  
		ROLLBACK;
	    ELSE  
		COMMIT;
	    END IF;
	    SELECT terror;
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_childnode` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_childnode` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_childnode`(IN NODEID INT, IN LVL INT)
BEGIN
   
    SELECT c.*, t.`sort`
    FROM `app_exp_knowledge_lib` AS c
    JOIN `app_exp_tree_paths` AS t ON c.`kownledge_id` = t.`child`
    WHERE t.`parent` = NODEID AND t.`depth` = LVL 
    ORDER BY t.`sort`;
    
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_cpsubtree` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_cpsubtree` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_cpsubtree`(IN NODEID INT, IN TARGET_ID INT)
BEGIN
    
    -- 1、需要拷贝知识库
    
    -- 2、然后将孤立的树和新节点及它的祖先建立关系；
    --    可以使用CROSS JOIN创建一个新节点极其祖先和孤立树中所有节点的笛卡儿积来建立所有需要的关系
	INSERT INTO `app_exp_tree_paths` (parent, child, depth)
	SELECT supertree.parent, subtree.child, supertree.depth+subtree.depth+1
	FROM `app_exp_tree_paths` AS supertree
		CROSS JOIN `app_exp_tree_paths` AS subtree
	WHERE supertree.child = TARGET_ID
		AND subtree.parent = NODEID;
			
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_deleteleaf` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_deleteleaf` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_deleteleaf`(IN LEAFNODE BIGINT)
BEGIN
	DELETE FROM `app_exp_tree_paths` 
	WHERE child = LEAFNODE;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_deletesubtree` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_deletesubtree` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_deletesubtree`(IN NODEID INT)
BEGIN
    
    DELETE FROM `app_exp_tree_paths` 
	WHERE child IN (
		SELECT child 
		FROM `app_exp_tree_paths`
		WHERE parent = NODEID
	);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_getsubtree` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_getsubtree` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_getsubtree`(IN NODEID INT)
BEGIN
    
    SELECT c.*
    FROM `app_exp_knowledge_lib` AS c
    JOIN `app_exp_tree_paths` AS t ON c.`kownledge_id` = t.`child`
    WHERE t.`parent` = NODEID;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_getsupertree` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_getsupertree` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_getsupertree`(IN NODEID INT)
BEGIN
	SELECT c.*
	FROM `app_exp_knowledge_lib` AS c
	JOIN `app_exp_tree_paths` AS t ON c.`kownledge_id` = t.`parent`
	WHERE t.`child` = NODEID;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_gettop` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_gettop` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_gettop`()
BEGIN
    
    SELECT c.kownledge_id, c.title, c.create_time, c.formulaData
    FROM app_exp_knowledge_lib AS c 
    WHERE NOT EXISTS (
	SELECT 1 
	FROM `app_exp_tree_paths` AS p 
	WHERE p.child =  c.kownledge_id AND p.depth > 0
    );
    
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_movenode` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_movenode` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_movenode`(IN NODE_ID BIGINT, IN TARGET_ID BIGINT)
BEGIN
    
	    DECLARE terror INTEGER DEFAULT 0;
	    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET terror = 1;
	    START TRANSACTION;
	    
	    -- 1、断开与祖先的关系（先孤立子树）；
	    /*DELETE FROM `app_exp_tree_paths`
		WHERE child IN (
			SELECT child
			FROM `app_exp_tree_paths`
			WHERE parent = NODE_ID )
		AND parent IN (
			SELECT parent 
			FROM `app_exp_tree_paths`
			WHERE child = NODE_ID AND parent != child);*/
	    DELETE FROM `app_exp_tree_paths`
		WHERE child IN (
			SELECT child FROM (
				SELECT child
				FROM `app_exp_tree_paths`
				WHERE parent = NODE_ID 
			) AS childList
		)
		AND parent IN (
			SELECT parent FROM (
				SELECT parent 
				FROM `app_exp_tree_paths`
				WHERE child = NODE_ID AND parent != child
			) AS parentList
		);
			
	    -- 2、然后将孤立的树和新节点及它的祖先建立关系；
	    --    可以使用CROSS JOIN创建一个新节点极其祖先和孤立树中所有节点的笛卡儿积来建立所有需要的关系
	    INSERT INTO `app_exp_tree_paths` (parent, child, depth)
		SELECT supertree.parent, subtree.child, supertree.depth+subtree.depth+1
		FROM `app_exp_tree_paths` AS supertree
			CROSS JOIN `app_exp_tree_paths` AS subtree
		WHERE supertree.child = TARGET_ID
			AND subtree.parent = NODE_ID;
	    IF terror = 1 THEN  
		ROLLBACK;
	    ELSE  
		COMMIT;
	    END IF;
	    SELECT terror;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `app_sp_parentnode` */

/*!50003 DROP PROCEDURE IF EXISTS  `app_sp_parentnode` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `app_sp_parentnode`(in NODEID INT, in LVL int)
BEGIN
    SELECT c.* 
    FROM `app_exp_knowledge_lib` AS c
    JOIN `app_exp_tree_paths` AS t ON c.`kownledge_id` = t.`parent`
    WHERE t.child = NODEID AND t.depth = LVL
    order by t.`sort`;
    
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;