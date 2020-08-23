ALTER TABLE `#__pro_critical_html_task` ADD `link` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id_component`;

ALTER TABLE `#__pro_critical_html_task` ADD `link_type` INT(11) NOT NULL DEFAULT 0 AFTER `link`;
