<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 * 
 *
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iispasswordchangeinterval.bol
 * @since 1.0
 */
class IISPASSWORDCHANGEINTERVAL_MCLASS_EventHandler
{
    private static $classInstance;

    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct()
    {
    }

    public function init()
    {
        $service = IISPASSWORDCHANGEINTERVAL_BOL_Service::getInstance();
        $eventManager = OW::getEventManager();
        $eventManager->bind(OW_EventManager::ON_AFTER_ROUTE, array($service, 'onAfterRoute'));
        $eventManager->bind(IISEventManager::ON_AFTER_PASSWORD_UPDATE, array($service, 'onAfterPasswordUpdate'));
        $eventManager->bind(IISEventManager::ON_BEFORE_RESET_PASSWORD_FORM_RENDERER, array($service, 'onBeforeResetPasswordFormRenderer'));
        $eventManager->bind('base.members_only_exceptions', array($service, 'catchAllRequestsExceptions'));
        $eventManager->bind(OW_EventManager::ON_USER_REGISTER, array($service, 'onUserRegistered'));
        OW::getEventManager()->bind('mobile.notifications.on_item_render', array($this, 'onNotificationRender'));
    }

    public function onNotificationRender( OW_Event $e )
    {
        $params = $e->getParams();

        if ( $params['pluginKey'] != 'iispasswordchangeinterval'|| $params['entityType'] != 'iispasswordchangeinterval' )
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

        $data['string'] = OW::getLanguage()->text('iispasswordchangeinterval', 'description_change_password');

        $e->setData($data);
    }
}