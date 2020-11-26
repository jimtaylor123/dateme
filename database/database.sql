SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for swipes
-- ----------------------------
DROP TABLE IF EXISTS `swipes`;
CREATE TABLE IF NOT EXISTS `swipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `profileId` int(11) NOT NULL,
  `preference` ENUM('yes', 'no') NOT NULL,
  `createdAt` date NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `user_users_fk` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `profile_users_fk` FOREIGN KEY (`profileId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL UNIQUE,
  `password` varchar(128) NOT NULL,
  `gender` ENUM('male', 'female', 'other') NOT NULL,
  `dateOfBirth` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
