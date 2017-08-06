<?php

/**
 * iisgroupsplus
 */
/**
 * @author Mohammad Agha Abbasloo <a.mohammad85@gmail.com>
 * @package ow_plugins.iisgroupsplus
 * @since 1.0
 */

$dbPrefix = OW_DB_PREFIX;

$sql = "CREATE TABLE `" . OW_DB_PREFIX . "iisgroupsplus_category` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`label` VARCHAR(200) NOT NULL,
	 UNIQUE KEY `label` (`label`),
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM CHARSET=utf8 AUTO_INCREMENT=1;";
//installing database
OW::getDbo()->query($sql);

OW::getDbo()->query('CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'iisgroupsplus_group_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

OW::getDbo()->query('CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'iisgroupsplus_group_managers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

OW::getDbo()->query('CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'iisgroupsplus_group_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `attachmentId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');

$path = OW::getPluginManager()->getPlugin('iisgroupsplus')->getRootDir() . 'langs.zip';
BOL_LanguageService::getInstance()->importPrefixFromZip($path, 'iisgroupsplus');

OW::getPluginManager()->addPluginSettingsRouteName('iisgroupsplus', 'iisgroupsplus.admin');

$widgetService = BOL_ComponentAdminService::getInstance();
$widget = $widgetService->addWidget('IISGROUPSPLUS_CMP_FileListWidget', false);
$placeWidget = $widgetService->addWidgetToPlace($widget, 'group');
$widgetService->addWidgetToPosition($placeWidget, BOL_ComponentAdminService::SECTION_LEFT);

try {
    $authorization = OW::getAuthorization();
    $groupName = 'groups';
    $authorization->addAction($groupName, 'groups-add-file');
    $authorization->addAction($groupName, 'groups-update-status');
}catch(Exception $e){}
