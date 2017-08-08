<?php
/**
 * Created by PhpStorm.
 * User: CEBIT
 * Date: 8/4/2017
 * Time: 2:48 PM
 */

//BOL_LanguageService::getInstance()->addPrefix('jobsearch', 'Job Search');
//$path = OW::getPluginManager()->getPlugin('jobsearch')->getRootDir() . 'langs.zip';
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('jobsearch')->getRootDir() . 'langs.zip', 'jobsearch');

$sql1 = "CREATE TABLE `" . OW_DB_PREFIX . "jobsearch_applying` (
    `id` INT(11) NOT NULL,
    `applicant` INT(11) NOT NULL,
    `requirement` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql1);



$sql2 = "CREATE TABLE `" . OW_DB_PREFIX . "jobsearch_requirement` (
    `id` INT(11) NOT NULL,
    `creator` INT(11) NOT NULL,
    `description` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql2);



$sql3 = "CREATE TABLE `" . OW_DB_PREFIX . "jobsearch_requirement_skill` (
    `id` INT(11) NOT NULL,
    `requirement` INT(11) NOT NULL,
    `skill` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql3);



$sql4 = "CREATE TABLE `" . OW_DB_PREFIX . "jobsearch_skill` (
    `id` INT(11) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM
ROW_FORMAT=DEFAULT";

OW::getDbo()->query($sql4);







