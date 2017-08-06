<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/4/2017
 * Time: 2:48 PM
 */

//BOL_LanguageService::getInstance()->addPrefix('jobsearch', 'Job Search');
$path = OW::getPluginManager()->getPlugin('jobsearch')->getRootDir() . 'langs.zip';

$sql = "CREATE TABLE `" . OW_DB_PREFIX . "jobsearch_company` (
    `id` INT(11) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `address` VARCHAR(200) NOT NULL,
    `email` VARCHAR(200) NOT NULL,
    `website` VARCHAR(200),
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql);