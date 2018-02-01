/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : lingshi

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-02-01 10:46:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for areas
-- ----------------------------
DROP TABLE IF EXISTS `areas`;
CREATE TABLE `areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `province_code` varchar(20) DEFAULT NULL,
  `province_name` varchar(30) DEFAULT NULL,
  `province_en_name` varchar(100) DEFAULT NULL,
  `city_code` varchar(20) DEFAULT NULL,
  `city_name` varchar(30) DEFAULT NULL,
  `city_en_name` varchar(100) DEFAULT NULL,
  `area_code` varchar(20) DEFAULT NULL,
  `area_name` varchar(30) DEFAULT NULL,
  `area_en_name` varchar(100) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of areas
-- ----------------------------
INSERT INTO `areas` VALUES ('1', 'bj', '北京', '北京', 'bj2', '北京', '北京', 'hdq', '海淀区', '海淀区', '0');
INSERT INTO `areas` VALUES ('2', 'bj', '北京', '北京', 'bj2', '北京', '北京', 'ctq', '朝阳区', '朝阳区', '0');
INSERT INTO `areas` VALUES ('3', 'jl', '吉林省', '吉林省', 'ccs', '长春市', '长春市', 'ngq', '南关区', '南关区', '0');
INSERT INTO `areas` VALUES ('4', 'jl', '吉林省', '吉林省', 'ccs', '长春市', '长春市', 'kcq', '宽城区', '宽城区', '0');
INSERT INTO `areas` VALUES ('5', 'jl', '吉林省', '吉林省', 'jls', '吉林市', '吉林市', 'wzq', '吴中区', '吴中区', '0');

-- ----------------------------
-- Table structure for attachments
-- ----------------------------
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `diskposition` varchar(200) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(10) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attachments
-- ----------------------------
INSERT INTO `attachments` VALUES ('4', '/upload/file/20180123/', '15166861123638.xlsx', '.xlsx', '9824', '2018-01-23 13:41:52', '2018-01-23 13:41:52');
INSERT INTO `attachments` VALUES ('6', '/upload/file/20180123/', '15166864715733.xlsx', '.xlsx', '9824', '2018-01-23 13:47:51', '2018-01-23 13:47:51');
INSERT INTO `attachments` VALUES ('7', '/upload/file/20180123/', '15166867088517.xlsx', '.xlsx', '9824', '2018-01-23 13:51:48', '2018-01-23 13:51:48');
INSERT INTO `attachments` VALUES ('8', '/upload/file/20180123/', '15166867762023.xlsx', '.xlsx', '9824', '2018-01-23 13:52:56', '2018-01-23 13:52:56');
INSERT INTO `attachments` VALUES ('9', '/upload/file/20180123/', '15166870109176.xlsx', '.xlsx', '9824', '2018-01-23 13:56:50', '2018-01-23 13:56:50');
INSERT INTO `attachments` VALUES ('10', '/upload/file/20180123/', '1516687126899.xlsx', '.xlsx', '9824', '2018-01-23 13:58:46', '2018-01-23 13:58:46');
INSERT INTO `attachments` VALUES ('11', '/upload/file/20180123/', '15166873192947.xlsx', '.xlsx', '9824', '2018-01-23 14:01:59', '2018-01-23 14:01:59');
INSERT INTO `attachments` VALUES ('12', '/upload/file/20180123/', '15166874711335.xlsx', '.xlsx', '9824', '2018-01-23 14:04:32', '2018-01-23 14:04:32');
INSERT INTO `attachments` VALUES ('13', '/upload/file/20180123/', '15166875681023.xlsx', '.xlsx', '9824', '2018-01-23 14:06:08', '2018-01-23 14:06:08');
INSERT INTO `attachments` VALUES ('14', '/upload/file/20180123/', '15166877529206.xlsx', '.xlsx', '9824', '2018-01-23 14:09:12', '2018-01-23 14:09:12');
INSERT INTO `attachments` VALUES ('15', '/upload/file/20180123/', '15166929996731.xlsx', '.xlsx', '9824', '2018-01-23 15:36:39', '2018-01-23 15:36:39');
INSERT INTO `attachments` VALUES ('16', '/upload/file/20180123/', '15166932122164.xlsx', '.xlsx', '9824', '2018-01-23 15:40:12', '2018-01-23 15:40:12');
INSERT INTO `attachments` VALUES ('17', '/upload/file/20180123/', '15166932653039.xlsx', '.xlsx', '9824', '2018-01-23 15:41:05', '2018-01-23 15:41:05');
INSERT INTO `attachments` VALUES ('18', '/upload/file/20180123/', '15166933104997.xlsx', '.xlsx', '9824', '2018-01-23 15:41:50', '2018-01-23 15:41:50');
INSERT INTO `attachments` VALUES ('19', '/upload/file/20180123/', '15166934183938.xlsx', '.xlsx', '9824', '2018-01-23 15:43:38', '2018-01-23 15:43:38');
INSERT INTO `attachments` VALUES ('20', '/upload/file/20180123/', '15166934578113.xlsx', '.xlsx', '9824', '2018-01-23 15:44:17', '2018-01-23 15:44:17');
INSERT INTO `attachments` VALUES ('21', '/upload/file/20180123/', '15166936011761.xlsx', '.xlsx', '9824', '2018-01-23 15:46:41', '2018-01-23 15:46:41');
INSERT INTO `attachments` VALUES ('22', '/upload/file/20180123/', '15166937422303.xlsx', '.xlsx', '9824', '2018-01-23 15:49:02', '2018-01-23 15:49:02');
INSERT INTO `attachments` VALUES ('23', '/upload/file/20180123/', '15166938697221.xlsx', '.xlsx', '9824', '2018-01-23 15:51:09', '2018-01-23 15:51:09');
INSERT INTO `attachments` VALUES ('24', '/upload/file/20180123/', '1516693991238.xlsx', '.xlsx', '9824', '2018-01-23 15:53:11', '2018-01-23 15:53:11');
INSERT INTO `attachments` VALUES ('25', '/upload/file/20180123/', '15166966339851.xlsx', '.xlsx', '9824', '2018-01-23 16:37:13', '2018-01-23 16:37:13');
INSERT INTO `attachments` VALUES ('26', '/upload/file/20180123/', '15166967405549.xlsx', '.xlsx', '9824', '2018-01-23 16:39:01', '2018-01-23 16:39:01');
INSERT INTO `attachments` VALUES ('27', '/upload/file/20180123/', '15166968857764.xlsx', '.xlsx', '9824', '2018-01-23 16:41:25', '2018-01-23 16:41:25');
INSERT INTO `attachments` VALUES ('28', '/upload/file/20180123/', '15166969117123.xlsx', '.xlsx', '9824', '2018-01-23 16:41:51', '2018-01-23 16:41:51');
INSERT INTO `attachments` VALUES ('29', '/upload/file/20180123/', '15166970044909.xlsx', '.xlsx', '9781', '2018-01-23 16:43:24', '2018-01-23 16:43:24');
INSERT INTO `attachments` VALUES ('30', '/upload/file/20180123/', '15166970482424.xlsx', '.xlsx', '9826', '2018-01-23 16:44:08', '2018-01-23 16:44:08');
INSERT INTO `attachments` VALUES ('31', '/upload/file/20180123/', '15166974229654.xlsx', '.xlsx', '9826', '2018-01-23 16:50:22', '2018-01-23 16:50:22');
INSERT INTO `attachments` VALUES ('32', '/upload/file/20180123/', '15166976308842.xlsx', '.xlsx', '9826', '2018-01-23 16:53:50', '2018-01-23 16:53:50');
INSERT INTO `attachments` VALUES ('33', '/upload/file/20180123/', '15166981979684.xlsx', '.xlsx', '9826', '2018-01-23 17:03:17', '2018-01-23 17:03:17');
INSERT INTO `attachments` VALUES ('34', '/upload/file/20180123/', '1516698273929.xlsx', '.xlsx', '9826', '2018-01-23 17:04:33', '2018-01-23 17:04:33');
INSERT INTO `attachments` VALUES ('35', '/upload/file/20180123/', '15166983204212.xlsx', '.xlsx', '9824', '2018-01-23 17:05:20', '2018-01-23 17:05:20');
INSERT INTO `attachments` VALUES ('36', '/upload/file/20180123/', '15166983488530.xlsx', '.xlsx', '9823', '2018-01-23 17:05:48', '2018-01-23 17:05:48');
INSERT INTO `attachments` VALUES ('37', '/upload/file/20180123/', '15166983752287.xlsx', '.xlsx', '9823', '2018-01-23 17:06:15', '2018-01-23 17:06:15');
INSERT INTO `attachments` VALUES ('38', '/upload/file/20180123/', '15166983948394.xlsx', '.xlsx', '9823', '2018-01-23 17:06:34', '2018-01-23 17:06:34');
INSERT INTO `attachments` VALUES ('39', '/upload/file/20180123/', '15166984259133.xlsx', '.xlsx', '9823', '2018-01-23 17:07:05', '2018-01-23 17:07:05');
INSERT INTO `attachments` VALUES ('40', '/upload/file/20180123/', '15166985298030.xlsx', '.xlsx', '9823', '2018-01-23 17:08:49', '2018-01-23 17:08:49');
INSERT INTO `attachments` VALUES ('41', '/upload/file/20180123/', '15166987953885.xlsx', '.xlsx', '9823', '2018-01-23 17:13:15', '2018-01-23 17:13:15');
INSERT INTO `attachments` VALUES ('42', '/upload/file/20180123/', '15166988509055.xlsx', '.xlsx', '9823', '2018-01-23 17:14:10', '2018-01-23 17:14:10');
INSERT INTO `attachments` VALUES ('43', '/upload/file/20180123/', '15166989019086.xlsx', '.xlsx', '9823', '2018-01-23 17:15:01', '2018-01-23 17:15:01');
INSERT INTO `attachments` VALUES ('44', '/upload/file/20180123/', '15166989337658.xlsx', '.xlsx', '9823', '2018-01-23 17:15:33', '2018-01-23 17:15:33');
INSERT INTO `attachments` VALUES ('45', '/upload/file/20180123/', '15166991283573.xlsx', '.xlsx', '9823', '2018-01-23 17:18:48', '2018-01-23 17:18:48');
INSERT INTO `attachments` VALUES ('46', '/upload/file/20180123/', '15167026628382.xlsx', '.xlsx', '9823', '2018-01-23 18:17:42', '2018-01-23 18:17:42');
INSERT INTO `attachments` VALUES ('47', '/upload/file/20180123/', '151670269482.xlsx', '.xlsx', '9823', '2018-01-23 18:18:15', '2018-01-23 18:18:15');
INSERT INTO `attachments` VALUES ('48', '/upload/file/20180124/', '15167824205366.xlsx', '.xlsx', '9827', '2018-01-24 16:27:00', '2018-01-24 16:27:00');
INSERT INTO `attachments` VALUES ('49', '/upload/file/20180124/', '15167824806407.xlsx', '.xlsx', '9845', '2018-01-24 16:28:00', '2018-01-24 16:28:00');
INSERT INTO `attachments` VALUES ('50', '/upload/file/20180124/', '15167825934208.xlsx', '.xlsx', '9882', '2018-01-24 16:29:53', '2018-01-24 16:29:53');
INSERT INTO `attachments` VALUES ('51', '/upload/file/20180124/', '15167827347588.xlsx', '.xlsx', '9882', '2018-01-24 16:32:14', '2018-01-24 16:32:14');
INSERT INTO `attachments` VALUES ('52', '/upload/file/20180124/', '15167827795595.xlsx', '.xlsx', '9882', '2018-01-24 16:32:59', '2018-01-24 16:32:59');
INSERT INTO `attachments` VALUES ('53', '/upload/file/20180124/', '15167830604251.xlsx', '.xlsx', '9882', '2018-01-24 16:37:40', '2018-01-24 16:37:40');
INSERT INTO `attachments` VALUES ('54', '/upload/file/20180124/', '15167831334227.xlsx', '.xlsx', '9882', '2018-01-24 16:38:53', '2018-01-24 16:38:53');
INSERT INTO `attachments` VALUES ('55', '/upload/file/20180124/', '15167832169730.xlsx', '.xlsx', '9882', '2018-01-24 16:40:16', '2018-01-24 16:40:16');
INSERT INTO `attachments` VALUES ('56', '/upload/file/20180124/', '15167832952451.xlsx', '.xlsx', '9882', '2018-01-24 16:41:35', '2018-01-24 16:41:35');
INSERT INTO `attachments` VALUES ('57', '/upload/file/20180125/', '15168746425348.xlsx', '.xlsx', '10041', '2018-01-25 18:04:02', '2018-01-25 18:04:02');
INSERT INTO `attachments` VALUES ('58', '/upload/file/20180125/', '1516874756684.xlsx', '.xlsx', '10041', '2018-01-25 18:05:56', '2018-01-25 18:05:56');
INSERT INTO `attachments` VALUES ('59', '/upload/file/20180125/', '15168748289943.xlsx', '.xlsx', '10041', '2018-01-25 18:07:08', '2018-01-25 18:07:08');
INSERT INTO `attachments` VALUES ('60', '/upload/file/20180125/', '15168748583592.xlsx', '.xlsx', '10041', '2018-01-25 18:07:38', '2018-01-25 18:07:38');
INSERT INTO `attachments` VALUES ('61', '/upload/file/20180125/', '1516874971606.xlsx', '.xlsx', '10041', '2018-01-25 18:09:31', '2018-01-25 18:09:31');
INSERT INTO `attachments` VALUES ('62', '/upload/file/20180127/', '15170349515727.xlsx', '.xlsx', '10041', '2018-01-27 14:35:51', '2018-01-27 14:35:51');
INSERT INTO `attachments` VALUES ('63', '/upload/file/20180127/', '1517035090759.xlsx', '.xlsx', '10041', '2018-01-27 14:38:10', '2018-01-27 14:38:10');
INSERT INTO `attachments` VALUES ('64', '/upload/file/20180127/', '15170352622115.xlsx', '.xlsx', '10041', '2018-01-27 14:41:03', '2018-01-27 14:41:03');
INSERT INTO `attachments` VALUES ('65', '/upload/file/20180127/', '15170353225895.xlsx', '.xlsx', '10041', '2018-01-27 14:42:03', '2018-01-27 14:42:03');
INSERT INTO `attachments` VALUES ('66', '/upload/file/20180127/', '15170357098587.xlsx', '.xlsx', '10040', '2018-01-27 14:48:30', '2018-01-27 14:48:30');
INSERT INTO `attachments` VALUES ('67', '/upload/file/20180127/', '15170358237086.xlsx', '.xlsx', '10097', '2018-01-27 14:50:23', '2018-01-27 14:50:23');
INSERT INTO `attachments` VALUES ('68', '/upload/file/20180127/', '15170359636762.xlsx', '.xlsx', '10097', '2018-01-27 14:52:43', '2018-01-27 14:52:43');
INSERT INTO `attachments` VALUES ('69', '/upload/file/20180127/', '1517036611155.xlsx', '.xlsx', '10096', '2018-01-27 15:03:31', '2018-01-27 15:03:31');
INSERT INTO `attachments` VALUES ('70', '/upload/file/20180127/', '15170367143237.xlsx', '.xlsx', '10096', '2018-01-27 15:05:15', '2018-01-27 15:05:15');
INSERT INTO `attachments` VALUES ('71', '/upload/file/20180127/', '15170367683614.xlsx', '.xlsx', '10096', '2018-01-27 15:06:09', '2018-01-27 15:06:09');
INSERT INTO `attachments` VALUES ('72', '/upload/file/20180127/', '15170369203036.xlsx', '.xlsx', '10096', '2018-01-27 15:08:40', '2018-01-27 15:08:40');
INSERT INTO `attachments` VALUES ('73', '/upload/file/20180127/', '15170373406926.xlsx', '.xlsx', '10125', '2018-01-27 15:15:40', '2018-01-27 15:15:40');
INSERT INTO `attachments` VALUES ('74', '/upload/file/20180127/', '15170374534489.xlsx', '.xlsx', '10125', '2018-01-27 15:17:33', '2018-01-27 15:17:33');
INSERT INTO `attachments` VALUES ('75', '/upload/file/20180127/', '15170376908820.xlsx', '.xlsx', '10125', '2018-01-27 15:21:30', '2018-01-27 15:21:30');
INSERT INTO `attachments` VALUES ('76', '/upload/file/20180127/', '15170378726490.xlsx', '.xlsx', '10125', '2018-01-27 15:24:32', '2018-01-27 15:24:32');
INSERT INTO `attachments` VALUES ('77', '/upload/file/20180127/', '15170379535123.xlsx', '.xlsx', '10125', '2018-01-27 15:25:54', '2018-01-27 15:25:54');
INSERT INTO `attachments` VALUES ('78', '/upload/file/20180127/', '15170383973706.xlsx', '.xlsx', '10146', '2018-01-27 15:33:17', '2018-01-27 15:33:17');
INSERT INTO `attachments` VALUES ('79', '/upload/file/20180127/', '15170385736258.xlsx', '.xlsx', '10146', '2018-01-27 15:36:13', '2018-01-27 15:36:13');
INSERT INTO `attachments` VALUES ('80', '/upload/file/20180129/', '15172190906637.xlsx', '.xlsx', '10269', '2018-01-29 17:44:51', '2018-01-29 17:44:51');
INSERT INTO `attachments` VALUES ('81', '/upload/file/20180130/', '15173060936811.xlsx', '.xlsx', '9971', '2018-01-30 17:54:53', '2018-01-30 17:54:53');
INSERT INTO `attachments` VALUES ('82', '/upload/file/20180130/', '15173062007796.xlsx', '.xlsx', '9971', '2018-01-30 17:56:40', '2018-01-30 17:56:40');
INSERT INTO `attachments` VALUES ('83', '/upload/file/20180130/', '15173062526766.xlsx', '.xlsx', '9615', '2018-01-30 17:57:32', '2018-01-30 17:57:32');
INSERT INTO `attachments` VALUES ('84', '/upload/file/20180130/', '15173062811807.xlsx', '.xlsx', '9615', '2018-01-30 17:58:01', '2018-01-30 17:58:01');
INSERT INTO `attachments` VALUES ('85', '/upload/file/20180130/', '1517306353786.xlsx', '.xlsx', '9615', '2018-01-30 17:59:13', '2018-01-30 17:59:13');
INSERT INTO `attachments` VALUES ('86', '/upload/file/20180130/', '15173064055746.xlsx', '.xlsx', '9618', '2018-01-30 18:00:05', '2018-01-30 18:00:05');
INSERT INTO `attachments` VALUES ('87', '/upload/file/20180130/', '1517306577726.xlsx', '.xlsx', '9565', '2018-01-30 18:02:57', '2018-01-30 18:02:57');
INSERT INTO `attachments` VALUES ('88', '/upload/file/20180130/', '15173066446643.xlsx', '.xlsx', '9620', '2018-01-30 18:04:04', '2018-01-30 18:04:04');

-- ----------------------------
-- Table structure for chat_logs
-- ----------------------------
DROP TABLE IF EXISTS `chat_logs`;
CREATE TABLE `chat_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chat_logs
-- ----------------------------

-- ----------------------------
-- Table structure for dicts
-- ----------------------------
DROP TABLE IF EXISTS `dicts`;
CREATE TABLE `dicts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) DEFAULT NULL,
  `value` varchar(60) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dicts
-- ----------------------------
INSERT INTO `dicts` VALUES ('1', 'course', '英语', '0', '2018-01-22 10:20:27', '2018-01-22 10:20:27');
INSERT INTO `dicts` VALUES ('2', 'course', '数学', '0', '2018-01-22 10:20:45', '2018-01-22 10:20:45');
INSERT INTO `dicts` VALUES ('3', 'course', '语文', '0', '2018-01-22 10:20:57', '2018-01-22 10:20:57');
INSERT INTO `dicts` VALUES ('4', 'grade', '一年级', '0', '2018-01-22 10:21:26', '2018-01-22 10:21:26');
INSERT INTO `dicts` VALUES ('5', 'grade', '二年级', '0', '2018-01-22 10:21:45', '2018-01-22 10:21:45');
INSERT INTO `dicts` VALUES ('6', 'grade', '语文2', '1', '2018-01-22 10:29:52', '2018-01-22 10:29:52');
INSERT INTO `dicts` VALUES ('7', 'user_title', '初级', '0', '2018-01-24 19:02:10', '2018-01-24 19:02:10');
INSERT INTO `dicts` VALUES ('8', 'user_title', '中级', '0', '2018-01-24 19:02:22', '2018-01-24 19:02:22');
INSERT INTO `dicts` VALUES ('9', 'user_title', '高级', '0', '2018-01-24 19:02:26', '2018-01-24 19:02:26');

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `goods_type_id` bigint(20) DEFAULT NULL COMMENT '商品类别 dicts',
  `goods_count` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `abstract` varchar(255) DEFAULT NULL COMMENT '商品介绍',
  `goods_detail` varchar(255) DEFAULT NULL COMMENT '详情描述',
  `creator` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods
-- ----------------------------

-- ----------------------------
-- Table structure for goods_atts
-- ----------------------------
DROP TABLE IF EXISTS `goods_atts`;
CREATE TABLE `goods_atts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `goods_id` bigint(20) DEFAULT NULL,
  `att_id` bigint(20) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods_atts
-- ----------------------------

-- ----------------------------
-- Table structure for meets
-- ----------------------------
DROP TABLE IF EXISTS `meets`;
CREATE TABLE `meets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `area_id` bigint(20) DEFAULT NULL,
  `addr` varchar(200) DEFAULT NULL,
  `begin_time` datetime DEFAULT NULL COMMENT '会议开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '会议结束时间',
  `abstract` varchar(200) DEFAULT NULL COMMENT '会议介绍',
  `creator` bigint(20) DEFAULT NULL,
  `keynote_speaker` varchar(100) DEFAULT NULL COMMENT '主讲人',
  `limit_count` int(11) DEFAULT NULL COMMENT '限制人数',
  `to_object` varchar(100) DEFAULT NULL COMMENT '针对人群',
  `status` smallint(6) DEFAULT NULL COMMENT '会议状态 0 正常 1 已删除',
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reason` varchar(50) DEFAULT NULL COMMENT '会议删除原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of meets
-- ----------------------------

-- ----------------------------
-- Table structure for meet_prizes
-- ----------------------------
DROP TABLE IF EXISTS `meet_prizes`;
CREATE TABLE `meet_prizes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `prize_count` int(11) DEFAULT NULL,
  `meet_id` bigint(20) DEFAULT NULL,
  `creator` varchar(255) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of meet_prizes
-- ----------------------------

-- ----------------------------
-- Table structure for meet_prize_users
-- ----------------------------
DROP TABLE IF EXISTS `meet_prize_users`;
CREATE TABLE `meet_prize_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `meet_user_id` bigint(20) DEFAULT NULL,
  `prize_id` bigint(20) DEFAULT NULL,
  `creator` bigint(255) DEFAULT NULL COMMENT '创建人：为后台暗箱操作预留字段',
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of meet_prize_users
-- ----------------------------

-- ----------------------------
-- Table structure for meet_users
-- ----------------------------
DROP TABLE IF EXISTS `meet_users`;
CREATE TABLE `meet_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `meet_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL COMMENT '状态 0 已报名 1 已付款 2 已签到',
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of meet_users
-- ----------------------------

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news
-- ----------------------------

-- ----------------------------
-- Table structure for news_atts
-- ----------------------------
DROP TABLE IF EXISTS `news_atts`;
CREATE TABLE `news_atts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `news_id` bigint(20) DEFAULT NULL,
  `att_id` bigint(20) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news_atts
-- ----------------------------

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `place_order_time` datetime DEFAULT NULL COMMENT '下单时间',
  `status` smallint(6) DEFAULT NULL COMMENT '0 未支付 1 待发货 2 已发货 3 已签收',
  `pay_way` varchar(50) DEFAULT NULL COMMENT '支付方式',
  `pay_code` varchar(200) DEFAULT NULL,
  `place_order_people` bigint(20) DEFAULT NULL COMMENT '下单人',
  `total_price` float DEFAULT NULL,
  `take_addr` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `take_tel` varchar(20) DEFAULT NULL COMMENT '收货手机号',
  `take_name` varchar(50) DEFAULT NULL,
  `bill_type` smallint(6) DEFAULT NULL COMMENT '发票类型 0 无 1 电子 2 纸质',
  `bill_title` varchar(200) DEFAULT NULL COMMENT '发票抬头',
  `pay_duty_code` varchar(200) DEFAULT NULL COMMENT '纳税人编码',
  `bill_use_id` varchar(255) DEFAULT NULL COMMENT '发票用途 dicts--明细，书籍，办公用品等',
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for order_goods
-- ----------------------------
DROP TABLE IF EXISTS `order_goods`;
CREATE TABLE `order_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `orders_id` bigint(20) DEFAULT NULL,
  `goods_id` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', '超级管理员', '1', '0', '2018-01-19 15:43:08', '2018-01-19 15:43:08');
INSERT INTO `roles` VALUES ('2', '教师', '1', '0', '2018-01-21 14:27:30', '2018-01-21 14:27:30');
INSERT INTO `roles` VALUES ('3', '教研员', '1', '0', '2018-01-27 19:58:34', '2018-01-27 19:58:34');
INSERT INTO `roles` VALUES ('4', '管理员', '1', '0', '2018-01-27 20:58:41', '2018-01-27 20:58:41');
INSERT INTO `roles` VALUES ('5', '入场管理员', '1', '0', '2018-01-27 20:58:49', '2018-01-27 20:58:49');

-- ----------------------------
-- Table structure for schools
-- ----------------------------
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `area_id` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of schools
-- ----------------------------
INSERT INTO `schools` VALUES ('1', '文庙小学', '1', '1', '0', '2018-01-19 15:06:50', '2018-01-19 15:06:47');
INSERT INTO `schools` VALUES ('2', '文庙小学2', '1', '2', '0', '2018-01-30 16:22:17', '2018-01-30 16:22:17');
INSERT INTO `schools` VALUES ('3', '文庙小学', '1', '3', '0', '2018-01-30 14:45:05', '2018-01-30 14:44:15');
INSERT INTO `schools` VALUES ('4', '1文庙小学12df1', null, '5', '0', '2018-01-31 11:46:50', '2018-01-31 11:46:50');
INSERT INTO `schools` VALUES ('5', '文庙小学1', null, '1', '0', '2018-01-30 18:00:06', '2018-01-30 18:00:06');
INSERT INTO `schools` VALUES ('6', '文庙小学11', null, '1', '0', '2018-01-30 18:04:06', '2018-01-30 18:04:06');
INSERT INTO `schools` VALUES ('7', 'test_add', null, '4', '0', '2018-01-31 11:47:28', '2018-01-31 11:47:28');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL COMMENT '状态 0 正常 1 待审核 ',
  `role_id` bigint(20) DEFAULT NULL,
  `unum` varchar(100) DEFAULT NULL COMMENT '继教号',
  `sex` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `seniority` int(11) DEFAULT NULL COMMENT '工龄',
  `user_title_id` bigint(50) DEFAULT NULL COMMENT '职称',
  `address` varchar(255) DEFAULT NULL,
  `school_id` bigint(20) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `creator` bigint(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `im_token` varchar(200) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'zhangfj', '15510249632', 'fengjin@weibo.com', '$2y$10$bU0MTqWYISqJNoQb3sPwg.K0Lqi6G.XNpNshRHtLZOJF9.Z.JZPFm', '1', '12asdf', '1', '12', '1', null, null, null, '0', null, 'VfL3JyBZM7rr89jVIdgtFfE1nkD62FjOo7Zl9vgtuoT6hkJNM7vUA9eOKaIT', '1212', '0', '2018-01-14 21:45:07', '2018-01-29 15:25:05');
INSERT INTO `users` VALUES ('23', '122', '15510240001', '15510240001@qq.com', '$2y$10$cY4uJKQ2rlPE4QE0JOeFluI7AM6ZbmqYOVuHeyhbA6wQ6kaNKQH5q', '2', '1', '1', '11', null, '8', '1111', '1', '0', null, null, '', '0', '2018-01-29 17:45:00', '2018-01-29 17:45:00');
INSERT INTO `users` VALUES ('24', '123', '15510240002', '15510240002@qq.com', '$2y$10$WIQEOJ1IEERQeRcT5IKS3u2SAjlF4W8yPd/dcQ1lnt.2aV9h6ZTyK', '1', '2', '2', '12', null, '7', '2222', '0', '0', null, null, '', '0', '2018-01-29 17:45:01', '2018-01-29 17:45:01');

-- ----------------------------
-- Table structure for user_courses
-- ----------------------------
DROP TABLE IF EXISTS `user_courses`;
CREATE TABLE `user_courses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `course_id` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_courses
-- ----------------------------
INSERT INTO `user_courses` VALUES ('85', '23', '1', '0', '2018-01-29 17:45:00', '2018-01-29 17:45:00');
INSERT INTO `user_courses` VALUES ('86', '23', '3', '0', '2018-01-29 17:45:00', '2018-01-29 17:45:00');
INSERT INTO `user_courses` VALUES ('87', '24', '3', '0', '2018-01-29 17:45:01', '2018-01-29 17:45:01');

-- ----------------------------
-- Table structure for user_grades
-- ----------------------------
DROP TABLE IF EXISTS `user_grades`;
CREATE TABLE `user_grades` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `grade_id` bigint(20) DEFAULT NULL,
  `flag` smallint(6) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_grades
-- ----------------------------
INSERT INTO `user_grades` VALUES ('37', '23', '4', '0', '2018-01-29 17:45:00', '2018-01-29 17:45:00');
INSERT INTO `user_grades` VALUES ('38', '23', '5', '0', '2018-01-29 17:45:00', '2018-01-29 17:45:00');
INSERT INTO `user_grades` VALUES ('39', '24', '5', '0', '2018-01-29 17:45:01', '2018-01-29 17:45:01');
