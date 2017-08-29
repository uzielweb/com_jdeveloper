CREATE TABLE IF NOT EXISTS `#__jdeveloper_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  `site` tinyint(1),
  `display_name` varchar(100) NOT NULL,
  `description` tinytext,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(40) NOT NULL,
  `table` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `dbtype` varchar(10) NOT NULL,
  `rule` varchar(50) NOT NULL,
  `label` varchar(40) NOT NULL,
  `description` tinytext,
  `maxlength` smallint(5) NOT NULL,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_forms` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`parent_id` int(11) unsigned NOT NULL DEFAULT '0',
	`lft` int(11) unsigned NOT NULL DEFAULT '0',
	`rgt` int(11) unsigned NOT NULL DEFAULT '0',
	`level` int(11) unsigned NOT NULL DEFAULT '0',
	`path` varchar(255) NOT NULL DEFAULT '',
	`relation` varchar(50) NOT NULL DEFAULT '',
	`tag` varchar(255) NOT NULL DEFAULT '',
	`name` VARCHAR(50) NOT NULL COMMENT 'Field name',
	`type` VARCHAR(50) NOT NULL COMMENT 'Field type',
	`label` VARCHAR(50) NOT NULL COMMENT 'Field label',
	`description` MEDIUMTEXT NOT NULL COMMENT 'Field Description',
	`default` VARCHAR(100) NOT NULL COMMENT 'Field default value',
	`class` VARCHAR(100) NOT NULL COMMENT 'Field CSS class',
	`maxlength` INT(10) NOT NULL COMMENT 'Field size',
	`validation` VARCHAR(50) NOT NULL COMMENT 'Field validation',
	`filter` VARCHAR(50) NOT NULL COMMENT 'Field filter',
	`disabled` TINYINT(1) NOT NULL,
	`readonly` TINYINT(1) NOT NULL,
	`required` TINYINT(1) NOT NULL,
	`options` TEXT NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`params` TEXT NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

INSERT INTO `#__jdeveloper_forms` (`id`, `parent_id`, `lft`, `rgt`, `level`, `path`, `name`, `alias`) VALUES (NULL, '0', '0', '1', '0', '', 'Root', 'root');

CREATE TABLE IF NOT EXISTS `#__jdeveloper_formfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `name` varchar(40) NOT NULL,
  `source` text NOT NULL,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_formrules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `name` varchar(40) NOT NULL,
  `source` text NOT NULL,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(40)NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  `table` varchar(100) NOT NULL,
  `description` tinytext,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_overrides` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `item_id` INT(11) NOT NULL COMMENT 'prmary key of the item',
  `type` varchar(50) NOT NULL COMMENT 'extension / element type',
  `name` varchar(255) NOT NULL COMMENT 'template name',
  `source` mediumtext NOT NULL COMMENT 'template override',
  `params` text NOT NULL COMMENT 'JSON encoded params',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(40)NOT NULL,
  `component` int(11) NOT NULL,
  `plural` varchar(40) NOT NULL,
  `singular` varchar(40) NOT NULL,
  `pk` varchar(40) NOT NULL,
  `jfields` text NOT NULL COMMENT 'JSON encoded jfields',
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(40)NOT NULL,
  `version` varchar(20) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` tinytext,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__jdeveloper_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ordering` smallint(5) NOT NULL COMMENT 'Table ordering',
  `name` varchar(40)NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `version` varchar(20) NOT NULL,
  `folder` varchar(40) NOT NULL,
  `description` tinytext,
  `params` text NOT NULL COMMENT 'JSON encoded params',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;