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

 Date: 12/20/2017 17:51:29 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chat_system_privs`
-- ----------------------------
DROP TABLE IF EXISTS `chat_system_privs`;
CREATE TABLE `chat_system_privs` (
  `id` int(10) NOT NULL,
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(24) DEFAULT NULL,
  `icon` varchar(48) DEFAULT NULL COMMENT '图标',
  `url` text,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `chat_system_privs`
-- ----------------------------
BEGIN;
INSERT INTO `chat_system_privs` VALUES ('1', '0', '管理员管理', 'fa-user-secret', '/admin/', null, null), ('2', '0', '权限管理', 'fa-cog', null, null, null), ('3', '0', '权限3', '', null, null, null), ('4', '1', '管理员列表', '', '/admin/administrator/', null, null), ('5', '2', '角色列表', '', '/admin/set/', null, null), ('6', '1', '新增管理员', '', '/admin/administrator/create/', null, null), ('7', '4', '管理员编辑', null, '/admin/administrator/update/', null, null), ('8', '4', '使过期', null, '/admin/administrator/update_expire_time.html/', null, null), ('9', '5', '编辑角色权限', null, '/admin/set/update_role_privs/', null, null), ('10', '5', '删除角色权限', null, '/admin/set/role_delete/', null, null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
