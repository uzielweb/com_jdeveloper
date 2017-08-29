ALTER TABLE `#__jdeveloper_forms` ADD `relation` VARCHAR(50) NOT NULL DEFAULT '' AFTER `path`;
ALTER TABLE `#__jdeveloper_forms` ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' AFTER `relation`;
ALTER TABLE `#__jdeveloper_forms` CHANGE  `deactivated`  `disabled` TINYINT( 1 ) NOT NULL;