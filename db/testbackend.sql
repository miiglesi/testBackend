/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : testbackend

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 07/23/2018 17:33:28 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `roles` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `users`
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('1', 'admin', '$2y$10$4gSqNbKkCnlFz45UCr1Y0eHjamx81LlrXO7Xjyl1tMcVpPr5egtd6', '1,2,3,4'), ('3', 'usuario1', '$2y$10$A3hisSRgu8hzQJ1jDqVEb.iaZmlmJAL1wfX1eOzKezem9IvjWleDa', '2'), ('4', 'usuario2', '$2y$10$K1SVTNb2jG484rO16PsWDObUOAcWrmgMpEbgdQmumf5pILPWcc4O6', '3'), ('5', 'usuario3', '$2y$10$y69cC.QMRJjO9X54RTE7TOxDcdARZW5nCVR4T0V9AvlOe/Lv.Q1SO', '4'), ('6', 'usuario4', '$2y$10$WxyJ1WH7aOyMEMmu0LbdTeDTk.tlEoQwv6JqnQWo6k5JrAL4HEp3i', '2,3');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
