<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 * 
 *
 * @author Mohammad Aghaabbasloo
 * @package ow_plugins.iisphotoplus
 * @since 1.0
 */
class IISPHOTOPLUS_BOL_Service
{
    private static $PHOTO_FRIENDS = 'photo_friends';
    private static $classInstance;
    public $isPhotoTabActive=false;
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * @return mixed
     */
    public function getIsPhotoTabActive()
    {
        return $this->isPhotoTabActive;
    }

    /**
     * @param mixed $isPhotoTabActive
     */
    public function setIsPhotoTabActive($isPhotoTabActive)
    {
        $this->isPhotoTabActive = $isPhotoTabActive;
    }

    
    private function __construct()
    {

    }

    public function setTtileHeaderListItemPHOTO( OW_Event $event )
    {
        $params = $event->getParams();
        if (isset($params['listType']) && $params['listType'] == IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS) {
            OW::getDocument()->setTitle(OW::getLanguage()->text('iisphotoplus', 'meta_title_photo_add_friends'));
            OW::getDocument()->setDescription(OW::getLanguage()->text('iisphotoplus', 'meta_description_photo_friends'));
        }
    }
    public function getValidListForPhoto( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['validLists'])){
            $validLists = $params['validLists'];
            if(OW::getUser()->isAuthenticated()) {
                $validLists[] = IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS;
            }
            $event->setData(array('validLists' => $validLists));
        }
    }

    public function addListTypeToPhoto( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['menu']) && OW::getUser()->isAuthenticated()){
            $menu = $params['menu'];
            if(isset($menu->sortItems[IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS]) && isset($menu->sortItems[IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS]['isActive']) && $menu->sortItems[IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS]['isActive']==true) {
                $this->setIsPhotoTabActive(true);
                $sortItems = array();
                $menu->setSortItems($sortItems);
                $event->setData(array('menu' => $menu));
            }
        }
        if(isset($params['validLists'])){
            $validLists = $params['validLists'];
            if(OW::getUser()->isAuthenticated()) {
                $validLists[] = IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS;
            }
            $event->setData(array('validLists' => $validLists));
        }
        if(isset($params['menuItems']) && OW::getUser()->isAuthenticated() && isset($params['isCmp']) && $params['isCmp']==true
        && isset($params['uniqId'])){
            $menuItems = $params['menuItems'];
            $menuItems['photo_friends'] = array(
                    'label' => OW::getLanguage()->text('iisphotoplus', IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS),
                    'id' => 'photo-cmp-menu-photo_friends-'.$params['uniqId'],
                    'contId' => 'photo-cmp-photo_friends-'.$params['uniqId'],
                    'active' => false,
                    'visibility' => true
                );
            $event->setData(array('menuItems' => $menuItems));
        }
        else if(isset($params['menuItems']) && OW::getUser()->isAuthenticated()){
            $menuItems = $params['menuItems'];
            if($this->isPhotoTabActive==true){
                foreach($menuItems as $item){
                    $item->setActive(false);
                }
            }
            $item = new BASE_MenuItem();
            $item->setLabel(OW::getLanguage()->text('iisphotoplus', IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS));
            $item->setUrl(OW::getRouter()->urlForRoute('view_photo_list', array('listType' => IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS)));
            $item->setKey(IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS);
            $item->setIconClass('ow_ic_clock');
            $item->setOrder(sizeof($params['menuItems']));
            $item->setActive($this->isPhotoTabActive) ;
            array_push($menuItems, $item);
            $event->setData(array('menuItems' => $menuItems));
        }
    }

    public function getResultForListItemPhoto( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['listtype']) &&
            $params['listtype'] == IISPHOTOPLUS_BOL_Service::$PHOTO_FRIENDS){
            $exclude = array();
            if(isset($params['exclude'])){
                $exclude=$params['exclude'];
            }
            $friendsOfCurrentUser = array();
            if(OW::getUser()->isAuthenticated()){
                $friendsOfCurrentUser = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => OW::getUser()->getId()));
            }
            if(!empty($friendsOfCurrentUser)) {
                if(isset($params['onlyCount'])){
                    $count = PHOTO_BOL_PhotoService::getInstance()->findPhotoListByUserIdListCount($friendsOfCurrentUser,$exclude);
                    $event->setData(array('count' => $count));
                }else{
                    $count = PHOTO_BOL_PhotoService::getInstance()->findPhotoListByUserIdListCount($friendsOfCurrentUser,$exclude);
                    $photos = PHOTO_BOL_PhotoService::getInstance()->findPhotoListByUserIdList($friendsOfCurrentUser, $params['page'], $params['photosPerPage'],$exclude);
                    $event->setData(array('count' => $count,'result' => $photos));
                }
            }
        }
    }
}
