<?php

/**
 * iiseventplus
 */
$plugin = OW::getPluginManager()->getPlugin('iiseventplus');
IISEVENTPLUS_CLASS_EventHandler::getInstance()->init();
$router = OW::getRouter();
$router->addRoute(new OW_Route('iiseventplus.leave', 'iiseventplus/leave/:eventId', 'IISEVENTPLUS_CTRL_Base', 'leave'));
$router->addRoute(new OW_Route('iiseventplus.admin', 'admin/plugins/iiseventplus', "IISEVENTPLUS_CTRL_Admin", 'eventCategory'));
$router->addRoute(new OW_Route('iiseventplus.admin.edit.item', 'iiseventplus/admin/edit-item', 'IISEVENTPLUS_CTRL_Admin', 'editItem'));