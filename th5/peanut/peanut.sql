/*
Navicat MySQL Data Transfer

Source Server         : gjs
Source Server Version : 50727
Source Host           : 118.190.149.14:3306
Source Database       : peanut

Target Server Type    : MYSQL
Target Server Version : 50727
File Encoding         : 65001

Date: 2020-06-29 15:04:02
*/


DROP DATABASE IF EXISTS `peanut`;
CREATE DATABASE `peanut` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
SET FOREIGN_KEY_CHECKS=0;
use `peanut`;

-- ----------------------------
-- Table structure for `peanut_auction`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_auction`;
CREATE TABLE `peanut_auction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆ID',
  `sell_id` int(11) NOT NULL COMMENT '拍卖者ID',
  `price` int(11) NOT NULL COMMENT '拍卖金额',
  `auction_time` datetime NOT NULL COMMENT '拍卖时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拍卖表';

-- ----------------------------
-- Records of peanut_auction
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_brand`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_brand`;
CREATE TABLE `peanut_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '品牌名称',
  `brand_img` varchar(255) NOT NULL COMMENT '品牌图片',
  `sales_volume` int(11) NOT NULL COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='品牌表';

-- ----------------------------
-- Records of peanut_brand
-- ----------------------------
INSERT INTO `peanut_brand` VALUES ('1', '保时捷', 'b_22.png', '3');
INSERT INTO `peanut_brand` VALUES ('2', '别克', 'b_34.png', '4');
INSERT INTO `peanut_brand` VALUES ('3', '大众', 'b_84.png', '5');
INSERT INTO `peanut_brand` VALUES ('4', '宝马', 'b_38.png', '6');
INSERT INTO `peanut_brand` VALUES ('5', '奔驰', 'b_47.png', '7');
INSERT INTO `peanut_brand` VALUES ('6', '奥迪', 'b_62.png', '8');
INSERT INTO `peanut_brand` VALUES ('7', '本田', 'b_59.png', '2');
INSERT INTO `peanut_brand` VALUES ('8', '雪佛兰', 'b_73.png', '3');

-- ----------------------------
-- Table structure for `peanut_chat`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_chat`;
CREATE TABLE `peanut_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `hair_id` int(11) NOT NULL COMMENT '发送者ID',
  `collect_id` int(11) NOT NULL COMMENT '接收者ID',
  `chat_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '发送时间',
  `content` varchar(255) NOT NULL COMMENT '聊天内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='聊天表';

-- ----------------------------
-- Records of peanut_chat
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_collection`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_collection`;
CREATE TABLE `peanut_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- ----------------------------
-- Records of peanut_collection
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_collection_category`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_collection_category`;
CREATE TABLE `peanut_collection_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `collection_id` int(11) NOT NULL COMMENT '收藏ID',
  `sell_id` int(11) NOT NULL COMMENT '卖家ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏分类表';

-- ----------------------------
-- Records of peanut_collection_category
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_comment`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_comment`;
CREATE TABLE `peanut_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `comment_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '评论时间',
  `sell_id` int(11) NOT NULL COMMENT '卖家ID',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `comment_num` int(11) NOT NULL COMMENT '点评分数',
  `fabulous_num` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `father_id` int(11) NOT NULL COMMENT '父级ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of peanut_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_jurisdiction`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_jurisdiction`;
CREATE TABLE `peanut_jurisdiction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `menu_id` varchar(255) NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of peanut_jurisdiction
-- ----------------------------
INSERT INTO `peanut_jurisdiction` VALUES ('1', '1', '1,2,3,4,5,6,7,8,9,10,11');
INSERT INTO `peanut_jurisdiction` VALUES ('2', '2', '1,2,3,4,8');
INSERT INTO `peanut_jurisdiction` VALUES ('3', '3', '1,5,6,9,11');
INSERT INTO `peanut_jurisdiction` VALUES ('4', '4', '10');

-- ----------------------------
-- Table structure for `peanut_menu`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_menu`;
CREATE TABLE `peanut_menu` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) DEFAULT NULL COMMENT '菜单名',
  `menu_class` varchar(255) NOT NULL COMMENT '菜单logo样式',
  `menu_url` varchar(255) DEFAULT NULL COMMENT '菜单地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of peanut_menu
-- ----------------------------
INSERT INTO `peanut_menu` VALUES ('1', '主页', 'el-icon-s-home', '/');
INSERT INTO `peanut_menu` VALUES ('2', '角色管理', 'el-icon-s-help', '/AdminRole');
INSERT INTO `peanut_menu` VALUES ('3', '权限管理', 'el-icon-s-menu', '/jurisdic');
INSERT INTO `peanut_menu` VALUES ('4', '员工管理', 'el-icon-s-custom', '/staffManage');
INSERT INTO `peanut_menu` VALUES ('5', '用户管理', 'el-icon-s-solid', '/users');
INSERT INTO `peanut_menu` VALUES ('6', '商品管理', 'el-icon-s-shop', '/goods');
INSERT INTO `peanut_menu` VALUES ('7', '订单管理', 'el-icon-s-goods', '/order');
INSERT INTO `peanut_menu` VALUES ('8', '报表管理', 'el-icon-s-data', '/AdminEcharts');
INSERT INTO `peanut_menu` VALUES ('9', '公告管理', 'el-icon-s-solid', '/AdminNotice');
INSERT INTO `peanut_menu` VALUES ('10', '客服聊天', 'el-icon-s-phone', '/chat');
INSERT INTO `peanut_menu` VALUES ('11', '举报管理', 'el-icon-s-solid', '/AdminComplain');

-- ----------------------------
-- Table structure for `peanut_notice`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_notice`;
CREATE TABLE `peanut_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `notice_Publisher` varchar(255) NOT NULL COMMENT '公告发布者',
  `notice_cont` varchar(255) NOT NULL COMMENT '公告内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='公告表';

-- ----------------------------
-- Records of peanut_notice
-- ----------------------------
INSERT INTO `peanut_notice` VALUES ('1', '保时捷热销', 'aaa');
INSERT INTO `peanut_notice` VALUES ('2', '玛莎拉蒂热销', 'bbb');
INSERT INTO `peanut_notice` VALUES ('3', '宝马热销', 'ccc');
INSERT INTO `peanut_notice` VALUES ('4', '奔驰热销', 'ddd');
INSERT INTO `peanut_notice` VALUES ('5', '大众热销', 'fff');

-- ----------------------------
-- Table structure for `peanut_order`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_order`;
CREATE TABLE `peanut_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `order_num` varchar(100) NOT NULL COMMENT '订单号',
  `sell_id` int(11) NOT NULL COMMENT '卖家ID',
  `buy_id` int(11) NOT NULL COMMENT '买家ID',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆ID',
  `state` varchar(255) NOT NULL COMMENT '状态',
  `transaction_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '交易时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of peanut_order
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_province`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_province`;
CREATE TABLE `peanut_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `province_name` varchar(255) NOT NULL COMMENT '省名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='省位置表';

-- ----------------------------
-- Records of peanut_province
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_report`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_report`;
CREATE TABLE `peanut_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `report_id` int(11) NOT NULL COMMENT '举报人ID',
  `reported_id` int(11) NOT NULL COMMENT '被举报人ID',
  `text_content` varchar(255) NOT NULL COMMENT '文字内容',
  `img_content` varchar(255) NOT NULL COMMENT '图片内容',
  `report_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '举报时间',
  `state` varchar(255) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='举报表';

-- ----------------------------
-- Records of peanut_report
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_role`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_role`;
CREATE TABLE `peanut_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(11) NOT NULL COMMENT '角色名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of peanut_role
-- ----------------------------
INSERT INTO `peanut_role` VALUES ('1', '超级管理员', '系统管理员');
INSERT INTO `peanut_role` VALUES ('2', '经理', '添加员工查看报表等');
INSERT INTO `peanut_role` VALUES ('3', '普通业务员', '商品管理，订单管理等');
INSERT INTO `peanut_role` VALUES ('4', '客服', '客服人员');

-- ----------------------------
-- Table structure for `peanut_series`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_series`;
CREATE TABLE `peanut_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '系类名称',
  `brand_id` int(11) NOT NULL COMMENT '品牌ID',
  `sales_volume` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='系类表';

-- ----------------------------
-- Records of peanut_series
-- ----------------------------
INSERT INTO `peanut_series` VALUES ('1', '718', '1', '4');
INSERT INTO `peanut_series` VALUES ('2', '911', '1', '4');
INSERT INTO `peanut_series` VALUES ('3', '918', '1', '4');
INSERT INTO `peanut_series` VALUES ('4', 'Boxster', '1', '4');
INSERT INTO `peanut_series` VALUES ('5', '昂科拉', '2', '4');
INSERT INTO `peanut_series` VALUES ('6', '昂科拉GX', '2', '4');
INSERT INTO `peanut_series` VALUES ('7', '昂科雷', '2', '4');
INSERT INTO `peanut_series` VALUES ('8', '昂科旗', '2', '4');
INSERT INTO `peanut_series` VALUES ('9', '昂科威', '2', '4');
INSERT INTO `peanut_series` VALUES ('10', 'Amarok', '3', '4');
INSERT INTO `peanut_series` VALUES ('11', '宝来', '3', '4');
INSERT INTO `peanut_series` VALUES ('12', '宝来·纯电', '3', '4');
INSERT INTO `peanut_series` VALUES ('13', 'CC', '3', '4');
INSERT INTO `peanut_series` VALUES ('14', 'Eos', '3', '4');
INSERT INTO `peanut_series` VALUES ('15', '1系', '4', '4');
INSERT INTO `peanut_series` VALUES ('16', '1系M', '4', '4');
INSERT INTO `peanut_series` VALUES ('17', '2系', '4', '4');
INSERT INTO `peanut_series` VALUES ('18', '3系', '4', '4');
INSERT INTO `peanut_series` VALUES ('19', '4系', '4', '4');
INSERT INTO `peanut_series` VALUES ('20', 'Actros', '5', '4');
INSERT INTO `peanut_series` VALUES ('21', 'A级', '5', '4');
INSERT INTO `peanut_series` VALUES ('22', 'B级', '5', '4');
INSERT INTO `peanut_series` VALUES ('23', 'C级', '5', '4');
INSERT INTO `peanut_series` VALUES ('24', 'CLA级', '5', '4');
INSERT INTO `peanut_series` VALUES ('25', 'A1', '6', '4');
INSERT INTO `peanut_series` VALUES ('26', 'A3', '6', '4');
INSERT INTO `peanut_series` VALUES ('27', 'A4', '6', '4');
INSERT INTO `peanut_series` VALUES ('28', 'A5', '6', '4');
INSERT INTO `peanut_series` VALUES ('29', 'A6', '6', '4');
INSERT INTO `peanut_series` VALUES ('30', '艾力绅', '7', '4');
INSERT INTO `peanut_series` VALUES ('31', '奥德赛', '7', '4');
INSERT INTO `peanut_series` VALUES ('32', '缤智', '7', '4');
INSERT INTO `peanut_series` VALUES ('33', '飞度', '7', '4');
INSERT INTO `peanut_series` VALUES ('34', '锋范', '7', '4');
INSERT INTO `peanut_series` VALUES ('35', '爱唯欧', '8', '4');
INSERT INTO `peanut_series` VALUES ('36', '畅巡', '8', '4');
INSERT INTO `peanut_series` VALUES ('37', '创界', '8', '4');
INSERT INTO `peanut_series` VALUES ('38', '创酷', '8', '4');
INSERT INTO `peanut_series` VALUES ('39', '豪放', '8', '4');

-- ----------------------------
-- Table structure for `peanut_staff`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_staff`;
CREATE TABLE `peanut_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '员工名称',
  `account` varchar(255) NOT NULL COMMENT '员工账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `phone` bigint(11) NOT NULL COMMENT '手机号',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `head_img` varchar(255) NOT NULL COMMENT '头像',
  `sex` enum('女','男') NOT NULL DEFAULT '男' COMMENT '性别',
  `state` varchar(255) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='员工表';

-- ----------------------------
-- Records of peanut_staff
-- ----------------------------
INSERT INTO `peanut_staff` VALUES ('1', '张一', 'admin', 'admin', '13850607080', '1', 'assets/images', '男', '1');
INSERT INTO `peanut_staff` VALUES ('2', '王二', 'admin2', 'admin2', '13850607082', '2', 'assets/images', '男', '1');
INSERT INTO `peanut_staff` VALUES ('3', '陈三', 'admin3', 'admin3', '13850607083', '3', 'assets/images', '男', '1');
INSERT INTO `peanut_staff` VALUES ('4', '李四', 'admin4', 'admin4', '13850607084', '4', '', '男', '1');

-- ----------------------------
-- Table structure for `peanut_transaction`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_transaction`;
CREATE TABLE `peanut_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `transaction_num` int(11) NOT NULL COMMENT '交易数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易表';

-- ----------------------------
-- Records of peanut_transaction
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_user`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_user`;
CREATE TABLE `peanut_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) DEFAULT NULL COMMENT '用户名称',
  `phone` bigint(11) NOT NULL COMMENT '用户手机号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `head_img` varchar(255) NOT NULL COMMENT '用户头像',
  `age` int(3) DEFAULT NULL COMMENT '年龄',
  `sex` enum('女','男') DEFAULT NULL COMMENT '性别',
  `state` varchar(255) NOT NULL COMMENT '用户状态',
  `money` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '用户金额',
  `register_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '注册时间',
  `last_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `credit` int(11) NOT NULL DEFAULT '100' COMMENT '信用值',
  `add` varchar(255) DEFAULT NULL COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of peanut_user
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_vehicle`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_vehicle`;
CREATE TABLE `peanut_vehicle` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `series_id` int(11) NOT NULL COMMENT '系类ID',
  `price` float(11,2) NOT NULL COMMENT '价格',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `vehicle_name` varchar(255) NOT NULL COMMENT '车辆名称',
  `vehicle_time` varchar(255) NOT NULL COMMENT '上架时间',
  `vehicle_distance` varchar(255) NOT NULL COMMENT '行驶路程',
  `introduce` varchar(255) NOT NULL COMMENT '车辆介绍',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `state` varchar(255) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='车辆表';

-- ----------------------------
-- Records of peanut_vehicle
-- ----------------------------
INSERT INTO `peanut_vehicle` VALUES ('1', '1', '65.21', '1', '保时捷 718 2018款 2.0T 自动 Boxster', '2019', '0.4', '车况极佳', '5ee05dce4139d395858_18.jpg', '上架中');
INSERT INTO `peanut_vehicle` VALUES ('2', '2', '61.11', '1', '保时捷 718 2018款 2.0T 自动 Boxster', '2019', '3.4', '车况极佳', '5e803c5784920773898_18.jpg', '上架中');
INSERT INTO `peanut_vehicle` VALUES ('3', '2', '72.16', '1', '保时捷 911 2012款 3.4 自动 Carrera', '2014', '5.3', '车况极佳', '5ef5a95f856eb638323_18.jpg1', '上架中');
INSERT INTO `peanut_vehicle` VALUES ('4', '3', '55.67', '1', '保时捷 918 2018款 2.0T 自动', '2019', '0.6', '车况极佳', '5ee0acbaaecbf317048_18.jpg', '上架中');
INSERT INTO `peanut_vehicle` VALUES ('5', '4', '58.29', '1', '保时捷 2016款 2.0T 自动 Boxster', '2017', '3.8', '车况极佳', '5ef045c4b968a600627_18.jpg', '上架中');
