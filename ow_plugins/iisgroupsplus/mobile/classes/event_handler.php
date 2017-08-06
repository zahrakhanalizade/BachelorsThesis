<?php

/**
 * Copyright (c) 2016, Mohammad Agha Abbasloo
 * All rights reserved.
 */

/**
 * 
 *
 * @author Mohammad Agha Abbasloo <a.mohammad85@gmail.com>
 * @package ow_plugins.iisgroupsplus.bol
 * @since 1.0
 */
class IISGROUPSPLUS_MCLASS_EventHandler
{
    private static $classInstance;

    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }
    
    private function __construct()
    {
    }
    
    public function init()
    {
        $service = IISGROUPSPLUS_BOL_Service::getInstance();
        $eventManager = OW::getEventManager();
        $eventManager->bind(IISEventManager::GET_RESULT_FOR_LIST_ITEM_GROUP, array($service, 'getResultForListItemGroup'));
        $eventManager->bind(IISEventManager::ADD_GROUP_FILTER_FORM, array($service, 'addGroupFilterForm'));
        $eventManager->bind(IISEventManager::ADD_GROUP_CATEGORY_FILTER_ELEMENT, array($service, 'addGroupCategoryFilterElement'));
        $eventManager->bind(IISEventManager::GET_GROUP_SELECTED_CATEGORY_ID, array($service, 'getGroupSelectedCategoryId'));
        $eventManager->bind(IISEventManager::ADD_CATEGORY_TO_GROUP, array($service, 'addCategoryToGroup'));
        $eventManager->bind(IISEventManager::GET_GROUP_SELECTED_CATEGORY_LABEL, array($service, 'getGroupSelectedCategoryLabel'));
        OW::getEventManager()->bind('groups.on_toolbar_collect', array($service, "onGroupToolbarCollect"));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::SET_MOBILE_USER_MANAGER_STATUS, array($service, 'setMobileUserManagerStatus'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::CHECK_USER_MANAGER_STATUS, array($service, 'checkUserManagerStatus'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::DELETE_USER_AS_MANAGER, array($service, 'deleteUserAsManager'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::DELETE_FILES, array($service, 'deleteFiles'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::ADD_FILE_WIDGET, array($service, 'addFileWidget'));
        OW::getEventManager()->bind('notifications.collect_actions', array($service, 'onCollectNotificationActions'));
        OW::getEventManager()->bind('mobile.notifications.on_item_render', array($this, 'onNotificationRender'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::ON_UPDATE_GROUP_STATUS, array($service, 'onUpdateGroupStatus'));
    }


    public function onNotificationRender( OW_Event $e )
    {
        $params = $e->getParams();

        if ( $params['pluginKey'] != 'groups'|| ($params['entityType'] != 'groups-add-file' && $params['entityType'] != 'groups-update-status'))
        {
            return;
        }

        $data = $params['data'];

        if ( !isset($data['avatar']['urlInfo']['vars']['username']) )
        {
            return;
        }

        $userService = BOL_UserService::getInstance();
        $user = $userService->findByUsername($data['avatar']['urlInfo']['vars']['username']);
        if ( !$user )
        {
            return;
        }
        $e->setData($data);
    }
}