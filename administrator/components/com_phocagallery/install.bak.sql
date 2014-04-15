DROP TABLE IF EXISTS `jos_phocagallery`;
CREATE TABLE `jos_phocagallery` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL Default '0',
  `sid` int(11) NOT NULL Default '0',
  `title` varchar(250) NOT NULL Default '',
  `alias` varchar(255) NOT NULL Default '',
  `filename` varchar(250) NOT NULL Default '',
  `description` text,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL Default '0',
  `latitude` varchar(20) NOT NULL Default '',
  `longitude` varchar(20) NOT NULL Default '',
  `zoom` int(3) NOT NULL Default '0',
  `geotitle` varchar(255) NOT NULL Default '',
  `userid` int(11) NOT NULL Default '0',
  `videocode` text,
  `vmproductid` int(11) NOT NULL Default '0',
  `imgorigsize` int(11) NOT NULL Default '0',
  `published` tinyint(1) NOT NULL Default '0',
  `approved` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `extlink1` text,
  `extlink2` text,
  `exttype` tinyint(1) NOT NULL Default '0',
  `extid` varchar(255) NOT NULL Default '',
  `extl` varchar(255) NOT NULL Default '',
  `extm` varchar(255) NOT NULL Default '',
  `exts` varchar(255) NOT NULL Default '',
  `exto` varchar(255) NOT NULL Default '',
  `extw` varchar(255) NOT NULL Default '',
  `exth` varchar(255) NOT NULL Default '',
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jos_phocagallery_categories`;
CREATE TABLE `jos_phocagallery_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL Default 0,
  `owner_id` int(11) NOT NULL Default 0,
  `title` varchar(255) NOT NULL Default '',
  `name` varchar(255) NOT NULL Default '',
  `alias` varchar(255) NOT NULL Default '',
  `image` varchar(255) NOT NULL Default '',
  `section` varchar(50) NOT NULL Default '',
  `image_position` varchar(30) NOT NULL Default '',
  `description` text,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL Default '0',
  `approved` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `editor` varchar(50) Default NULL,
  `ordering` int(11) NOT NULL Default '0',
  `access` tinyint(3) unsigned NOT NULL Default '0',
  `hits` int(11) NOT NULL Default '0',
  `count` int(11) NOT NULL Default '0',
  `accessuserid` text,
  `uploaduserid` text,
  `deleteuserid` text,
  `userfolder` text,
  `latitude` varchar(20) NOT NULL Default '',
  `longitude` varchar(20) NOT NULL Default '',
  `zoom` int(3) NOT NULL Default '0',
  `geotitle` varchar(255) NOT NULL Default '',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `extid` varchar(255) NOT NULL Default '',
  `exta` varchar(255) NOT NULL Default '',
  `extu` varchar(255) NOT NULL Default '',
  `extauth` varchar(255) NOT NULL Default '',
  `extfbuid` int(11) NOT NULL Default '0' '',
  `extfbcatid` varchar(255) NOT NULL Default '',
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
)   DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jos_phocagallery_votes`;
CREATE TABLE `jos_phocagallery_votes` (
  `id` int(11) NOT NULL auto_increment,
  `catid` int(11) NOT NULL Default 0,
  `userid` int(11) NOT NULL Default 0,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `rating` tinyint(1) NOT NULL Default '0',
  `published` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
)   DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jos_phocagallery_comments`;
CREATE TABLE `jos_phocagallery_comments` (
  `id` int(11) NOT NULL auto_increment,
  `catid` int(11) NOT NULL Default 0,
  `userid` int(11) NOT NULL Default 0,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL Default '',
  `comment` text,
  `published` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
)   DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jos_phocagallery_votes_statistics`;
CREATE TABLE `jos_phocagallery_votes_statistics` (
  `id` int(11) NOT NULL auto_increment,
  `catid` int(11) NOT NULL Default 0,
  `count` int(11) NOT NULL Default '0',
  `average` float(8,6) NOT NULL Default '0',
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
)   DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jos_phocagallery_img_votes`;
CREATE TABLE `jos_phocagallery_img_votes` (
  `id` int(11) NOT NULL auto_increment,
  `imgid` int(11) NOT NULL Default 0,
  `userid` int(11) NOT NULL Default 0,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `rating` tinyint(1) NOT NULL Default '0',
  `published` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jos_phocagallery_img_votes_statistics`;
CREATE TABLE `jos_phocagallery_img_votes_statistics` (
  `id` int(11) NOT NULL auto_increment,
  `imgid` int(11) NOT NULL Default 0,
  `count` int(11) NOT NULL Default '0',
  `average` float(8,6) NOT NULL Default '0',
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `jos_phocagallery_user`;
CREATE TABLE `jos_phocagallery_user` (
 `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL Default 0,
  `avatar` varchar(40) NOT NULL Default '',
  `published` tinyint(1) NOT NULL Default '0',
  `approved` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jos_phocagallery_img_comments`;
CREATE TABLE `jos_phocagallery_img_comments` (
  `id` int(11) NOT NULL auto_increment,
  `imgid` int(11) NOT NULL Default 0,
  `userid` int(11) NOT NULL Default 0,
  `date` datetime NOT NULL Default '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL Default '',
  `comment` text,
  `published` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

-- since 3.0.0

DROP TABLE IF EXISTS `jos_phocagallery_fb_users`;
CREATE TABLE `jos_phocagallery_fb_users` (
  `id` int(11) NOT NULL auto_increment,
  `appid` varchar(255) NOT NULL Default '',
  `appsid` varchar(255) NOT NULL Default '',
  `uid` varchar(255) NOT NULL Default '',
  `name` varchar(255) NOT NULL Default '',
  `link` varchar(255) NOT NULL Default '',
  `secret` varchar(255) NOT NULL Default '',
  `base_domain` varchar(255) NOT NULL Default '',
  `expires` varchar(100) NOT NULL Default '',
  `session_key` text,
  `access_token` text,
  `sig` text,
  `published` tinyint(1) NOT NULL Default '0',
  `checked_out` int(11) unsigned NOT NULL Default '0',
  `checked_out_time` datetime NOT NULL Default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL Default '0',
  `comments` text,
  `params` text,
  `language` char(7) NOT NULL Default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;



-- 3.0.0 UPDATE ONLY (to 2.7.1)
-- ALTER TABLE `#__phocagallery` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_categories` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_comments` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_votes` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_votes_statistics` ADD `language` char(7) NOT NULL Default '' AFTER `average` ;  
-- ALTER TABLE `#__phocagallery_img_votes` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_img_votes_statistics` ADD `language` char(7) NOT NULL Default '' AFTER `average` ;  
-- ALTER TABLE `#__phocagallery_user` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_img_comments` ADD `language` char(7) NOT NULL Default '' AFTER `params` ;  

-- ALTER TABLE `#__phocagallery` ADD `metadata` text AFTER `params` ;  
-- ALTER TABLE `#__phocagallery_categories` ADD `metadata` text AFTER `params` ; 

-- ALTER TABLE `#__phocagallery_categories` ADD `extfbuid` int(11) NOT NULL Default '0' AFTER `params` ; 
-- ALTER TABLE `#__phocagallery_categories` ADD `extfbcatid` varchar(255) NOT NULL Default '' AFTER `params` ;
-- ALTER TABLE `#__phocagallery` ADD `exttype` tinyint(1) NOT NULL Default '0' AFTER `params` ;

-- 3.0.0 RC3 (to 3.0.0RC2) 
-- ALTER TABLE `#__phocagallery_fb_users` ADD `comments` text AFTER `params` ;

