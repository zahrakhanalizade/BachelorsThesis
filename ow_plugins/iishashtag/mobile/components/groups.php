<?php

/**
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_MCMP_Groups extends OW_MobileComponent
{

    public function __construct( array $idList , $page = 1)
    {
        parent::__construct();

        $groupService = GROUPS_BOL_Service::getInstance();
        $groups = $groupService->findGroupsWithIds($idList);

        //delete removed ids
        $existingEntityIds = array();
        foreach($groups as $item){
            $existingEntityIds[] = $item->id;
        }
        if(count($idList)>count($existingEntityIds)){
            $newsfeedService = NEWSFEED_BOL_Service::getInstance();
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)){
                    if( $newsfeedService->findAction("group", $id) === null
                        && $newsfeedService->findAction("groups-status", $id) === null
                        && $newsfeedService->findAction("groups-join", $id) === null ) {
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
            $paging = new BASE_CMP_PagingMobile($page, ceil($itemsCount / $rpp), 5);
            $this->addComponent('paging', $paging);
            $first = ($page - 1) * $rpp;
            $count = $rpp;
            $groups = array_slice($groups, $first, $count);
        }else{
            $groups = array();
        }


        $out = array();

        foreach ( $groups as $item )
        {
            /* @var $item GROUPS_BOL_Group */

            $userCount = GROUPS_BOL_Service::getInstance()->findUserListCount($item->id);
            $title = strip_tags($item->title);

            $toolbar = array(
                array(
                    'label' => OW::getLanguage()->text('groups', 'listing_users_label', array(
                        'count' => $userCount
                    ))
                )
            );

            $out[] = array(
                'id' => $item->id,
                'url' => OW::getRouter()->urlForRoute('groups-view', array('groupId' => $item->id)),
                'title' => $title,
                'imageTitle' => $title,
                'content' => UTIL_String::truncate(strip_tags($item->description), 300, '...'),
                'time' => UTIL_DateTime::formatDate($item->timeStamp),
                'imageSrc' => GROUPS_BOL_Service::getInstance()->getGroupImageUrl($item),
                'users' => $userCount,
                'toolbar' => $toolbar
            );
        }

        $this->assign('list', $out);
    }
}