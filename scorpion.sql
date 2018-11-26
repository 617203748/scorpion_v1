/*
Navicat MySQL Data Transfer

Source Server         : MySql
Source Server Version : 50634
Source Host           : 127.0.0.1:3306
Source Database       : scorpion

Target Server Type    : MYSQL
Target Server Version : 50634
File Encoding         : 65001

Date: 2018-11-26 10:27:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `test_table`
-- ----------------------------
DROP TABLE IF EXISTS `test_table`;
CREATE TABLE `test_table` (
  `id` bigint(20) NOT NULL DEFAULT '-1',
  `name` varchar(255) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of test_table
-- ----------------------------
INSERT INTO `test_table` VALUES ('1', 'test_data');
