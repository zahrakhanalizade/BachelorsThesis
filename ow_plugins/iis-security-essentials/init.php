<?php

/**
 * User: Hamed Tahmooresi
 * Date: 12/23/2015
 * Time: 11:00 AM
 */
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.admin', 'iissecurityessentials/admin', 'IISSECURITYESSENTIALS_CTRL_Admin', 'index'));
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.admin.currentSection', 'iissecurityessentials/admin/:currentSection', 'IISSECURITYESSENTIALS_CTRL_Admin', 'index'));
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.edit_privacy', 'iissecurityessentials/edit-privacy', 'IISSECURITYESSENTIALS_CTRL_Iissecurityessentials', 'editPrivacy'));
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.delete_activity', 'iissecurityessentials/delete-activity/:activityId', 'IISSECURITYESSENTIALS_CTRL_Iissecurityessentials', 'deleteFeedItem'));
IISSECURITYESSENTIALS_CLASS_EventHandler::getInstance()->init();