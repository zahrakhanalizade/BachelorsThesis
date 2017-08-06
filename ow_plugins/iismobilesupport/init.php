<?php
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin', 'admin/iismobilesupport/settings', "IISMOBILESUPPORT_CTRL_Admin", 'settings'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-versions', 'admin/iismobilesupport/versions', "IISMOBILESUPPORT_CTRL_Admin", 'versions'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-android-versions', 'admin/iismobilesupport/android-versions', "IISMOBILESUPPORT_CTRL_Admin", 'androidVersions'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-ios-versions', 'admin/iismobilesupport/ios-versions', "IISMOBILESUPPORT_CTRL_Admin", 'iosVersions'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-delete-value', 'admin/iismobilesupport/delete-version/:id', "IISMOBILESUPPORT_CTRL_Admin", 'deleteVersion'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-deprecate-value', 'admin/iismobilesupport/deprecate-version/:id', "IISMOBILESUPPORT_CTRL_Admin", 'deprecateVersion'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-admin-approve-value', 'admin/iismobilesupport/approve-version/:id', "IISMOBILESUPPORT_CTRL_Admin", 'approveVersion'));
OW::getRouter()->addRoute(new OW_Route('iismobilesupport-index', 'mobile/service/:key', "IISMOBILESUPPORT_MCTRL_Service", 'index'));
IISMOBILESUPPORT_CLASS_EventHandler::getInstance()->init();