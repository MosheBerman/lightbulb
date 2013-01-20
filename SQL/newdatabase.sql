CREATE TABLE `Users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `isWatchingCourses` tinyint(1) NOT NULL DEFAULT '0',
  `password` char(60) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=ascii;

CREATE TABLE `Sections` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `courseID` int(5) NOT NULL,
  `section` varchar(5) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `code` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `openSeats` int(3) NOT NULL,
  `dayAndTime` text CHARACTER SET latin1 NOT NULL,
  `buildingAndRoom` text CHARACTER SET latin1 NOT NULL,
  `isOnline` tinyint(1) NOT NULL,
  `instructor` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2753 DEFAULT CHARSET=ascii;

CREATE TABLE `History` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `changeType` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `message` text CHARACTER SET latin1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

CREATE TABLE `FollowedSections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `code` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=ascii;

CREATE TABLE `Courses` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `startDate` text CHARACTER SET latin1,
  `endDate` text CHARACTER SET latin1,
  `name` text CHARACTER SET latin1,
  `description` text CHARACTER SET latin1,
  `credits` int(11) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `division` tinyint(1) DEFAULT NULL,
  `subject` text CHARACTER SET latin1,
  `lastUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1488 DEFAULT CHARSET=ascii;