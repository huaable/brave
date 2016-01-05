drop database if exists brave_cms;
create database if not EXISTS brave_cms;
use brave_cms;
/* 网站用户 */
CREATE TABLE `cms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL  DEFAULT  '' COMMENT '名称',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(32) NOT NULL,
  `auth_key` varchar(13) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT '手机',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `created` int(11) NOT NULL DEFAULT '0',
  `modified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

/* 文章 */
CREATE TABLE `cms_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL  DEFAULT  '' COMMENT '作者名称',
  `author_id` int(11) NOT NULL DEFAULT 0  COMMENT '作者ID',
  `title` varchar(255) NOT NULL  DEFAULT  '' COMMENT '标题',
  `summary` TEXT COMMENT '简介',
  `content` TEXT COMMENT '内容',
  `count_view` int(11) NOT NULL DEFAULT 0 COMMENT '阅读数',
  `count_like` int(11) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `count_comment` int(11) NOT NULL DEFAULT 0 COMMENT '评论数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `created` int(11) NOT NULL DEFAULT '0',
  `modified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

/* 网站附件表 */
CREATE TABLE `cms_disk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL  DEFAULT  '' COMMENT '文件名',
  `description` varchar(255) NOT NULL  DEFAULT  '' COMMENT '文件描述',
  `file` varchar(255) NOT NULL  DEFAULT  '' COMMENT '文件路径',
  `type` int(11) NOT NULL  DEFAULT  '1' COMMENT '文件类型',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `created` int(11) NOT NULL DEFAULT '0',
  `modified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

/* 文章标签 */
CREATE TABLE `cms_article_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL  COMMENT '文章ID',
  `name` varchar(255) NOT NULL  DEFAULT  '' COMMENT '名称',
  `created` int(11) NOT NULL DEFAULT '0',
  `modified` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;

/* 栏目 */
CREATE TABLE IF NOT EXISTS cms_category(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL COMMENT '英文名称',
	`alias` VARCHAR(255) NOT NULL COMMENT '栏目名称',
	`type` INT NOT NULL DEFAULT 2 COMMENT '类型',
	`parent_id` INT NOT NULL DEFAULT '0' COMMENT '父级',
	`path` VARCHAR(255) NOT NULL DEFAULT '0,',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
	`created` INT NOT NULL DEFAULT 0,
	`modified` INT NOT NULL DEFAULT 0,
  UNIQUE KEY `name` (`name`)
)ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=UTF8;

/* 栏目稿件 */
CREATE TABLE IF NOT EXISTS cms_category_article(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`article_id` int(11) NOT NULL  COMMENT '稿件ID',
	`author` int(11) NOT NULL  COMMENT '投稿人',
	`author_id` int(11) NOT NULL  COMMENT '投稿人ID',
	`category_id` int(11) NOT NULL DEFAULT 0 COMMENT '栏目ID',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
	`created` INT NOT NULL DEFAULT 0,
	`modified` INT NOT NULL DEFAULT 0
)ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=UTF8;

/* 文章评论 */
CREATE TABLE IF NOT EXISTS cms_article_comment(
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`article_id` int(11) NOT NULL  COMMENT '文章ID',
	`user_id` int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
	`username` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '评论用户名',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
	`created` INT NOT NULL DEFAULT 0,
	`modified` INT NOT NULL DEFAULT 0
)ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=UTF8;
