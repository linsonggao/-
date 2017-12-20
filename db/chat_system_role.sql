/*
 Navicat Premium Data Transfer

 Source Server         : 闲白
 Source Server Type    : MySQL
 Source Server Version : 50636
 Source Host           : 10.10.10.104
 Source Database       : chat

 Target Server Type    : MySQL
 Target Server Version : 50636
 File Encoding         : utf-8

 Date: 12/20/2017 17:51:56 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_system_role`
-- ----------------------------
DROP TABLE IF EXISTS `chat_system_role`;
CREATE TABLE `chat_system_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL DEFAULT '' COMMENT '角色名',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常,1:删除',
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `chat_system_role`
-- ----------------------------
BEGIN;
INSERT INTO `chat_system_role` VALUES ('1', '超级无敌管理员', '0', '2017-12-18 15:26:00', '2017-12-18 15:26:03'), ('2', '超级管理员', '0', '2017-12-20 10:42:34', '2017-12-20 10:42:34'), ('3', '超级管理员2', '0', '2017-12-20 11:30:10', '2017-12-20 11:30:10'), ('4', '超级管理员3', '1', '2017-12-20 11:30:35', '2017-12-20 14:38:45'), ('5', '超级管理员4', '1', '2017-12-20 11:40:18', '2017-12-20 14:58:00');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
