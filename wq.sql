/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50027
Source Host           : localhost:3306
Source Database       : wq

Target Server Type    : MYSQL
Target Server Version : 50027
File Encoding         : 65001

Date: 2015-11-14 15:31:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for device_info
-- ----------------------------
DROP TABLE IF EXISTS `device_info`;
CREATE TABLE `device_info` (
  `token_id` int(11) NOT NULL auto_increment,
  `device_token` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of device_info
-- ----------------------------

-- ----------------------------
-- Table structure for sr_action_log
-- ----------------------------
DROP TABLE IF EXISTS `sr_action_log`;
CREATE TABLE `sr_action_log` (
  `id` int(11) NOT NULL auto_increment,
  `member_id` int(11) default '0',
  `username` varchar(50) default '0',
  `url` char(50) default NULL COMMENT '存储当前操作的moudle/controller/function,配置文件中通过此项定义显示的title，如：\r\n''backend/news/add''=>''添加新闻''\r\n''backend/news/edit''=>''修改新闻''',
  `table_name` varchar(50) default NULL,
  `table_id` text COMMENT '操作记录的id,批量操作可能会有多个ID',
  `description` text COMMENT '备注',
  `ip` varchar(15) default '0' COMMENT '操作者的IP',
  `status` tinyint(1) default '1',
  `add_time` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台操作记录';

-- ----------------------------
-- Records of sr_action_log
-- ----------------------------
INSERT INTO `sr_action_log` VALUES ('1', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-10 16:02:05');
INSERT INTO `sr_action_log` VALUES ('2', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-10 16:03:25');
INSERT INTO `sr_action_log` VALUES ('3', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-10 16:53:32');
INSERT INTO `sr_action_log` VALUES ('4', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 14:04:25');
INSERT INTO `sr_action_log` VALUES ('5', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 14:13:53');
INSERT INTO `sr_action_log` VALUES ('6', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 15:35:32');
INSERT INTO `sr_action_log` VALUES ('7', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 15:42:04');
INSERT INTO `sr_action_log` VALUES ('8', '1', 'admin', 'backend/base/menu/del', 'menu', '60', 'null', '0.0.0.0', '1', '2015-06-11 15:55:19');
INSERT INTO `sr_action_log` VALUES ('9', '1', 'admin', 'backend/base/menu/del', 'menu', '74', 'null', '0.0.0.0', '1', '2015-06-11 15:55:32');
INSERT INTO `sr_action_log` VALUES ('10', '1', 'admin', 'backend/base/menu/del', 'menu', '7', 'null', '0.0.0.0', '1', '2015-06-11 15:55:51');
INSERT INTO `sr_action_log` VALUES ('11', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 16:50:53');
INSERT INTO `sr_action_log` VALUES ('12', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-11 16:54:40');
INSERT INTO `sr_action_log` VALUES ('13', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-11 16:55:03');
INSERT INTO `sr_action_log` VALUES ('14', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-11 17:37:37');
INSERT INTO `sr_action_log` VALUES ('15', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-11 18:22:58');
INSERT INTO `sr_action_log` VALUES ('16', '1', 'admin', 'backend/member/index/disable', 'member', '4', '[{\"id\":\"4\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 08:56:00');
INSERT INTO `sr_action_log` VALUES ('17', '1', 'admin', 'backend/member/index/disable', 'member', '5', '[{\"id\":\"5\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 08:56:04');
INSERT INTO `sr_action_log` VALUES ('18', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-12 09:29:36');
INSERT INTO `sr_action_log` VALUES ('19', '1', 'admin', 'backend/member/index/enable', 'member', '4,5', '[{\"id\":\"4\",\"title\":\"admin2\"},{\"id\":\"5\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 09:39:10');
INSERT INTO `sr_action_log` VALUES ('20', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-12 10:32:58');
INSERT INTO `sr_action_log` VALUES ('21', '1', 'admin', 'backend/member/index/del', 'member', '86', '[{\"id\":\"86\",\"title\":\"ceshi\"}]', '127.0.0.1', '1', '2015-06-12 10:59:49');
INSERT INTO `sr_action_log` VALUES ('22', '1', 'admin', 'backend/member/index/del', 'member', '85', '[{\"id\":\"85\",\"title\":\"15220243601\"}]', '127.0.0.1', '1', '2015-06-12 10:59:53');
INSERT INTO `sr_action_log` VALUES ('23', '1', 'admin', 'backend/member/index/disable', 'member', '5', '[{\"id\":\"5\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 11:10:16');
INSERT INTO `sr_action_log` VALUES ('24', '1', 'admin', 'backend/member/index/disable', 'member', '5', '[{\"id\":\"5\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 11:10:17');
INSERT INTO `sr_action_log` VALUES ('25', '1', 'admin', 'backend/member/index/disable', 'member', '6', '[{\"id\":\"6\",\"title\":\"admin3\"}]', '127.0.0.1', '1', '2015-06-12 11:10:21');
INSERT INTO `sr_action_log` VALUES ('26', '1', 'admin', 'backend/member/index/disable', 'member', '7', '[{\"id\":\"7\",\"title\":\"admin4\"}]', '127.0.0.1', '1', '2015-06-12 11:10:24');
INSERT INTO `sr_action_log` VALUES ('27', '1', 'admin', 'backend/member/index/disable', 'member', '4', '[{\"id\":\"4\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-12 11:10:26');
INSERT INTO `sr_action_log` VALUES ('28', '1', 'admin', 'backend/member/index/enable', 'member', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23', '[{\"id\":\"4\",\"title\":\"admin2\"},{\"id\":\"5\",\"title\":\"admin2\"},{\"id\":\"6\",\"title\":\"admin3\"},{\"id\":\"7\",\"title\":\"admin4\"},{\"id\":\"8\",\"title\":\"admin5\"},{\"id\":\"9\",\"title\":\"admin6\"},{\"id\":\"10\",\"title\":\"admin7\"},{\"id\":\"11\",\"title\":\"admin8\"},{\"id\":\"12\",\"title\":\"admin9\"},{\"id\":\"13\",\"title\":\"admin6\"},{\"id\":\"14\",\"title\":\"admin6\"},{\"id\":\"15\",\"title\":\"admin6\"},{\"id\":\"16\",\"title\":\"admin6\"},{\"id\":\"17\",\"title\":\"admin6\"},{\"id\":\"18\",\"title\":\"admin6\"},{\"id\":\"19\",\"title\":\"admin6\"},{\"id\":\"20\",\"title\":\"admin6\"},{\"id\":\"21\",\"title\":\"admin6\"},{\"id\":\"22\",\"title\":\"admin6\"},{\"id\":\"23\",\"title\":\"admin6\"}]', '127.0.0.1', '1', '2015-06-12 11:10:39');
INSERT INTO `sr_action_log` VALUES ('29', '1', 'admin', 'backend/member/index/disable', 'member', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23', '[{\"id\":\"4\",\"title\":\"admin2\"},{\"id\":\"5\",\"title\":\"admin2\"},{\"id\":\"6\",\"title\":\"admin3\"},{\"id\":\"7\",\"title\":\"admin4\"},{\"id\":\"8\",\"title\":\"admin5\"},{\"id\":\"9\",\"title\":\"admin6\"},{\"id\":\"10\",\"title\":\"admin7\"},{\"id\":\"11\",\"title\":\"admin8\"},{\"id\":\"12\",\"title\":\"admin9\"},{\"id\":\"13\",\"title\":\"admin6\"},{\"id\":\"14\",\"title\":\"admin6\"},{\"id\":\"15\",\"title\":\"admin6\"},{\"id\":\"16\",\"title\":\"admin6\"},{\"id\":\"17\",\"title\":\"admin6\"},{\"id\":\"18\",\"title\":\"admin6\"},{\"id\":\"19\",\"title\":\"admin6\"},{\"id\":\"20\",\"title\":\"admin6\"},{\"id\":\"21\",\"title\":\"admin6\"},{\"id\":\"22\",\"title\":\"admin6\"},{\"id\":\"23\",\"title\":\"admin6\"}]', '127.0.0.1', '1', '2015-06-12 11:10:49');
INSERT INTO `sr_action_log` VALUES ('30', '1', 'admin', 'backend/member/index/enable', 'member', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23', '[{\"id\":\"4\",\"title\":\"admin2\"},{\"id\":\"5\",\"title\":\"admin2\"},{\"id\":\"6\",\"title\":\"admin3\"},{\"id\":\"7\",\"title\":\"admin4\"},{\"id\":\"8\",\"title\":\"admin5\"},{\"id\":\"9\",\"title\":\"admin6\"},{\"id\":\"10\",\"title\":\"admin7\"},{\"id\":\"11\",\"title\":\"admin8\"},{\"id\":\"12\",\"title\":\"admin9\"},{\"id\":\"13\",\"title\":\"admin6\"},{\"id\":\"14\",\"title\":\"admin6\"},{\"id\":\"15\",\"title\":\"admin6\"},{\"id\":\"16\",\"title\":\"admin6\"},{\"id\":\"17\",\"title\":\"admin6\"},{\"id\":\"18\",\"title\":\"admin6\"},{\"id\":\"19\",\"title\":\"admin6\"},{\"id\":\"20\",\"title\":\"admin6\"},{\"id\":\"21\",\"title\":\"admin6\"},{\"id\":\"22\",\"title\":\"admin6\"},{\"id\":\"23\",\"title\":\"admin6\"}]', '127.0.0.1', '1', '2015-06-12 11:12:56');
INSERT INTO `sr_action_log` VALUES ('31', '1', 'admin', 'backend/member/index/disable', 'member', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23', '[{\"id\":\"4\",\"title\":\"admin2\"},{\"id\":\"5\",\"title\":\"admin2\"},{\"id\":\"6\",\"title\":\"admin3\"},{\"id\":\"7\",\"title\":\"admin4\"},{\"id\":\"8\",\"title\":\"admin5\"},{\"id\":\"9\",\"title\":\"admin6\"},{\"id\":\"10\",\"title\":\"admin7\"},{\"id\":\"11\",\"title\":\"admin8\"},{\"id\":\"12\",\"title\":\"admin9\"},{\"id\":\"13\",\"title\":\"admin6\"},{\"id\":\"14\",\"title\":\"admin6\"},{\"id\":\"15\",\"title\":\"admin6\"},{\"id\":\"16\",\"title\":\"admin6\"},{\"id\":\"17\",\"title\":\"admin6\"},{\"id\":\"18\",\"title\":\"admin6\"},{\"id\":\"19\",\"title\":\"admin6\"},{\"id\":\"20\",\"title\":\"admin6\"},{\"id\":\"21\",\"title\":\"admin6\"},{\"id\":\"22\",\"title\":\"admin6\"},{\"id\":\"23\",\"title\":\"admin6\"}]', '127.0.0.1', '1', '2015-06-12 11:13:02');
INSERT INTO `sr_action_log` VALUES ('32', '1', 'admin', 'backend/products/category/disable', 'category', '1', '[{\"id\":\"1\",\"title\":\"\\u6b27\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-12 11:59:25');
INSERT INTO `sr_action_log` VALUES ('33', '1', 'admin', 'backend/products/category/enable', 'category', '1', '[{\"id\":\"1\",\"title\":\"\\u6b27\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-12 11:59:31');
INSERT INTO `sr_action_log` VALUES ('34', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-15 09:31:18');
INSERT INTO `sr_action_log` VALUES ('35', '1', 'admin', 'backend/member/index/del', 'member', '87', '[{\"id\":\"87\",\"title\":\"\"}]', '127.0.0.1', '1', '2015-06-15 09:30:42');
INSERT INTO `sr_action_log` VALUES ('36', '1', 'admin', 'backend/member/index/del', 'member', '88', '[{\"id\":\"88\",\"title\":\"\"}]', '127.0.0.1', '1', '2015-06-15 09:31:54');
INSERT INTO `sr_action_log` VALUES ('37', '1', 'admin', 'backend/member/index/enable', 'member', '67', '[{\"id\":\"67\",\"title\":\"admin6\"}]', '0.0.0.0', '1', '2015-06-15 09:33:37');
INSERT INTO `sr_action_log` VALUES ('38', '1', 'admin', 'backend/member/index/enable', 'member', '7', '[{\"id\":\"7\",\"title\":\"admin4\"}]', '0.0.0.0', '1', '2015-06-15 09:33:43');
INSERT INTO `sr_action_log` VALUES ('39', '1', 'admin', 'backend/member/index/disable', 'member', '7', '[{\"id\":\"7\",\"title\":\"admin4\"}]', '0.0.0.0', '1', '2015-06-15 09:33:44');
INSERT INTO `sr_action_log` VALUES ('40', '1', 'admin', 'backend/products/language/disable', 'category', '1', '[{\"id\":\"1\",\"title\":\"\\u6b27\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-15 15:41:16');
INSERT INTO `sr_action_log` VALUES ('41', '1', 'admin', 'backend/products/language/disable', 'category', '2', '[{\"id\":\"2\",\"title\":\"\\u4e9a\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-15 15:41:33');
INSERT INTO `sr_action_log` VALUES ('42', '1', 'admin', 'backend/products/language/enable', 'category', '1', '[{\"id\":\"1\",\"title\":\"\\u6b27\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-15 15:42:19');
INSERT INTO `sr_action_log` VALUES ('43', '1', 'admin', 'backend/products/language/enable', 'category', '2', '[{\"id\":\"2\",\"title\":\"\\u4e9a\\u6d32\",\"path\":\"-0-\"}]', '0.0.0.0', '1', '2015-06-15 15:42:21');
INSERT INTO `sr_action_log` VALUES ('44', '1', 'admin', 'backend/member/index/del', 'member', '89', '[{\"id\":\"89\",\"title\":\"\"}]', '127.0.0.1', '1', '2015-06-16 09:20:57');
INSERT INTO `sr_action_log` VALUES ('45', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-16 09:25:57');
INSERT INTO `sr_action_log` VALUES ('46', '1', 'admin', 'backend/navigation/index/add', 'navigation', '1', '[{\"id\":\"1\",\"title\":\"\\u9996\\u9875\"}]', '0.0.0.0', '1', '2015-06-16 10:11:56');
INSERT INTO `sr_action_log` VALUES ('47', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '2', '[{\"id\":\"2\",\"title\":\"\\u5e2e\\u52a9\\u4e2d\\u5fc3\"}]', '0.0.0.0', '1', '2015-06-16 10:20:16');
INSERT INTO `sr_action_log` VALUES ('48', '1', 'admin', 'backend/links/index/add', 'navigation', '3', '[{\"id\":\"3\",\"title\":\"\\u6cd5\\u8bed\\u4e4b\\u53cb\\u4e50\\u56ed\"}]', '0.0.0.0', '1', '2015-06-16 11:05:59');
INSERT INTO `sr_action_log` VALUES ('49', '1', 'admin', 'backend/member/index/disable', 'member', '93', '[{\"id\":\"93\",\"title\":\"557f8426e776f\"}]', '127.0.0.1', '1', '2015-06-16 11:15:34');
INSERT INTO `sr_action_log` VALUES ('50', '1', 'admin', 'backend/member/index/enable', 'member', '93', '[{\"id\":\"93\",\"title\":\"557f8426e776f\"}]', '127.0.0.1', '1', '2015-06-16 11:15:36');
INSERT INTO `sr_action_log` VALUES ('51', '1', 'admin', 'backend/member/common/enable', 'member', '4', '[{\"id\":\"4\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-16 11:16:24');
INSERT INTO `sr_action_log` VALUES ('52', '1', 'admin', 'backend/member/common/disable', 'member', '4', '[{\"id\":\"4\",\"title\":\"admin2\"}]', '127.0.0.1', '1', '2015-06-16 11:16:26');
INSERT INTO `sr_action_log` VALUES ('53', '1', 'admin', 'backend/member/common/disable', 'member', '91', '[{\"id\":\"91\",\"title\":\"557f83c9175ad\"}]', '127.0.0.1', '1', '2015-06-16 11:16:27');
INSERT INTO `sr_action_log` VALUES ('54', '1', 'admin', 'backend/member/index/disable', 'member', '92', '[{\"id\":\"92\",\"title\":\"557f8402c5c06\"}]', '127.0.0.1', '1', '2015-06-16 11:37:10');
INSERT INTO `sr_action_log` VALUES ('55', '1', 'admin', 'backend/member/index/disable', 'member', '93', '[{\"id\":\"93\",\"title\":\"557f8426e776f\"}]', '127.0.0.1', '1', '2015-06-16 11:37:11');
INSERT INTO `sr_action_log` VALUES ('56', '1', 'admin', 'backend/member/index/disable', 'member', '90', '[{\"id\":\"90\",\"title\":\"557f79f262f50\"}]', '127.0.0.1', '1', '2015-06-16 11:37:15');
INSERT INTO `sr_action_log` VALUES ('57', '1', 'admin', 'backend/member/index/enable', 'member', '93', '[{\"id\":\"93\",\"title\":\"557f8426e776f\"}]', '127.0.0.1', '1', '2015-06-16 11:38:34');
INSERT INTO `sr_action_log` VALUES ('58', '1', 'admin', 'backend/member/index/enable', 'member', '92', '[{\"id\":\"92\",\"title\":\"557f8402c5c06\"}]', '127.0.0.1', '1', '2015-06-16 11:38:35');
INSERT INTO `sr_action_log` VALUES ('59', '1', 'admin', 'backend/member/index/disable', 'member', '93', '[{\"id\":\"93\",\"title\":\"557f8426e776f\"}]', '127.0.0.1', '1', '2015-06-16 11:38:38');
INSERT INTO `sr_action_log` VALUES ('60', '1', 'admin', 'backend/member/index/disable', 'member', '92', '[{\"id\":\"92\",\"title\":\"557f8402c5c06\"}]', '127.0.0.1', '1', '2015-06-16 11:38:39');
INSERT INTO `sr_action_log` VALUES ('61', '1', 'admin', 'backend/member/common/enable', 'member', '91', '[{\"id\":\"91\",\"title\":\"557f83c9175ad\"}]', '127.0.0.1', '1', '2015-06-16 11:38:43');
INSERT INTO `sr_action_log` VALUES ('62', '1', 'admin', 'backend/member/common/disable', 'member', '91', '[{\"id\":\"91\",\"title\":\"557f83c9175ad\"}]', '127.0.0.1', '1', '2015-06-16 11:38:45');
INSERT INTO `sr_action_log` VALUES ('63', '1', 'admin', 'backend/member/level/disable', 'level', '4', '[{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:38:52');
INSERT INTO `sr_action_log` VALUES ('64', '1', 'admin', 'backend/member/level/disable', 'level', '4', '[{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:38:52');
INSERT INTO `sr_action_log` VALUES ('65', '1', 'admin', 'backend/member/level/disable', 'level', '5', '[{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:38:59');
INSERT INTO `sr_action_log` VALUES ('66', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:39:33');
INSERT INTO `sr_action_log` VALUES ('67', '1', 'admin', 'backend/member/level/disable', 'level', '2', '[{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:39:36');
INSERT INTO `sr_action_log` VALUES ('68', '1', 'admin', 'backend/member/level/disable', 'level', '3', '[{\"id\":\"3\",\"title\":\"\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:39:46');
INSERT INTO `sr_action_log` VALUES ('69', '1', 'admin', 'backend/member/level/disable', 'level', '4', '[{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:39:48');
INSERT INTO `sr_action_log` VALUES ('70', '1', 'admin', 'backend/member/level/disable', 'level', '5', '[{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:42:36');
INSERT INTO `sr_action_log` VALUES ('71', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:48:11');
INSERT INTO `sr_action_log` VALUES ('72', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:49:35');
INSERT INTO `sr_action_log` VALUES ('73', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:49:39');
INSERT INTO `sr_action_log` VALUES ('74', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:11');
INSERT INTO `sr_action_log` VALUES ('75', '1', 'admin', 'backend/member/level/disable', 'level', '5', '[{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:16');
INSERT INTO `sr_action_log` VALUES ('76', '1', 'admin', 'backend/member/level/disable', 'level', '4', '[{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:19');
INSERT INTO `sr_action_log` VALUES ('77', '1', 'admin', 'backend/member/level/disable', 'level', '3', '[{\"id\":\"3\",\"title\":\"\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:20');
INSERT INTO `sr_action_log` VALUES ('78', '1', 'admin', 'backend/member/level/disable', 'level', '2', '[{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:22');
INSERT INTO `sr_action_log` VALUES ('79', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:24');
INSERT INTO `sr_action_log` VALUES ('80', '1', 'admin', 'backend/member/level/enable', 'level', '1,2,3,4,5', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"},{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"},{\"id\":\"3\",\"title\":\"\\u4e09\\u7ea7\"},{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"},{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:29');
INSERT INTO `sr_action_log` VALUES ('81', '1', 'admin', 'backend/member/level/disable', 'level', '1,2,3,4,5', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"},{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"},{\"id\":\"3\",\"title\":\"\\u4e09\\u7ea7\"},{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"},{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 11:50:34');
INSERT INTO `sr_action_log` VALUES ('82', '1', 'admin', 'backend/base/area/disable', 'area', '1', '[{\"id\":\"1\",\"title\":\"\\u5317\\u4eac\"}]', '0.0.0.0', '1', '2015-06-16 12:00:00');
INSERT INTO `sr_action_log` VALUES ('83', '1', 'admin', 'backend/base/area/enable', 'area', '1', '[{\"id\":\"1\",\"title\":\"\\u5317\\u4eac\"}]', '0.0.0.0', '1', '2015-06-16 12:00:10');
INSERT INTO `sr_action_log` VALUES ('84', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 13:34:16');
INSERT INTO `sr_action_log` VALUES ('85', '1', 'admin', 'backend/member/level/enable', 'level', '2', '[{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 13:34:18');
INSERT INTO `sr_action_log` VALUES ('86', '1', 'admin', 'backend/member/level/disable', 'level', '2', '[{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 13:34:20');
INSERT INTO `sr_action_log` VALUES ('87', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 13:34:22');
INSERT INTO `sr_action_log` VALUES ('88', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:23:26');
INSERT INTO `sr_action_log` VALUES ('89', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:23:28');
INSERT INTO `sr_action_log` VALUES ('90', '1', 'admin', 'backend/member/level/disable', 'level', '6', '[{\"id\":\"6\",\"title\":\"\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:24:07');
INSERT INTO `sr_action_log` VALUES ('91', '1', 'admin', 'backend/member/level/enable', 'level', '6', '[{\"id\":\"6\",\"title\":\"\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:24:11');
INSERT INTO `sr_action_log` VALUES ('92', '1', 'admin', 'backend/member/level/disable', 'level', '6', '[{\"id\":\"6\",\"title\":\"\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:24:14');
INSERT INTO `sr_action_log` VALUES ('93', '1', 'admin', 'backend/member/level/enable', 'level', '6,1,2,3,4,5', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"},{\"id\":\"2\",\"title\":\"\\u4e8c\\u7ea7\"},{\"id\":\"3\",\"title\":\"\\u4e09\\u7ea7\"},{\"id\":\"4\",\"title\":\"\\u56db\\u7ea7\"},{\"id\":\"5\",\"title\":\"\\u4e94\\u7ea7\"},{\"id\":\"6\",\"title\":\"\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-16 14:24:20');
INSERT INTO `sr_action_log` VALUES ('94', '1', 'admin', 'backend/contents/help/enable', 'article', '59', '[{\"id\":\"59\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u5c31\\u662f\"}]', '127.0.0.1', '1', '2015-06-16 16:04:09');
INSERT INTO `sr_action_log` VALUES ('95', '1', 'admin', 'backend/contents/help/enable', 'article', '60', '[{\"id\":\"60\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-16 16:04:12');
INSERT INTO `sr_action_log` VALUES ('96', '1', 'admin', 'backend/contents/help/enable', 'article', '61', '[{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-16 16:04:14');
INSERT INTO `sr_action_log` VALUES ('97', '1', 'admin', 'backend/contents/help/enable', 'article', '62', '[{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-16 16:04:17');
INSERT INTO `sr_action_log` VALUES ('98', '1', 'admin', 'backend/contents/help/enable', 'article', '59,60,61,62', '[{\"id\":\"59\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u5c31\\u662f\"},{\"id\":\"60\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-16 16:04:22');
INSERT INTO `sr_action_log` VALUES ('99', '1', 'admin', 'backend/contents/help/disable', 'article', '59,60,61,62', '[{\"id\":\"59\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u5c31\\u662f\"},{\"id\":\"60\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-16 16:04:26');
INSERT INTO `sr_action_log` VALUES ('100', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-16 16:35:49');
INSERT INTO `sr_action_log` VALUES ('101', '1', 'admin', 'backend/contents/help/del', 'article', '59', '[{\"id\":\"59\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u5c31\\u662f\"}]', '127.0.0.1', '1', '2015-06-17 09:07:11');
INSERT INTO `sr_action_log` VALUES ('102', '1', 'admin', 'backend/contents/help/del', 'article', '60', '[{\"id\":\"60\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 09:07:15');
INSERT INTO `sr_action_log` VALUES ('103', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-17 09:14:24');
INSERT INTO `sr_action_log` VALUES ('104', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-17 09:20:37');
INSERT INTO `sr_action_log` VALUES ('105', '1', 'admin', 'backend/shop/shop/setrec', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 10:57:45');
INSERT INTO `sr_action_log` VALUES ('106', '1', 'admin', 'backend/shop/shop/setrec', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 10:57:54');
INSERT INTO `sr_action_log` VALUES ('107', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 11:01:45');
INSERT INTO `sr_action_log` VALUES ('108', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 11:04:06');
INSERT INTO `sr_action_log` VALUES ('109', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 11:04:09');
INSERT INTO `sr_action_log` VALUES ('110', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 11:11:33');
INSERT INTO `sr_action_log` VALUES ('111', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 11:11:38');
INSERT INTO `sr_action_log` VALUES ('112', '1', 'admin', 'backend/contents/notice/disable', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 11:48:34');
INSERT INTO `sr_action_log` VALUES ('113', '1', 'admin', 'backend/contents/notice/enable', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 11:48:39');
INSERT INTO `sr_action_log` VALUES ('114', '1', 'admin', 'backend/contents/notice/disable', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 11:48:43');
INSERT INTO `sr_action_log` VALUES ('115', '1', 'admin', 'backend/contents/notice/enable', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 11:48:49');
INSERT INTO `sr_action_log` VALUES ('116', '1', 'admin', 'backend/contents/notice/enable', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 11:48:52');
INSERT INTO `sr_action_log` VALUES ('117', '1', 'admin', 'backend/contents/picture/enable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 11:50:26');
INSERT INTO `sr_action_log` VALUES ('118', '1', 'admin', 'backend/contents/picture/disable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 11:50:29');
INSERT INTO `sr_action_log` VALUES ('119', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 11:50:38');
INSERT INTO `sr_action_log` VALUES ('120', '1', 'admin', 'backend/contents/picture/enable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 11:50:38');
INSERT INTO `sr_action_log` VALUES ('121', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 11:50:39');
INSERT INTO `sr_action_log` VALUES ('122', '1', 'admin', 'backend/contents/picture/enable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 11:50:47');
INSERT INTO `sr_action_log` VALUES ('123', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 13:35:54');
INSERT INTO `sr_action_log` VALUES ('124', '1', 'admin', 'backend/contents/picture/disable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 13:35:56');
INSERT INTO `sr_action_log` VALUES ('125', '1', 'admin', 'backend/contents/picture/enable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 13:35:59');
INSERT INTO `sr_action_log` VALUES ('126', '1', 'admin', 'backend/contents/picture/enable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 13:36:01');
INSERT INTO `sr_action_log` VALUES ('127', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 13:48:04');
INSERT INTO `sr_action_log` VALUES ('128', '1', 'admin', 'backend/contents/picture/disable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 13:48:06');
INSERT INTO `sr_action_log` VALUES ('129', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1,2', '[{\"id\":\"1\",\"type\":\"2\"},{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 13:48:10');
INSERT INTO `sr_action_log` VALUES ('130', '1', 'admin', 'backend/contents/picture/del', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 13:48:15');
INSERT INTO `sr_action_log` VALUES ('131', '1', 'admin', 'backend/contents/help/enable', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-17 14:00:16');
INSERT INTO `sr_action_log` VALUES ('132', '1', 'admin', 'backend/contents/help/disable', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-17 14:00:20');
INSERT INTO `sr_action_log` VALUES ('133', '1', 'admin', 'backend/contents/help/disable', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:00:22');
INSERT INTO `sr_action_log` VALUES ('134', '1', 'admin', 'backend/contents/help/disable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:00:24');
INSERT INTO `sr_action_log` VALUES ('135', '1', 'admin', 'backend/contents/help/enable', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:00:26');
INSERT INTO `sr_action_log` VALUES ('136', '1', 'admin', 'backend/contents/help/disable', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:00:28');
INSERT INTO `sr_action_log` VALUES ('137', '1', 'admin', 'backend/contents/help/enable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:20');
INSERT INTO `sr_action_log` VALUES ('138', '1', 'admin', 'backend/contents/help/disable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:24');
INSERT INTO `sr_action_log` VALUES ('139', '1', 'admin', 'backend/contents/help/enable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:26');
INSERT INTO `sr_action_log` VALUES ('140', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:29');
INSERT INTO `sr_action_log` VALUES ('141', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:32');
INSERT INTO `sr_action_log` VALUES ('142', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:37');
INSERT INTO `sr_action_log` VALUES ('143', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:39');
INSERT INTO `sr_action_log` VALUES ('144', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:41');
INSERT INTO `sr_action_log` VALUES ('145', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:47');
INSERT INTO `sr_action_log` VALUES ('146', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:17:48');
INSERT INTO `sr_action_log` VALUES ('147', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:21:17');
INSERT INTO `sr_action_log` VALUES ('148', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:24:26');
INSERT INTO `sr_action_log` VALUES ('149', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:24:30');
INSERT INTO `sr_action_log` VALUES ('150', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:24:33');
INSERT INTO `sr_action_log` VALUES ('151', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:25:27');
INSERT INTO `sr_action_log` VALUES ('152', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:25:31');
INSERT INTO `sr_action_log` VALUES ('153', '1', 'admin', 'backend/contents/help/recommend', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-17 14:25:33');
INSERT INTO `sr_action_log` VALUES ('154', '1', 'admin', 'backend/contents/help/recommend', 'article', '61', '[{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:25:36');
INSERT INTO `sr_action_log` VALUES ('155', '1', 'admin', 'backend/contents/help/recommend', 'article', '62', '[{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:25:39');
INSERT INTO `sr_action_log` VALUES ('156', '1', 'admin', 'backend/contents/help/recommend', 'article', '62', '[{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:25:41');
INSERT INTO `sr_action_log` VALUES ('157', '1', 'admin', 'backend/contents/help/recommend', 'article', '61', '[{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:25:44');
INSERT INTO `sr_action_log` VALUES ('158', '1', 'admin', 'backend/contents/help/recommend', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-17 14:25:45');
INSERT INTO `sr_action_log` VALUES ('159', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:25:47');
INSERT INTO `sr_action_log` VALUES ('160', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:25:51');
INSERT INTO `sr_action_log` VALUES ('161', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:25:53');
INSERT INTO `sr_action_log` VALUES ('162', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:25:55');
INSERT INTO `sr_action_log` VALUES ('163', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:25:59');
INSERT INTO `sr_action_log` VALUES ('164', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:26:03');
INSERT INTO `sr_action_log` VALUES ('165', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:28:52');
INSERT INTO `sr_action_log` VALUES ('166', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:29:04');
INSERT INTO `sr_action_log` VALUES ('167', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:29:40');
INSERT INTO `sr_action_log` VALUES ('168', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:29:43');
INSERT INTO `sr_action_log` VALUES ('169', '1', 'admin', 'backend/contents/help/recommend', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-17 14:29:45');
INSERT INTO `sr_action_log` VALUES ('170', '1', 'admin', 'backend/contents/help/recommend', 'article', '61', '[{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:29:48');
INSERT INTO `sr_action_log` VALUES ('171', '1', 'admin', 'backend/contents/help/recommend', 'article', '62', '[{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:29:50');
INSERT INTO `sr_action_log` VALUES ('172', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:29:53');
INSERT INTO `sr_action_log` VALUES ('173', '1', 'admin', 'backend/contents/help/enable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:29:56');
INSERT INTO `sr_action_log` VALUES ('174', '1', 'admin', 'backend/contents/help/enable', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:29:58');
INSERT INTO `sr_action_log` VALUES ('175', '1', 'admin', 'backend/contents/help/disable', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:30:00');
INSERT INTO `sr_action_log` VALUES ('176', '1', 'admin', 'backend/contents/help/disable', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-17 14:30:29');
INSERT INTO `sr_action_log` VALUES ('177', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:31');
INSERT INTO `sr_action_log` VALUES ('178', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:33');
INSERT INTO `sr_action_log` VALUES ('179', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:35');
INSERT INTO `sr_action_log` VALUES ('180', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:37');
INSERT INTO `sr_action_log` VALUES ('181', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:41');
INSERT INTO `sr_action_log` VALUES ('182', '1', 'admin', 'backend/contents/notice/recommend', 'article', '58', '[{\"id\":\"58\",\"title\":\"123111\"}]', '127.0.0.1', '1', '2015-06-17 14:33:43');
INSERT INTO `sr_action_log` VALUES ('183', '1', 'admin', 'backend/contents/news/recommend', 'article', '8', '[{\"id\":\"8\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:35:50');
INSERT INTO `sr_action_log` VALUES ('184', '1', 'admin', 'backend/contents/news/recommend', 'article', '3', '[{\"id\":\"3\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:35:54');
INSERT INTO `sr_action_log` VALUES ('185', '1', 'admin', 'backend/contents/news/recommend', 'article', '5', '[{\"id\":\"5\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:35:57');
INSERT INTO `sr_action_log` VALUES ('186', '1', 'admin', 'backend/contents/news/recommend', 'article', '1', '[{\"id\":\"1\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:36:01');
INSERT INTO `sr_action_log` VALUES ('187', '1', 'admin', 'backend/contents/news/recommend', 'article', '22', '[{\"id\":\"22\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:36:14');
INSERT INTO `sr_action_log` VALUES ('188', '1', 'admin', 'backend/contents/activity/recommend', 'article', '69', '[{\"id\":\"69\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:37:34');
INSERT INTO `sr_action_log` VALUES ('189', '1', 'admin', 'backend/contents/activity/recommend', 'article', '69', '[{\"id\":\"69\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:37:39');
INSERT INTO `sr_action_log` VALUES ('190', '1', 'admin', 'backend/contents/activity/recommend', 'article', '69', '[{\"id\":\"69\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-17 14:37:39');
INSERT INTO `sr_action_log` VALUES ('191', '1', 'admin', 'backend/contents/news/disable', 'article', '53', '[{\"id\":\"53\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 14:38:29');
INSERT INTO `sr_action_log` VALUES ('192', '1', 'admin', 'backend/contents/picture/del', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 15:09:03');
INSERT INTO `sr_action_log` VALUES ('193', '1', 'admin', 'backend/contents/picture/enable', 'resources', '1,2', '[{\"id\":\"1\",\"type\":\"2\"},{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 15:15:36');
INSERT INTO `sr_action_log` VALUES ('194', '1', 'admin', 'backend/contents/news/enable', 'article', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20', '[{\"id\":\"1\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"2\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"3\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"4\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"5\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"6\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"7\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"8\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"9\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"10\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"11\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"12\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"13\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"14\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"15\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"16\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"17\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"18\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"19\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"20\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 15:16:15');
INSERT INTO `sr_action_log` VALUES ('195', '1', 'admin', 'backend/contents/news/disable', 'article', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20', '[{\"id\":\"1\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"2\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"3\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"4\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"5\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"6\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"7\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"8\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"9\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"10\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"11\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"12\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"13\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"14\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"15\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"16\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"17\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"18\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"19\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"20\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 15:16:30');
INSERT INTO `sr_action_log` VALUES ('196', '1', 'admin', 'backend/contents/news/disable', 'article', '41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57', '[{\"id\":\"41\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"42\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"43\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"44\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"45\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"46\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"47\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"48\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"49\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"50\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"51\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"52\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"53\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"54\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"55\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"56\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"},{\"id\":\"57\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-17 15:17:11');
INSERT INTO `sr_action_log` VALUES ('197', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"2\"}]', '127.0.0.1', '1', '2015-06-17 15:17:32');
INSERT INTO `sr_action_log` VALUES ('198', '1', 'admin', 'backend/contents/picture/disable', 'resources', '2', '[{\"id\":\"2\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-17 15:17:34');
INSERT INTO `sr_action_log` VALUES ('199', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 15:23:42');
INSERT INTO `sr_action_log` VALUES ('200', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '1', '[{\"id\":\"1\",\"title\":\"\\u5e97\\u94fa\\u4e00\"}]', '0.0.0.0', '1', '2015-06-17 15:23:46');
INSERT INTO `sr_action_log` VALUES ('201', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 15:23:51');
INSERT INTO `sr_action_log` VALUES ('202', '1', 'admin', 'backend/shop/pendingshop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 17:16:31');
INSERT INTO `sr_action_log` VALUES ('203', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 17:16:36');
INSERT INTO `sr_action_log` VALUES ('204', '1', 'admin', 'backend/shop/pendingshop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 17:16:42');
INSERT INTO `sr_action_log` VALUES ('205', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-06-17 17:16:51');
INSERT INTO `sr_action_log` VALUES ('206', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-18 09:13:23');
INSERT INTO `sr_action_log` VALUES ('207', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-18 16:00:15');
INSERT INTO `sr_action_log` VALUES ('208', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-19 09:36:30');
INSERT INTO `sr_action_log` VALUES ('209', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-19 15:21:16');
INSERT INTO `sr_action_log` VALUES ('210', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:37');
INSERT INTO `sr_action_log` VALUES ('211', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:41');
INSERT INTO `sr_action_log` VALUES ('212', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:43');
INSERT INTO `sr_action_log` VALUES ('213', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:44');
INSERT INTO `sr_action_log` VALUES ('214', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:44');
INSERT INTO `sr_action_log` VALUES ('215', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:44');
INSERT INTO `sr_action_log` VALUES ('216', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:44');
INSERT INTO `sr_action_log` VALUES ('217', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:45');
INSERT INTO `sr_action_log` VALUES ('218', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:08:49');
INSERT INTO `sr_action_log` VALUES ('219', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:13:41');
INSERT INTO `sr_action_log` VALUES ('220', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:13:44');
INSERT INTO `sr_action_log` VALUES ('221', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:20:55');
INSERT INTO `sr_action_log` VALUES ('222', '1', 'admin', 'backend/comment/list/enable', 'comment', '2', '[{\"id\":\"2\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:20:57');
INSERT INTO `sr_action_log` VALUES ('223', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:45:10');
INSERT INTO `sr_action_log` VALUES ('224', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:45:12');
INSERT INTO `sr_action_log` VALUES ('225', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:45:15');
INSERT INTO `sr_action_log` VALUES ('226', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:45:32');
INSERT INTO `sr_action_log` VALUES ('227', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:45:36');
INSERT INTO `sr_action_log` VALUES ('228', '1', 'admin', 'backend/comment/list/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:46:59');
INSERT INTO `sr_action_log` VALUES ('229', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 17:47:02');
INSERT INTO `sr_action_log` VALUES ('230', '1', 'admin', 'backend/comment/new/disable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:02:40');
INSERT INTO `sr_action_log` VALUES ('231', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:05:41');
INSERT INTO `sr_action_log` VALUES ('232', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:05:44');
INSERT INTO `sr_action_log` VALUES ('233', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:06:22');
INSERT INTO `sr_action_log` VALUES ('234', '1', 'admin', 'backend/comment/new/enable', 'comment', '1,1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:08:35');
INSERT INTO `sr_action_log` VALUES ('235', '1', 'admin', 'backend/comment/new/enable', 'comment', '1,1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-19 18:09:22');
INSERT INTO `sr_action_log` VALUES ('236', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 08:56:26');
INSERT INTO `sr_action_log` VALUES ('237', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 08:56:31');
INSERT INTO `sr_action_log` VALUES ('238', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 08:56:40');
INSERT INTO `sr_action_log` VALUES ('239', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', '[{\"id\":\"1\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:21:00');
INSERT INTO `sr_action_log` VALUES ('240', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:23:39');
INSERT INTO `sr_action_log` VALUES ('241', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:24:23');
INSERT INTO `sr_action_log` VALUES ('242', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:26:33');
INSERT INTO `sr_action_log` VALUES ('243', '1', 'admin', 'backend/comment/new/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:26:36');
INSERT INTO `sr_action_log` VALUES ('244', '1', 'admin', 'backend/comment/new/enable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:28:05');
INSERT INTO `sr_action_log` VALUES ('245', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:42:23');
INSERT INTO `sr_action_log` VALUES ('246', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:42:26');
INSERT INTO `sr_action_log` VALUES ('247', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:42:28');
INSERT INTO `sr_action_log` VALUES ('248', '1', 'admin', 'backend/comment/list/enable', 'comment', '1', 'null', '127.0.0.1', '1', '2015-06-23 09:42:30');
INSERT INTO `sr_action_log` VALUES ('249', '1', 'admin', 'backend/comment/list/enable', 'comment', '3', '[{\"id\":\"3\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:43:54');
INSERT INTO `sr_action_log` VALUES ('250', '1', 'admin', 'backend/comment/list/enable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:43:56');
INSERT INTO `sr_action_log` VALUES ('251', '1', 'admin', 'backend/comment/list/disable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:43:57');
INSERT INTO `sr_action_log` VALUES ('252', '1', 'admin', 'backend/comment/list/disable', 'comment', '3', '[{\"id\":\"3\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:44:00');
INSERT INTO `sr_action_log` VALUES ('253', '1', 'admin', 'backend/comment/new/enable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:44:37');
INSERT INTO `sr_action_log` VALUES ('254', '1', 'admin', 'backend/comment/list/enable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:43');
INSERT INTO `sr_action_log` VALUES ('255', '1', 'admin', 'backend/comment/list/enable', 'comment', '5', '[{\"id\":\"5\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:45');
INSERT INTO `sr_action_log` VALUES ('256', '1', 'admin', 'backend/comment/list/enable', 'comment', '6', '[{\"id\":\"6\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:47');
INSERT INTO `sr_action_log` VALUES ('257', '1', 'admin', 'backend/comment/list/disable', 'comment', '6', '[{\"id\":\"6\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:49');
INSERT INTO `sr_action_log` VALUES ('258', '1', 'admin', 'backend/comment/list/disable', 'comment', '5', '[{\"id\":\"5\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:51');
INSERT INTO `sr_action_log` VALUES ('259', '1', 'admin', 'backend/comment/list/disable', 'comment', '4', '[{\"id\":\"4\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:52');
INSERT INTO `sr_action_log` VALUES ('260', '1', 'admin', 'backend/comment/list/enable', 'comment', '5', '[{\"id\":\"5\",\"product_id\":\"1\"}]', '127.0.0.1', '1', '2015-06-23 09:46:54');
INSERT INTO `sr_action_log` VALUES ('261', '1', 'admin', 'backend/base/languagelevel/disable', 'level', '9', '[{\"id\":\"9\",\"title\":\"\\u9ad8\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:07:32');
INSERT INTO `sr_action_log` VALUES ('262', '1', 'admin', 'backend/base/languagelevel/disable', 'level', '8', '[{\"id\":\"8\",\"title\":\"\\u4e2d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:07:34');
INSERT INTO `sr_action_log` VALUES ('263', '1', 'admin', 'backend/base/languagelevel/disable', 'level', '7', '[{\"id\":\"7\",\"title\":\"\\u666e\\u901a\"}]', '127.0.0.1', '1', '2015-06-23 14:07:35');
INSERT INTO `sr_action_log` VALUES ('264', '1', 'admin', 'backend/base/languagelevel/enable', 'level', '8', '[{\"id\":\"8\",\"title\":\"\\u4e2d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:07:37');
INSERT INTO `sr_action_log` VALUES ('265', '1', 'admin', 'backend/base/languagelevel/enable', 'level', '9', '[{\"id\":\"9\",\"title\":\"\\u9ad8\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:07:38');
INSERT INTO `sr_action_log` VALUES ('266', '1', 'admin', 'backend/base/languagelevel/enable', 'level', '7', '[{\"id\":\"7\",\"title\":\"\\u666e\\u901a\"}]', '127.0.0.1', '1', '2015-06-23 14:07:39');
INSERT INTO `sr_action_log` VALUES ('267', '1', 'admin', 'backend/base/languagelevel/del', 'level', '10', '[{\"id\":\"10\",\"title\":\"VIP\"}]', '127.0.0.1', '1', '2015-06-23 14:13:39');
INSERT INTO `sr_action_log` VALUES ('268', '1', 'admin', 'backend/base/englishlevel/disable', 'level', '14', '[{\"id\":\"14\",\"title\":\"\\u4e13\\u4e1a\\u82f1\\u8bed\\u516b\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:27:11');
INSERT INTO `sr_action_log` VALUES ('269', '1', 'admin', 'backend/base/englishlevel/disable', 'level', '13', '[{\"id\":\"13\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:27:12');
INSERT INTO `sr_action_log` VALUES ('270', '1', 'admin', 'backend/base/englishlevel/disable', 'level', '12', '[{\"id\":\"12\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:27:15');
INSERT INTO `sr_action_log` VALUES ('271', '1', 'admin', 'backend/base/englishlevel/disable', 'level', '11', '[{\"id\":\"11\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:27:17');
INSERT INTO `sr_action_log` VALUES ('272', '1', 'admin', 'backend/base/englishlevel/enable', 'level', '11', '[{\"id\":\"11\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:27:19');
INSERT INTO `sr_action_log` VALUES ('273', '1', 'admin', 'backend/base/englishlevel/disable', 'level', '11', '[{\"id\":\"11\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:29:58');
INSERT INTO `sr_action_log` VALUES ('274', '1', 'admin', 'backend/base/englishlevel/enable', 'level', '11', '[{\"id\":\"11\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u4e09\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:30:00');
INSERT INTO `sr_action_log` VALUES ('275', '1', 'admin', 'backend/base/englishlevel/enable', 'level', '12', '[{\"id\":\"12\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u56db\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:30:02');
INSERT INTO `sr_action_log` VALUES ('276', '1', 'admin', 'backend/base/englishlevel/enable', 'level', '13', '[{\"id\":\"13\",\"title\":\"\\u5927\\u5b66\\u82f1\\u8bed\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:30:03');
INSERT INTO `sr_action_log` VALUES ('277', '1', 'admin', 'backend/base/englishlevel/enable', 'level', '14', '[{\"id\":\"14\",\"title\":\"\\u4e13\\u4e1a\\u82f1\\u8bed\\u516b\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 14:30:04');
INSERT INTO `sr_action_log` VALUES ('278', '1', 'admin', 'backend/base/creditlevel/disable', 'level', '18', '[{\"id\":\"18\",\"title\":\"\\u56db\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:14');
INSERT INTO `sr_action_log` VALUES ('279', '1', 'admin', 'backend/base/creditlevel/disable', 'level', '17', '[{\"id\":\"17\",\"title\":\"\\u4e09\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:16');
INSERT INTO `sr_action_log` VALUES ('280', '1', 'admin', 'backend/base/creditlevel/disable', 'level', '16', '[{\"id\":\"16\",\"title\":\"\\u4e8c\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:17');
INSERT INTO `sr_action_log` VALUES ('281', '1', 'admin', 'backend/base/creditlevel/disable', 'level', '15', '[{\"id\":\"15\",\"title\":\"\\u4e00\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:19');
INSERT INTO `sr_action_log` VALUES ('282', '1', 'admin', 'backend/base/creditlevel/enable', 'level', '15', '[{\"id\":\"15\",\"title\":\"\\u4e00\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:20');
INSERT INTO `sr_action_log` VALUES ('283', '1', 'admin', 'backend/base/creditlevel/enable', 'level', '16', '[{\"id\":\"16\",\"title\":\"\\u4e8c\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:21');
INSERT INTO `sr_action_log` VALUES ('284', '1', 'admin', 'backend/base/creditlevel/enable', 'level', '17', '[{\"id\":\"17\",\"title\":\"\\u4e09\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:22');
INSERT INTO `sr_action_log` VALUES ('285', '1', 'admin', 'backend/base/creditlevel/enable', 'level', '18', '[{\"id\":\"18\",\"title\":\"\\u56db\\u661f\"}]', '127.0.0.1', '1', '2015-06-23 14:47:24');
INSERT INTO `sr_action_log` VALUES ('286', '1', 'admin', 'backend/base/limitwords/disable', 'level', '22', '[{\"id\":\"22\",\"title\":\"\\u64cd\"}]', '127.0.0.1', '1', '2015-06-23 15:03:39');
INSERT INTO `sr_action_log` VALUES ('287', '1', 'admin', 'backend/base/limitwords/enable', 'level', '22', '[{\"id\":\"22\",\"title\":\"\\u64cd\"}]', '127.0.0.1', '1', '2015-06-23 15:03:41');
INSERT INTO `sr_action_log` VALUES ('288', '1', 'admin', 'backend/member/level/disable', 'level', '6', '[{\"id\":\"6\",\"title\":\"\\u516d\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 15:14:04');
INSERT INTO `sr_action_log` VALUES ('289', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 15:14:06');
INSERT INTO `sr_action_log` VALUES ('290', '1', 'admin', 'backend/member/level/enable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 15:14:08');
INSERT INTO `sr_action_log` VALUES ('291', '1', 'admin', 'backend/member/level/disable', 'level', '1', '[{\"id\":\"1\",\"title\":\"\\u4e00\\u7ea7\"}]', '127.0.0.1', '1', '2015-06-23 15:14:09');
INSERT INTO `sr_action_log` VALUES ('292', '1', 'admin', 'backend/shop/theme/disable', 'theme', '1', '[{\"id\":\"1\",\"title\":\"\\u9b54\\u5e7b\\u73b0\\u5b9e\\u4e3b\\u4e49\"}]', '127.0.0.1', '1', '2015-06-23 16:41:56');
INSERT INTO `sr_action_log` VALUES ('293', '1', 'admin', 'backend/shop/theme/enable', 'theme', '1', '[{\"id\":\"1\",\"title\":\"\\u9b54\\u5e7b\\u73b0\\u5b9e\\u4e3b\\u4e49\"}]', '127.0.0.1', '1', '2015-06-23 16:41:58');
INSERT INTO `sr_action_log` VALUES ('294', '1', 'admin', 'backend/shop/theme/disable', 'theme', '1', '[{\"id\":\"1\",\"title\":\"\\u9b54\\u5e7b\\u73b0\\u5b9e\\u4e3b\\u4e49\"}]', '127.0.0.1', '1', '2015-06-23 16:42:04');
INSERT INTO `sr_action_log` VALUES ('295', '1', 'admin', 'backend/shop/theme/disable', 'theme', '1', '[{\"id\":\"1\",\"title\":\"\\u9b54\\u5e7b\\u73b0\\u5b9e\\u4e3b\\u4e49\"}]', '127.0.0.1', '1', '2015-06-23 16:42:05');
INSERT INTO `sr_action_log` VALUES ('296', '1', 'admin', 'backend/shop/theme/del', 'theme', '2', '[{\"id\":\"2\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-23 16:42:45');
INSERT INTO `sr_action_log` VALUES ('297', '1', 'admin', 'backend/shop/theme/del', 'theme', '8', '[{\"id\":\"8\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-23 17:52:22');
INSERT INTO `sr_action_log` VALUES ('298', '1', 'admin', 'backend/shop/theme/del', 'theme', '6', '[{\"id\":\"6\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-23 17:52:29');
INSERT INTO `sr_action_log` VALUES ('299', '1', 'admin', 'backend/shop/theme/del', 'theme', '5', '[{\"id\":\"5\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-23 17:52:33');
INSERT INTO `sr_action_log` VALUES ('300', '1', 'admin', 'backend/shop/theme/del', 'theme', '4', '[{\"id\":\"4\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-23 17:52:36');
INSERT INTO `sr_action_log` VALUES ('301', '1', 'admin', 'backend/shop/theme/del', 'theme', '3', '[{\"id\":\"3\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-23 17:52:38');
INSERT INTO `sr_action_log` VALUES ('302', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-24 09:41:50');
INSERT INTO `sr_action_log` VALUES ('303', '1', 'admin', 'backend/contents/help/recommend', 'article', '68', '[{\"id\":\"68\",\"title\":\"Police descend on Baltimore to enforce curfew after riots\"}]', '127.0.0.1', '1', '2015-06-24 09:41:52');
INSERT INTO `sr_action_log` VALUES ('304', '1', 'admin', 'backend/contents/help/recommend', 'article', '67', '[{\"id\":\"67\",\"title\":\"ssss\"}]', '127.0.0.1', '1', '2015-06-24 09:41:54');
INSERT INTO `sr_action_log` VALUES ('305', '1', 'admin', 'backend/contents/help/recommend', 'article', '66', '[{\"id\":\"66\",\"title\":\"China hits out as Abe visits the US\"}]', '127.0.0.1', '1', '2015-06-24 09:41:55');
INSERT INTO `sr_action_log` VALUES ('306', '1', 'admin', 'backend/contents/help/recommend', 'article', '61', '[{\"id\":\"61\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:41:59');
INSERT INTO `sr_action_log` VALUES ('307', '1', 'admin', 'backend/contents/help/recommend', 'article', '62', '[{\"id\":\"62\",\"title\":\"8776PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:42:01');
INSERT INTO `sr_action_log` VALUES ('308', '1', 'admin', 'backend/contents/news/recommend', 'article', '1', '[{\"id\":\"1\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:42:08');
INSERT INTO `sr_action_log` VALUES ('309', '1', 'admin', 'backend/contents/news/recommend', 'article', '2', '[{\"id\":\"2\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:42:10');
INSERT INTO `sr_action_log` VALUES ('310', '1', 'admin', 'backend/contents/news/recommend', 'article', '3', '[{\"id\":\"3\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:42:13');
INSERT INTO `sr_action_log` VALUES ('311', '1', 'admin', 'backend/contents/news/recommend', 'article', '4', '[{\"id\":\"4\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-24 09:42:16');
INSERT INTO `sr_action_log` VALUES ('312', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-24 09:58:01');
INSERT INTO `sr_action_log` VALUES ('313', '1', 'admin', 'backend/contents/picture/enable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"1\"}]', '127.0.0.1', '1', '2015-06-24 09:58:02');
INSERT INTO `sr_action_log` VALUES ('314', '1', 'admin', 'backend/contents/picture/disable', 'resources', '1', '[{\"id\":\"1\",\"type\":\"1\"}]', '0.0.0.0', '1', '2015-06-24 10:06:23');
INSERT INTO `sr_action_log` VALUES ('315', '1', 'admin', 'backend/base/menu/del', 'menu', '572', 'null', '127.0.0.1', '1', '2015-06-24 10:32:08');
INSERT INTO `sr_action_log` VALUES ('316', '1', 'admin', 'backend/contents/files/del', 'files', '13', '[{\"id\":\"13\",\"title\":\"13\"}]', '0.0.0.0', '1', '2015-06-24 13:55:08');
INSERT INTO `sr_action_log` VALUES ('317', '1', 'admin', 'backend/contents/files/del', 'files', '12', '[{\"id\":\"12\",\"title\":\"12\"}]', '0.0.0.0', '1', '2015-06-24 13:55:13');
INSERT INTO `sr_action_log` VALUES ('318', '1', 'admin', 'backend/contents/files/del', 'files', '11', '[{\"id\":\"11\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-06-24 14:03:19');
INSERT INTO `sr_action_log` VALUES ('319', '1', 'admin', 'backend/contents/files/del', 'files', '10,9,8,7,6,5,4,3,2', '[{\"id\":\"2\",\"title\":\"22\"},{\"id\":\"3\",\"title\":\"33\"},{\"id\":\"4\",\"title\":\"44\"},{\"id\":\"5\",\"title\":\"55\"},{\"id\":\"6\",\"title\":\"66\"},{\"id\":\"7\",\"title\":\"7\"},{\"id\":\"8\",\"title\":\"8\"},{\"id\":\"9\",\"title\":\"9\"},{\"id\":\"10\",\"title\":\"10\"}]', '0.0.0.0', '1', '2015-06-24 14:03:29');
INSERT INTO `sr_action_log` VALUES ('320', '1', 'admin', 'backend/contents/files/del', 'files', '2', '[{\"id\":\"2\",\"title\":\"22\"}]', '0.0.0.0', '1', '2015-06-24 14:10:59');
INSERT INTO `sr_action_log` VALUES ('321', '1', 'admin', 'backend/contents/files/enable', 'files', '3', '[{\"id\":\"3\",\"title\":\"33\"}]', '0.0.0.0', '1', '2015-06-24 14:13:50');
INSERT INTO `sr_action_log` VALUES ('322', '1', 'admin', 'backend/contents/files/enable', 'files', '4', '[{\"id\":\"4\",\"title\":\"44\"}]', '0.0.0.0', '1', '2015-06-24 14:13:52');
INSERT INTO `sr_action_log` VALUES ('323', '1', 'admin', 'backend/contents/files/enable', 'files', '5', '[{\"id\":\"5\",\"title\":\"55\"}]', '0.0.0.0', '1', '2015-06-24 14:13:53');
INSERT INTO `sr_action_log` VALUES ('324', '1', 'admin', 'backend/contents/files/enable', 'files', '6', '[{\"id\":\"6\",\"title\":\"66\"}]', '0.0.0.0', '1', '2015-06-24 14:13:53');
INSERT INTO `sr_action_log` VALUES ('325', '1', 'admin', 'backend/contents/files/enable', 'files', '7', '[{\"id\":\"7\",\"title\":\"7\"}]', '0.0.0.0', '1', '2015-06-24 14:13:54');
INSERT INTO `sr_action_log` VALUES ('326', '1', 'admin', 'backend/contents/files/enable', 'files', '9', '[{\"id\":\"9\",\"title\":\"9\"}]', '0.0.0.0', '1', '2015-06-24 14:13:55');
INSERT INTO `sr_action_log` VALUES ('327', '1', 'admin', 'backend/contents/files/enable', 'files', '8', '[{\"id\":\"8\",\"title\":\"8\"}]', '0.0.0.0', '1', '2015-06-24 14:13:58');
INSERT INTO `sr_action_log` VALUES ('328', '1', 'admin', 'backend/contents/files/enable', 'files', '10', '[{\"id\":\"10\",\"title\":\"10\"}]', '0.0.0.0', '1', '2015-06-24 14:13:59');
INSERT INTO `sr_action_log` VALUES ('329', '1', 'admin', 'backend/contents/files/del', 'files', '3', '[{\"id\":\"3\",\"title\":\"33\"}]', '0.0.0.0', '1', '2015-06-24 14:18:55');
INSERT INTO `sr_action_log` VALUES ('330', '1', 'admin', 'backend/contents/files/del', 'files', '13', '[{\"id\":\"13\",\"title\":\"13\"}]', '0.0.0.0', '1', '2015-06-24 14:48:36');
INSERT INTO `sr_action_log` VALUES ('331', '1', 'admin', 'backend/contents/files/del', 'files', '11', '[{\"id\":\"11\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-06-24 14:51:02');
INSERT INTO `sr_action_log` VALUES ('332', '1', 'admin', 'backend/contents/files/del', 'files', '9', '[{\"id\":\"9\",\"title\":\"9\"}]', '0.0.0.0', '1', '2015-06-24 14:51:09');
INSERT INTO `sr_action_log` VALUES ('333', '1', 'admin', 'backend/contents/files/del', 'files', '7', '[{\"id\":\"7\",\"title\":\"7\"}]', '0.0.0.0', '1', '2015-06-24 14:52:04');
INSERT INTO `sr_action_log` VALUES ('334', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-24 15:08:47');
INSERT INTO `sr_action_log` VALUES ('335', '1', 'admin', 'backend/contents/files/del', 'files', '6,4,1,12,10,5,3,2,8', '[{\"id\":\"1\",\"title\":\"11\"},{\"id\":\"2\",\"title\":\"22\"},{\"id\":\"3\",\"title\":\"33\"},{\"id\":\"4\",\"title\":\"44\"},{\"id\":\"5\",\"title\":\"55\"},{\"id\":\"6\",\"title\":\"66\"},{\"id\":\"8\",\"title\":\"8\"},{\"id\":\"10\",\"title\":\"10\"},{\"id\":\"12\",\"title\":\"12\"}]', '0.0.0.0', '1', '2015-06-24 16:22:06');
INSERT INTO `sr_action_log` VALUES ('336', '1', 'admin', 'backend/contents/files/del', 'files', '13', '[{\"id\":\"13\",\"title\":\"13\"}]', '0.0.0.0', '1', '2015-06-24 16:31:21');
INSERT INTO `sr_action_log` VALUES ('337', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-25 09:31:46');
INSERT INTO `sr_action_log` VALUES ('338', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-26 10:20:48');
INSERT INTO `sr_action_log` VALUES ('339', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-06-26 10:26:57');
INSERT INTO `sr_action_log` VALUES ('340', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-26 10:48:33');
INSERT INTO `sr_action_log` VALUES ('341', '1', 'admin', 'backend/base/menu/del', 'menu', '1', 'null', '0.0.0.0', '1', '2015-06-26 10:49:06');
INSERT INTO `sr_action_log` VALUES ('342', '1', 'admin', 'backend/contents/files/del', 'files', '11', '[{\"id\":\"11\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-06-26 10:59:24');
INSERT INTO `sr_action_log` VALUES ('343', '1', 'admin', 'backend/contents/files/del', 'files', '9', '[{\"id\":\"9\",\"title\":\"9\"}]', '0.0.0.0', '1', '2015-06-26 11:18:55');
INSERT INTO `sr_action_log` VALUES ('344', '1', 'admin', 'backend/contents/files/del', 'files', '7', '[{\"id\":\"7\",\"title\":\"7\"}]', '0.0.0.0', '1', '2015-06-26 11:19:45');
INSERT INTO `sr_action_log` VALUES ('345', '1', 'admin', 'backend/contents/files/del', 'files', '6', '[{\"id\":\"6\",\"title\":\"66\"}]', '0.0.0.0', '1', '2015-06-26 11:23:27');
INSERT INTO `sr_action_log` VALUES ('346', '1', 'admin', 'backend/contents/news/recommend', 'article', '1', '[{\"id\":\"1\",\"title\":\"PHP\\u4f1a\\u8bdd\\u5904\\u7406\\u76f8\\u5173\\u51fd\\u6570\\u4ecb\\u7ecd\"}]', '127.0.0.1', '1', '2015-06-26 16:38:19');
INSERT INTO `sr_action_log` VALUES ('347', '1', 'admin', 'backend/message/letter/setstatus', 'message', '14', '[{\"id\":\"14\",\"title\":\"sss\"}]', '127.0.0.1', '1', '2015-06-27 14:26:12');
INSERT INTO `sr_action_log` VALUES ('348', '1', 'admin', 'backend/message/letter/setstatus', 'message', '14', '[{\"id\":\"14\",\"title\":\"sss\"}]', '127.0.0.1', '1', '2015-06-27 14:26:19');
INSERT INTO `sr_action_log` VALUES ('349', '1', 'admin', 'backend/message/letter/setstatus', 'message', '14', '[{\"id\":\"14\",\"title\":\"sss\"}]', '127.0.0.1', '1', '2015-06-27 14:26:19');
INSERT INTO `sr_action_log` VALUES ('350', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-27 17:23:15');
INSERT INTO `sr_action_log` VALUES ('351', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-29 10:30:36');
INSERT INTO `sr_action_log` VALUES ('352', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-29 11:27:29');
INSERT INTO `sr_action_log` VALUES ('353', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-29 11:35:19');
INSERT INTO `sr_action_log` VALUES ('354', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-29 16:10:56');
INSERT INTO `sr_action_log` VALUES ('355', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-30 10:09:51');
INSERT INTO `sr_action_log` VALUES ('356', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-06-30 16:37:37');
INSERT INTO `sr_action_log` VALUES ('357', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-01 09:37:10');
INSERT INTO `sr_action_log` VALUES ('358', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-01 18:48:22');
INSERT INTO `sr_action_log` VALUES ('359', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-02 09:28:03');
INSERT INTO `sr_action_log` VALUES ('360', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-02 11:34:18');
INSERT INTO `sr_action_log` VALUES ('361', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-02 14:05:24');
INSERT INTO `sr_action_log` VALUES ('362', '1', 'admin', 'backend/products/products/disable', 'products', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18', '[{\"id\":\"4\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"5\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"6\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"7\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"8\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"9\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"10\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"15\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"16\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"17\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"18\",\"title\":\"\\u5546\\u54c1\\u4e00\"}]', '127.0.0.1', '1', '2015-07-02 14:05:45');
INSERT INTO `sr_action_log` VALUES ('363', '1', 'admin', 'backend/products/products/del', 'products', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18', '[{\"id\":\"4\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"5\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"6\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"7\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"8\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"9\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"10\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"15\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"16\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"17\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"18\",\"title\":\"\\u5546\\u54c1\\u4e00\"}]', '127.0.0.1', '1', '2015-07-02 14:05:53');
INSERT INTO `sr_action_log` VALUES ('364', '1', 'admin', 'backend/products/products/del', 'products', '4,5,6,7,8,9,10,11,12,13,14,15,16,17,18', '[{\"id\":\"4\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"5\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"6\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"7\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"8\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"9\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"10\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"11\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"12\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"13\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"14\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"15\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"16\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"17\",\"title\":\"\\u5546\\u54c1\\u4e00\"},{\"id\":\"18\",\"title\":\"\\u5546\\u54c1\\u4e00\"}]', '127.0.0.1', '1', '2015-07-02 14:06:06');
INSERT INTO `sr_action_log` VALUES ('365', '1', 'admin', 'backend/contents/files/del', 'files', '1', '[{\"id\":\"1\",\"title\":\"11\"}]', '127.0.0.1', '1', '2015-07-02 14:06:23');
INSERT INTO `sr_action_log` VALUES ('366', '1', 'admin', 'backend/contents/files/del', 'files', '78,77,76,75,74,73,72,71,70,69,68,67,66,65,64', '[{\"id\":\"64\",\"title\":\"11\"},{\"id\":\"65\",\"title\":\"11\"},{\"id\":\"66\",\"title\":\"11\"},{\"id\":\"67\",\"title\":\"11\"},{\"id\":\"68\",\"title\":\"11\"},{\"id\":\"69\",\"title\":\"11\"},{\"id\":\"70\",\"title\":\"11\"},{\"id\":\"71\",\"title\":\"11\"},{\"id\":\"72\",\"title\":\"11\"},{\"id\":\"73\",\"title\":\"11\"},{\"id\":\"74\",\"title\":\"11\"},{\"id\":\"75\",\"title\":\"11\"},{\"id\":\"76\",\"title\":\"11\"},{\"id\":\"77\",\"title\":\"11\"},{\"id\":\"78\",\"title\":\"11\"}]', '127.0.0.1', '1', '2015-07-02 14:09:19');
INSERT INTO `sr_action_log` VALUES ('367', '1', 'admin', 'backend/contents/files/del', 'files', '93,92,91,90,89,88,87,86,85,84,83,82,81,80,79', '[{\"id\":\"79\",\"title\":\"11\"},{\"id\":\"80\",\"title\":\"11\"},{\"id\":\"81\",\"title\":\"11\"},{\"id\":\"82\",\"title\":\"11\"},{\"id\":\"83\",\"title\":\"11\"},{\"id\":\"84\",\"title\":\"11\"},{\"id\":\"85\",\"title\":\"11\"},{\"id\":\"86\",\"title\":\"11\"},{\"id\":\"87\",\"title\":\"11\"},{\"id\":\"88\",\"title\":\"11\"},{\"id\":\"89\",\"title\":\"11\"},{\"id\":\"90\",\"title\":\"11\"},{\"id\":\"91\",\"title\":\"11\"},{\"id\":\"92\",\"title\":\"11\"},{\"id\":\"93\",\"title\":\"11\"}]', '127.0.0.1', '1', '2015-07-02 14:09:28');
INSERT INTO `sr_action_log` VALUES ('368', '1', 'admin', 'backend/contents/files/del', 'files', '63', '[{\"id\":\"63\",\"title\":\"11\"}]', '127.0.0.1', '1', '2015-07-02 14:26:56');
INSERT INTO `sr_action_log` VALUES ('369', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-03 09:52:14');
INSERT INTO `sr_action_log` VALUES ('370', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-03 14:04:46');
INSERT INTO `sr_action_log` VALUES ('371', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-03 16:53:39');
INSERT INTO `sr_action_log` VALUES ('372', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-06 09:45:10');
INSERT INTO `sr_action_log` VALUES ('373', '1', 'admin', 'backend/navigation/index/add', 'navigation', '4', '[{\"id\":\"4\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-06 09:48:43');
INSERT INTO `sr_action_log` VALUES ('374', '1', 'admin', 'backend/navigation/index/add', 'navigation', '5', '[{\"id\":\"5\",\"title\":\"\\u53e3\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-06 09:49:05');
INSERT INTO `sr_action_log` VALUES ('375', '1', 'admin', 'backend/navigation/index/add', 'navigation', '6', '[{\"id\":\"6\",\"title\":\"\\u5e73\\u53f0\\u4ea4\\u6d41\"}]', '0.0.0.0', '1', '2015-07-06 09:49:28');
INSERT INTO `sr_action_log` VALUES ('376', '1', 'admin', 'backend/navigation/index/add', 'navigation', '7', '[{\"id\":\"7\",\"title\":\"\\u7ffb\\u8bd1\\u5165\\u9a7b\"}]', '0.0.0.0', '1', '2015-07-06 09:50:04');
INSERT INTO `sr_action_log` VALUES ('377', '1', 'admin', 'backend/navigation/index/add', 'navigation', '8', '[{\"id\":\"8\",\"title\":\"\\u4e0b\\u8f7dAPP\"}]', '0.0.0.0', '1', '2015-07-06 09:50:22');
INSERT INTO `sr_action_log` VALUES ('378', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '9', '[{\"id\":\"9\",\"title\":\"\\u652f\\u4ed8\\u65b9\\u5f0f\"}]', '0.0.0.0', '1', '2015-07-06 10:01:50');
INSERT INTO `sr_action_log` VALUES ('379', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '10', '[{\"id\":\"10\",\"title\":\"\\u8054\\u7cfb\\u65b9\\u5f0f\"}]', '0.0.0.0', '1', '2015-07-06 10:02:00');
INSERT INTO `sr_action_log` VALUES ('380', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '11', '[{\"id\":\"11\",\"title\":\"\\u5ba2\\u670d\\u4e2d\\u5fc3\"}]', '0.0.0.0', '1', '2015-07-06 10:02:23');
INSERT INTO `sr_action_log` VALUES ('381', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '12', '[{\"id\":\"12\",\"title\":\"\\u670d\\u52a1\\u652f\\u6301\"}]', '0.0.0.0', '1', '2015-07-06 10:02:35');
INSERT INTO `sr_action_log` VALUES ('382', '1', 'admin', 'backend/navigation/bottom/add', 'navigation', '13', '[{\"id\":\"13\",\"title\":\"\\u7f51\\u7ad9\\u5730\\u56fe\"}]', '0.0.0.0', '1', '2015-07-06 10:02:50');
INSERT INTO `sr_action_log` VALUES ('383', '1', 'admin', 'backend/links/index/add', 'navigation', '14', '[{\"id\":\"14\",\"title\":\" \\u4e2d\\u8bd1\\u6cd5\\u7814\\u8ba8\\u4f1a\\u535a\\u5ba2\"}]', '0.0.0.0', '1', '2015-07-06 10:04:06');
INSERT INTO `sr_action_log` VALUES ('384', '1', 'admin', 'backend/links/index/add', 'navigation', '15', '[{\"id\":\"15\",\"title\":\"\\u8499\\u7279\\u96f7\\u56fd\\u9645\\u7814\\u7a76\\u5b66\\u9662\\uff08MIIS\\uff09\"}]', '0.0.0.0', '1', '2015-07-06 10:08:41');
INSERT INTO `sr_action_log` VALUES ('385', '1', 'admin', 'backend/links/index/add', 'navigation', '16', '[{\"id\":\"16\",\"title\":\"\\u56fd\\u9645\\u4f1a\\u8bae\\u53e3\\u8bd1\\u5458\\u534f\\u4f1a\\uff08AIIC\\uff09\"}]', '0.0.0.0', '1', '2015-07-06 10:09:33');
INSERT INTO `sr_action_log` VALUES ('386', '1', 'admin', 'backend/navigation/index/edit', 'navigation', '4', '[{\"id\":\"4\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-06 11:20:23');
INSERT INTO `sr_action_log` VALUES ('387', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-06 17:38:44');
INSERT INTO `sr_action_log` VALUES ('388', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-06 18:12:54');
INSERT INTO `sr_action_log` VALUES ('389', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-07 09:07:14');
INSERT INTO `sr_action_log` VALUES ('390', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-07 09:25:04');
INSERT INTO `sr_action_log` VALUES ('391', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '2', '[{\"id\":\"2\",\"title\":\"\\u5e97\\u94fa\\u4e8c\"}]', '0.0.0.0', '1', '2015-07-07 11:05:27');
INSERT INTO `sr_action_log` VALUES ('392', '1', 'admin', 'backend/shop/shop/setstatus', 'shop', '3', '[{\"id\":\"3\",\"title\":\"\\u7b2c\\u4e8c\\u7ffb\\u8bd1\\u516c\\u53f8\"}]', '0.0.0.0', '1', '2015-07-07 11:05:29');
INSERT INTO `sr_action_log` VALUES ('393', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '127.0.0.1', '1', '2015-07-07 16:55:51');
INSERT INTO `sr_action_log` VALUES ('394', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-08 17:52:13');
INSERT INTO `sr_action_log` VALUES ('395', '1', 'admin', 'backend/products/ability/del', 'category', ',36,37,56', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"},{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"},{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 17:55:17');
INSERT INTO `sr_action_log` VALUES ('396', '1', 'admin', 'backend/products/ability/del', 'category', ',36,37,56', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"},{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"},{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 17:55:32');
INSERT INTO `sr_action_log` VALUES ('397', '1', 'admin', 'backend/products/ability/del', 'category', ',36,37,56', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"},{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"},{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 17:56:18');
INSERT INTO `sr_action_log` VALUES ('398', '1', 'admin', 'backend/products/ability/del', 'category', ',36,37,56', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"},{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"},{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 17:58:23');
INSERT INTO `sr_action_log` VALUES ('399', '1', 'admin', 'backend/products/ability/del', 'category', '56', '[{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 17:58:30');
INSERT INTO `sr_action_log` VALUES ('400', '1', 'admin', 'backend/products/ability/disable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:17');
INSERT INTO `sr_action_log` VALUES ('401', '1', 'admin', 'backend/products/ability/enable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:20');
INSERT INTO `sr_action_log` VALUES ('402', '1', 'admin', 'backend/products/ability/disable', 'category', '37', '[{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:25');
INSERT INTO `sr_action_log` VALUES ('403', '1', 'admin', 'backend/products/ability/disable', 'category', '56', '[{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:29');
INSERT INTO `sr_action_log` VALUES ('404', '1', 'admin', 'backend/products/ability/disable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:31');
INSERT INTO `sr_action_log` VALUES ('405', '1', 'admin', 'backend/products/ability/enable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:47');
INSERT INTO `sr_action_log` VALUES ('406', '1', 'admin', 'backend/products/ability/enable', 'category', '37', '[{\"id\":\"37\",\"title\":\"\\u53e3\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:49');
INSERT INTO `sr_action_log` VALUES ('407', '1', 'admin', 'backend/products/ability/enable', 'category', '56', '[{\"id\":\"56\",\"title\":\"\\u97f3\\u9891\\u7ffb\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:02:51');
INSERT INTO `sr_action_log` VALUES ('408', '1', 'admin', 'backend/products/ability/disable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:16:08');
INSERT INTO `sr_action_log` VALUES ('409', '1', 'admin', 'backend/products/ability/enable', 'category', '36', '[{\"id\":\"36\",\"title\":\"\\u7b14\\u8bd1\"}]', '0.0.0.0', '1', '2015-07-08 18:16:13');
INSERT INTO `sr_action_log` VALUES ('410', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-09 17:36:39');
INSERT INTO `sr_action_log` VALUES ('411', '1', 'admin', 'backend/base/config/del', 'config', '14', '[{\"id\":\"14\",\"title\":\"CONTACT\"}]', '0.0.0.0', '1', '2015-07-09 17:37:25');
INSERT INTO `sr_action_log` VALUES ('412', '1', 'admin', 'backend/base/config/del', 'config', '15', '[{\"id\":\"15\",\"title\":\"PHONES\"}]', '0.0.0.0', '1', '2015-07-09 17:37:52');
INSERT INTO `sr_action_log` VALUES ('413', '1', 'admin', 'backend/base/config/del', 'config', '16', '[{\"id\":\"16\",\"title\":\"OFFICE ADDRESS\"}]', '0.0.0.0', '1', '2015-07-09 17:37:58');
INSERT INTO `sr_action_log` VALUES ('414', '1', 'admin', 'backend/base/config/del', 'config', '17', '[{\"id\":\"17\",\"title\":\"EMAILS\"}]', '0.0.0.0', '1', '2015-07-09 17:38:07');
INSERT INTO `sr_action_log` VALUES ('415', '1', 'admin', 'backend/base/config/del', 'config', '18', '[{\"id\":\"18\",\"title\":\"Facebook\"}]', '0.0.0.0', '1', '2015-07-09 17:38:12');
INSERT INTO `sr_action_log` VALUES ('416', '1', 'admin', 'backend/base/config/del', 'config', '12', '[{\"id\":\"12\",\"title\":\"ABOUT US\"}]', '0.0.0.0', '1', '2015-07-09 17:38:15');
INSERT INTO `sr_action_log` VALUES ('417', '1', 'admin', 'backend/base/config/del', 'config', '19', '[{\"id\":\"19\",\"title\":\"linkedin\"}]', '0.0.0.0', '1', '2015-07-09 17:38:19');
INSERT INTO `sr_action_log` VALUES ('418', '1', 'admin', 'backend/base/config/del', 'config', '20', '[{\"id\":\"20\",\"title\":\"youtube\"}]', '0.0.0.0', '1', '2015-07-09 17:38:22');
INSERT INTO `sr_action_log` VALUES ('419', '1', 'admin', 'backend/base/config/del', 'config', '21', '[{\"id\":\"21\",\"title\":\"twitter\"}]', '0.0.0.0', '1', '2015-07-09 17:38:26');
INSERT INTO `sr_action_log` VALUES ('420', '1', 'admin', 'backend/base/config/del', 'config', '22', '[{\"id\":\"22\",\"title\":\"google\"}]', '0.0.0.0', '1', '2015-07-09 17:38:29');
INSERT INTO `sr_action_log` VALUES ('421', '1', 'admin', 'backend/base/config/del', 'config', '23', '[{\"id\":\"23\",\"title\":\"About Us\"}]', '0.0.0.0', '1', '2015-07-09 17:38:38');
INSERT INTO `sr_action_log` VALUES ('422', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-09 17:39:24');
INSERT INTO `sr_action_log` VALUES ('423', '1', 'admin', 'backend/contents/files/del', 'files', '22,21,20,19,18,17,16,15,14', '[{\"id\":\"14\",\"title\":\"11\"},{\"id\":\"15\",\"title\":\"11\"},{\"id\":\"16\",\"title\":\"11\"},{\"id\":\"17\",\"title\":\"11\"},{\"id\":\"18\",\"title\":\"11\"},{\"id\":\"19\",\"title\":\"11\"},{\"id\":\"20\",\"title\":\"11\"},{\"id\":\"21\",\"title\":\"11\"},{\"id\":\"22\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-07-09 20:04:47');
INSERT INTO `sr_action_log` VALUES ('424', '1', 'admin', 'backend/contents/files/del', 'files', '37,36,35,34,33,32,31,30,29,28,27,26,25,24,23', '[{\"id\":\"23\",\"title\":\"11\"},{\"id\":\"24\",\"title\":\"11\"},{\"id\":\"25\",\"title\":\"11\"},{\"id\":\"26\",\"title\":\"11\"},{\"id\":\"27\",\"title\":\"11\"},{\"id\":\"28\",\"title\":\"11\"},{\"id\":\"29\",\"title\":\"11\"},{\"id\":\"30\",\"title\":\"11\"},{\"id\":\"31\",\"title\":\"11\"},{\"id\":\"32\",\"title\":\"11\"},{\"id\":\"33\",\"title\":\"11\"},{\"id\":\"34\",\"title\":\"11\"},{\"id\":\"35\",\"title\":\"11\"},{\"id\":\"36\",\"title\":\"11\"},{\"id\":\"37\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-07-09 20:05:51');
INSERT INTO `sr_action_log` VALUES ('425', '1', 'admin', 'backend/contents/files/del', 'files', '52,51,50,49,48,47,46,45,44,43,42,41,40,39,38', '[{\"id\":\"38\",\"title\":\"11\"},{\"id\":\"39\",\"title\":\"11\"},{\"id\":\"40\",\"title\":\"11\"},{\"id\":\"41\",\"title\":\"11\"},{\"id\":\"42\",\"title\":\"11\"},{\"id\":\"43\",\"title\":\"11\"},{\"id\":\"44\",\"title\":\"11\"},{\"id\":\"45\",\"title\":\"11\"},{\"id\":\"46\",\"title\":\"11\"},{\"id\":\"47\",\"title\":\"11\"},{\"id\":\"48\",\"title\":\"11\"},{\"id\":\"49\",\"title\":\"11\"},{\"id\":\"50\",\"title\":\"11\"},{\"id\":\"51\",\"title\":\"11\"},{\"id\":\"52\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-07-09 20:06:00');
INSERT INTO `sr_action_log` VALUES ('426', '1', 'admin', 'backend/contents/files/del', 'files', '62,61,60,59,58,57,56,55,54,53', '[{\"id\":\"53\",\"title\":\"11\"},{\"id\":\"54\",\"title\":\"11\"},{\"id\":\"55\",\"title\":\"11\"},{\"id\":\"56\",\"title\":\"11\"},{\"id\":\"57\",\"title\":\"11\"},{\"id\":\"58\",\"title\":\"11\"},{\"id\":\"59\",\"title\":\"11\"},{\"id\":\"60\",\"title\":\"11\"},{\"id\":\"61\",\"title\":\"11\"},{\"id\":\"62\",\"title\":\"11\"}]', '0.0.0.0', '1', '2015-07-09 20:06:14');
INSERT INTO `sr_action_log` VALUES ('427', '1', 'admin', 'backend/base/public/login', 'member', '1', '[{\"id\":\"1\",\"title\":\"admin\"}]', '0.0.0.0', '1', '2015-07-10 10:13:49');

-- ----------------------------
-- Table structure for upload_files
-- ----------------------------
DROP TABLE IF EXISTS `upload_files`;
CREATE TABLE `upload_files` (
  `name` varchar(255) default NULL,
  `localpath` varchar(255) default NULL,
  `type` varchar(168) NOT NULL,
  `md5_value` varchar(32) default NULL,
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL default '0',
  `thumbnail` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `upload_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of upload_files
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` bigint(11) NOT NULL auto_increment,
  `username` varchar(255) default '',
  `email` varchar(40) default NULL,
  `status` int(11) default '-1',
  `pwd` varchar(20) default NULL,
  `token_id` int(11) default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('0', 'weiquan', null, '0', '', '0');
INSERT INTO `user` VALUES ('1', '', null, '0', null, '0');
