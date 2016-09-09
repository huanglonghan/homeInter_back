CREATE TABLE `user` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `accountID` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '账户ID',
  `passwd` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `registerTime` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `accountID` (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;