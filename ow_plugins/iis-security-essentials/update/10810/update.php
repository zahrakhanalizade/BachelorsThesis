<?php

Updater::getLanguageService()->importPrefixFromZip(dirname(__FILE__) . DS . 'langs.zip', 'iissecurityessentials');

$config = OW::getConfig();

if ( !$config->configExists('iissecurityessentials', 'disabled_home_page_action_types') )
{
    $config->addConfig('iissecurityessentials', 'disabled_home_page_action_types', '');
}