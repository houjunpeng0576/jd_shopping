-- 后台用户表
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE IF NOT EXISTS `shop_admin`(
  `adminid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `adminuser` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `adminpass` CHAR(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `adminemail` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `logintime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登陆时间',
  `loginip` BIGINT NOT NULL DEFAULT 0 COMMENT '最后登陆IP',
  `createtime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `valid` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否有效',
  PRIMARY KEY(`adminid`),
  UNIQUE shop_admin_adminuser_adminpass(`adminuser`,`adminpass`),
  UNIQUE shop_admin_adminemail(`adminemail`,`adminpass`)
)ENGINE InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shop_admin`(adminuser, adminpass, adminemail, createtime,valid) VALUES ('admin',md5('111111'),'test@qq.com',UNIX_TIMESTAMP(),1);


-- 用户表
DROP TABLE IF EXISTS `shop_user`;
CREATE TABLE IF NOT EXISTS `shop_user`(
  `userid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `username` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `userpass` CHAR(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `useremail` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `usermobile` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '用户手机',
  `userqq` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户qq',
  `userwb` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户微博',
  `userwx` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户微信',
  `logintime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登陆时间',
  `loginip` BIGINT NOT NULL DEFAULT 0 COMMENT '最后登陆IP',
  `createtime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `valid` TINYINT(4) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否有效',
  PRIMARY KEY(`userid`),
  UNIQUE shop_user_username_userpass(`username`,`userpass`),
  UNIQUE shop_user_useremail_userpass(`useremail`,`userpass`),
  UNIQUE shop_user_usermobile_userpass(`usermobile`,`userpass`),
  UNIQUE shop_user_userqq_userpass(`userqq`,`userpass`),
  UNIQUE shop_user_userwx_userpass(`userwx`,`userpass`),
  UNIQUE shop_user_userwb_userpass(`userwb`,`userpass`)
)ENGINE InnoDB DEFAULT CHARSET=utf8;

-- 用户详情表
DROP TABLE IF EXISTS `shop_user_profile`;
CREATE TABLE IF NOT EXISTS `shop_user_profile`(
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `realname` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `age` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '年龄',
  `sex` ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '性别',
  `avatar` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '头像',
  `birthday` DATE NOT NULL DEFAULT 0 COMMENT '生日',
  `nickname` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `company` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '公司',
  `userid` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户的ID',
  `createtime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY(`id`),
  UNIQUE shop_user_profilr_userid(`userid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;