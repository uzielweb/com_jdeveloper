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

ALTER TABLE `#__jdeveloper_modules` DROP `ordering`;
ALTER TABLE `#__jdeveloper_plugins` DROP `ordering`;
ALTER TABLE `#__jdeveloper_templates` DROP `ordering`;