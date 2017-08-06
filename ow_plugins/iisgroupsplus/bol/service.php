<?php

/**
 * Copyright (c) 2016, Mohammad Agha Abbasloo
 * All rights reserved.
 */

/**
 * 
 *
 * @author Mohammad Aghaabbasloo
 * @package ow_plugins.iisgroupsplus
 * @since 1.0
 */
class IISGROUPSPLUS_BOL_Service
{
    const SET_MOBILE_USER_MANAGER_STATUS = 'iisgroupsplus.set.mobile.user.manager.status';
    const SET_USER_MANAGER_STATUS = 'iisgroupsplus.set.user.manager.status';
    const DELETE_USER_AS_MANAGER = 'iisgroupsplus.delete.user.as.manager';
    const DELETE_FILES = 'iisgroupsplus.delete.files';
    const ADD_FILE_WIDGET = 'iisgroupsplus.add.file.widget';
    const CHECK_USER_MANAGER_STATUS = 'iisgroupsplus.check.user.manager.status';
    const ON_UPDATE_GROUP_STATUS = 'iisgroupsplus.on.update.group.status';
    private static $classInstance;

    private  $groupInformationDao;
    private  $groupManagersDao;
    private  $categoryDao;
    private  $groupFileDao;
    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }


    private function __construct()
    {
        $this->groupInformationDao = IISGROUPSPLUS_BOL_GroupInformationDao::getInstance();
        $this->groupManagersDao = IISGROUPSPLUS_BOL_GroupManagersDao::getInstance();
        $this->categoryDao = IISGROUPSPLUS_BOL_CategoryDao::getInstance();
        $this->groupFileDao = IISGROUPSPLUS_BOL_GroupFilesDao::getInstance();
    }

    public function addGroupFilterForm(OW_Event $event)
    {
        $params = $event->getParams();
        $tab = 'latest';
       if (isset($params['tab'])) {
            $tab = $params['tab'];
        }
        if (isset($params['categoryStatus'])) {
            $categoryStatus = $params['categoryStatus'];
        }
        if (isset($params['searchTitle'])) {
            $searchTitle = $params['searchTitle'];
        }

        $event->setData(array('groupFilterForm' => $this->getGroupFilterForm('GroupFilterForm', $tab,$categoryStatus,$searchTitle)));
    }


    public function getResultForListItemGroup(OW_Event $event)
    {
        $params = $event->getParams();

        $groupService = GROUPS_BOL_Service::getInstance();
        $groupController = $params['groupController'];
        $tab='';
        $categoryStatus='';
        $searchTitle='';
        $latest='';
        $popular='';
        $activeTab=1;
        $groupIds = array();
        $page =null;
        $first = $params['first'];
        $count = $params['count'];
        if(isset($params['page'])){
            $page = $params['page'];
        }
        if (OW::getRequest()->isPost()) {
            $categoryStatus = $_POST['categoryStatus'];
            $searchTitle = $_POST['searchTitle'];
            $page=1;
            $perPage = 20;
            $first = ($page - 1) * $perPage;
            $count = $perPage;
        }

        if(isset($_GET['categoryStatus'])){
            $categoryStatus = $_GET['categoryStatus'];
            $page=1;
            $perPage = 20;
            $first = ($page - 1) * $perPage;
            $count = $perPage;
        }

        if(isset($params['activeTab'])){
            $tab = $params['activeTab'];
        }
        if(isset($params['popular'])){
            $popular = $params['popular'];
        }
        if(isset($params['onlyActive'])){
            $onlyActive = $params['onlyActive'];
        }
        if(isset($params['latest'])){
            $latest = $params['latest'];
        }
        $userId='';
        if(isset($params['userId'])){
            $userId = $params['userId'];
        }

        $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ADD_GROUP_FILTER_FORM, array('tab' => $tab, 'categoryStatus' =>$categoryStatus, 'searchTitle' => $searchTitle)));
        if (isset($resultsEvent->getData()['groupFilterForm'])) {
            $groupFilterForm = $resultsEvent->getData()['groupFilterForm'];
        }
        if($categoryStatus!=null) {
            $groupIds = $this->getGroupIdListByCategoryID($categoryStatus);
            if($groupIds==null){
                $groupIds[]=-1;
            }
        }
        $groups = $groupService->findGroupsByFiltering($popular,$onlyActive,$latest,$first,$count,$userId,$groupIds,$searchTitle);
        $groupsCount =$groupService->findGroupsByFilteringCount($popular,$onlyActive,$latest,$userId,$groupIds,$searchTitle);
        $event->setData(array('groups' => $groups, 'groupsCount' => $groupsCount, 'page'=>$page));
        $this->setGroupController($activeTab, $groupFilterForm, $groupController);
    }

    public function setGroupController($activeTab, $filterForm, $groupController)
    {
        if (isset($filterForm)) {
            $groupController->assign('filterForm', true);
            $groupController->addForm($filterForm);
            $filterFormElementsKey = array();
            foreach ($filterForm->getElements() as $element) {
                if ($element->getAttribute('type') != 'hidden') {
                    $filterFormElementsKey[] = $element->getAttribute('name');
                }
            }
            $groupController->assign('filterFormElementsKey', $filterFormElementsKey);
        }
    }

    /**
     * Add select date filter Form
     * @param $name
     * @return Form
     */
    public function getGroupFilterForm($name, $tab, $selectedCategory=1,$searchedTitle=null)
    {
        $form = new Form($name);

        $searchTitle = new TextField('searchTitle');
        $searchTitle->addAttribute('placeholder',OW::getLanguage()->text('iisgroupsplus', 'search_title'));
        $searchTitle->addAttribute('class','group_search_title');
        if($searchedTitle!=null) {
            $searchTitle->setValue($searchedTitle);
        }
        $searchTitle->setHasInvitation(false);
        $form->addElement($searchTitle);

        $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ADD_GROUP_CATEGORY_FILTER_ELEMENT, array('form' => $form, 'selectedCategory' => $selectedCategory)));
        if(isset($resultsEvent->getData()['form'])) {
            $form = $resultsEvent->getData()['form'];
        }
        $submit = new Submit('save');
        $form->addElement($submit);

        return $form;
    }

    /*
      * add category filter element
    */
    public function addGroupCategoryFilterElement(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form'])) {
            $form = $params['form'];
            $categories = $this->getGroupCategoryList();
            $categoryStatus = new Selectbox('categoryStatus');
            $option = array();
            $option[null] = OW::getLanguage()->text('iisgroupsplus','select_category');
            foreach ($categories as $category) {
                $option[$category->id] = $category->label;
            }
            $categoryStatus->setHasInvitation(false);
            if(isset($params['selectedCategory'])) {
                $categoryStatus->setValue($params['selectedCategory']);
            }else if(isset($params['groupId'])){
                $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::GET_GROUP_SELECTED_CATEGORY_ID, array('groupId' => $params['groupId'])));
                if(isset($resultsEvent->getData()['selectedCategoryId'])) {
                    $categoryStatus->setValue($resultsEvent->getData()['selectedCategoryId']);
                }
            }
            $categoryStatus->setOptions($option);
            $form->addElement($categoryStatus);
            $event->setData(array('form' => $form));
        }
    }

    /*
    * get group selected category id
    */
    public function getGroupSelectedCategoryId(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId'])){
            $categoryId = $this->getGroupCategoryByGroupId($params['groupId']);
            $event->setData(array('selectedCategoryId' => $categoryId));
        }
    }

    /*
    * get group selected category id
    */
    public function getGroupSelectedCategoryLabel(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId'])){
            $categoryId = $this->getGroupCategoryByGroupId($params['groupId']);
            if($categoryId!=null) {
                $category = $this->categoryDao->findById($categoryId);
                $event->setData(array('categoryLabel' => $category->getLabel(),'categoryStatus'=>$categoryId));
            }
        }
    }


    public function addCategoryToGroup(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId']) && isset($params['categoryId']))
        {
            $this->groupInformationDao->addCategoryToGroup($params['groupId'],$params['categoryId']);
        }
    }


    public function getGroupCategoryList()
    {
        return $this->categoryDao->findAll();
    }

    public function getCategoryById($id)
    {
        return $this->categoryDao->findById($id);
    }
    public function getGroupInformationByCategoryId($categoryId)
    {
        return $this->groupInformationDao->getGroupInformationByCategoryId($categoryId);
    }

    public function getGroupIdListByCategoryID($categoryId)
    {
        if($categoryId!=null) {
            $groupInfoList = $this->getGroupInformationByCategoryId($categoryId);
            $groupIdList = array();
            foreach ($groupInfoList as $groupInfo) {
                $groupIdList[] = $groupInfo->groupId;
            }
            return $groupIdList;
        }
    }


    public function getGroupCategoryByGroupId($groupId)
    {
        $groupInfo =  $this->groupInformationDao->getGroupInformationByGroupId($groupId);
        if(isset($groupInfo->categoryId)) {
            return $groupInfo->categoryId;
        }
        return null;
    }

    public function addGroupCategory($label)
    {
        $category = new IISGROUPSPLUS_BOL_Category();
        $category->label = $label;
        IISGROUPSPLUS_BOL_CategoryDao::getInstance()->save($category);
    }

    public function deleteGroupCategory( $categoryId )
    {
        $categoryId = (int) $categoryId;
        if ( $categoryId > 0 )
        {
            $this->groupInformationDao->deleteByCategoryId($categoryId);
            $this->categoryDao->deleteById($categoryId);
        }
    }

    public function getItemForm($id)
    {
        $item = $this->getCategoryById($id);
        $formName = 'edit-item';
        $submitLabel = 'edit';
        $actionRoute = OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Admin', 'editItem');

        $form = new Form($formName);
        $form->setAction($actionRoute);

        if ($item != null) {
            $idField = new HiddenField('id');
            $idField->setValue($item->id);
            $form->addElement($idField);
        }

        $fieldLabel = new TextField('label');
        $fieldLabel->setRequired();
        $fieldLabel->setInvitation(OW::getLanguage()->text('iisgroupsplus', 'label_category_label'));
        $fieldLabel->setValue($item->label);
        $fieldLabel->setHasInvitation(true);
        $validator = new IISGROUPSPLUS_CLASS_LabelValidator();
        $language = OW::getLanguage();
        $validator->setErrorMessage($language->text('iisgroupsplus', 'label_error_already_exist'));
        $fieldLabel->addValidator($validator);
        $form->addElement($fieldLabel);

        $submit = new Submit('submit', 'button');
        $submit->setValue(OW::getLanguage()->text('iisgroupsplus', 'edit_item'));
        $form->addElement($submit);

        return $form;
    }

    public function editItem($id, $label)
    {
        $item = $this->getCategoryById($id);
        if ($item == null) {
            return;
        }
        if ($label == null) {
            $label = false;
        }
        $item->label = $label;

        $this->categoryDao->save($item);
        return $item;
    }

    public function getSearchBox(OW_Event $event)
    {

    }

    public function addWidgetToOthers(OW_Event $event)
    {
        $params = $event->getParams();

        if ( !isset($params['place']) || !isset($params['section']) )
        {
            return;
        }
        try
        {
            $widgetService = BOL_ComponentAdminService::getInstance();
            $widget = $widgetService->addWidget('IISGROUPSPLUS_CMP_PendingInvitation', false);
            $widgetUniqID = $params['place'] . '-' . $widget->className;

            //*remove if exists
            $widgets = $widgetService->findPlaceComponentList($params['place']);
            foreach ( $widgets as $w )
            {
                if($w['uniqName'] == $widgetUniqID)
                    $widgetService->deleteWidgetPlace($widgetUniqID);
            }
            //----------*/

            //add
            $placeWidget = $widgetService->addWidgetToPlace($widget, $params['place'], $widgetUniqID);
            $widgetService->addWidgetToPosition($placeWidget, $params['section'], -1);
        }
        catch ( Exception $e ) { }
    }

    public function onGroupToolbarCollect( BASE_CLASS_EventCollector $e )
    {
        $params = $e->getParams();
        if ( !OW::getUser()->isAuthenticated() || !isset($params['groupId']) )
        {
            return;
        }
        $groupId = $params['groupId'];
        $users = GROUPS_BOL_Service::getInstance()->findAllInviteList($groupId);
        if($users!=null && sizeof($users)>0) {
            $e->add(array(
                'label' => OW::getLanguage()->text('iisgroupsplus', 'pending_invitation'),
                'href' => '#',
                'click' => "javascript:OW.ajaxFloatBox('IISGROUPSPLUS_CMP_PendingUsers', {groupId: '".$groupId."'} , {width:700, iconClass: 'ow_ic_add'});"
            ));
        }
    }

    public function setUserManagerStatus(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['contextParentActionKey']) && isset($params['userId']) &&
            isset($params['groupOwnerId'])&& isset($params['groupId']) && isset($params['contextActionMenu'])){
            if ($params['userId'] != $params['groupOwnerId']) {
                $contextAction = new BASE_ContextAction();
                $contextAction->setParentKey($params['contextParentActionKey']);
                if ($params['groupOwnerId'] != $params['userId']) {
                    $groupManager = $this->groupManagersDao->getGroupManagerByUidAndGid($params['groupId'],$params['userId']);
                    if(isset($groupManager)){
                        $contextAction->setKey('delete_user_as_manager');
                        $contextAction->setLabel(OW::getLanguage()->text('iisgroupsplus', 'remove_group_user_manager_label'));
                        $callbackUri = OW::getRequest()->getRequestUri();
                        $deleteUrl = OW::getRequest()->buildUrlQueryString(OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Groups', 'deleteUserAsManager', array(
                            'groupId' => $params['groupId'],
                            'userId' => $params['userId']
                        )), array(
                            'redirectUri' => urlencode($callbackUri)
                        ));

                        $contextAction->setUrl($deleteUrl);

                        $contextAction->addAttribute('data-message', OW::getLanguage()->text('iisgroupsplus', 'delete_group_user_confirmation'));
                        $contextAction->addAttribute('onclick', "return confirm($(this).data().message)");
                    }else {
                        $contextAction->setKey('add_user_as_manager');
                        $contextAction->setLabel(OW::getLanguage()->text('iisgroupsplus', 'add_group_user_as_manager_label'));
                        $callbackUri = OW::getRequest()->getRequestUri();
                        $addUrl = OW::getRequest()->buildUrlQueryString(OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Groups', 'addUserAsManager', array(
                            'groupId' => $params['groupId'],
                            'userId' => $params['userId']
                        )), array(
                            'redirectUri' => urlencode($callbackUri)
                        ));

                        $contextAction->setUrl($addUrl);
                    }
                } else {
                    $contextAction->setUrl('javascript://');
                    $contextAction->addAttribute('data-message', OW::getLanguage()->text('iisgroupsplus', 'group_owner_delete_error'));
                    $contextAction->addAttribute('onclick', "OW.error($(this).data().message); return false;");
                }
                $params['contextActionMenu']->addAction($contextAction);
            }
        }
    }

    public function deleteUserManager($groupId,$userId){
        if(!isset($groupId) || !isset($userId) ){
            return;
        }
        $this->groupManagersDao->deleteGroupManagerByUidAndGid($groupId,$userId);
    }

    public function addUserAsManager($groupId,$userId){
        if(!isset($groupId) || !isset($userId) ){
            return;
        }
        $this->groupManagersDao->addUserAsManager($groupId,$userId);
    }

    public function checkUserManagerStatus(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId'])){
            $userGroupManager = $this->groupManagersDao->getGroupManagerByUidAndGid($params['groupId'],OW::getUser()->getId());
            if(isset($userGroupManager)){
                $event->setData(array('isUserManager'=>true));
            }
        }
    }
    public function deleteUserAsManager(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId']) && isset($params['userId']) ){
            $this->groupManagersDao->deleteGroupManagerByUidAndGid($params['groupId'],$params['userId']);
        }
    }

    public function setMobileUserManagerStatus(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['contextMenu']) && isset($params['userId']) &&
            isset($params['groupOwnerId'])&& isset($params['groupId'])){
            if ($params['userId'] != $params['groupOwnerId']) {
                if ($params['groupOwnerId'] != $params['userId']) {
                    $groupManager = $this->groupManagersDao->getGroupManagerByUidAndGid($params['groupId'],$params['userId']);
                    if(isset($groupManager)){
                        $callbackUri = OW::getRequest()->getRequestUri();
                        $deleteUrl = OW::getRequest()->buildUrlQueryString(OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Groups', 'deleteUserAsManager', array(
                            'groupId' => $params['groupId'],
                            'userId' => $params['userId']
                        )), array(
                            'redirectUri' => urlencode($callbackUri)
                        ));
                        array_unshift($params['contextMenu'], array(
                            'label' => OW::getLanguage()->text('iisgroupsplus', 'remove_group_user_manager_label'),
                            'attributes' => array(
                                'onclick' => UTIL_JsGenerator::composeJsString('if ( confirm($(this).data(\'confirm-msg\')) ) window.location = \''.$deleteUrl.'\'', array(
                                    'groupId' => $params['groupId'],
                                    'userId' => $params['userId']
                                )),
                                "data-confirm-msg" => OW::getLanguage()->text('iisgroupsplus', 'delete_group_user_confirmation')
                            ),
                            "class" => "owm_red_btn"
                        ));

                    }else {
                        $callbackUri = OW::getRequest()->getRequestUri();
                        $addUrl = OW::getRequest()->buildUrlQueryString(OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Groups', 'addUserAsManager', array(
                            'groupId' => $params['groupId'],
                            'userId' => $params['userId']
                        )), array(
                            'redirectUri' => urlencode($callbackUri)
                        ));



                        array_unshift($params['contextMenu'], array(
                            'label' => OW::getLanguage()->text('iisgroupsplus', 'add_group_user_as_manager_label'),
                            'attributes' => array(
                                'onclick' => UTIL_JsGenerator::composeJsString('window.location = \''.$addUrl.'\'', array(
                                    'groupId' => $params['groupId'],
                                    'userId' => $params['userId']
                                ))
                            ),
                            "class" => "owm_red_btn"
                        ));
                    }
                }
                $event->setData(array('contextMenu'=>$params['contextMenu']));
            }
        }
    }

    public function findFileList($groupId, $first=0, $count)
    {
        $groupFileList = $this->groupFileDao->findFileListByGroupId($groupId, $first, $count);
        $attachmentList = array();
        foreach ( $groupFileList as $groupFile )
        {
            $attachment = BOL_AttachmentDao::getInstance()->findById($groupFile->attachmentId);
            if(isset($attachment) && $attachment->getId()>0) {
                $attachmentList[] = $attachment;
            }
        }

        return $attachmentList;

    }

    public function findFileListCount($groupId)
    {
        return $this->groupFileDao->findCountByGroupId($groupId);

    }

    public function getUploadFileForm($groupId)
    {
        $plugin = OW::getPluginManager()->getPlugin('iisgroupsplus');
        /*        if (!$this->service->isCurrentUserCanCreate()) {
                    $permissionStatus = BOL_AuthorizationService::getInstance()->getActionStatus('groups', 'create');

                    throw new AuthorizationException($permissionStatus['msg']);
                }*/

        $language = OW::getLanguage();

        OW::getDocument()->setHeading($language->text('iisgroupsplus', 'file_create_heading'));
        OW::getDocument()->setHeadingIconClass('ow_ic_new');
        OW::getDocument()->setTitle($language->text('iisgroupsplus', 'file_create_page_title'));
        OW::getDocument()->setDescription($language->text('iisgroupsplus', 'file_create_page_description'));

        $form = new IISGROUPSPLUS_FileUploadForm();
        $actionRoute = OW::getRouter()->urlFor('IISGROUPSPLUS_CTRL_Groups', 'addFile', array('groupId' => $groupId));
        $form->setAction($actionRoute);
        return $form;
    }

    public function addFileForGroup($groupId, $attachmentId){
        return $this->groupFileDao->addFileForGroup($groupId,$attachmentId);
    }

    public function deleteFileForGroup($groupId, $attachmentId){
        $this->groupFileDao->deleteGroupFilesByAidAndGid($groupId,$attachmentId);
    }

    public function deleteFileForGroupByGroupId($groupId){
        $this->groupFileDao->deleteGroupFilesByGroupId($groupId);
    }

    public function findFileIdByAidAndGid($groupId, $attachmentId){
        return $this->groupFileDao->findFileIdByAidAndGid($groupId,$attachmentId);
    }
    public function deleteFiles(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['groupId'])) {
            $filesDto = $this->groupFileDao->getGroupFilesByGroupId($params['groupId']);
            foreach ($filesDto as $file) {
                try {
                    OW::getEventManager()->call("feed.delete_item", array(
                        'entityType' => 'groups-add-file',
                        'entityId' => $file->id
                    ));
                    OW::getEventManager()->call('notifications.remove', array(
                        'entityType' => 'groups-add-file',
                        'entityId' => $file->id
                    ));
                    $this->deleteFileForGroupByGroupId($params['groupId']);
                    BOL_AttachmentService::getInstance()->deleteAttachmentById($file->attachmentId);
                } catch (exception $e) {

                }
            }
        }
        else if(isset($params['allFiles'])) {
            $filesDto = $this->groupFileDao->findAllFiles();
            foreach ($filesDto as $file) {
                try {
                    BOL_AttachmentService::getInstance()->deleteAttachmentById($file->attachmentId);
                    OW::getEventManager()->call("feed.delete_item", array(
                        'entityType' => 'groups-add-file',
                        'entityId' => $file->id
                    ));
                    OW::getEventManager()->call('notifications.remove', array(
                        'entityType' => 'groups-add-file',
                        'entityId' => $file->id
                    ));
                } catch (exception $e) {

                }
            }
        }
    }

    public function addFileWidget(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['controller']) && isset($params['groupId'])){
            $bcw = new BASE_CLASS_WidgetParameter();
            $bcw->additionalParamList=array('entityId'=>$params['groupId']);
            $groupController = $params['controller'];
            $groupController->addComponent('groupFileList', new IISGROUPSPLUS_MCMP_FileListWidget($bcw));
            $fileBoxInformation = array(
                'show_title' => true,
                'title' => OW_Language::getInstance()->text('iisgroupsplus', 'widget_files_title'),
                'wrap_in_box' => true,
                'icon' => 'ow_ic_info',
                'type' => "",
            );
            $groupController->assign('fileBoxInformation', $fileBoxInformation);
        }
    }

    public function onCollectNotificationActions( BASE_CLASS_EventCollector $e )
    {
        $e->add(array(
            'section' => 'groups',
            'action' => 'groups-add-file',
            'description' => OW::getLanguage()->text('iisgroupsplus', 'email_notifications_setting_file'),
            'selected' => true,
            'sectionLabel' => OW::getLanguage()->text('iisgroupsplus', 'email_notification_section_label'),
            'sectionIcon' => 'ow_ic_write'
        ));
        $e->add(array(
            'section' => 'groups',
            'action' => 'groups-update-status',
            'description' => OW::getLanguage()->text('iisgroupsplus', 'email_notifications_setting_status'),
            'selected' => true,
            'sectionLabel' => OW::getLanguage()->text('iisgroupsplus', 'email_notification_section_label'),
            'sectionIcon' => 'ow_ic_write'
        ));
    }

    public function onUpdateGroupStatus(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['feedId']) && (isset($params['feedType']) && $params['feedType']=='groups') && isset($params['status'])) {
            $groupService = GROUPS_BOL_Service::getInstance();
            $group = $groupService->findGroupById($params['feedId']);
            if ($group) {
                $groupUrl = $groupService->getGroupUrl($group);
                /*
                  * send notification to group members
                 */
                $userId = OW::getUser()->getId();
                $avatars = BOL_AvatarService::getInstance()->getDataForUserAvatars(array($userId));
                $avatar = $avatars[$userId];
                $userUrl = BOL_UserService::getInstance()->getUserUrl($userId);
                $notificationParams = array(
                    'pluginKey' => 'groups',
                    'action' => 'groups-update-status',
                    'entityType' => 'groups-update-status',
                    'entityId' => $params['feedId'],
                    'userId' => null,
                    'time' => time()
                );

                $notificationData = array(
                    'string' => array(
                        "key" => 'iisgroupsplus+notif_update_status_string',
                        "vars" => array(
                            'groupTitle' => $group->title,
                            'groupUrl' => $groupUrl,
                            'userName' => BOL_UserService::getInstance()->getDisplayName($userId),
                            'userUrl' => $userUrl
                        )
                    ),
                    'avatar' => $avatar,
                    'content' => '',
                    'url' => $groupUrl
                );

                $userIds = GROUPS_BOL_Service::getInstance()->findGroupUserIdList($group->id);

                foreach ($userIds as $uid) {
                    if ($uid == OW::getUser()->getId()) {
                        continue;
                    }

                    $notificationParams['userId'] = $uid;

                    $event = new OW_Event('notifications.add', $notificationParams, $notificationData);
                    OW::getEventManager()->trigger($event);
                }
            }
        }
    }

}

class IISGROUPSPLUS_FileUploadForm extends Form
{
    public function __construct()
    {
        parent::__construct('fileUploadForm');

        $this->setEnctype(Form::ENCTYPE_MULTYPART_FORMDATA);

        $language = OW::getLanguage();

        $nameField = new TextField('name');
        $nameField->setLabel($language->text('iisgroupsplus', 'create_field_file_name_label'));
        $this->addElement($nameField);

        $fileField = new FileField('fileUpload');
        $fileField->setLabel($language->text('iisgroupsplus', 'create_field_file_upload_label'));
        $this->addElement($fileField);

        $saveField = new Submit('save');
        $saveField->setValue(OW::getLanguage()->text('iisgroupsplus', 'create_submit_btn_label'));
        $this->addElement($saveField);
    }

}