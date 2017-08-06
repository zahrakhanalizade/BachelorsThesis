<?php

$path = OW::getPluginManager()->getPlugin('iisprofilemanagement')->getRootDir() . 'langs.zip';
BOL_LanguageService::getInstance()->importPrefixFromZip($path, 'iisprofilemanagement');


