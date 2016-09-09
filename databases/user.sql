/*
Navicat MySQL Data Transfer

Source Server         : HomeInter
Source Server Version : 50547
Source Host           : 192.168.169.18:3306
Source Database       : homeinter

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-03-08 12:30:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '账户ID',
  `deviceId` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '设备Id',
  `passwd` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `tokenCode` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'tcp用的授权码',
  `mail` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '手机号码',
  `nickname` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `headImg` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像url',
  `registryTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `validity` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '有效性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_account_mobile` (`uid`,`mobile`) USING BTREE COMMENT '保证用户标识唯一',
  KEY `index_accountid` (`uid`,`deviceId`) USING BTREE COMMENT '快速检索'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
