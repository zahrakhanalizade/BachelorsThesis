<?php

/**
 * iiseventplus
 */
/**
 * @author Mohammad Agha Abbasloo <a.mohammad85@gmail.com>
 * @package ow_plugins.iiseventplus
 * @since 1.0
 */

$dbPrefix = OW_DB_PREFIX;

$sql = "CREATE TABLE `" . OW_DB_PREFIX . "iiseventplus_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`label` VARCHAR(200) NOT NULL,
	 UNIQUE KEY `label` (`label`),
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM CHARSET=utf8 AUTO_INCREMENT=1;";
//installing database
OW::getDbo()->query($sql);

OW::getDbo()->query('CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'iiseventplus_event_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

$path = OW::getPluginManager()->getPlugin('iiseventplus')->getRootDir() . 'langs.zip';
BOL_LanguageService::getInstance()->importPrefixFromZip($path, 'iiseventplus');

OW::getPluginManager()->addPluginSettingsRouteName('iiseventplus', 'iiseventplus.admin');