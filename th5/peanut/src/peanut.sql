/*
Navicat MySQL Data Transfer

Source Server         : gjs
Source Server Version : 50727
Source Host           : 118.190.149.14:3306
Source Database       : peanut

Target Server Type    : MYSQL
Target Server Version : 50727
File Encoding         : 65001

Date: 2020-06-25 12:09:00
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
  `sales_volume` int(11) NOT NULL COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='品牌表';

-- ----------------------------
-- Records of peanut_brand
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_chat`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_chat`;
CREATE TABLE `peanut_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `hair_id` int(11) NOT NULL COMMENT '发送者ID',
  `collect_id` int(11) NOT NULL COMMENT '接收者ID',
  `chat_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '发送时间',
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
  `comment_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '评论时间',
  `sell_id` int(11) NOT NULL COMMENT '卖家ID',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `comment_num` int(11) NOT NULL COMMENT '点评分数',
  `fabulous_num` int(11) NOT NULL COMMENT '点赞数',
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
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of peanut_jurisdiction
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_menu`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_menu`;
CREATE TABLE `peanut_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '菜单名称',
  `menu_url` varchar(255) NOT NULL COMMENT '菜单路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of peanut_menu
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_order`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_order`;
CREATE TABLE `peanut_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `sell_id` int(11) NOT NULL COMMENT '卖家ID',
  `buy_id` int(11) NOT NULL COMMENT '买家ID',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆ID',
  `state` varchar(255) NOT NULL COMMENT '状态',
  `transaction_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '交易时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of peanut_order
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
  `report_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '举报时间',
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of peanut_role
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_series`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_series`;
CREATE TABLE `peanut_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '系类名称',
  `brand

_id` int(11) NOT NULL COMMENT '品牌ID',
  `sales_volume` int(11) NOT NULL COMMENT '销量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系类表';

-- ----------------------------
-- Records of peanut_series
-- ----------------------------

-- ----------------------------
-- Table structure for `peanut_staff`
-- ----------------------------
DROP TABLE IF EXISTS `peanut_staff`;
CREATE TABLE `peanut_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '员工名称',
  `account` varchar(255) NOT NULL COMMENT '员工账号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `phone` int(11) NOT NULL COMMENT '手机号',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `head_img` varchar(11) NOT NULL COMMENT '头像',
  `sex` enum('女','男') NOT NULL DEFAULT '男' COMMENT '性别',
  `state` varchar(255) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='员工表';

-- ----------------------------
-- Records of peanut_staff
-- ----------------------------

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
  `name` varchar(255) NOT NULL COMMENT '用户名称',
  `phone` int(11) NOT NULL COMMENT '用户手机号',
  `password` varchar(11) NOT NULL COMMENT '密码',
  `head_img` varchar(11) NOT NULL COMMENT '用户头像',
  `age` int(3) NOT NULL COMMENT '年龄',
  `sex` enum('女','男') NOT NULL DEFAULT '男' COMMENT '性别',
  `state` varchar(255) NOT NULL COMMENT '用户状态',
  `money` float(11,2) NOT NULL COMMENT '用户金额',
  `register_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '注册时间',
  `las_time` datetime NOT NULL COMMENT '最后登录时间',
  `credit` int(11) NOT NULL DEFAULT '100' COMMENT '信用值',
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
  `introduce` varchar(255) NOT NULL COMMENT '车辆介绍',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `state` varchar(255) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='车辆表';

-- ----------------------------
-- Records of peanut_vehicle
-- ----------------------------
