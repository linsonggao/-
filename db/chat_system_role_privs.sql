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

 Date: 12/20/2017 17:52:09 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_system_role_privs`
-- ----------------------------
DROP TABLE IF EXISTS `chat_system_role_privs`;
CREATE TABLE `chat_system_role_privs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `privs_id` int(10) DEFAULT NULL,
  `role_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `chat_system_role_privs`
-- ----------------------------
BEGIN;
INSERT INTO `chat_system_role_privs` VALUES ('9', '2', '1'), ('8', '6', '1'), ('7', '4', '1'), ('6', '1', '1'), ('10', '3', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
