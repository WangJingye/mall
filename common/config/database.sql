# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 121.40.224.59 (MySQL 5.6.43)
# Database: test
# Generation Time: 2020-06-17 05:59:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tbl_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名称',
  `password` char(64) DEFAULT '' COMMENT '密码',
  `realname` varchar(30) DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) DEFAULT '' COMMENT '联系电话',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像地址',
  `salt` char(4) DEFAULT '',
  `identity` tinyint(4) DEFAULT '0' COMMENT '0:普通用户 1:管理员',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `passwd_modify_time` int(11) DEFAULT '0' COMMENT '密码最后修改日期',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '信息修改时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '用户账号状态 0:删除,1:锁定（不可登陆）[2-8保留] 9 正常 ',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

LOCK TABLES `tbl_admin` WRITE;
/*!40000 ALTER TABLE `tbl_admin` DISABLE KEYS */;

INSERT INTO `tbl_admin` (`admin_id`, `username`, `password`, `realname`, `mobile`, `email`, `avatar`, `salt`, `identity`, `last_login_time`, `passwd_modify_time`, `create_time`, `update_time`, `status`)
VALUES
	(1,'admin','de5adcf92bd1be1f221e3bad88f97f6e','超级管理员','','11@qq.com','','6544',1,1592357026,0,1566546983,1572591787,1),
	(2,'thomas','500a3828fb135cf6db8b652264b4b2d7','叶','13646622759','thomas.wang@heavengifts.com','','0305',0,1592204360,0,1592204349,1592204349,1);

/*!40000 ALTER TABLE `tbl_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_brand
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_brand`;

CREATE TABLE `tbl_brand` (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌logo',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 0删除 1使用中',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='品牌';

LOCK TABLES `tbl_brand` WRITE;
/*!40000 ALTER TABLE `tbl_brand` DISABLE KEYS */;

INSERT INTO `tbl_brand` (`brand_id`, `brand_name`, `logo`, `sort`, `status`, `create_time`, `update_time`)
VALUES
	(1,'CHANEL','http://backend.local.com//upload/erp/brand/a911606e73237e80df3997fcbbca6851.png',0,1,1592213616,1592213650);

/*!40000 ALTER TABLE `tbl_brand` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_carousel
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_carousel`;

CREATE TABLE `tbl_carousel` (
  `carousel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carousel_type` tinyint(4) DEFAULT '1' COMMENT '轮播类型 1首页轮播',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `link_type` tinyint(4) DEFAULT '1' COMMENT '1:无跳转 2:跳转到详情页 3跳转到分类页',
  `link_id` int(11) DEFAULT '0' COMMENT '跳转链接ID',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否展示 0否 1是',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`carousel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tbl_coupon
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_coupon`;

CREATE TABLE `tbl_coupon` (
  `coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT '1' COMMENT '类型 1通用券 2品类券 3商品券',
  `relation_id` int(11) DEFAULT '0' COMMENT '类型不是通用券时关联ID必填',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '优惠券标题',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券面值',
  `points` int(11) DEFAULT '0' COMMENT '兑换所需积分',
  `min_price` decimal(10,2) DEFAULT '0.00' COMMENT '最低消费多少金额可用优惠券',
  `expire` int(11) DEFAULT '0' COMMENT '过期时长，单位分',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 0删除 1可用 2禁用',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_coupon` WRITE;
/*!40000 ALTER TABLE `tbl_coupon` DISABLE KEYS */;

INSERT INTO `tbl_coupon` (`coupon_id`, `type`, `relation_id`, `title`, `price`, `points`, `min_price`, `expire`, `status`, `create_time`, `update_time`)
VALUES
	(1,1,0,'满99减30',30.00,1000,99.00,43200,1,1592208795,1592213457),
	(2,2,2,'测测试',10.00,10,100.00,30,1,1592225114,1592302333);

/*!40000 ALTER TABLE `tbl_coupon` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_coupon_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_coupon_user`;

CREATE TABLE `tbl_coupon_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `coupon_id` int(11) NOT NULL COMMENT '优惠券ID',
  `type` tinyint(4) DEFAULT '1' COMMENT '优惠券类型',
  `relation_id` int(11) DEFAULT '0' COMMENT '关联ID',
  `coupon_name` varchar(64) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `min_price` decimal(10,2) DEFAULT '0.00' COMMENT '最小使用金额',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1:未使用 2:已使用 3:已过期',
  `expire_time` int(11) DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_coupon_user` WRITE;
/*!40000 ALTER TABLE `tbl_coupon_user` DISABLE KEYS */;

INSERT INTO `tbl_coupon_user` (`id`, `user_id`, `coupon_id`, `type`, `relation_id`, `coupon_name`, `price`, `min_price`, `create_time`, `status`, `expire_time`)
VALUES
	(1,2,1,1,0,'满99减30',30.00,99.00,1592214614,2,1594806614),
	(2,1,1,1,0,'满100减30',30.00,100.00,1592214614,2,1594806614),
	(3,2,1,1,0,'满100减30',30.00,100.00,1592214614,2,1594806614),
	(4,2,2,2,4,'测测试',10.00,100.00,1592229788,2,1592231588),
	(5,2,1,1,0,'满99减30',30.00,99.00,1592302341,2,1594894341);

/*!40000 ALTER TABLE `tbl_coupon_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_freight_template
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_freight_template`;

CREATE TABLE `tbl_freight_template` (
  `freight_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) NOT NULL DEFAULT '' COMMENT '模版名称',
  `freight_type` tinyint(4) DEFAULT '1' COMMENT '计价方式 1按件 2按重量',
  `number` int(11) DEFAULT '0' COMMENT '开始数量',
  `start_price` decimal(10,2) DEFAULT '0.00' COMMENT '起步价',
  `step_number` int(11) DEFAULT '0' COMMENT '增加数量',
  `step_price` decimal(10,2) DEFAULT '0.00' COMMENT '增加费用',
  `status` tinyint(4) DEFAULT '1' COMMENT '0删除 1使用中',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`freight_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='运费模版';

LOCK TABLES `tbl_freight_template` WRITE;
/*!40000 ALTER TABLE `tbl_freight_template` DISABLE KEYS */;

INSERT INTO `tbl_freight_template` (`freight_id`, `template_name`, `freight_type`, `number`, `start_price`, `step_number`, `step_price`, `status`, `create_time`, `update_time`)
VALUES
	(1,'5元起送，每1件加2元',1,1,5.00,1,2.00,1,1592213851,1592213851);

/*!40000 ALTER TABLE `tbl_freight_template` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_menu`;

CREATE TABLE `tbl_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(200) DEFAULT '' COMMENT '菜单文件路径',
  `desc` varchar(255) DEFAULT '' COMMENT '菜单描述',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单icon样式',
  `sort` int(10) unsigned DEFAULT '0' COMMENT '菜单权重排序号',
  `depth` tinyint(4) DEFAULT '1' COMMENT '菜单等级',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '菜单状态 1 有效 0 无效',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建菜单时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '修改菜单时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='后台菜单数据表';

LOCK TABLES `tbl_menu` WRITE;
/*!40000 ALTER TABLE `tbl_menu` DISABLE KEYS */;

INSERT INTO `tbl_menu` (`id`, `name`, `url`, `desc`, `parent_id`, `icon`, `sort`, `depth`, `status`, `create_time`, `update_time`)
VALUES
	(1,'系统管理','','root',0,'glyphicon glyphicon-cog',10,1,1,1562982765,1562982765),
	(2,'业务管理','','',0,'glyphicon glyphicon-briefcase',9,1,1,1562982765,1562982765),
	(3,'菜单权限管理','','',1,'glyphicon glyphicon-list',0,2,1,1562982765,1562982765),
	(4,'菜单列表','system/menu/index','',3,'',0,3,1,1562982765,1562982765),
	(5,'编辑菜单','system/menu/edit-menu','',4,'',0,4,1,1562982765,1562982765),
	(6,'角色列表','system/role/index','',3,'',0,3,1,1562982765,1562982765),
	(7,'编辑角色','system/role/edit-role','',6,'',0,4,1,1562982765,1562982765),
	(8,'设置角色权限','system/role/set-role-menu','',6,'',0,4,1,1562982765,1562982765),
	(9,'设置角色用户','system/role/set-role-admin','',6,'',0,4,1,1562982765,1562982765),
	(10,'后台账号管理','','',1,'glyphicon glyphicon-user',0,2,1,1562982765,1562982765),
	(11,'账号列表','system/admin/index','',10,'',0,3,1,1562982765,1562982765),
	(12,'编辑账号','system/admin/edit-admin','',11,'',0,4,1,1562982765,1562982765),
	(13,'菜单启用/禁用','system/menu/set-status','',4,'',0,4,1,1562982765,1562982765),
	(14,'账号启用/禁用','system/admin/set-status','',11,'',0,4,1,1562982765,1562982765),
	(15,'重置密码','system/admin/reset-password','',11,'',0,4,1,1563005512,1564936542),
	(16,'个人信息','system/admin/profile','',11,'',0,4,1,1563005512,1564936542),
	(17,'后台首页','','',2,'glyphicon glyphicon-home',9,2,1,1572591987,1572591987),
	(18,'网站信息','erp/site-info/base-info','',17,'',8,3,1,1573021780,1573021780),
	(19,'首页','erp/site-info/index','',17,'',9,3,1,1591234735,1591234735),
	(20,'微信相关','erp/site-info/wechat','',17,'',7,3,1,1573021780,1573021780),
	(21,'小程序管理','erp/site-info/app-info','',17,'',6,3,1,1573021780,1573021780),
	(22,'轮播图列表','erp/carousel/index','',17,'',5,3,1,1591319869,1591319869),
	(23,'添加/编辑轮播图','erp/carousel/edit','',22,'',0,4,1,1591322088,1591322088),
	(24,'删除轮播图','erp/carousel/delete','',22,'',0,4,1,1591322093,1591322093),
	(25,'设置轮播图显示','erp/carousel/set-show','',22,'',0,4,1,1591838862,1591838862),
	(26,'设置轮播图排序','erp/carousel/set-sort','',22,'',0,4,1,1591839279,1591839279),
	(27,'商品管理','','',2,'glyphicon glyphicon-th',8,2,1,1591247601,1591247601),
	(28,'商品列表','erp/product/index','',27,'',7,3,1,1591340036,1591340036),
	(29,'添加/编辑商品','erp/product/edit','',28,'glyphicon glyphicon-bookmark',0,4,1,1591340057,1591340057),
	(30,'禁用/解禁商品','erp/product/set-status','',28,'',0,4,1,1591529691,1591529691),
	(31,'删除商品','erp/product/delete','',28,'',0,4,1,1591529702,1591529702),
	(32,'设置商品顺序','erp/product/set-sort','',28,'',0,4,1,1591837679,1591837679),
	(33,'查看操作日志','erp/product/show-log','',28,'',0,4,1,1591531338,1591531338),
	(34,'品牌列表','erp/brand/index','',27,'',9,3,1,1591247848,1591247848),
	(35,'添加/编辑品牌','erp/brand/edit','',34,'',0,4,1,1591247883,1591247883),
	(36,'删除品牌','erp/brand/delete','',34,'',0,4,1,1591248238,1591248238),
	(37,'设置品牌顺序','erp/brand/set-sort','',34,'',0,4,1,1591837554,1591837554),
	(38,'分类列表','erp/category/index','',27,'',8,3,1,1591250358,1591250358),
	(39,'添加/编辑分类','erp/category/edit','',38,'',0,4,1,1591250371,1591250371),
	(40,'删除分类','erp/category/delete','',38,'',0,4,1,1591250388,1591256759),
	(41,'商品评论','erp/product-comment/index','',27,'',6,3,1,1591595898,1591595898),
	(42,'删除评论','erp/product-comment/delete','',41,'',0,4,1,1591596182,1591596182),
	(43,'设置评论是否显示','erp/product-comment/set-show','',41,'',0,4,1,1591597608,1591597608),
	(44,'回复评论','erp/product-comment/reply','',41,'',0,4,1,1591598729,1591598729),
	(45,'物流管理','','',2,'glyphicon glyphicon-plane',6,2,1,1591263366,1591263366),
	(46,'物流方式列表','erp/transport/index','',45,'',10,3,1,1591263796,1591263796),
	(47,'添加/编辑物流方式','erp/transport/edit','',46,'',0,4,1,1591263828,1591263828),
	(48,'删除物流方式','erp/transport/delete','',46,'',0,4,1,1591263834,1591263834),
	(49,'启用/禁用物流方式','erp/transport/set-status','',46,'',0,4,1,1591600978,1591600978),
	(50,'运费模版列表','erp/freight-template/index','',45,'',9,3,1,1591262804,1591263812),
	(51,'添加/编辑物流运费模版','erp/freight-template/edit','',50,'',0,4,1,1591262838,1591263812),
	(52,'删除物流运费模版','erp/freight-template/delete','',50,'',0,4,1,1591263329,1591263812),
	(53,'用户管理','','',2,'glyphicon glyphicon-user',5,2,1,1591366255,1591366255),
	(54,'会员列表','erp/user/index','',53,'',9,3,1,1591366278,1591366278),
	(55,'解禁/禁用会员','erp/user/set-status','',54,'',0,4,1,1591367816,1591367816),
	(56,'会员详情','erp/user/detail','',54,'',0,4,1,1591368914,1591368914),
	(57,'设置会员默认收货地址','erp/user/set-default-address','',55,'',0,5,1,1591498638,1591498638),
	(58,'添加/编辑会员','erp/user/edit','',54,'',0,4,1,1592301626,1592301626),
	(67,'营销管理','','',2,'glyphicon glyphicon-leaf',4,2,1,1592302088,1592302088),
	(68,'优惠券列表','erp/coupon/index','',67,'',0,3,1,1592302135,1592302135),
	(69,'添加/编辑优惠券','erp/coupon/edit','',68,'',0,4,1,1592302181,1592302181),
	(70,'删除优惠券','erp/coupon/delete','',68,'',0,4,1,1592302201,1592302201),
	(71,'启用/禁用优惠券','erp/coupon/set-status','',68,'',0,4,1,1592302214,1592302214),
	(72,'用户优惠券列表','erp/coupon-user/index','',67,'',0,3,1,1592302248,1592302248),
	(73,'优惠券发放','erp/coupon-user/edit','',72,'',0,4,1,1592302295,1592302295),
	(74,'优惠券删除','erp/coupon-user/delete','',72,'',0,4,1,1592302300,1592302300),
	(75,'用户积分行为','erp/user-points-behavior/index','',67,'',0,3,1,1592302391,1592302391),
	(76,'添加/编辑积分行为','erp/user-points-behavior/edit','',75,'',0,4,1,1592302404,1592302404),
	(77,'删除用户积分行为','erp/user-points-behavior/delete','',75,'',0,4,1,1592302432,1592302432),
	(78,'启用/禁用用户积分行为','erp/user-points-behavior/set-status','',75,'',0,4,1,1592302442,1592302442),
	(79,'订单管理','','',2,'glyphicon glyphicon-align-justify',4,2,1,1592302510,1592302510),
	(80,'添加/编辑订单','erp/order/edit','',81,'',0,4,1,1592302542,1592302542),
	(81,'订单列表','erp/order/index','',79,'',0,3,1,1592302549,1592302549),
	(82,'删除订单','erp/order/delete','',81,'',0,4,1,1592302558,1592302558),
	(83,'关闭订单','erp/order/close','',81,'glyphicon glyphicon-bookmark',0,4,1,1592302580,1592302580),
	(84,'订单发货','erp/order/ship','',81,'glyphicon glyphicon-bookmark',0,4,1,1592302586,1592302586),
	(85,'订单完成','erp/order/complete','',81,'glyphicon glyphicon-bookmark',0,4,1,1592302595,1592302595),
	(86,'订单详情','erp/order/detail','',81,'glyphicon glyphicon-bookmark',0,4,1,1592302602,1592302602),
	(87,'订单付款','erp/order/pay','',81,'glyphicon glyphicon-bookmark',0,4,1,1592302607,1592302607),
	(93,'财务管理','','',2,'iconfont icon-money',0,2,1,1592302860,1592302860),
	(99,'分销管理','','',2,'iconfont icon-spread',2,2,1,1592303056,1592303056),
	(100,'分销配置','erp/spread/config','',99,'',0,3,1,1592303068,1592303068),
	(101,'分销员列表','erp/spread/promoter','',99,'',0,3,1,1592303092,1592303092),
	(102,'推广订单列表','erp/spread/order','',101,'glyphicon glyphicon-bookmark',0,5,1,1592303131,1592303131);

/*!40000 ALTER TABLE `tbl_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_order`;

CREATE TABLE `tbl_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_type` tinyint(4) DEFAULT '1' COMMENT '订单类型 1实物订单 2虚拟物品订单',
  `order_code` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_title` varchar(255) DEFAULT '' COMMENT '订单标题',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `product_money` decimal(10,2) DEFAULT '0.00' COMMENT '商品总额',
  `coupon_id` int(11) DEFAULT '0' COMMENT '用户优惠券ID',
  `rate_money` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `freight_money` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `pay_money` decimal(10,2) DEFAULT '0.00' COMMENT '支付金额',
  `receiver_name` varchar(255) DEFAULT '' COMMENT '收货人',
  `receiver_mobile` varchar(16) DEFAULT '' COMMENT '收货人联系方式',
  `receiver_address` varchar(255) DEFAULT '' COMMENT '收货地址',
  `receiver_postal` varchar(16) DEFAULT '' COMMENT '邮编',
  `transport_id` int(11) DEFAULT '0' COMMENT '物流方式ID',
  `transport_order` varchar(255) DEFAULT '' COMMENT '物流单号',
  `pay_method` tinyint(4) DEFAULT '0' COMMENT '支付方式',
  `pay_time` int(11) DEFAULT '0' COMMENT '支付时间',
  `transaction_id` varchar(255) DEFAULT '' COMMENT '交易流水号',
  `deliver_time` int(11) DEFAULT '0' COMMENT '发货时间',
  `receive_time` int(11) DEFAULT '0' COMMENT '收货时间',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '订单状态 0已关闭 1待付款 2待发货 3已发货 4已完成',
  `is_commented` tinyint(4) DEFAULT '0' COMMENT '是否已评论 0否 1是',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_order` WRITE;
/*!40000 ALTER TABLE `tbl_order` DISABLE KEYS */;

INSERT INTO `tbl_order` (`order_id`, `order_type`, `order_code`, `user_id`, `order_title`, `money`, `product_money`, `coupon_id`, `rate_money`, `freight_money`, `pay_money`, `receiver_name`, `receiver_mobile`, `receiver_address`, `receiver_postal`, `transport_id`, `transport_order`, `pay_method`, `pay_time`, `transaction_id`, `deliver_time`, `receive_time`, `remark`, `status`, `is_commented`, `create_time`, `update_time`)
VALUES
	(1,2,'20200615180629904486',2,'111',75.00,100.00,3,30.00,5.00,75.00,'1','1','1','1',0,'',4,0,'',0,0,'1111',2,0,1592215589,1592273919),
	(4,1,'20200615220201271610',2,'ces',75.00,100.00,1,30.00,5.00,0.00,'1','1','1','1',0,'',0,0,'',0,0,'1111',5,0,1592229721,1592302626),
	(5,1,'20200615221112928156',2,'111',95.00,100.00,4,10.00,5.00,95.00,'1','1','1','1',0,'',1,0,'',0,0,'111',2,0,1592230272,1592359940),
	(6,1,'20200615221538167831',2,'111',95.00,100.00,4,10.00,5.00,95.00,'1','1','1','1',0,'',4,0,'',0,0,'111',4,0,1592230538,1592360204),
	(7,2,'20200616145116067869',1,'测试分销',210.00,200.00,0,0.00,10.00,210.00,'1','1','1','1',0,'',5,0,'',0,0,'',4,0,1592290276,1592290540),
	(8,2,'20200617103104067875',2,'111',70.00,100.00,5,30.00,0.00,70.00,'1','1','1','1',0,'',1,0,'',0,0,'',4,0,1592361064,1592363321),
	(9,2,'20200617103223373963',2,'111',70.00,100.00,5,30.00,0.00,70.00,'1','1','1','1',1,'ZTC11234',4,0,'',0,0,'',3,0,1592361143,1592362609);

/*!40000 ALTER TABLE `tbl_order` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_order_trace
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_order_trace`;

CREATE TABLE `tbl_order_trace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `user_type` tinyint(4) DEFAULT '1' COMMENT '用户类型 1后台用户 2前台用户',
  `detail` varchar(255) DEFAULT '' COMMENT '操作内容',
  `create_userid` int(11) NOT NULL COMMENT '创建人',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_order_trace` WRITE;
/*!40000 ALTER TABLE `tbl_order_trace` DISABLE KEYS */;

INSERT INTO `tbl_order_trace` (`id`, `order_id`, `user_type`, `detail`, `create_userid`, `create_time`)
VALUES
	(1,1,1,'创建',1,1592215589),
	(4,4,1,'创建',1,1592229721),
	(5,5,1,'创建',1,1592230272),
	(6,6,1,'创建',1,1592230538),
	(7,1,1,'收款',1,1592273919),
	(8,7,1,'创建',1,1592290276),
	(9,7,1,'收款',1,1592290333),
	(10,7,1,'使用',1,1592290338),
	(17,7,1,'使用',1,1592290540),
	(18,4,1,'关闭',1,1592302626),
	(19,6,1,'收款',1,1592359925),
	(20,5,1,'收款',1,1592359940),
	(21,6,1,'发货',1,1592360199),
	(22,6,1,'确认收货',1,1592360204),
	(23,8,1,'创建',1,1592361064),
	(24,9,1,'创建',1,1592361143),
	(25,9,1,'付款',1,1592361755),
	(26,9,1,'发货',1,1592362609),
	(29,8,1,'付款',1,1592362996),
	(30,8,1,'发送电子券',1,1592362996),
	(31,8,1,'使用电子券',1,1592363321);

/*!40000 ALTER TABLE `tbl_order_trace` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_order_variation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_order_variation`;

CREATE TABLE `tbl_order_variation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned DEFAULT NULL COMMENT '订单ID',
  `product_id` int(11) unsigned NOT NULL COMMENT '商品ID',
  `variation_id` int(11) unsigned NOT NULL COMMENT 'skuID',
  `variation_code` varchar(32) DEFAULT '' COMMENT 'sku',
  `pic` varchar(255) DEFAULT '' COMMENT '商品主图',
  `product_name` varchar(255) DEFAULT '' COMMENT '商品名称',
  `rules_name` varchar(255) DEFAULT '' COMMENT '规格名称',
  `rules_value` varchar(255) DEFAULT '' COMMENT '规格值',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `status` tinyint(4) DEFAULT '1' COMMENT '1可用 0删除',
  `number` int(11) DEFAULT '0' COMMENT '数量',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_order_variation` WRITE;
/*!40000 ALTER TABLE `tbl_order_variation` DISABLE KEYS */;

INSERT INTO `tbl_order_variation` (`id`, `order_id`, `product_id`, `variation_id`, `variation_code`, `pic`, `product_name`, `rules_name`, `rules_value`, `price`, `status`, `number`, `create_time`, `update_time`)
VALUES
	(1,1,1,1,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test','Color','red',100.00,1,1,1592215589,1592215589),
	(2,4,1,1,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test','Color','red',100.00,1,1,1592229721,1592229721),
	(3,5,1,1,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test','Color','red',100.00,1,1,1592230272,1592230272),
	(4,6,2,2,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test2','Color','red',100.00,1,1,1592230538,1592230538),
	(5,7,1,1,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test','Color','red',100.00,1,1,1592290276,1592290276),
	(6,7,2,2,'3515005887695','http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','test2','Color','red',100.00,1,1,1592290276,1592290276),
	(7,8,6,3,'3515005813359','http://mx.local.com/upload/erp/product/ee67e3da709a7e8808a1541b27f7d3b8.png','肯德基优惠券','','',100.00,1,1,1592361064,1592361064),
	(8,9,6,3,'3515005813359','http://mx.local.com/upload/erp/product/ee67e3da709a7e8808a1541b27f7d3b8.png','肯德基优惠券','','',100.00,1,1,1592361143,1592361143);

/*!40000 ALTER TABLE `tbl_order_variation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_pay_method
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_pay_method`;

CREATE TABLE `tbl_pay_method` (
  `method_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '支付方式名称',
  `is_online` tinyint(4) DEFAULT '1' COMMENT '1线上 2线下',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 0删除 1使用中',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_pay_method` WRITE;
/*!40000 ALTER TABLE `tbl_pay_method` DISABLE KEYS */;

INSERT INTO `tbl_pay_method` (`method_id`, `name`, `is_online`, `status`, `create_time`)
VALUES
	(1,'线上-支付宝',1,1,0),
	(2,'线上-微信',1,1,0),
	(3,'线上-银行卡',1,1,0),
	(4,'线下-支付宝',0,1,0),
	(5,'线下-微信',0,1,0),
	(6,'线下-银行卡',0,1,0);

/*!40000 ALTER TABLE `tbl_pay_method` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_product
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_product`;

CREATE TABLE `tbl_product` (
  `product_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_type` tinyint(4) DEFAULT '1' COMMENT '添加类型 1:后台添加 2:其他方式添加',
  `product_name` varchar(255) NOT NULL COMMENT '商品名称',
  `product_type` tinyint(4) DEFAULT '1' COMMENT '1实物 2虚拟物品',
  `product_sub_name` varchar(255) DEFAULT '' COMMENT '商品副标题',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `category_name` varchar(255) DEFAULT '' COMMENT '分类全路径',
  `brand_id` int(11) NOT NULL COMMENT '品牌ID',
  `product_weight` decimal(10,2) DEFAULT '0.00' COMMENT '商品重量，单位g',
  `pic` varchar(255) DEFAULT '' COMMENT '主图',
  `media` varchar(255) DEFAULT '' COMMENT '商品主视频',
  `detail` text COMMENT '商品详情',
  `freight_id` int(11) DEFAULT '0' COMMENT '运费模版ID',
  `extra` text COMMENT '其他信息 商品规格，商品图片，商品参数',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '0删除 1上架 2下架',
  `verify_status` int(11) DEFAULT '1' COMMENT '0:未审核 1:审核通过 2:审核拒绝',
  `verify_reply` varchar(255) DEFAULT '' COMMENT '审核回复',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

LOCK TABLES `tbl_product` WRITE;
/*!40000 ALTER TABLE `tbl_product` DISABLE KEYS */;

INSERT INTO `tbl_product` (`product_id`, `add_type`, `product_name`, `product_type`, `product_sub_name`, `product_code`, `category_id`, `category_name`, `brand_id`, `product_weight`, `pic`, `media`, `detail`, `freight_id`, `extra`, `sort`, `status`, `verify_status`, `verify_reply`, `create_time`, `update_time`)
VALUES
	(1,1,'test',1,'test','3515005887695',1,'测试',1,100.00,'http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','','这是详情',1,'{\"category\":[\"1\"],\"images\":\"http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png,http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png\",\"product_params\":[{\"name\":\"\\u4ea7\\u5730\",\"value\":\"\\u4e2d\\u56fd\"}],\"rules\":[{\"name\":\"Color\",\"value\":[\"red\"]}]}',0,1,1,'',1592213749,1592213749),
	(2,1,'test2',1,'test','3515005887695',4,'测试,测试1,测试2,测试3',1,100.00,'http://backend.local.com//upload/erp/product/a911606e73237e80df3997fcbbca6851.png','','这是详情',1,'{\"category\":{\"0\":\"1\",\"1\":\"2\",\"3\":\"3\",\"5\":\"4\"},\"images\":\"http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png,http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png\",\"product_params\":[{\"name\":\"\\u4ea7\\u5730\",\"value\":\"\\u4e2d\\u56fd\"}],\"rules\":[{\"name\":\"Color\",\"value\":[\"red\"]}]}',0,1,1,'',1592213749,1592230380),
	(6,1,'肯德基优惠券',2,'','3515005813359',4,'测试,测试1,测试2,测试3',1,0.00,'http://mx.local.com/upload/erp/product/ee67e3da709a7e8808a1541b27f7d3b8.png','','32131231',0,'{\"category\":[\"1\",\"2\",\"3\",\"4\"],\"images\":\"http:\\/\\/mx.local.com\\/upload\\/erp\\/product\\/ee67e3da709a7e8808a1541b27f7d3b8.png\",\"product_params\":[{\"name\":\"\\u4ea7\\u5730\",\"value\":\"\\u4e2d\\u56fd\"}],\"rules\":[]}',0,1,1,'',1592360568,1592360702);

/*!40000 ALTER TABLE `tbl_product` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_product_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_product_category`;

CREATE TABLE `tbl_product_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pic` varchar(255) DEFAULT '' COMMENT '分类图片',
  `level` tinyint(11) DEFAULT '1' COMMENT '分类等级',
  `has_child` tinyint(4) DEFAULT '0' COMMENT '是否存在下级',
  `parent_id` int(11) DEFAULT '0' COMMENT '父级ID',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 0删除 1使用中',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品分类';

LOCK TABLES `tbl_product_category` WRITE;
/*!40000 ALTER TABLE `tbl_product_category` DISABLE KEYS */;

INSERT INTO `tbl_product_category` (`category_id`, `category_name`, `pic`, `level`, `has_child`, `parent_id`, `status`, `create_time`, `update_time`)
VALUES
	(1,'测试','http://backend.local.com//upload/erp/category/a911606e73237e80df3997fcbbca6851.png',1,1,0,1,1592204551,1592213663),
	(2,'测试1','',2,1,1,1,1592224516,1592224516),
	(3,'测试2','',3,1,2,1,1592224527,1592224527),
	(4,'测试3','',4,0,3,1,1592224539,1592224539);

/*!40000 ALTER TABLE `tbl_product_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_product_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_product_comment`;

CREATE TABLE `tbl_product_comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `user_type` tinyint(4) DEFAULT '1' COMMENT '1后台用户 2前台用户',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `variation_id` int(11) NOT NULL COMMENT 'skuID',
  `star` tinyint(4) DEFAULT '1' COMMENT '星星',
  `detail` varchar(255) DEFAULT '' COMMENT '评论内容',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `reply` varchar(255) DEFAULT '' COMMENT '回复内容',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1未回复 2已回复 0删除',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tbl_product_trace
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_product_trace`;

CREATE TABLE `tbl_product_trace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `user_type` tinyint(4) DEFAULT '1' COMMENT '用户类型 1后台用户 2前台用户',
  `detail` varchar(255) DEFAULT '' COMMENT '操作内容',
  `params` mediumtext COMMENT '具体内容',
  `create_userid` int(11) NOT NULL COMMENT '创建人',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_product_trace` WRITE;
/*!40000 ALTER TABLE `tbl_product_trace` DISABLE KEYS */;

INSERT INTO `tbl_product_trace` (`id`, `product_id`, `user_type`, `detail`, `params`, `create_userid`, `create_time`)
VALUES
	(1,1,1,'创建','{\"product_id\":\"1\",\"product_type\":\"1\",\"product_name\":\"test\",\"product_sub_name\":\"test\",\"product_code\":\"3515005887695\",\"category_id\":[\"1\"],\"brand_id\":\"1\",\"product_weight\":\"100\",\"user_id\":\"0\",\"detail\":\"\\u8fd9\\u662f\\u8be6\\u60c5\",\"sort\":\"0\",\"status\":\"1\",\"product_params\":\"[{\\\"name\\\":\\\"\\u4ea7\\u5730\\\",\\\"value\\\":\\\"\\u4e2d\\u56fd\\\"}]\",\"rules\":\"[{\\\"name\\\":\\\"Color\\\",\\\"value\\\":[\\\"red\\\"]}]\",\"variations\":\"[{\\\"rules_name\\\":\\\"Color\\\",\\\"rules_value\\\":\\\"red\\\",\\\"product_variation\\\":\\\"3515005887695\\\",\\\"price\\\":\\\"100\\\",\\\"market_price\\\":\\\"120\\\",\\\"stock\\\":\\\"5\\\"}]\",\"pic\":\"http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png,http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png\",\"media\":\"\"}',1,1592213749),
	(2,2,1,'编辑','{\"product_id\":\"2\",\"product_type\":\"1\",\"product_name\":\"test2\",\"product_sub_name\":\"test\",\"product_code\":\"3515005887695\",\"category_id\":{\"0\":\"1\",\"1\":\"2\",\"3\":\"3\",\"5\":\"4\"},\"brand_id\":\"1\",\"product_weight\":\"100.00\",\"pic\":\"http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png,http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/a911606e73237e80df3997fcbbca6851.png\",\"user_id\":\"0\",\"detail\":\"\\u8fd9\\u662f\\u8be6\\u60c5\",\"freight_id\":\"1\",\"sort\":\"0\",\"status\":\"1\",\"product_params\":\"[{\\\"name\\\":\\\"\\u4ea7\\u5730\\\",\\\"value\\\":\\\"\\u4e2d\\u56fd\\\"}]\",\"rules\":\"[{\\\"name\\\":\\\"Color\\\",\\\"value\\\":[\\\"red\\\"]}]\",\"variations\":\"[{\\\"rules_name\\\":\\\"Color\\\",\\\"rules_value\\\":\\\"red\\\",\\\"product_variation\\\":\\\"3515005887695\\\",\\\"price\\\":\\\"100.00\\\",\\\"market_price\\\":\\\"120.00\\\",\\\"stock\\\":\\\"3\\\"}]\",\"media\":\"\"}',1,1592230380),
	(6,6,1,'创建','{\"product_id\":\"6\",\"product_type\":\"2\",\"product_name\":\"\\u80af\\u5fb7\\u57fa\\u4f18\\u60e0\\u5238\",\"product_sub_name\":\"\",\"product_code\":\"3515005813359\",\"category_id\":{\"0\":\"1\",\"1\":\"2\",\"3\":\"3\",\"5\":\"4\"},\"brand_id\":\"1\",\"detail\":\"32131231\",\"sort\":\"0\",\"status\":\"1\",\"product_params\":\"[{\\\"name\\\":\\\"\\u4ea7\\u5730\\\",\\\"value\\\":\\\"\\u4e2d\\u56fd\\\"}]\",\"rules\":\"[]\",\"variations\":\"[{\\\"rules_name\\\":\\\"\\\",\\\"rules_value\\\":\\\"\\\",\\\"product_variation\\\":\\\"3515005813359\\\",\\\"price\\\":\\\"100\\\",\\\"market_price\\\":\\\"120\\\",\\\"stock\\\":\\\"10\\\"}]\",\"pic\":\"http:\\/\\/backend.local.com\\/\\/upload\\/erp\\/product\\/df9197cc7a7974c23e891d3d599bff62.png\",\"media\":\"\"}',1,1592360568),
	(7,6,1,'编辑','{\"product_id\":\"6\",\"product_type\":\"2\",\"product_name\":\"\\u80af\\u5fb7\\u57fa\\u4f18\\u60e0\\u5238\",\"product_sub_name\":\"\",\"product_code\":\"3515005813359\",\"category_id\":[\"1\",\"2\",\"3\",\"4\"],\"brand_id\":\"1\",\"detail\":\"32131231\",\"sort\":\"0\",\"status\":\"1\",\"product_params\":\"[{\\\"name\\\":\\\"\\u4ea7\\u5730\\\",\\\"value\\\":\\\"\\u4e2d\\u56fd\\\"}]\",\"rules\":\"[]\",\"variations\":\"[{\\\"rules_name\\\":\\\"\\\",\\\"rules_value\\\":\\\"\\\",\\\"product_variation\\\":\\\"3515005813359\\\",\\\"price\\\":\\\"100.00\\\",\\\"market_price\\\":\\\"120.00\\\",\\\"stock\\\":\\\"10\\\"}]\",\"pic\":\"http:\\/\\/mx.local.com\\/upload\\/erp\\/product\\/ee67e3da709a7e8808a1541b27f7d3b8.png\",\"media\":\"\"}',1,1592360702);

/*!40000 ALTER TABLE `tbl_product_trace` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_product_variation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_product_variation`;

CREATE TABLE `tbl_product_variation` (
  `variation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `variation_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'sku',
  `rules_name` varchar(255) DEFAULT '' COMMENT '规格名称',
  `rules_value` varchar(255) DEFAULT '' COMMENT '规格值',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '销售价格',
  `market_price` decimal(10,2) DEFAULT '0.00' COMMENT '划线价格',
  `sale_number` int(11) DEFAULT '0' COMMENT '销售数量',
  `sale_amount` decimal(10,2) DEFAULT '0.00' COMMENT '销售金额',
  `status` tinyint(4) DEFAULT '1' COMMENT '0删除 1上架 2下架',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`variation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='SKU表';

LOCK TABLES `tbl_product_variation` WRITE;
/*!40000 ALTER TABLE `tbl_product_variation` DISABLE KEYS */;

INSERT INTO `tbl_product_variation` (`variation_id`, `product_id`, `variation_code`, `rules_name`, `rules_value`, `stock`, `price`, `market_price`, `sale_number`, `sale_amount`, `status`, `create_time`, `update_time`)
VALUES
	(1,1,'3515005887695','Color','red',2,100.00,120.00,0,0.00,1,1592213750,1592213750),
	(2,2,'3515005887695','Color','red',1,100.00,120.00,0,0.00,1,1592213750,1592230380),
	(3,6,'3515005813359','','',8,100.00,120.00,0,0.00,1,1592360568,1592360702);

/*!40000 ALTER TABLE `tbl_product_variation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_role`;

CREATE TABLE `tbl_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名称',
  `desc` varchar(255) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '角色状态 0 无效 1 有效',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色表';

LOCK TABLES `tbl_role` WRITE;
/*!40000 ALTER TABLE `tbl_role` DISABLE KEYS */;

INSERT INTO `tbl_role` (`id`, `name`, `desc`, `status`, `create_time`, `update_time`)
VALUES
	(1,'管理员','root',1,1562982778,1562982993);

/*!40000 ALTER TABLE `tbl_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_role_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_role_admin`;

CREATE TABLE `tbl_role_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '用户ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

LOCK TABLES `tbl_role_admin` WRITE;
/*!40000 ALTER TABLE `tbl_role_admin` DISABLE KEYS */;

INSERT INTO `tbl_role_admin` (`id`, `admin_id`, `role_id`, `create_time`)
VALUES
	(1,2,1,1592204412);

/*!40000 ALTER TABLE `tbl_role_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_role_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_role_menu`;

CREATE TABLE `tbl_role_menu` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role-menu` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

LOCK TABLES `tbl_role_menu` WRITE;
/*!40000 ALTER TABLE `tbl_role_menu` DISABLE KEYS */;

INSERT INTO `tbl_role_menu` (`id`, `role_id`, `menu_id`, `create_time`)
VALUES
	(1,1,1,1591698158),
	(2,1,3,1591698158),
	(3,1,4,1591698158),
	(4,1,5,1591698158),
	(5,1,13,1591698158),
	(6,1,6,1591698158),
	(7,1,7,1591698158),
	(8,1,8,1591698158),
	(9,1,9,1591698158),
	(10,1,10,1591698158),
	(11,1,11,1591698158),
	(12,1,12,1591698158),
	(13,1,14,1591698158),
	(14,1,15,1591698158),
	(15,1,2,1591698158),
	(16,1,17,1591698158),
	(17,1,18,1591698158),
	(18,1,34,1591698158),
	(19,1,35,1591698158),
	(20,1,36,1591698158),
	(21,1,37,1591698158),
	(22,1,38,1591698158),
	(23,1,20,1591698158),
	(24,1,21,1591698158),
	(25,1,22,1591698158),
	(26,1,23,1591698158),
	(27,1,24,1591698158),
	(28,1,25,1591698158),
	(29,1,26,1591698158),
	(30,1,39,1591698158),
	(32,1,46,1591698158),
	(33,1,47,1591698158),
	(34,1,48,1591698158),
	(35,1,60,1591698158),
	(36,1,61,1591698158),
	(37,1,62,1591698158),
	(38,1,63,1591698158),
	(39,1,65,1591698158),
	(40,1,66,1591698158),
	(41,1,67,1591698158),
	(42,1,68,1591698158),
	(43,1,69,1591698158),
	(44,1,70,1591698158),
	(45,1,76,1591698158),
	(46,1,71,1591698158),
	(47,1,72,1591698158),
	(48,1,73,1591698158),
	(49,1,74,1591698158),
	(50,1,75,1591698158),
	(51,1,77,1591698158),
	(52,1,41,1591698158),
	(53,1,42,1591698158),
	(54,1,43,1591698158),
	(55,1,44,1591698158),
	(56,1,45,1591698158),
	(57,1,49,1591698158),
	(58,1,50,1591698158),
	(59,1,51,1591698158),
	(60,1,52,1591698158),
	(61,1,53,1591698158),
	(62,1,58,1591698158),
	(63,1,59,1591698158),
	(64,1,54,1591698158),
	(65,1,55,1591698158),
	(66,1,56,1591698158),
	(67,1,57,1591698158),
	(68,1,27,1591698158),
	(69,1,31,1591698158),
	(70,1,32,1591698158),
	(71,1,33,1591698158),
	(72,1,64,1591698158),
	(73,1,28,1591698158),
	(74,1,29,1591698158),
	(75,1,30,1591698158),
	(76,1,78,1591698158),
	(77,1,79,1591698158),
	(78,1,80,1591698158),
	(79,1,81,1591698158),
	(80,1,82,1591698158),
	(81,1,83,1591698158),
	(82,1,91,1592204525),
	(83,1,92,1592204525),
	(84,1,93,1592204525),
	(85,1,94,1592204525),
	(86,1,84,1592204525),
	(87,1,85,1592204525),
	(88,1,86,1592204525),
	(89,1,87,1592204525),
	(90,1,89,1592204525);

/*!40000 ALTER TABLE `tbl_role_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_site_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_site_info`;

CREATE TABLE `tbl_site_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `web_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `web_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `web_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `default_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '123456',
  `wechat_app_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `wechat_app_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `wechat_mch_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `wechat_pay_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `app_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `about_us` text COLLATE utf8mb4_unicode_ci COMMENT '关于我们',
  `expire_order_pay` int(11) DEFAULT '0',
  `spread` text COLLATE utf8mb4_unicode_ci COMMENT '分销配置',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  `expire_order_finish` int(11) DEFAULT '0',
  `expire_order_comment` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='站点信息';

LOCK TABLES `tbl_site_info` WRITE;
/*!40000 ALTER TABLE `tbl_site_info` DISABLE KEYS */;

INSERT INTO `tbl_site_info` (`id`, `web_name`, `web_host`, `web_ip`, `default_password`, `wechat_app_id`, `wechat_app_secret`, `wechat_mch_id`, `wechat_pay_key`, `app_logo`, `about_us`, `expire_order_pay`, `spread`, `create_time`, `update_time`, `expire_order_finish`, `expire_order_comment`)
VALUES
	(1,'后台管理系统','http://mx.local.com','121.40.224.59','123456','','','','','http://mx.delcache.com//upload/erp/site-info/8786fd6846163dfbd1476401fffc5149.jpeg','111',15,'{\"type\":\"1\",\"depth\":\"3\",\"back\":[\"20\",\"10\",\"5\"]}',1573022804,1592360677,1,1);

/*!40000 ALTER TABLE `tbl_site_info` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_transport
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_transport`;

CREATE TABLE `tbl_transport` (
  `transport_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `transport_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '0删除 1使用中',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`transport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_transport` WRITE;
/*!40000 ALTER TABLE `tbl_transport` DISABLE KEYS */;

INSERT INTO `tbl_transport` (`transport_id`, `transport_name`, `remark`, `status`, `create_time`, `update_time`)
VALUES
	(1,'中通快递','满5元起送',1,1592213778,1592213778);

/*!40000 ALTER TABLE `tbl_transport` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(4) DEFAULT '1' COMMENT '用户等级 1普通用户 2企业用户',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `realname` varchar(255) DEFAULT '' COMMENT '真实姓名',
  `city` varchar(255) DEFAULT '' COMMENT '城市',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像',
  `telephone` varchar(16) DEFAULT '' COMMENT '手机号',
  `birthday` varchar(32) DEFAULT '' COMMENT '生日',
  `openid` varchar(28) DEFAULT '',
  `gender` tinyint(4) DEFAULT '0' COMMENT '性别 1男 2女',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态 1可用 0禁用',
  `is_promoter` tinyint(4) DEFAULT '0' COMMENT '是否推广员 1是0否',
  `spread_id` int(11) DEFAULT '0' COMMENT '上级分销用户ID',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `upgrade_time` int(11) DEFAULT '0' COMMENT '升级时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_user` WRITE;
/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;

INSERT INTO `tbl_user` (`user_id`, `level`, `nickname`, `realname`, `city`, `avatar`, `telephone`, `birthday`, `openid`, `gender`, `status`, `is_promoter`, `spread_id`, `create_time`, `upgrade_time`, `update_time`)
VALUES
	(1,1,'叶','','','http://backend.local.com/upload/erp/product/a911606e73237e80df3997fcbbca6851.png','13646622759',NULL,'9',0,1,1,2,0,0,0),
	(2,2,'叶1','王静叶','武汉市','http://backend.local.com//upload/erp/user/229f0a950c16fa33703f11afb2ba1490.gif','18055232662','2020-06-16','9',1,1,1,0,0,0,1592301782);

/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user_address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_address`;

CREATE TABLE `tbl_user_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `receiver_name` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `receiver_mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '联系方式',
  `detail_address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `postal` varchar(32) DEFAULT '' COMMENT '邮编',
  `is_default` tinyint(4) DEFAULT '1' COMMENT '是否默认 1是 0否',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tbl_user_bill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_bill`;

CREATE TABLE `tbl_user_bill` (
  `bill_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '账单ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `bill_type` tinyint(4) unsigned DEFAULT '1' COMMENT '账单类型 1付款 2退款 3收款',
  `relation_type` tinyint(4) unsigned DEFAULT '1' COMMENT '关联类型 1订单',
  `relation_id` int(11) unsigned DEFAULT '0' COMMENT '关联ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '账单名称',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '账单金额',
  `pay_method` int(11) unsigned NOT NULL COMMENT '支付方式',
  `transaction_id` varchar(255) DEFAULT '' COMMENT '交易流水号/商家订单号',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_user_bill` WRITE;
/*!40000 ALTER TABLE `tbl_user_bill` DISABLE KEYS */;

INSERT INTO `tbl_user_bill` (`bill_id`, `user_id`, `bill_type`, `relation_type`, `relation_id`, `title`, `amount`, `pay_method`, `transaction_id`, `create_time`)
VALUES
	(1,2,1,1,5,'',95.00,4,'x12345',1592359925),
	(2,2,1,1,6,'',95.00,1,'dasf',1592359940),
	(3,2,1,1,9,'111',70.00,4,'xc123456',1592361755),
	(6,2,1,1,8,'111',70.00,1,'XC123456',1592362996);

/*!40000 ALTER TABLE `tbl_user_bill` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user_points_behavior
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_points_behavior`;

CREATE TABLE `tbl_user_points_behavior` (
  `behavior_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `behavior_name` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(4) DEFAULT '1' COMMENT '行为类型 1每日 2总共',
  `url` varchar(191) NOT NULL DEFAULT '' COMMENT '行为方法路径',
  `points` int(11) DEFAULT '0' COMMENT '积分数量',
  `number` int(11) DEFAULT '0' COMMENT '次数',
  `status` tinyint(4) unsigned DEFAULT '1' COMMENT '状态 1:启用 2:禁用',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`behavior_id`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_user_points_behavior` WRITE;
/*!40000 ALTER TABLE `tbl_user_points_behavior` DISABLE KEYS */;

INSERT INTO `tbl_user_points_behavior` (`behavior_id`, `behavior_name`, `type`, `url`, `points`, `number`, `status`, `create_time`, `update_time`)
VALUES
	(1,'登录',1,'v1/public/get-user-info',10,2,1,1592200369,1592204746);

/*!40000 ALTER TABLE `tbl_user_points_behavior` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user_points_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_points_log`;

CREATE TABLE `tbl_user_points_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points` int(11) DEFAULT '0',
  `behavior_id` tinyint(4) DEFAULT '1' COMMENT '用户行为',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user-behavior` (`user_id`,`behavior_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户积分记录';

LOCK TABLES `tbl_user_points_log` WRITE;
/*!40000 ALTER TABLE `tbl_user_points_log` DISABLE KEYS */;

INSERT INTO `tbl_user_points_log` (`id`, `user_id`, `points`, `behavior_id`, `create_time`)
VALUES
	(1,1,10,1,1592203311),
	(2,1,10,1,1592203372);

/*!40000 ALTER TABLE `tbl_user_points_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user_spread
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_spread`;

CREATE TABLE `tbl_user_spread` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spread_id` int(11) unsigned DEFAULT '0' COMMENT '分销员ID',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '订单用户ID',
  `order_code` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `back_money` decimal(15,2) DEFAULT '0.00' COMMENT '返现金额',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户推广返现记录';

LOCK TABLES `tbl_user_spread` WRITE;
/*!40000 ALTER TABLE `tbl_user_spread` DISABLE KEYS */;

INSERT INTO `tbl_user_spread` (`id`, `spread_id`, `order_id`, `user_id`, `order_code`, `back_money`, `create_time`, `update_time`)
VALUES
	(1,2,7,1,'20200616145116067869',42.00,1592290540,1592290540);

/*!40000 ALTER TABLE `tbl_user_spread` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_user_verify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_verify`;

CREATE TABLE `tbl_user_verify` (
  `verify_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `industry_id` int(11) NOT NULL COMMENT '行业ID',
  `company_name` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `realname` varchar(255) DEFAULT '' COMMENT '真实姓名',
  `telephone` varchar(16) DEFAULT '' COMMENT '联系电话',
  `verify_status` tinyint(4) DEFAULT '0' COMMENT '审核状态 0:未审核 1:已通过 2:已拒绝',
  `verify_reply` varchar(255) DEFAULT '' COMMENT '审核回复',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `verify_time` int(11) DEFAULT '0' COMMENT '审核时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`verify_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tbl_user_wallet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_wallet`;

CREATE TABLE `tbl_user_wallet` (
  `wallet_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `points` int(11) DEFAULT '0' COMMENT '用户可用积分',
  `balance` decimal(15,2) DEFAULT '0.00' COMMENT '余额',
  `frozen_money` decimal(15,2) DEFAULT '0.00' COMMENT '冻结金额',
  `spread_money` decimal(15,2) DEFAULT '0.00' COMMENT '推广金额',
  `spread_order_money` decimal(15,2) DEFAULT '0.00' COMMENT '推广订单金额',
  `cash_out_money` decimal(15,2) DEFAULT '0.00' COMMENT '已提现金额',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`wallet_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `tbl_user_wallet` WRITE;
/*!40000 ALTER TABLE `tbl_user_wallet` DISABLE KEYS */;

INSERT INTO `tbl_user_wallet` (`wallet_id`, `user_id`, `points`, `balance`, `frozen_money`, `spread_money`, `spread_order_money`, `cash_out_money`, `create_time`, `update_time`)
VALUES
	(1,1,0,0.00,0.00,0.00,0.00,0.00,0,0),
	(2,2,0,42.00,0.00,42.00,210.00,0.00,0,0);

/*!40000 ALTER TABLE `tbl_user_wallet` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
