<?php

/**
 * iisgroupsplus
 */
$plugin = OW::getPluginManager()->getPlugin('iisgroupsplus');
IISGROUPSPLUS_CLASS_EventHandler::getInstance()->init();
$router = OW::getRouter();
$router->addRoute(new OW_Route('iisgroupsplus.admin', 'admin/plugins/iisgroupsplus', "IISGROUPSPLUS_CTRL_Admin", 'groupCategory'));
$router->addRoute(new OW_Route('iisgroupsplus.admin.edit.item', 'iisgroupsplus/admin/edit-item', 'IISGROUPSPLUS_CTRL_Admin', 'editItem'));
OW::getRouter()->addRoute(new OW_Route('iisgroupsplus.file-list', 'groups/:groupId/files', 'IISGROUPSPLUS_CTRL_Groups', 'fileList'));
$router->addRoute(new OW_Route('iisgroupsplus.addFile', 'groups/:groupId/addFile', 'IISGROUPSPLUS_CTRL_Groups', 'addFile'));
$router->addRoute(new OW_Route('iisgroupsplus.deleteFile', 'groups/:groupId/attachmentId/:attachmentId/deleteFile', 'IISGROUPSPLUS_CTRL_Groups', 'deleteFile'));
