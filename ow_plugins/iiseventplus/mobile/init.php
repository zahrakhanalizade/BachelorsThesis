<?php

/**
 * iiseventplus
 */
$plugin = OW::getPluginManager()->getPlugin('iiseventplus');
IISEVENTPLUS_MCLASS_EventHandler::getInstance()->init();
$router = OW::getRouter();
$router->addRoute(new OW_Route('iiseventplus.leave', 'iiseventplus/leave/:eventId', 'IISEVENTPLUS_MCTRL_Base', 'leave'));