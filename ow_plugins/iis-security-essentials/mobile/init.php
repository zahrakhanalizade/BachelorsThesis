<?php

IISSECURITYESSENTIALS_MCLASS_EventHandler::getInstance()->init();
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.edit_privacy', 'iissecurityessentials/edit-privacy', 'IISSECURITYESSENTIALS_CTRL_Iissecurityessentials', 'editPrivacy'));
OW::getRouter()->addRoute(new OW_Route('iissecurityessentials.delete_activity', 'iissecurityessentials/delete-activity/:activityId', 'IISSECURITYESSENTIALS_CTRL_Iissecurityessentials', 'deleteFeedItem'));