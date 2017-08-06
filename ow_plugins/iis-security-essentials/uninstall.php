<?php
$config = OW::getConfig();
if($config->configExists('iissecurityessentials', 'disabled_home_page_action_types'))
{
    $config->deleteConfig('iissecurityessentials', 'disabled_home_page_action_types');
}