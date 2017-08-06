<?php

/**
 * iismainpage
 */
/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iismainpage
 * @since 1.0
 */

class IISMAINPAGE_MCTRL_Index extends OW_MobileActionController
{
    public function dashboard($params)
    {
        if (!OW::getUser()->isAuthenticated() || !OW::getPluginManager()->isPluginActive('newsfeed')) {
            throw new Redirect404Exception();
        }
        $this->assign('userId', OW::getUser()->getId());

        $menuCmp = new IISMAINPAGE_MCMP_Menu('dashboard');
        $this->addComponent('menuCmp', $menuCmp);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('iismainpage')->getStaticCssUrl() . 'iismainpage.css');

    }

    public function friends($params)
    {
        if (!OW::getUser()->isAuthenticated() || !OW::getPluginManager()->isPluginActive('friends')) {
            throw new Redirect404Exception();
        }
        OW::getDocument()->setHeading(OW::getLanguage()->text('friends', 'notification_section_label'));
        $friendsService = FRIENDS_BOL_Service::getInstance();
        $userId = OW::getUser()->getId();
        $count = IISMAINPAGE_BOL_Service::$item_count;

        $data = $friendsService->findUserFriendsInList($userId, 0, $count);

        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('base')->getStaticJsUrl() . 'mobile_user_list.js');

        $cmp = new IISMAINPAGE_MCMP_FriendList('latest', $data, true);
        $this->addComponent('list', $cmp);
        $this->assign('listType', 'latest');

        if(OW::getPluginManager()->isPluginActive('iisadvancesearch')){
            $this->assign('find_friends_url', OW::getRouter()->urlForRoute('iisadvancesearch.all_users'));
        }

        OW::getDocument()->addOnloadScript("
            window.mobileUserList = new OW_UserList(" . json_encode(array(
                'component' => 'IISMAINPAGE_MCMP_FriendList',
                'listType' => 'latest',
                'excludeList' => $data,
                'node' => '.owm_user_list',
                'showOnline' => true,
                'count' => $count,
                'responderUrl' => OW::getRouter()->urlForRoute('iismainpage.friends_responder')
            )) . ");
        ", 50);

        $menuCmp = new IISMAINPAGE_MCMP_Menu('friends');
        $this->addComponent('menuCmp', $menuCmp);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('iismainpage')->getStaticCssUrl() . 'iismainpage.css');
    }

    public function friends_responder($params)
    {
        if (!OW::getRequest()->isAjax()) {
            throw new Redirect404Exception();
        }
        $excludeList = empty($_POST['excludeList']) ? array() : $_POST['excludeList'];
        $showOnline = empty($_POST['showOnline']) ? false : $_POST['showOnline'];
        $count = empty($_POST['count']) ? IISMAINPAGE_BOL_Service::$item_count : (int)$_POST['count'];
        $start = count($excludeList);

        $userId = OW::getUser()->getId();
        $userService = FRIENDS_BOL_Service::getInstance();
        $data = $userService->findUserFriendsInList($userId, $start, $count);

        echo json_encode($data);
        exit;
    }

    public function userGroups($params)
    {
        if (!OW::getUser()->isAuthenticated() || !OW::getPluginManager()->isPluginActive('groups')) {
            throw new Redirect404Exception();
        }
        OW::getDocument()->setHeading(OW::getLanguage()->text('groups', 'group_list_menu_item_my'));
        $groupService = GROUPS_BOL_Service::getInstance();
        $userId = OW::getUser()->getId();
        $count = IISMAINPAGE_BOL_Service::$item_count;

        $tplList = $groupService->findMyGroups($userId, 0, $count);
        $data = array();
        foreach ($tplList as $key => $item) {
            $data[] = $item->id;
        }
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('base')->getStaticJsUrl() . 'mobile_user_list.js');

        $cmp = new IISMAINPAGE_MCMP_GroupList('latest', $data, true);
        $this->addComponent('list', $cmp);
        $this->assign('listType', 'latest');

        OW::getDocument()->addOnloadScript("
            window.mobileUserList = new OW_UserList(" . json_encode(array(
                'component' => 'IISMAINPAGE_MCMP_GroupList',
                'listType' => 'latest',
                'excludeList' => $data,
                'node' => '.owm_user_list',
                'showOnline' => true,
                'count' => $count,
                'responderUrl' => OW::getRouter()->urlForRoute('iismainpage.user.groups_responder')
            )) . ");
        ", 50);

        $menuCmp = new IISMAINPAGE_MCMP_Menu('user-groups');
        $this->addComponent('menuCmp', $menuCmp);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('iismainpage')->getStaticCssUrl() . 'iismainpage.css');
    }

    public function userGroups_responder($params)
    {
        if (!OW::getRequest()->isAjax()) {
            throw new Redirect404Exception();
        }
        $excludeList = empty($_POST['excludeList']) ? array() : $_POST['excludeList'];
        $showOnline = empty($_POST['showOnline']) ? false : $_POST['showOnline'];
        $count = empty($_POST['count']) ? IISMAINPAGE_BOL_Service::$item_count : (int)$_POST['count'];
        $start = count($excludeList);

        $userId = OW::getUser()->getId();
        $groupService = GROUPS_BOL_Service::getInstance();
        $data = $groupService->findMyGroups($userId, $start, $count);

        echo json_encode($data);
        exit;
    }

    public function mailbox($params){
        if (!OW::getUser()->isAuthenticated() || !OW::getPluginManager()->isPluginActive('mailbox')) {
            throw new Redirect404Exception();
        }
//        OW::getDocument()->setHeading(OW::getLanguage()->text('mailbox', 'messages_console_title'));
        //--JS for loading
//        $js = "$('.owm_sidebar_top_block').append('<div id=\"console_preloader\"></div>');";
//        $js .= 'OW.bind(\'mailbox.ready\', function(readyStatus){ if (readyStatus == 2) $(\'.iismainpage #console_preloader\').hide()})';
//        OW::getDocument()->addOnloadScript($js);
        //--

//        $cmp = new MAILBOX_MCMP_ConsoleConversationsPage();
//        $this->addComponent('cmp', $cmp);

        $currentSubMenu = 'chat';
        if(isset($params['type'])){
            $currentSubMenu = $params['type'];
        }
        $conversationItemList = array();
        $userId = OW::getUser()->getId();
        $activeModes = MAILBOX_BOL_ConversationService::getInstance()->getActiveModeList();
        $validLists = array();
        if(in_array('chat', $activeModes) && 'chat' == $currentSubMenu) {
            $conversationItemList = MAILBOX_BOL_ConversationDao::getInstance()->findConversationItemListByUserId($userId, array('chat'), 0, 1000);
        }

        if(in_array('mail', $activeModes) && 'mail' == $currentSubMenu) {
            $conversationItemList = MAILBOX_BOL_ConversationDao::getInstance()->findConversationItemListByUserId($userId, array('mail'), 0, 1000);
        }

        if(in_array('chat', $activeModes)) {
            $validLists[] = 'chat';
        }

        if(in_array('mail', $activeModes)) {
            $validLists[] = 'mail';
        }

        foreach($conversationItemList as $i => $conversation)
        {
            $conversationItemList[$i]['timeStamp'] = (int)$conversation['initiatorMessageTimestamp'];
            $conversationItemList[$i]['lastMessageSenderId'] = $conversation['initiatorMessageSenderId'];
            $conversationItemList[$i]['isSystem'] = $conversation['initiatorMessageIsSystem'];
            $conversationItemList[$i]['text'] = $conversation['initiatorText'];

            $conversationItemList[$i]['lastMessageId'] = $conversation['initiatorLastMessageId'];
            $conversationItemList[$i]['recipientRead'] = $conversation['initiatorRecipientRead'];
            $conversationItemList[$i]['lastMessageRecipientId'] = $conversation['initiatorMessageRecipientId'];
            $conversationItemList[$i]['lastMessageWasAuthorized'] = $conversation['initiatorMessageWasAuthorized'];
        }

        $conversationData = MAILBOX_BOL_ConversationService::getInstance()->getConversationItemByConversationIdListForApi( $conversationItemList );
        $this->assign('conversationData', $conversationData);

        if(count($validLists)>1) {
            $subMenuItems = array();
            $order = 0;
            foreach ($validLists as $type) {
                $item = new BASE_MenuItem();
                $item->setLabel(OW::getLanguage()->text('iismainpage', $type));
                $item->setUrl(OW::getRouter()->urlForRoute('iismainpage.mailbox.type', array('type' => $type)));
                $item->setKey($type);
                $item->setOrder($order);
                array_push($subMenuItems, $item);
                $order++;
            }

            $subMenu = new BASE_MCMP_ContentMenu($subMenuItems);
            $el = $subMenu->getElement($currentSubMenu);
            $el->setActive(true);
            $this->addComponent('subMenu', $subMenu);
        }

        $menuCmp = new IISMAINPAGE_MCMP_Menu('mailbox');
        $this->addComponent('menuCmp', $menuCmp);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('iismainpage')->getStaticCssUrl() . 'iismainpage.css');
    }

    public function settings($params){
        if (!OW::getUser()->isAuthenticated() || !OW::getPluginManager()->isPluginActive('mailbox')) {
            throw new Redirect404Exception();
        }
        OW::getDocument()->setHeading(OW::getLanguage()->text('iismainpage', 'settings'));
        $cmp = new BASE_MCMP_ConsoleProfilePage();
        $this->addComponent('cmp', $cmp);

        $menuCmp = new IISMAINPAGE_MCMP_Menu('settings');
        $this->addComponent('menuCmp', $menuCmp);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('iismainpage')->getStaticCssUrl() . 'iismainpage.css');
    }
}