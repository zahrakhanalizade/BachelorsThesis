<?php

/**
 * User: Hamed Tahmooresi
 * Date: 12/23/2015
 * Time: 11:00 AM
 */

OW::getDbo()->query('CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'iissecurityessentials_question_privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `privacy` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');


$path = OW::getPluginManager()->getPlugin('iissecurityessentials')->getRootDir() . 'langs.zip';
BOL_LanguageService::getInstance()->importPrefixFromZip($path, 'iissecurityessentials');

$config = OW::getConfig();

if ( !$config->configExists('iissecurityessentials', 'idleTime') )
{
    $config->addConfig('iissecurityessentials', 'idleTime', 20);
}
if ( !$config->configExists('iissecurityessentials', 'viewUserCommentWidget') )
{
    $config->addConfig('iissecurityessentials', 'viewUserCommentWidget', false);
}

if ( !$config->configExists('iissecurityessentials', 'approveUserAfterEditProfile') )
{
    $config->addConfig('iissecurityessentials', 'approveUserAfterEditProfile', false);
}
if ( !$config->configExists('iissecurityessentials', 'disabled_home_page_action_types') )
{
    OW::getConfig()->addConfig('iissecurityessentials', 'disabled_home_page_action_types', '');
}

OW::getPluginManager()->addPluginSettingsRouteName('iissecurityessentials', 'iissecurityessentials.admin');