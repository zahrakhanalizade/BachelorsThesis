<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_MCMP_Newsfeed extends OW_MobileComponent
{

    public function __construct( array $idList )
    {
        parent::__construct();
        $actions = IISHASHTAG_BOL_Service::getInstance()->findActionsByEntityIds('user-status', $idList);
        $actionIdList = array();
        $existingEntityIds = array();
        foreach($actions as $action){
            array_unshift($actionIdList,$action->getId());
            $existingEntityIds[] = $action->entityId;
        }
        if(count($idList)>count($existingEntityIds)){
            $newsfeedService = NEWSFEED_BOL_Service::getInstance();
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)){
                    if ($newsfeedService->findAction("user-status", $id) === null) {
                        $deletedEntityIds[] = $key;
                    }
                }
            }
            IISHASHTAG_BOL_Service::getInstance()->deleteEntitiesByListIds($deletedEntityIds);
        }

        $feedParams['displayCount'] = 20;

        $feedParams['displayCount'] = $feedParams['displayCount'] > 20
            ? 20
            : $feedParams['displayCount'];

        $feedParams['includeActionIdList'] = $actionIdList;
        $feedParams['viewMore'] = false;
        if(is_array($idList)){
            if(sizeof($idList)>20){
                $feedParams['viewMore'] = true;
            }
        }

        $feed = $this->createFeed('site', null);
        $feed->setDisplayType(NEWSFEED_CMP_Feed::DISPLAY_TYPE_ACTIVITY);

        $feed->setup($feedParams);
        $this->addComponent('feed', $feed);
    }

    /**
     *
     * @param string $feedType
     * @param int $feedId
     * @return NEWSFEED_CMP_Feed
     */
    protected function createFeed( $feedType, $feedId )
    {
        $driver = OW::getClassInstance("IISHASHTAG_CLASS_NewsfeedDriver");

        return OW::getClassInstance("NEWSFEED_MCMP_Feed", $driver, $feedType, $feedId);
    }
}