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
class IISGROUPSPLUS_CLASS_EventHandler
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
        $eventManager->bind('iisgroupsplus.add_widget', array($service, 'addWidgetToOthers'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::SET_USER_MANAGER_STATUS, array($service, 'setUserManagerStatus'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::CHECK_USER_MANAGER_STATUS, array($service, 'checkUserManagerStatus'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::DELETE_USER_AS_MANAGER, array($service, 'deleteUserAsManager'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::DELETE_FILES, array($service, 'deleteFiles'));
        OW::getEventManager()->bind('notifications.collect_actions', array($service, 'onCollectNotificationActions'));
        $eventManager->bind(IISGROUPSPLUS_BOL_Service::ON_UPDATE_GROUP_STATUS, array($service, 'onUpdateGroupStatus'));
    }
}