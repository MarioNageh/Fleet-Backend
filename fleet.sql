/*
 Navicat MySQL Data Transfer

 Source Server         : Server
 Source Server Type    : MySQL
 Source Server Version : 80028
 Source Host           : localhost:3306
 Source Schema         : fleet

 Target Server Type    : MySQL
 Target Server Version : 80028
 File Encoding         : 65001

 Date: 06/05/2022 17:14:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bus
-- ----------------------------
DROP TABLE IF EXISTS `bus`;
CREATE TABLE `bus`  (
  `IdBus` int NOT NULL AUTO_INCREMENT,
  `DriverName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `VehicleRegistrationPlate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `AvailableSeats` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`IdBus`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bus
-- ----------------------------
INSERT INTO `bus` VALUES (1, 'Magdy', '789-GOI', 12);
INSERT INTO `bus` VALUES (2, 'Ami', '249-MIW', 2);

-- ----------------------------
-- Table structure for city
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city`  (
  `IdCity` int NOT NULL AUTO_INCREMENT,
  `CityNameEn` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CityNameAr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdCity`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of city
-- ----------------------------
INSERT INTO `city` VALUES (1, 'Cairo', 'القاهرة', '2022-05-06 16:55:18', '2022-05-06 16:55:18');
INSERT INTO `city` VALUES (2, 'Giza', 'الجيزة', '2022-05-06 16:55:56', '2022-05-06 16:55:56');
INSERT INTO `city` VALUES (3, 'Alex', 'الاسكندرية', '2022-05-06 16:56:28', '2022-05-06 16:56:28');
INSERT INTO `city` VALUES (4, 'Alfayum', 'الفيوم', '2022-05-06 16:56:37', '2022-05-06 16:56:37');
INSERT INTO `city` VALUES (5, 'AlMinya', 'المنيا', '2022-05-06 16:56:43', '2022-05-06 16:56:43');
INSERT INTO `city` VALUES (6, 'Asyut', 'اسيوط', '2022-05-06 16:56:48', '2022-05-06 16:56:48');
INSERT INTO `city` VALUES (7, 'Aswan', 'اسوان', '2022-05-06 16:56:51', '2022-05-06 16:56:51');
INSERT INTO `city` VALUES (8, 'Monofeya', 'المنوفية', '2022-05-06 16:56:59', '2022-05-06 16:56:59');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
INSERT INTO `personal_access_tokens` VALUES (1, 'App\\Models\\User', 3, 'Token', 'bc9f009899ea8faf42e758cbeccf2664059636aaff94e41f9101626bc987ec97', '[\"*\"]', '2022-05-06 15:13:37', '2022-05-06 14:57:26', '2022-05-06 15:13:37');

-- ----------------------------
-- Table structure for reservation
-- ----------------------------
DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation`  (
  `IdReservation` int NOT NULL AUTO_INCREMENT,
  `IdBus` int NULL DEFAULT NULL,
  `IdTrip` int NULL DEFAULT NULL,
  `IdPathTripStart` int NULL DEFAULT NULL,
  `IdPathTripEnd` int NULL DEFAULT NULL,
  `IdWorkingHour` int NULL DEFAULT NULL,
  `IdUser` bigint UNSIGNED NULL DEFAULT NULL,
  `BusLocation` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ReservationDate` date NULL DEFAULT NULL,
  `IsArrived` tinyint NULL DEFAULT 0,
  `CreationTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdReservation`) USING BTREE,
  INDEX `buss`(`IdBus` ASC) USING BTREE,
  INDEX `tripp`(`IdTrip` ASC) USING BTREE,
  INDEX `startPath`(`IdPathTripStart` ASC) USING BTREE,
  INDEX `endPath`(`IdPathTripEnd` ASC) USING BTREE,
  INDEX `whoure`(`IdWorkingHour` ASC) USING BTREE,
  INDEX `ussser`(`IdUser` ASC) USING BTREE,
  CONSTRAINT `buss` FOREIGN KEY (`IdBus`) REFERENCES `bus` (`IdBus`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `endPath` FOREIGN KEY (`IdPathTripEnd`) REFERENCES `trippath` (`IdTripPath`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `startPath` FOREIGN KEY (`IdPathTripStart`) REFERENCES `trippath` (`IdTripPath`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tripp` FOREIGN KEY (`IdTrip`) REFERENCES `trip` (`IdTrip`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `whoure` FOREIGN KEY (`IdWorkingHour`) REFERENCES `working_hours` (`IdWorkingHour`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ussser` FOREIGN KEY (`IdUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reservation
-- ----------------------------

-- ----------------------------
-- Table structure for trip
-- ----------------------------
DROP TABLE IF EXISTS `trip`;
CREATE TABLE `trip`  (
  `IdTrip` int NOT NULL AUTO_INCREMENT,
  `TripName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IdBus` int NULL DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdTrip`) USING BTREE,
  INDEX `bus`(`IdBus` ASC) USING BTREE,
  CONSTRAINT `bus` FOREIGN KEY (`IdBus`) REFERENCES `bus` (`IdBus`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trip
-- ----------------------------
INSERT INTO `trip` VALUES (1, 'Cairo-Minya', 2, '2022-05-06 16:54:14', '2022-05-06 16:54:14');

-- ----------------------------
-- Table structure for trippath
-- ----------------------------
DROP TABLE IF EXISTS `trippath`;
CREATE TABLE `trippath`  (
  `IdTripPath` int NOT NULL AUTO_INCREMENT,
  `IdCity` int NULL DEFAULT NULL,
  `IdTrip` int NULL DEFAULT NULL,
  `Order` smallint NULL DEFAULT NULL,
  `NextIdTripPath` int NULL DEFAULT NULL,
  PRIMARY KEY (`IdTripPath`) USING BTREE,
  INDEX `city`(`IdCity` ASC) USING BTREE,
  INDEX `trip`(`IdTrip` ASC) USING BTREE,
  CONSTRAINT `city` FOREIGN KEY (`IdCity`) REFERENCES `city` (`IdCity`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trip` FOREIGN KEY (`IdTrip`) REFERENCES `trip` (`IdTrip`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trippath
-- ----------------------------
INSERT INTO `trippath` VALUES (1, 1, 1, 1, 2);
INSERT INTO `trippath` VALUES (2, 4, 1, 2, 3);
INSERT INTO `trippath` VALUES (3, 5, 1, 3, 4);
INSERT INTO `trippath` VALUES (4, 6, 1, 4, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (3, 'Marioo', 'm@mm.com', '$2y$10$Px3yRKxvii6cV5WsYYsveuSR2zv3rSjdqRkFsBe1ucXWEKjBvh20G', '2022-05-06 14:57:24', '2022-05-06 14:57:24');

-- ----------------------------
-- Table structure for working_hours
-- ----------------------------
DROP TABLE IF EXISTS `working_hours`;
CREATE TABLE `working_hours`  (
  `IdWorkingHour` int NOT NULL AUTO_INCREMENT,
  `IdTrip` int NULL DEFAULT NULL,
  `DayOfWeek` tinyint NULL DEFAULT NULL,
  `TripStatringTime` time NULL DEFAULT NULL,
  `TripEndingTime` time NULL DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdWorkingHour`) USING BTREE,
  INDEX `whour`(`IdTrip` ASC) USING BTREE,
  CONSTRAINT `whour` FOREIGN KEY (`IdTrip`) REFERENCES `trip` (`IdTrip`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of working_hours
-- ----------------------------
INSERT INTO `working_hours` VALUES (1, 1, 0, '18:00:00', '20:00:00', '2022-05-06 17:07:52', '2022-05-06 17:07:52');

SET FOREIGN_KEY_CHECKS = 1;
