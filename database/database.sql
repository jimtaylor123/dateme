SET FOREIGN_KEY_CHECKS = 0;

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
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `gender`, `dateOfBirth`, `lat`, `lng`) VALUES
('1', 'Alex Smith', 'pearline.hand@gmail.com', 'e327b5206ab0fbf000c01df084035298d8246381a18e21ada7d0dc6c35a3a7b17f4d38c49daa70bba27d344306eab1de1779cbe099858cffcd6389db7a162c01', 'male', '1977-08-10', '53.48395900', '-2.24464400'),
('2', 'Bob Smith', 'garrick.collier@gmail.com', '02eb9da5ebbf853efc3e06684c4100320e2feb226fcf627cce8ed03b94b4187d65981e759bdca82a351f9aac18c23e89c06bb9a7fb0c87a3a4581c696aedf52e', 'male', '1987-03-12', '53.48395900', '-2.24464400'),
('3', 'Charlie Smith', 'coty.abernathy@bins.com', 'f5b16dca7ff802355022d07200d87eeee5972420702ca097ea2917e647499c45e545d6dd5e4830e9446076c5fc0f2f0ed85898fc42f638cc9abcc0c09a7b75c3', 'male', '1953-06-20', '53.48395900', '-2.24464400'),
('4', 'Del Smith', 'larry85@thompson.com', '6bc93a24f6eef46b9a6bf742839497dff95f1aebe920f46700ecaa2006a9a6874bc388c3fb35cb77425a8ff26963bd41f464629f935d459062985a37694bb84f', 'male', '1961-09-21', '53.40480000', '-2.98333300'),
('5', 'Eric Smith', 'snitzsche@hotmail.com', '47224abd435175ede61455bbfd1d1f828cbdb28bdf6330f623a659672ade2bcbbbc900b5e3b4a7bf1962a28eaa4cf3a7f57b6d670b572af4a69d586cb3e85dc9', 'male', '1948-12-04', '53.40480000', '-2.98333300'),
('6', 'Fred Smith', 'aliyah94@hotmail.com', '770c8302658d855a96d1c206e21d7ac44f42e557a999934108333b58a706080711c729e1596db44d0f38cdac4531ae317307af8a2d18e1281e96546266f257f6', 'male', '1958-12-17', '55.95325100', '-3.18826700'),
('7', 'George Smith', 'murray.shad@parker.org', '3894dd6c253ca7703d571e5c5b3d7fca4edc9a735721bc06bc51f8efdbb866b8eeae0981fa94cb199eeb10e55bbc5e972a1b09200766d5b4b24fdf1f7344b1df', 'male', '1948-04-19', '55.95325100', '-3.18826700'),
('8', 'Mrs. Enola Kihn Sr.', 'carroll.clifford@dooley.com', '4bf3898a824c58b1555ae306eb6cea609c563f3f42dc1dc9886f41f76332e34aa4d4b124b9e23d35e62a43120dc859462e8f695db415c8ccd2a681f163704291', 'other', '1938-03-25', '55.95325100', '-3.18826700'),
('9', 'Ms. Ida Harris PhD', 'bartell.toby@gmail.com', '266475f46c66901fac8b36db6907f8de0289451ddd5f5735e75868099a029d29e004245f7b15afed9c4fc8e8e552be62e492aa1f64c758e01a710e01ace1324b', 'other', '1977-01-03', '53.48395900', '-2.24464400'),
('10', 'Hailie Von', 'kitty.larkin@mante.biz', '272f9c99544c99111c6b84a1cb48bc176df566a2b2d8144d5ac0de3ec3f4c27eccfd71f9a9ae78288ae04794c8f4633cd2ccf27b2b9751e1f56b4ae1886e3afc', 'other', '2000-02-10', '55.95325100', '-3.18826700'),
('11', 'Gillian Rippin', 'omer.price@yahoo.com', 'f7a5c2761d75ec1f959aab1c1aa260b13efec732f7d5144369729aff0980e21753b4918031a54cef57ac2971c94e583d9870ae0cc46fd00155e93e16bbb6dd33', 'female', '1997-08-05', '53.40480000', '-2.98333300'),
('12', 'Roselyn Smith DVM', 'ned.schroeder@lowe.com', '2d32d7e644c636ceea3af68bea49a377baa4ca35e7cba006a74c572ec774cad03e46c0be894d1d7ddb40b7836ff7cc02582454dad775a2f78a964cc19f2f2b15', 'female', '1955-04-08', '53.40480000', '-2.98333300'),
('13', 'Brenda Schmeler', 'kulas.amina@gmail.com', 'c43c1e630e91630a9e8439b2f562156be49ea020f145d001a313cbd9f17dba80b36205ee5f1832ca51ae165d4efa51328387bf68696da3948b26875d1d06878d', 'female', '1921-06-14', '53.48395900', '-2.24464400'),
('14', 'Glenda Schoen', 'soberbrunner@terry.com', 'a74d3ecfe0828ce951f05a9332094f0e7f426b4996de0b750bbcd167b16374b10abb1af8d7a2e2e8a7497b7031421610e5ba414ec01f45afafff83731c7ecbc4', 'female', '1923-11-05', '53.48395900', '-2.24464400');

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
  CONSTRAINT `fk_swipes_users_user_id` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_swipes_users_profile_id` FOREIGN KEY (`profileId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `swipes` (`id`, `userId`, `profileId`, `preference`, `createdAt`) VALUES
('1', '8', '1', 'yes', '2020-11-29'),
('2', '11', '1', 'yes', '2020-11-29'),
('3', '12', '1', 'yes', '2020-11-29'),
('4', '8', '2', 'yes', '2020-11-29'),
('5', '11', '2', 'yes', '2020-11-29'),
('6', '8', '4', 'yes', '2020-11-29'),
('7', '11', '4', 'yes', '2020-11-29'),
('8', '12', '4', 'yes', '2020-11-29'),
('9', '8', '5', 'yes', '2020-11-29'),
('10', '11', '5', 'yes', '2020-11-29'),
('11', '11', '6', 'yes', '2020-11-29'),
('12', '12', '6', 'yes', '2020-11-29');

-- ----------------------------
-- Table structure for images
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL UNIQUE,
  `createdAt` date NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_images_users__user_id` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `images` (`id`, `userId`, `name`, `createdAt`) VALUES
('1', '1', '5fc576ec30ddc7-91245857.jpeg', '2020-11-29'),
('2', '1', '5fc576ec30dff7-48701123.jpeg', '2020-11-29'),
('6', '1', '6fc576ec30dff7-48701123.jpeg', '2020-11-29'),
('7', '2', '5fc57746499d57-49161491.jpeg', '2020-11-29'),
('8', '2', '5fc57746499f72-82957829.jpeg', '2020-11-29'),
('9', '3', '5fc5774795da29-92618862.jpeg', '2020-11-29'),
('10', '4', '5fc5774795e721-45924876.jpeg', '2020-11-29'),
('11', '4', '5fc57748ef9022-68368335.jpeg', '2020-11-29'),
('12', '4', '5fc57748ef9488-70292482.jpeg', '2020-11-29'),
('13', '5', '5fc5774a33e127-49051697.jpeg', '2020-11-29'),
('14', '5', '5fc5774a33e364-05170860.jpeg', '2020-11-29'),
('15', '7', '5fc5774b6de626-75632180.jpeg', '2020-11-29'),
('16', '7', '5fc5774b6de8e4-01422771.jpeg', '2020-11-29'),
('17', '7', '5fc5774c932612-15081999.jpeg', '2020-11-29'),
('18', '14', '5fc5774c9338b3-05961508.jpeg', '2020-11-29'),
('19', '14', '5fc5774de68eb6-58292301.jpeg', '2020-11-29'),
('20', '14', '5fc5774de6a0f1-07091409.jpeg', '2020-11-29');

SET FOREIGN_KEY_CHECKS = 1;
