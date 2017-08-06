<?php

/**
 * iismainpage
 */
/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iisslideshow
 * @since 1.0
 */
OW::getRouter()->addRoute(new OW_Route('iismainpage.index', 'iismainpage/index', 'IISMAINPAGE_MCTRL_Index', 'dashboard'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.dashboard', 'iismainpage/dashboard', 'IISMAINPAGE_MCTRL_Index', 'dashboard'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.user.groups', 'iismainpage/user-groups', 'IISMAINPAGE_MCTRL_Index', 'userGroups'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.friends', 'iismainpage/friends', 'IISMAINPAGE_MCTRL_Index', 'friends'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.mailbox', 'iismainpage/mailbox', 'IISMAINPAGE_MCTRL_Index', 'mailbox'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.mailbox.type', 'iismainpage/mailbox/:type', 'IISMAINPAGE_MCTRL_Index', 'mailbox'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.settings', 'iismainpage/settings', 'IISMAINPAGE_MCTRL_Index', 'settings'));

OW::getRouter()->addRoute(new OW_Route('iismainpage.friends_responder', 'iismainpage/friends/responder', 'IISMAINPAGE_MCTRL_Index', 'friends_responder'));
OW::getRouter()->addRoute(new OW_Route('iismainpage.user.groups_responder', 'iismainpage/user-groups/responder', 'IISMAINPAGE_MCTRL_Index', 'userGroups_responder'));


$eventHandler = new IISMAINPAGE_MCLASS_EventHandler();
$eventHandler->init();
