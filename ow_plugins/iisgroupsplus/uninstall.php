<?php

/**
 * iisgroupsplus
 */

BOL_ComponentAdminService::getInstance()->deleteWidget('IISGROUPSPLUS_CMP_FileListWidget');

$eventIisGroupsPlusFiles = new OW_Event('iisgroupsplus.delete.files', array('allFiles'=>true));
OW::getEventManager()->trigger($eventIisGroupsPlusFiles);

try {
    $authorization = OW::getAuthorization();
    $groupName = 'groups';
    $authorization->deleteAction($groupName, 'groups-add-file');
    $authorization->deleteAction($groupName, 'groups-update-status');
}catch (Exception $e){}