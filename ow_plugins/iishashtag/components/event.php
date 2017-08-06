<?php

/**
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_CMP_Event extends OW_Component
{

    public function __construct( array $idList , $page = 1)
    {
        parent::__construct();

        $eventService = EVENT_BOL_EventService::getInstance();
        $events = $eventService->findEventsWithIds($idList, 1, 500, true);

        //delete removed ids
        $existingEntityIds = array();
        foreach($events as $item){
            $existingEntityIds[] = $item->id;
        }
        if(count($idList)>count($existingEntityIds)){
            $newsfeedService = NEWSFEED_BOL_Service::getInstance();
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)){
                    if( $newsfeedService->findAction("event", $id) === null ) {
                        $deletedEntityIds[] = $key;
                    }
                }
            }
            IISHASHTAG_BOL_Service::getInstance()->deleteEntitiesByListIds($deletedEntityIds);
        }

        //paging
        $rpp = 10;
        $itemsCount = count($existingEntityIds);
        if($page>0 && $page<=ceil($itemsCount / $rpp)) {
            $paging = new BASE_CMP_Paging($page, ceil($itemsCount / $rpp), 5);
            $this->addComponent('paging', $paging);
            $first = ($page - 1) * $rpp;
            $count = $rpp;
            $events = array_slice($events, $first, $count);
        }else{
            $events = array();
            $this->assign('events', $events);
            return;
        }

        if ( ( !OW::getUser()->isAuthenticated() || !OW::getUser()->isAuthorized('event', 'add_event') ) && sizeof($events) == 0 )
        {
            $this->setVisible(false);
            return;
        }

        $this->assign('events', $eventService->getListingDataWithToolbar($events));

        if ( sizeof($idList) > sizeof($events) )
        {
            $toolbarArray = array(array('href' => OW::getRouter()->urlForRoute('event.view_event_list', array('list' => 'latest')), 'label' => OW::getLanguage()->text('event', 'view_all_label')));
            $this->assign('toolbar', $toolbarArray);
        }
    }
}