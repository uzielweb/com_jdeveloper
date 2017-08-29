CREATE TABLE IF NOT EXISTS `#__jdeveloper_forms` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`parent_id` int(11) unsigned NOT NULL DEFAULT '0',
	`lft` int(11) unsigned NOT NULL DEFAULT '0',
	`rgt` int(11) unsigned NOT NULL DEFAULT '0',
	`level` int(11) unsigned NOT NULL DEFAULT '0',
	`path` varchar(255) NOT NULL DEFAULT '',
	`name` VARCHAR(50) NOT NULL COMMENT 'Field name',
	`type` VARCHAR(50) NOT NULL COMMENT 'Field type',
	`label` VARCHAR(50) NOT NULL COMMENT 'Field label',
	`description` MEDIUMTEXT NOT NULL COMMENT 'Field Description',
	`default` VARCHAR(100) NOT NULL COMMENT 'Field default value',
	`class` VARCHAR(100) NOT NULL COMMENT 'Field CSS class',
	`maxlength` INT(10) NOT NULL COMMENT 'Field size',
	`validation` VARCHAR(50) NOT NULL COMMENT 'Field validation',
	`filter` VARCHAR(50) NOT NULL COMMENT 'Field filter',
	`deactivated` TINYINT(1) NOT NULL,
	`readonly` TINYINT(1) NOT NULL,
	`required` TINYINT(1) NOT NULL,
	`options` TEXT NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`params` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

INSERT INTO `#__jdeveloper_forms` (`id`, `parent_id`, `lft`, `rgt`, `level`, `path`, `name`, `alias`) VALUES (NULL, '0', '0', '1', '0', '', 'Root', 'root');