<?php

/**
 * iismainpage
 */
/**
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iismainpage
 * @since 1.0
 */

class IISMAINPAGE_BOL_Service
{

    static $item_count = 20;

    /**
     * Constructor.
     */
    private function __construct()
    {
    }
    /**
     * Singleton instance.
     *
     * @var IISMAINPAGE_BOL_Service
     */
    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return IISMAINPAGE_BOL_Service
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function getMenu($type){
        $languages = OW::getLanguage();
        $menus = array();
        $imgSource = OW::getPluginManager()->getPlugin('iismainpage')->getStaticUrl() . 'img/';
        if(OW::getPluginManager()->isPluginActive('newsfeed')) {
            $menu = array();
            $menu['title'] = $languages->text('base', 'console_item_label_dashboard');
            $menu['iconUrl'] = $imgSource.'dashboard.svg';
            if ($type == 'dashboard') {
                $menu['active'] = true;
            } else {
                $menu['active'] = false;
            }
            $menu['url'] = OW::getRouter()->urlForRoute('iismainpage.dashboard');
            $menus[] = $menu;
        }

        if(OW::getPluginManager()->isPluginActive('groups')) {
            $menu = array();
            $menu['title'] = $languages->text('groups', 'group_list_menu_item_my');
            $menu['iconUrl'] = $imgSource.'groups.svg';
            if ($type == 'user-groups') {
                $menu['active'] = true;
            } else {
                $menu['active'] = false;
            }
            $menu['url'] = OW::getRouter()->urlForRoute('iismainpage.user.groups');
            $menus[] = $menu;
        }

        if(OW::getPluginManager()->isPluginActive('friends')) {
            $menu = array();
            $menu['title'] = $languages->text('friends', 'notification_section_label');
            $menu['iconUrl'] = $imgSource.'friend.svg';
            if ($type == 'friends') {
                $menu['active'] = true;
            } else {
                $menu['active'] = false;
            }
            $menu['url'] = OW::getRouter()->urlForRoute('iismainpage.friends');
            $menus[] = $menu;
        }

        if(OW::getPluginManager()->isPluginActive('mailbox')) {
            $menu = array();
            $menu['title'] = $languages->text('mailbox', 'messages_console_title');
            $menu['iconUrl'] = $imgSource.'chat.svg';
            $menu['class'] = 'menu_messages';
            if ($type == 'mailbox') {
                $menu['active'] = true;
            } else {
                $menu['active'] = false;
            }
            $menu['url'] = OW::getRouter()->urlForRoute('iismainpage.mailbox');
            $menus[] = $menu;
        }

        {
            $menu = array();
            $menu['title'] = $languages->text('iismainpage', 'settings');
            $menu['iconUrl'] = $imgSource.'Settings.svg';
            if ($type == 'settings') {
                $menu['active'] = true;
            } else {
                $menu['active'] = false;
            }
            $menu['url'] = OW::getRouter()->urlForRoute('iismainpage.settings');
            $menus[] = $menu;
        }

        return $menus;
    }
}