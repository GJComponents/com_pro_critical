CREATE TABLE IF NOT EXISTS `#__pro_critical_css` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` VARCHAR(255) NOT NULL DEFAULT '',
	`critical_css_code` TEXT NOT NULL,
	`option` INT(11) NOT NULL DEFAULT 0,
	`photo_afte` VARCHAR(1024) NOT NULL DEFAULT '',
	`photo_before` VARCHAR(1024) NOT NULL DEFAULT '',
	`pro_critical_url_id` INT NOT NULL DEFAULT 0,
	`query_params` TEXT NOT NULL,
	`query_string` VARCHAR(255) NOT NULL DEFAULT '',
	`type_device_id` INT(11) NOT NULL DEFAULT 0,
	`user_agent_id` INT NOT NULL DEFAULT 0,
	`view` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_query_string` (`query_string`),
	KEY `idx_alias` (`alias`),
	KEY `idx_user_agent_id` (`user_agent_id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_cache` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`cache` LONGTEXT NOT NULL,
	`hash` VARCHAR(50) NOT NULL DEFAULT '',
	`pro_critical_url_id` INT NOT NULL DEFAULT 0,
	`type` VARCHAR(64) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_user_agent` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`brand` VARCHAR(50) NOT NULL DEFAULT '',
	`browser_id_list` VARCHAR(11) NOT NULL DEFAULT '',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`os_list` VARCHAR(100) NOT NULL DEFAULT '',
	`type_browser_id` INT NOT NULL DEFAULT 0,
	`type_device_id` INT(11) NOT NULL DEFAULT 0,
	`ua` VARCHAR(1024) NOT NULL DEFAULT '',
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_user_agent_os` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`code` VARCHAR(255) NOT NULL DEFAULT '',
	`family` VARCHAR(255) NOT NULL DEFAULT '',
	`family_vendor` VARCHAR(255) NOT NULL DEFAULT '',
	`hache` CHAR(50) NOT NULL DEFAULT '',
	`icon` VARCHAR(255) NOT NULL DEFAULT '',
	`icon_large` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `idx_hache` (`hache`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_user_agent_browser` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`engine` VARCHAR(255) NOT NULL DEFAULT '',
	`hache` CHAR(50) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`version` VARCHAR(255) NOT NULL DEFAULT '',
	`version_major` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `idx_hache` (`hache`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_device_client` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`avail_height` INT(10) NOT NULL DEFAULT 0,
	`avail_left` VARCHAR(10) NOT NULL DEFAULT '',
	`avail_top` VARCHAR(10) NOT NULL DEFAULT '',
	`avail_width` INT(10) NOT NULL DEFAULT 0,
	`color_depth` VARCHAR(10) NOT NULL DEFAULT '',
	`hache` CHAR(50) NOT NULL DEFAULT '',
	`height` INT(10) NOT NULL DEFAULT 0,
	`orientation` CHAR(50) NOT NULL DEFAULT '',
	`pixel_depth` VARCHAR(10) NOT NULL DEFAULT '',
	`width` INT(10) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `idx_hache` (`hache`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_admins` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`admin_name` VARCHAR(255) NOT NULL DEFAULT '',
	`admin_panel_on` INT(11) NOT NULL DEFAULT 0,
	`admin_password` VARCHAR(64) NOT NULL DEFAULT '',
	`ip_user_admin` VARCHAR(255) NOT NULL DEFAULT '',
	`user_as_admin_id` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_html_task` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`component_view_id` VARCHAR(255) NOT NULL DEFAULT '',
	`description` VARCHAR(1024) NOT NULL DEFAULT '',
	`event_show` VARCHAR(64) NOT NULL DEFAULT '',
	`html_processing` VARCHAR(64) NOT NULL DEFAULT '',
	`id_component` INT(11) NOT NULL DEFAULT 0,
	`link` VARCHAR(255) NOT NULL DEFAULT '',
	`link_type` INT(11) NOT NULL DEFAULT 0,
	`query_params` TEXT NOT NULL,
	`selector` VARCHAR(1024) NOT NULL DEFAULT '',
	`selector_element_for_event` VARCHAR(255) NOT NULL DEFAULT '',
	`short_description` VARCHAR(50) NOT NULL DEFAULT '',
	`skip_items` INT(11) NOT NULL DEFAULT 0,
	`task_id` VARCHAR(255) NOT NULL DEFAULT '',
	`type_device_id` INT(11) NOT NULL DEFAULT 0,
	`type_html_task` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_task_id` (`task_id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_type_device` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`type_device` VARCHAR(50) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_type_device` (`type_device`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_type_browser` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`type` VARCHAR(50) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_type` (`type`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_directory_components` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`copmonent_name` VARCHAR(64) NOT NULL DEFAULT '',
	`extension_id` INT(11) NOT NULL DEFAULT 0,
	`value_option` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_copmonent_name` (`copmonent_name`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_directory_views` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`hash` VARCHAR(255) NOT NULL DEFAULT '',
	`id_component` INT(11) NOT NULL DEFAULT 0,
	`query_params` TEXT NOT NULL,
	`value_view` VARCHAR(50) NOT NULL DEFAULT '',
	`view_component` VARCHAR(50) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_view_component` (`view_component`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_css_file` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`cash_external` INT(11) NOT NULL DEFAULT 0,
	`cash_time` INT(11) NOT NULL DEFAULT 0,
	`cash_time_custom` INT(11) NOT NULL DEFAULT 0,
	`delayed_loading` INT(11) NOT NULL DEFAULT 0,
	`err_href` VARCHAR(1024) NOT NULL DEFAULT '',
	`err_path_log` TEXT NOT NULL,
	`file` VARCHAR(255) NOT NULL DEFAULT '',
	`file_debug` INT(11) NOT NULL DEFAULT 0,
	`hash` VARCHAR(50) NOT NULL DEFAULT '',
	`is_error` INT(11) NOT NULL DEFAULT 0,
	`last_update` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`load` INT(11) NOT NULL DEFAULT 0,
	`load_if_criticalis_set` INT(11) NOT NULL DEFAULT 1,
	`media` VARCHAR(255) NOT NULL DEFAULT '',
	`minify` INT(11) NOT NULL DEFAULT 0,
	`minify_file` VARCHAR(255) NOT NULL DEFAULT '',
	`no_external` INT(11) NOT NULL DEFAULT 0,
	`override` INT(10) NOT NULL DEFAULT 0,
	`override_file` VARCHAR(255) NOT NULL DEFAULT '',
	`params_query` TEXT NOT NULL,
	`preload` INT(11) NOT NULL DEFAULT 0,
	`revision_id` VARCHAR(255) NOT NULL DEFAULT '',
	`type` VARCHAR(64) NOT NULL DEFAULT '',
	`use_critical` INT(11) NOT NULL DEFAULT 1,
	`ver_type` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_css_style` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`content` TEXT NOT NULL,
	`content_min` TEXT NOT NULL,
	`hash` VARCHAR(50) NOT NULL DEFAULT '',
	`load` INT(11) NOT NULL DEFAULT 0,
	`minify` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_js_file` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`async` INT(11) NOT NULL DEFAULT 0,
	`cash_external` INT(11) NOT NULL DEFAULT 0,
	`cash_time` INT(11) NOT NULL DEFAULT 0,
	`cash_time_custom` INT(11) NOT NULL DEFAULT 0,
	`defer` INT(11) NOT NULL DEFAULT 0,
	`delayed_loading` INT(11) NOT NULL DEFAULT 0,
	`err_href` VARCHAR(1024) NOT NULL DEFAULT '',
	`err_path_log` TEXT NOT NULL,
	`file` VARCHAR(255) NOT NULL DEFAULT '',
	`file_debug` INT(11) NOT NULL DEFAULT 0,
	`hash` VARCHAR(50) NOT NULL DEFAULT '',
	`is_error` INT(11) NOT NULL DEFAULT 0,
	`last_update` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`load` INT(11) NOT NULL DEFAULT 0,
	`media` VARCHAR(255) NOT NULL DEFAULT '',
	`minify` INT(11) NOT NULL DEFAULT 0,
	`minify_file` VARCHAR(255) NOT NULL DEFAULT '',
	`no_external` INT(11) NOT NULL DEFAULT 0,
	`override` INT(10) NOT NULL DEFAULT 0,
	`override_file` VARCHAR(255) NOT NULL DEFAULT '',
	`params_query` TEXT NOT NULL,
	`preload` INT(11) NOT NULL DEFAULT 0,
	`revision_id` VARCHAR(255) NOT NULL DEFAULT '',
	`type` VARCHAR(64) NOT NULL DEFAULT '',
	`ver_type` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_js_style` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`content` TEXT NOT NULL,
	`content_min` TEXT NOT NULL,
	`hash` VARCHAR(50) NOT NULL DEFAULT '',
	`load` INT(11) NOT NULL DEFAULT 0,
	`minify` INT(11) NOT NULL DEFAULT 0,
	`params_query` TEXT NOT NULL,
	`type` VARCHAR(64) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__pro_critical_url` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`url_page` VARCHAR(1024) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__pro_critical_help_document_data` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`admin_view` VARCHAR(255) NOT NULL DEFAULT '',
	`content` TEXT NOT NULL,
	`file` VARCHAR(255) NOT NULL DEFAULT '',
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`type` INT NOT NULL DEFAULT 0,
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL,
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_title` (`title`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
--
-- Table structure for table `#__pro_critical_html_setting_type_device_map`
--

CREATE TABLE IF NOT EXISTS `#__pro_critical_html_setting_type_device_map` (
  `id` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `html_setting_id` int(1),
  `type_device_id` int(1) UNSIGNED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `html_setting_id` (`html_setting_id`,`type_device_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Maps a task Html to a Type device';



--
-- Always insure this column rules is large enough for all the access control values.
--
ALTER TABLE `#__assets` CHANGE `rules` `rules` MEDIUMTEXT NOT NULL COMMENT 'JSON encoded access control.';

--
-- Always insure this column name is large enough for long component and view names.
--
ALTER TABLE `#__assets` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The unique name for the asset.';