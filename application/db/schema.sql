/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

SET foreign_key_checks = 0;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
	`session_id` varchar(40) NOT NULL DEFAULT '0',
	`ip_address` varchar(45) NOT NULL DEFAULT '0',
	`user_agent` varchar(120) NOT NULL,
	`last_activity` int(10) unsigned NOT NULL DEFAULT '0',
	`user_data` text NOT NULL,
	PRIMARY KEY (`session_id`),
	KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `food` (
	`name` varchar(42) NOT NULL DEFAULT '',
	`grams_per_serving` smallint(5) unsigned NOT NULL DEFAULT '0',
	`carbohydrates` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`calories` smallint(5) unsigned NOT NULL DEFAULT '0',
	`fiber` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`protein` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`fat` tinyint(3) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `glucose` (
	`level_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(10) unsigned DEFAULT NULL,
	`value` smallint(5) unsigned NOT NULL DEFAULT '0',
	`time` datetime NOT NULL,
	`notes` varchar(83) DEFAULT NULL,
	PRIMARY KEY (`level_id`),
	KEY `who_when` (`user_id`,`time`),
	CONSTRAINT `glucose_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `insulin` (
	`injection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int(10) unsigned DEFAULT NULL,
	`units` decimal(4,1) NOT NULL DEFAULT '0.0',
	`time` datetime NOT NULL,
	`type` enum('glargine','lispro') NOT NULL DEFAULT 'lispro',
	`notes` varchar(83) DEFAULT NULL,
	PRIMARY KEY (`injection_id`),
	KEY `who_when` (`user_id`,`time`),
	CONSTRAINT `insulin_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `user` (
	`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(83) DEFAULT NULL,
	PRIMARY KEY (`user_id`),
	UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET foreign_key_checks = 1;
