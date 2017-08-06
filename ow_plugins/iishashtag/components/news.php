<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_CMP_News extends OW_Component
{

    public function __construct( array $idList , $page = 1)
    {
        parent::__construct();
        $entryService = EntryService::getInstance();
        $entries = $entryService->findEntryListByIds($idList);

        //delete removed ids
        $existingEntityIds = array();
        foreach($entries as $key=>$dto){
            $existingEntityIds[] = $dto->id;
        }
        if(count($idList)>count($existingEntityIds)){
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)){
                    $deletedEntityIds[] = $key;
                }
            }
            IISHASHTAG_BOL_Service::getInstance()->deleteEntitiesByListIds($deletedEntityIds);
        }

        //paging
        $rpp = (int) OW::getConfig()->getValue('iisnews', 'results_per_page');
        $itemsCount = count($existingEntityIds);
        if($page>0 && $page<=ceil($itemsCount / $rpp)) {
            $paging = new BASE_CMP_Paging($page, ceil($itemsCount / $rpp), 5);
            $this->addComponent('paging', $paging);
            $first = $itemsCount - (($page - 1) * $rpp) - $rpp;
            $count = $rpp;
            if($first<0){
                $count = $count + $first;
                $first = 0;
            }
            $entries = array_slice($entries, $first, $count);
        }else{
            $entries = array();
        }


        //get list
        $list = array ();
        foreach($entries as $key=>$dto){
            if ($dto->isDraft())
                continue;
            $info[$dto->id]['dto'] = $dto;

            $list[] = array(
                'dto' => $dto,
                //'commentCount' => $info[$dto->id] ['commentCount'],
            );
        }

        $entries = array();
        $authorIdList = array();
        foreach ( $list as $item )
        {
            $dto = $item['dto'];
            $stringRenderer = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ON_AFTER_NEWSFEED_STATUS_STRING_READ,array('string' => $dto->getEntry())));
            if(isset($stringRenderer->getData()['string'])){
                $dto->setEntry($stringRenderer->getData()['string']);
            }
            $dto->setEntry($dto->getEntry());
            $dto->setTitle( UTIL_String::truncate( strip_tags($dto->getTitle()), 150, '...' )  );

            $text = $dto->getEntry();
            if(strlen($text)>250){
                $text = UTIL_String::truncate( strip_tags($dto->getEntry()), 250, '...' );
                $showMore = true;
            }
            else {
                $text = explode("<!--more-->", $dto->getEntry());
                $isPreview = count($text) > 1;
                if (!$isPreview) {
                    $text = explode('<!--page-->', $text[0]);
                    $showMore = count($text) > 1;
                } else {
                    $showMore = true;
                }
                $text = $text[0];
            }

            $new_entry = array(
                'dto' => $dto,
                'text' => $text,
                'showMore' => $showMore,
                'url' => OW::getRouter()->urlForRoute('user-entry', array('id'=>$dto->getId())),
                'toolbar' => array(
                    array(
                        'class' => 'ow_ipc_date',
                        'label' => UTIL_DateTime::formatDate($item['dto']->timestamp)
                    ),
                )
            );
            array_unshift($entries, $new_entry);
            $authorIdList[] = $dto->authorId;
        }
        if ( !empty($entries) ) {
            $avatars = BOL_AvatarService::getInstance()->getDataForUserAvatars($authorIdList, true, false);
            $this->assign('avatars', $avatars);
        }
        $this->assign('list', $entries);
    }
}