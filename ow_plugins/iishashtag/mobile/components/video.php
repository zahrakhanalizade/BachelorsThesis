<?php

/**
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_MCMP_Video extends OW_MobileComponent
{

    public function __construct( array $idList , $page = 1)
    {
        parent::__construct();

        $clipObject = VIDEO_BOL_ClipDao::getInstance()->getClipsList('latest', 1, 500, $idList);

        //delete removed ids
        $existingEntityIds = array();
        foreach($clipObject as $item){
            $existingEntityIds[] = $item->id;
        }
        if(count($idList)>count($existingEntityIds)){
            $newsfeedService = NEWSFEED_BOL_Service::getInstance();
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)) {
                    if ($newsfeedService->findAction("video_comments", $id) === null) {
                        $deletedEntityIds[] = $key;
                    }
                }
            }
            IISHASHTAG_BOL_Service::getInstance()->deleteEntitiesByListIds($deletedEntityIds);
        }

        //paging
        $rpp = VIDEO_BOL_ClipService::getInstance()->getClipPerPageConfig();
        $itemsCount = count($existingEntityIds);
        if($page>0 && $page<=ceil($itemsCount / $rpp)) {
            $paging = new BASE_CMP_PagingMobile($page, ceil($itemsCount / $rpp), 5);
            $this->addComponent('paging', $paging);
            $first = $itemsCount - (($page - 1) * $rpp) - $rpp;
            $count = $rpp;
            if($first<0){
                $count = $count + $first;
                $first = 0;
            }
            $clipObject = array_slice($clipObject, $first, $count);
        }else{
            $clipObject = array();
        }

        $clips = array();
        if ( is_array($clipObject) )
        {
            foreach ( $clipObject as $key => $clip )
            {
                $clip = (array) $clip;
                $clips[$key] = $clip;
                $clips[$key]['thumb'] = VIDEO_BOL_ClipService::getInstance()->getClipThumbUrl($clip['id'], $clip['code'], $clip['thumbUrl']);
                $clips[$key]['username'] = BOL_UserService::getInstance()->getUserName($clip['userId']);
            }
        }
        $event = new OW_Event('videplus.on.video.list.view.render', array('clips'=>$clips));
        OW::getEventManager()->trigger($event);
        if(isset($event->getData()['clips'])){
            $clips=$event->getData()['clips'];
        }
        $this->assign('clips', $clips);
        $this->assign('count', count($clips));
    }
}