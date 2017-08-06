<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 * 
 *
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iismobilesupport.bol
 * @since 1.0
 */
class IISMOBILESUPPORT_BOL_Service
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

    private $deviceDao;
    private $appVersionDao;
    public $AndroidKey = 1;
    public $iOSKey = 2;

    private function __construct()
    {
        $this->deviceDao = IISMOBILESUPPORT_BOL_DeviceDao::getInstance();
        $this->appVersionDao = IISMOBILESUPPORT_BOL_AppVersionDao::getInstance();
    }

    /***
     * @param $type
     * @return array
     */
    public function getAllVersions($type){
        return $this->appVersionDao->getAllVersions($type);
    }

    /***
     * @param $type
     * @return mixed
     */
    public function getLastVersions($type){
        return $this->appVersionDao->getLastVersions($type);
    }

    /***
     * @param $id
     */
    public function deleteVersion($id){
        $this->appVersionDao->deleteVersion($id);
    }

    /***
     * @param $id
     * @return mixed
     */
    public function deprecateVersion($id){
        return $this->appVersionDao->deprecateVersion($id);
    }

    /***
     * @param $id
     * @return mixed
     */
    public function approveVersion($id){
        return $this->appVersionDao->approveVersion($id);
    }

    /***
     * @param $type
     * @param $versionCode
     * @return mixed
     */
    public function getVersionUsingCode($type, $versionCode){
        return $this->appVersionDao->getVersionUsingCode($type, $versionCode);
    }

    /***
     * @param $type
     * @param $versionName
     * @param $versionCode
     * @return bool
     */
    public function hasVersion($type, $versionName, $versionCode){
        return $this->appVersionDao->hasVersion($type, $versionName, $versionCode);
    }

    /***
     * @param $type
     * @param $versionName
     * @param $versionCode
     * @return bool|IISMOBILESUPPORT_BOL_AppVersion
     */
    public function saveVersion($type, $versionName, $versionCode, $url){
        return $this->appVersionDao->saveVersion($type, $versionName, $versionCode, $url);
    }

    /***
     * @param $type
     * @return array
     */
    public function getArraysOfVersions($type){
        $versions = $this->getAllVersions($type);
        $lastVersion = $this->getLastVersions($type);
        $versionsArray = array();
        foreach ($versions as $value) {
            $versionInformation = array(
                'versionName' => $value->versionName,
                'versionCode' => $value->versionCode,
                'isDeprecated' => $value->deprected == true ? "0" : "1",
                'time' => UTIL_DateTime::formatSimpleDate($value->timestamp),
                'deleteUrl' => "if(confirm('".OW::getLanguage()->text('iismobilesupport','delete_item_warning')."')){location.href='" . OW::getRouter()->urlForRoute('iismobilesupport-admin-delete-value', array('id' => $value->id)) . "';}"
            );

            if($value->deprecated){
                $versionInformation['deprecateLabel'] = OW::getLanguage()->text('iismobilesupport', 'approve');
                $versionInformation['deprecateUrl'] = "location.href='" . OW::getRouter()->urlForRoute('iismobilesupport-admin-approve-value', array('id' => $value->id)) . "';";
            }else{
                $versionInformation['deprecateLabel'] = OW::getLanguage()->text('iismobilesupport', 'deprecate');
                $versionInformation['deprecateUrl'] = "location.href='" . OW::getRouter()->urlForRoute('iismobilesupport-admin-deprecate-value', array('id' => $value->id)) . "';";
            }

            $versionInformation['isLastVersion'] = false;
            if($lastVersion!=null && $lastVersion->versionCode == $value->versionCode){
                $versionInformation['isLastVersion'] = true;
            }

            $versionsArray[] = $versionInformation;
        }

        return $versionsArray;
    }

    /***
     * @param $userId
     * @return array
     */
    public function getUsersDevices($userId){
        return $this->deviceDao->getUsersDevices($userId);
    }

    public function deleteInActiveDevicesOfUser($userId){
        $devices = IISMOBILESUPPORT_BOL_Service::getInstance()->getUsersDevices($userId);
        $response = $this->sendDataToDevice($userId, 'check-user-activity', 'check-user-activity', '', '', $devices);
        $results = json_decode($response->getBody())->results;

        $orderOfTokensMustBeDeleted = array();
        $count = 0;
        foreach($results as $result){
            if(isset($result->error) && ($result->error=='InvalidRegistration' || $result->error=='NotRegistered')){
                $orderOfTokensMustBeDeleted[] = $count;
            }
            $count++;
        }

        $count = 0;
        foreach($devices as $device){
            if(in_array($count, $orderOfTokensMustBeDeleted)){
                $this->deleteUserDevice($userId, $device->token);
            }
            $count++;
        }
    }

    /***
     * @param $userId
     * @param $title
     * @param $description
     * @param $avatarUrl
     * @param $url
     * @param null $devices
     * @return null|UTIL_HttpClientResponse
     */
    public function sendDataToDevice($userId, $title, $description, $avatarUrl, $url, $devices = null){
        if($devices == null) {
            $devices = IISMOBILESUPPORT_BOL_Service::getInstance()->getUsersDevices($userId);
        }

        if(OW::getConfig()->getValue('iismobilesupport', 'disable_notification_content')){
            $description = OW::getLanguage()->text('iismobilesupport', 'new_notification_label');
        }else{
            $description = OW::getLanguage()->text('iismobilesupport', 'new_notification_label') . ': '. $description;
        }

        $deviceTokens = array();
        foreach ($devices as $device) {
            $deviceTokens[] = $device->token;
        }
        $data = array();
        $data['title'] = $title;
        $data['description'] = strip_tags($description);
        $data['avatarUrl'] = $avatarUrl;
        $data['url'] = $url;
        $sendData['data'] = $data;
        $sendData["registration_ids"] = $deviceTokens;

        $fcmUrl = OW::getConfig()->getValue('iismobilesupport','fcm_api_url');
        $fcmKey = OW::getConfig()->getValue('iismobilesupport','fcm_api_key');

        $params = new UTIL_HttpClientParams();
        $params->setHeader('Content-Type' ,'application/json');
        $params->setHeader('Authorization' ,'key=' . $fcmKey);
        $params->setJson($sendData);
        try {
            $response = UTIL_HttpClient::post($fcmUrl, $params);
            return $response;
        } catch (Exception $e) {
            return null;
        }
    }

    /***
     * @param $userId
     */
    public function deleteAllDevicesOfUser($userId){
        $this->deviceDao->deleteAllDevicesOfUser($userId);
    }


    /***
     * @param $userId
     * @param $token
     */
    public function saveDevice($userId, $token){
        if(!$this->hasUserDevice($userId, $token)) {
            $allUserDevices = $this->getUsersDevices($userId);
            $canAddDevice = sizeof($allUserDevices) < OW::getConfig()->getValue('iismobilesupport', 'constraint_user_device');
            if(!$canAddDevice){
                $this->deleteInActiveDevicesOfUser($userId);
                $allUserDevices = $this->getUsersDevices($userId);
                $canAddDevice = sizeof($allUserDevices) < OW::getConfig()->getValue('iismobilesupport', 'constraint_user_device');
            }

            if($canAddDevice) {
                $this->deviceDao->saveDevice($userId, $token);
            }
        }
    }

    /***
     * @param $userId
     * @param $token
     * @return array|bool
     */
    public function hasUserDevice($userId, $token){
        return $this->deviceDao->hasUserDevice($userId, $token);
    }

    /***
     * @param $userId
     * @param $token
     */
    public function deleteUserDevice($userId, $token){
        $this->deviceDao->deleteUserDevice($userId, $token);
    }

    public function useMobile(){
        return isset($_COOKIE['UsingMobileApp']);
    }

    public function useAndroidMobile(){
        return $_COOKIE['UsingMobileApp']=='android';
    }

    public function useIOSMobile(){
        return $_COOKIE['UsingMobileApp']=='ios';
    }

    public function saveDeviceToken(OW_Event $event){
        if($this->useMobile() && isset($_COOKIE['MobileTokenNotification']) && OW::getUser()->isAuthenticated()){
            IISMOBILESUPPORT_BOL_Service::getInstance()->saveDevice(OW::getUser()->getId(), $_COOKIE['MobileTokenNotification']);
        }
    }

    public function addMobileCss(OW_Event $event){
        if($this->useMobile()) {
            $cssUrl = OW::getPluginManager()->getPlugin('iismobilesupport')->getStaticCssUrl() . "mobile.css";
            OW::getDocument()->addStyleSheet($cssUrl);

            $jsUrl = OW::getPluginManager()->getPlugin('iismobilesupport')->getStaticJsUrl() . "mobile.js";
            OW::getDocument()->addScript($jsUrl);
        }
    }

    public function getBrowserInformation(OW_Event $event){
        if($this->useMobile()) {
            if($this->useAndroidMobile()){
                $event->setData(array('browser_name' => OW::getLanguage()->text('iismobilesupport','android_app_label')));
            }else if($this->useIOSMobile()){
                $event->setData(array('browser_name' => OW::getLanguage()->text('iismobilesupport','ios_app_label')));
            }
        }
    }

    public function userLogout(OW_Event $event){
        if($this->useMobile()) {
            $params = $event->getParams();
            if (isset($params['userId'])) {
                $deleteAllDevices = false;
                if (isset($_COOKIE['MobileTokenNotification'])) {
                    $service = IISMOBILESUPPORT_BOL_Service::getInstance();
                    $existUserDevice = $service->hasUserDevice($params['userId'], $_COOKIE['MobileTokenNotification']);
                    if ($existUserDevice) {
                        $service->deleteUserDevice($params['userId'], $_COOKIE['MobileTokenNotification']);
                    } else {
                        $deleteAllDevices = true;
                    }
                } else {
                    $deleteAllDevices = true;
                }

                if ($deleteAllDevices) {
                    IISMOBILESUPPORT_BOL_Service::getInstance()->deleteAllDevicesOfUser($params['userId']);
                }
            }
        }
    }

    /***
     * @param OW_Event $event
     * @return array|void
     */
    public function addNotification(OW_Event $event){
        $params = $event->getParams();
        $data = $event->getData();

        $fcmUrl = OW::getConfig()->getValue('iismobilesupport','fcm_api_url');
        $fcmKey = OW::getConfig()->getValue('iismobilesupport','fcm_api_key');

        if (is_string($data) || $fcmUrl == null || $fcmUrl == '' || $fcmKey == null || $fcmKey == '' || empty($data['avatar'])){
            return;
        }

        foreach ( array('string', 'conten') as $langProperty )
        {
            if ( !empty($data[$langProperty]) && is_array($data[$langProperty]) )
            {
                $key = explode('+', $data[$langProperty]['key']);
                $vars = empty($data[$langProperty]['vars']) ? array() : $data[$langProperty]['vars'];
                $data[$langProperty] = BOL_LanguageService::getInstance()->getText(BOL_LanguageService::getInstance()->getCurrent()->getId(), $key[0], $key[1], $vars);
            }
        }

        if ( empty($data['string']) )
        {
            return array();
        }

        $title = OW::getConfig()->getValue('base', 'site_name');
        $description = $data['string'];
        $url = $data['url'];
        $avatarUrl = null;
        $user = null;
        if(isset($params['userId'])) {
            $user = BOL_UserService::getInstance()->findUserById($params['userId']);
        }
        if(isset($data['avatar']['src'])){
            $avatarUrl = $data['avatar']['src'];
        }
        if ($avatarUrl == null) {
            $avatarUrl = BOL_AvatarService::getInstance()->getDefaultAvatarUrl();
        }

        if($user != null) {
            IISMOBILESUPPORT_BOL_Service::getInstance()->sendDataToDevice($user->getId(), $title, $description, $avatarUrl, $url);
        }
    }

    public function getAllSections($sectionKey){
        $sections = array();

        $sections[] = array(
            'sectionId' => "settings",
            'active' => $sectionKey == "settings" ? true : false,
            'url' => OW::getRouter()->urlForRoute('iismobilesupport-admin'),
            'label' => OW::getLanguage()->text('iismobilesupport', 'settings')
        );

        $sections[] = array(
            'sectionId' => "versions",
            'active' => $sectionKey == "versions" ? true : false,
            'url' => OW::getRouter()->urlForRoute('iismobilesupport-admin-versions'),
            'label' => OW::getLanguage()->text('iismobilesupport', 'versions')
        );

        $sections[] = array(
            'sectionId' => "android-versions",
            'active' => $sectionKey == "android-versions" ? true : false,
            'url' => OW::getRouter()->urlForRoute('iismobilesupport-admin-android-versions'),
            'label' => OW::getLanguage()->text('iismobilesupport', 'android_versions')
        );

        $sections[] = array(
            'sectionId' => "ios-versions",
            'active' => $sectionKey == "ios-versions" ? true : false,
            'url' => OW::getRouter()->urlForRoute('iismobilesupport-admin-ios-versions'),
            'label' => OW::getLanguage()->text('iismobilesupport', 'ios_versions')
        );

        return $sections;
    }

    public function onAddMembersOnlyException( BASE_CLASS_EventCollector $event )
    {
        $event->add(array('controller' => 'IISMOBILESUPPORT_MCTRL_Service', 'action' => 'index'));
    }

}