CREATE TABLE `user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(64) NOT NULL,
  `user_password` varchar(64) NOT NULL,
  `user_balance` bigint NOT NULL,
  `user_precision` tinyint,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` 
SET `user_login` = 'bitappuser', 
`user_password` = sha1('123'), 
`user_balance` = 200000000;