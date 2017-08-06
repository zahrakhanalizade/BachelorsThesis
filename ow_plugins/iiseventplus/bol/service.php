<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 * 
 *
 * @author Mohammad Aghaabbasloo
 * @package ow_plugins.iiseventplus
 * @since 1.0
 */
class IISEVENTPLUS_BOL_Service
{
    private static $EVENT_GENERAL = 'event_general';
    private static $EVENT_MY = 'event_my';
    private static $classInstance;
    public static $PARTICIPATE_ALL = 1;
    public static $PARTICIPATE_SURE = 2;
    public static $PARTICIPATE_MAYBE = 3;
    public static $PARTICIPATE_NO = 4;
    const ADD_FILTER_PARAMETERS_TO_PAGING = 'eventplus.add.filter.parameters.to.paging';
    public static $DATE_ALL = 1;
    public static $DATE_LATEST = 2;
    public static $DATE_PAST = 3;


    private  $eventInformationDao;
    private $categoryDao;
    private $past=null;
    private $categoryStatus = '';
    private $searchTitle = '';
    private $dateStatus = '';
    private $participateStatus = '';
    private $participationStatus = '';
    private $page;
    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }


    private function __construct()
    {
        $this->eventInformationDao = IISEVENTPLUS_BOL_EventInformationDao::getInstance();
        $this->categoryDao = IISEVENTPLUS_BOL_CategoryDao::getInstance();
    }

    public function setTitleHeaderListItemEvent(OW_Event $event)
    {
        $params = $event->getParams();
        if (isset($params['listType']) && $params['listType'] == IISEVENTPLUS_BOL_Service::$EVENT_GENERAL) {
            OW::getDocument()->setTitle(OW::getLanguage()->text('iiseventplus', 'meta_title_event_add_general'));
            OW::getDocument()->setDescription(OW::getLanguage()->text('iiseventplus', 'meta_description_event_general'));
        } else if (isset($params['listType']) && $params['listType'] == IISEVENTPLUS_BOL_Service::$EVENT_MY) {
            OW::getDocument()->setTitle(OW::getLanguage()->text('iiseventplus', 'meta_title_event_add_my'));
            OW::getDocument()->setDescription(OW::getLanguage()->text('iiseventplus', 'meta_description_event_my'));
        }
    }

    public function addListTypeToEvent(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['list'])){
            $list = $params['list'];
            $keys = array(IISEVENTPLUS_BOL_Service::$EVENT_GENERAL
            ,IISEVENTPLUS_BOL_Service::$EVENT_MY, 'invited');
            if(!in_array($list, $keys))
            {
                $list=self::$EVENT_GENERAL;
            }
            $event->setData(array('list' => $list));
        }
        else {
            if (isset($params['menuItems'])) {
                $menuItems = $params['menuItems'];
                if (OW::getUser()->isAuthenticated()) {
                    $item = new BASE_MenuItem();
                    $item->setLabel(OW::getLanguage()->text('iiseventplus', IISEVENTPLUS_BOL_Service::$EVENT_MY));
                    $item->setUrl(OW::getRouter()->urlForRoute('event.view_event_list', array('list' => IISEVENTPLUS_BOL_Service::$EVENT_MY)));
                    $item->setKey(IISEVENTPLUS_BOL_Service::$EVENT_MY);
                    $item->setIconClass('ow_ic_clock');
                    array_push($menuItems, $item);

                }
                $item = new BASE_MenuItem();
                $item->setLabel(OW::getLanguage()->text('iiseventplus', IISEVENTPLUS_BOL_Service::$EVENT_GENERAL));
                $item->setUrl(OW::getRouter()->urlForRoute('event.view_event_list', array('list' => IISEVENTPLUS_BOL_Service::$EVENT_GENERAL)));
                $item->setKey(IISEVENTPLUS_BOL_Service::$EVENT_GENERAL);
                $item->setIconClass('ow_ic_clock');
                array_push($menuItems, $item);

                $correctedMenuItems = array();
                $toRemoveKeys = array('joined', 'past', 'latest');
                $sizeOfMenuItems = sizeof($menuItems);
                foreach ($menuItems as $item) {
                    if (!in_array((string)$item->getKey(), $toRemoveKeys)) {
                        $correctedMenuItems[] = $item;
                        $item->setOrder($sizeOfMenuItems);
                        $sizeOfMenuItems--;
                    }
                }
                $event->setData(array('menuItems' => $correctedMenuItems));
            }
        }
    }

    public function addEventFilterForm(OW_Event $event)
    {
        $params = $event->getParams();
        $tab = self::$EVENT_GENERAL;
        $participationStatus = '';
        $dateStatus = '';
        $searchTitle = '';
        if (isset($params['tab'])) {
            $tab = $params['tab'];
        }
        if (isset($params['participationStatus'])) {
            $participationStatus = $params['participationStatus'];
        }
        if (isset($params['dateStatus'])) {
            $dateStatus = $params['dateStatus'];
        }
        if (isset($params['categoryStatus'])) {
            $categoryStatus = $params['categoryStatus'];
        }
        if (isset($params['searchTitle'])) {
            $searchTitle = $params['searchTitle'];
        }

        $event->setData(array('eventFilterForm' => $this->getEventFilterForm('EventFilterForm', $tab, $participationStatus, $dateStatus,$categoryStatus,$searchTitle)));
    }

    public function setFilterParameters(){
        if (OW::getRequest()->isPost()) {
            $this->page=1;
            $this->getFilterParameters($_POST);
        }else{
            $this->getFilterParameters( $_GET);
        }
    }

    public function getFilterParameters( $data){
        $eventService = EVENT_BOL_EventService::getInstance();
        if(isset($data['dateStatus'])) {
            $this->dateStatus = $data['dateStatus'];
        }
        switch($this->dateStatus){
            case self::$DATE_LATEST:
                $this->past=false;
                break;
            case self::$DATE_PAST:
                $this->past=true;
                break;
            default:
                $this->past = null;
        }

        if(isset($data['participationStatus'])) {
            $this->participationStatus = $data['participationStatus'];
        }
        switch($this->participationStatus){
            case self::$PARTICIPATE_SURE:
                $this->participateStatus= $eventService::USER_STATUS_YES ;
                break;
            case self::$PARTICIPATE_MAYBE:
                $this->participateStatus= $eventService::USER_STATUS_MAYBE ;
                break;
            case self::$PARTICIPATE_NO:
                $this->participateStatus= $eventService::USER_STATUS_NO ;
                break;
            default:
                $this->participateStatus = null;
        }
        if(isset($data['categoryStatus'])) {
            $this->categoryStatus = $data['categoryStatus'];
        }
        if(isset($data['searchTitle'])) {
            $this->searchTitle = $data['searchTitle'];
        }
    }

    public function getResultForListItemEvent(OW_Event $event)
    {
        if(class_exists("EVENT_BOL_EventService")) {
            $params = $event->getParams();
            $eventService = EVENT_BOL_EventService::getInstance();
            $eventController = $params['eventController'];
            $isPublic = true;
            $addUnapproved = false;
            $userId = '';
            $eventIds = array();
            $activeTab = IISEVENTPLUS_BOL_Service::$EVENT_GENERAL;
            $this->page = $params['page'];
            $this->setFilterParameters();
            if ($params['list'] != IISEVENTPLUS_BOL_Service::$EVENT_MY) {
                $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ADD_EVENT_FILTER_FORM, array('tab' => self::$EVENT_GENERAL, 'dateStatus' => $this->dateStatus, 'categoryStatus' => $this->categoryStatus, 'searchTitle' => $this->searchTitle)));
                if (isset($resultsEvent->getData()['eventFilterForm'])) {
                    $eventFilterForm = $resultsEvent->getData()['eventFilterForm'];
                }

            } else {
                $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ADD_EVENT_FILTER_FORM, array('tab' => self::$EVENT_MY, 'participationStatus' => $this->participationStatus, 'dateStatus' => $this->dateStatus, 'categoryStatus' => $this->categoryStatus, 'searchTitle' => $this->searchTitle)));
                if (isset($resultsEvent->getData()['eventFilterForm'])) {
                    $eventFilterForm = $resultsEvent->getData()['eventFilterForm'];
                }
                $isPublic = false;
                $addUnapproved = true;
                $userId = OW::getUser()->getId();
                $activeTab = self::$EVENT_MY;
            }
            if ($this->categoryStatus != null) {
                $eventIds = $this->getEventIdListByCategoryID($this->categoryStatus);
                if ($eventIds == null) {
                    $eventIds[] = -1;
                }
            }
            $events = $eventService->findPublicEventsByFiltering($this->page, null, $userId, $this->participateStatus, $this->past, $eventIds, $addUnapproved, $isPublic, $this->searchTitle);
            $eventsCount = $eventService->findPublicEventsByFilteringCount($userId, $this->participateStatus, $this->past, $eventIds, $addUnapproved, $isPublic, $this->searchTitle);
            $event->setData(array('events' => $events, 'eventsCount' => $eventsCount, 'page' => $this->page));
            $this->setEventController($activeTab, $eventFilterForm, $eventController);
        }
    }

    public function setEventController($activeTab, $filterForm, $eventController)
    {
        $contentMenu = EVENT_BOL_EventService::getInstance()->getContentMenu();
        $contentMenu->getElement($activeTab)->setActive(true);
        $eventController->addComponent('contentMenu', $contentMenu);
        if (isset($filterForm)) {
            $eventController->assign('filterForm', true);
            $eventController->addForm($filterForm);
            $filterFormElementsKey = array();
            foreach ($filterForm->getElements() as $element) {
                if ($element->getAttribute('type') != 'hidden') {
                    $filterFormElementsKey[] = $element->getAttribute('name');
                }
            }
            $eventController->assign('filterFormElementsKey', $filterFormElementsKey);
        }
    }

    /**
     * Add select date filter Form
     * @param $name
     * @return Form
     */
    public function getEventFilterForm($name, $tab, $selectedParticipationStatus = 1, $selectedDateStatus = 1, $selectedCategory=1,$searchedTitle=null)
    {
        $form = new Form($name);

        $searchTitle = new TextField('searchTitle');
        $searchTitle->addAttribute('placeholder',OW::getLanguage()->text('iiseventplus', 'search_title'));
        $searchTitle->addAttribute('class','event_search_title');
        if($searchedTitle!=null) {
            $searchTitle->setValue($searchedTitle);
        }
        $searchTitle->setHasInvitation(false);
        $form->addElement($searchTitle);

        $dateStatus = new Selectbox('dateStatus');
        $option = array();
        $option[IISEVENTPLUS_BOL_Service::$DATE_ALL] = OW::getLanguage()->text('iiseventplus', 'date_all');
        $option[IISEVENTPLUS_BOL_Service::$DATE_LATEST] = OW::getLanguage()->text('iiseventplus', 'date_latest');
        $option[IISEVENTPLUS_BOL_Service::$DATE_PAST] = OW::getLanguage()->text('iiseventplus', 'date_past');
        $dateStatus->setHasInvitation(false);
        $dateStatus->setValue($selectedDateStatus);
        $dateStatus->setOptions($option);
        $form->addElement($dateStatus);

        if ($tab == self::$EVENT_MY) {
            $participationStatus = new Selectbox('participationStatus');
            $option = array();
            $option[IISEVENTPLUS_BOL_Service::$PARTICIPATE_ALL] = OW::getLanguage()->text('iiseventplus', 'participate_all');
            $option[IISEVENTPLUS_BOL_Service::$PARTICIPATE_SURE] = OW::getLanguage()->text('iiseventplus', 'participate_sure');
            $option[IISEVENTPLUS_BOL_Service::$PARTICIPATE_MAYBE] = OW::getLanguage()->text('iiseventplus', 'participate_maybe');
            $option[IISEVENTPLUS_BOL_Service::$PARTICIPATE_NO] = OW::getLanguage()->text('iiseventplus', 'participate_no');
            $participationStatus->setHasInvitation(false);
            $participationStatus->setValue($selectedParticipationStatus);
            $participationStatus->setOptions($option);
            $form->addElement($participationStatus);
        }
        $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ADD_CATEGORY_FILTER_ELEMENT, array('form' => $form, 'selectedCategory' => $selectedCategory)));
        if(isset($resultsEvent->getData()['form'])) {
            $form = $resultsEvent->getData()['form'];
        }
        $submit = new Submit('save');
        $form->addElement($submit);
        $form->setAction(OW::getRouter()->urlForRoute('event.view_event_list', array('list' => $tab)));

        return $form;
    }

    /*
      * add category filter element
    */
    public function addCategoryFilterElement(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form'])) {
            $form = $params['form'];
            $categories = $this->getEventCategoryList();
            $categoryStatus = new Selectbox('categoryStatus');
            $option = array();
            $option[null] = OW::getLanguage()->text('iiseventplus','select_category');
            foreach ($categories as $category) {
                $option[$category->id] = $category->label;
            }
            $categoryStatus->setHasInvitation(false);
            if(isset($params['selectedCategory'])) {
                $categoryStatus->setValue($params['selectedCategory']);
            }else if(isset($params['eventId'])){
                $resultsEvent = OW::getEventManager()->trigger(new OW_Event(IISEventManager::GET_EVENT_SELECTED_CATEGORY_ID, array('eventId' => $params['eventId'])));
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
    * get event selected category id
    */
    public function getEventSelectedCategoryId(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['eventId'])){
            $categoryId = $this->getEventCategoryByEventId($params['eventId']);
            $event->setData(array('selectedCategoryId' => $categoryId));
        }
    }

    /*
     * add leave button in an event view
     */
    public function addLeaveButton(OW_Event $event)
    {
        if(class_exists("EVENT_BOL_EventService")) {
            $params = $event->getParams();
            if (isset($params['creatorId']) && OW::getUser()->getId() != $params['creatorId']
                && isset($params['eventId'])
            ) {
                $eventService = EVENT_BOL_EventService::getInstance();
                $eventsCount = $eventService->findPublicEventsByFilteringCount(OW::getUser()->getId(), null, null, null, true, false, null);
                $page = null;
                if (isset($params['page'])) {
                    $page = $params['page'];
                }
                $events = $eventService->findPublicEventsByFiltering($page, $eventsCount, OW::getUser()->getId(), null, null, null, true, false, null);
                $eventId = $params['eventId'];
                foreach ($events as $ev) {
                    if ($ev->getId() == $eventId) {
                        $button = array(
                            'leave' =>
                                array(
                                    'url' => OW::getRouter()->urlForRoute('iiseventplus.leave', array('eventId' => $eventId)),
                                    'label' => OW::getLanguage()->text('iiseventplus', 'leave_button_label'),
                                    'confirmMessage' => OW::getLanguage()->text('iiseventplus', 'leave_confirm_message')
                                )
                        );
                        $event->setData(array('leaveButton' => $button));
                        break;
                    }
                }
            }
        }
    }

    /*
    * get event selected category id
    */
    public function getEventSelectedCategoryLabel(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['eventId'])){
            $categoryId = $this->getEventCategoryByEventId($params['eventId']);
            if($categoryId!=null) {
                $category = $this->categoryDao->findById($categoryId);
                $event->setData(array('categoryLabel' => $category->getLabel()));
            }
        }
    }

    public function addFilterParametersToPaging(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['setForPaging'])){
            $pagingParams="";
            if(isset($_POST['searchTitle']) && $_POST['searchTitle']!=""){
                $pagingParams=$pagingParams."&searchTitle=".$_POST['searchTitle'];
            }
            if(isset($_POST['dateStatus']) && $_POST['dateStatus']!=""){
                $pagingParams=$pagingParams."&dateStatus=".$_POST['dateStatus'];
            }
            if(isset($_POST['participationStatus']) && $_POST['participationStatus']!=""){
                $pagingParams=$pagingParams."&participationStatus=".$_POST['participationStatus'];
            }
            if(isset($_POST['categoryStatus']) && $_POST['categoryStatus']!=""){
                $pagingParams=$pagingParams."&categoryStatus=".$_POST['categoryStatus'];
            }
            $event->setData(array('pagingParams' => $pagingParams));
        }
    }

    public function addCategoryToEvent(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['eventId']) && isset($params['categoryId']))
        {
            $this->eventInformationDao->addCategoryToEvent($params['eventId'],$params['categoryId']);
        }
    }

    /***
     * @param $eventId
     * @param $userId
     */
    public function leaveEvent($eventId , $userId){
        $this->eventInformationDao->leaveUserFromEvent($eventId,$userId);
    }

    public function getEventCategoryList()
    {
        return $this->categoryDao->findAll();
    }

    public function getCategoryById($id)
    {
        return $this->categoryDao->findById($id);
    }
    public function getEventInformationByCategoryId($categoryId)
    {
        return $this->eventInformationDao->getEventInformationByCategoryId($categoryId);
    }

    public function getEventIdListByCategoryID($categoryId)
    {
        if($categoryId!=null) {
            $eventInfoList = $this->getEventInformationByCategoryId($categoryId);
            $evetIdList = array();
            foreach ($eventInfoList as $eventInfo) {
                $evetIdList[] = $eventInfo->eventId;
            }
            return $evetIdList;
        }
    }


    public function getEventCategoryByEventId($eventId)
    {
        $eventInfo =  $this->eventInformationDao->getEventInformationByEventId($eventId);
        if(isset($eventInfo->categoryId)) {
            return $eventInfo->categoryId;
        }
        return null;
    }

    public function addEventCategory($label)
    {
        $category = new IISEVENTPLUS_BOL_Category();
        $category->label = $label;
        IISEVENTPLUS_BOL_CategoryDao::getInstance()->save($category);
    }

    public function deleteEventCategory( $categoryId )
    {
        $categoryId = (int) $categoryId;
        if ( $categoryId > 0 )
        {
            $this->eventInformationDao->deleteByCategoryId($categoryId);
            $this->categoryDao->deleteById($categoryId);
        }
    }

    private function getCategoryKey( $name )
    {
        return 'dept_' . trim($name);
    }


    public function getItemForm($id)
    {
        $item = $this->getCategoryById($id);
        $formName = 'edit-item';
        $submitLabel = 'edit';
        $actionRoute = OW::getRouter()->urlFor('IISEVENTPLUS_CTRL_Admin', 'editItem');

        $form = new Form($formName);
        $form->setAction($actionRoute);

        if ($item != null) {
            $idField = new HiddenField('id');
            $idField->setValue($item->id);
            $form->addElement($idField);
        }

        $fieldLabel = new TextField('label');
        $fieldLabel->setRequired();
        $fieldLabel->setInvitation(OW::getLanguage()->text('iiseventplus', 'label_category_label'));
        $fieldLabel->setValue($item->label);
        $fieldLabel->setHasInvitation(true);
        $validator = new IISEVENTPLUS_CLASS_LabelValidator();
        $language = OW::getLanguage();
        $validator->setErrorMessage($language->text('iiseventplus', 'label_error_already_exist'));
        $fieldLabel->addValidator($validator);
        $form->addElement($fieldLabel);

        $submit = new Submit('submit', 'button');
        $submit->setValue(OW::getLanguage()->text('iiseventplus', 'edit_item'));
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
}