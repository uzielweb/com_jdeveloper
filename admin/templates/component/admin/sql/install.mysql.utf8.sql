CREATE TABLE IF NOT EXISTS `#__##table_db##` (
	`##pk##` int(11) unsigned NOT NULL AUTO_INCREMENT,##{start_asset_id}##
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',##{end_asset_id}####{start_table_nested}##
	`parent_id` int(11) unsigned NOT NULL DEFAULT '0',
	`lft` int(11) unsigned NOT NULL DEFAULT '0',
	`rgt` int(11) unsigned NOT NULL DEFAULT '0',
	`level` int(11) unsigned NOT NULL DEFAULT '0',
	`path` varchar(255) NOT NULL DEFAULT '',##{end_table_nested}####{start_catid}##
	`catid` int(11) unsigned NOT NULL DEFAULT '0',##{end_catid}####relations####sql####{start_alias}##
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',##{end_alias}####{start_ordering}##
	`ordering` int(11) NOT NULL DEFAULT '0',##{end_ordering}####{start_published}##
	`published` tinyint(3) NOT NULL DEFAULT '0',##{end_published}####{start_checked_out}##
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',##{end_checked_out}####{start_created}##
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',##{end_created}####{start_created_by}##
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',##{end_created_by}####{start_created_by_alias}##
	`created_by_alias` varchar(255) NOT NULL DEFAULT '',##{end_created_by_alias}####{start_modified}##
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',##{end_modified}####{start_modified_by}##
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',##{end_modified_by}####{start_publish_up}##
	`publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',##{end_publish_up}####{start_publish_down}##
	`publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',##{end_publish_down}####{start_images}##
	`images` text NOT NULL,##{end_images}####{start_version}##
	`version` int(11) unsigned NOT NULL DEFAULT '1',##{end_version}####{start_hits}##
	`hits` int(11) NOT NULL DEFAULT '0',##{end_hits}####{start_access}##
	`access` int(11) unsigned NOT NULL DEFAULT '0',##{end_access}####{start_language}##
	`language` char(7) NOT NULL COMMENT 'The language code for the article.',##{end_language}####{start_params}##
	`params` text NOT NULL,##{end_params}####{start_metadata}##
	`metadata` text NOT NULL,##{end_metadata}####{start_metakey}##
	`metakey` text NOT NULL,##{end_metakey}####{start_metadesc}##
	`metadesc` text NOT NULL,##{end_metadesc}##
	PRIMARY KEY (##pk##)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;
##{start_table_nested}##
INSERT INTO `#__##table_db##` (`##pk##`, `parent_id`, `lft`, `rgt`, `level`, `path`, `##mainfield##`, `alias`##{start_published}##, `published`##{end_published}##) VALUES (NULL, '0', '0', '1', '0', '', 'Root', 'root'##{start_published}##, '1'##{end_published}##);##{end_table_nested}##