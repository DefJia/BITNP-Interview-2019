Drop DATABASE IF EXISTS `bitnpInterview`;
CREATE DATABASE IF NOT EXISTS `bitnpInterview` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bitnpInterview`;

CREATE TABLE IF NOT EXISTS `cmt` (
  `name` text NOT NULL,
  `cmt` text,
  `interviewee` text,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `info` (
  `id` int(3) NOT NULL PRIMARY KEY,
  `name` varchar(12) NOT NULL,
  `sex` varchar(3) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `major` varchar(33) DEFAULT NULL,
  `first` varchar(15) DEFAULT NULL,
  `second` varchar(15) DEFAULT NULL,
  `third` varchar(15) DEFAULT NULL,
  `is_` varchar(1) DEFAULT NULL,
  `intro` varchar(2276) DEFAULT NULL,
  `why` varchar(1391) DEFAULT NULL,
  `birthtime` varchar(10) DEFAULT NULL,
  `qqid` varchar(10) DEFAULT NULL,
  `wechatid` varchar(30) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `record` (
  `id` int(3) NOT NULL PRIMARY KEY,
  `name` varchar(12) DEFAULT NULL,
  `date` varchar(10) DEFAULT NULL,
  `time` varchar(5) DEFAULT NULL,
  `room` varchar(6) DEFAULT NULL,
  `status` int(2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(10) NOT NULL,
  `password` text NOT NULL,
  `type` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cmt`
  ADD PRIMARY KEY (`time`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

ALTER TABLE `info` CHANGE `id` `id` INT(3) NOT NULL AUTO_INCREMENT;