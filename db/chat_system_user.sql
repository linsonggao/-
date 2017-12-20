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

 Date: 12/20/2017 17:52:21 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_system_user`
-- ----------------------------
DROP TABLE IF EXISTS `chat_system_user`;
CREATE TABLE `chat_system_user` (
  `system_uid` int(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(20) NOT NULL DEFAULT '0' COMMENT '管理员角色',
  `username` varchar(50) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `mobile` varchar(30) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `is_show` int(1) NOT NULL DEFAULT '0' COMMENT '是否显示在前端  1是 0否',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `last_login_ip` varchar(50) DEFAULT NULL,
  `expire_time` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被删',
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`system_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
--  Records of `chat_system_user`
-- ----------------------------
BEGIN;
INSERT INTO `chat_system_user` VALUES ('1', '0', 'zushou', '4297f44b13955235245b2497399d7a93', '闲白小助手222', '', '1', '', '1', '0', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '2011-11-11 11:11:00', '2017-12-19 14:14:29'), ('2', '1', 'admin', '4297f44b13955235245b2497399d7a93', '后台管理员', '', '1', '', '0', '0', '127.0.0.1', '2017-12-21 01:29:33', '2017-12-20 17:29:33', '0', '2011-11-11 11:11:00', '0000-00-00 00:00:00'), ('5', '0', 'linsonggao', '4297f44b13955235245b2497399d7a93', 'linsonggao2', '', '1', null, '0', '0', null, null, null, '0', '2017-12-20 14:55:21', '2017-12-20 14:55:21');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
