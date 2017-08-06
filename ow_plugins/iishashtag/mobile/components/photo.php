<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_MCMP_Photo extends OW_MobileComponent
{

    public function __construct($tag)
    {
        parent::__construct();
        $service = IISHASHTAG_BOL_Service::getInstance();
        $idList = $service->findEntitiesByTag($tag,"photo_comments");

        //delete removed ids
        $existingEntityIds = array();
        foreach($idList as $item){
            $existingEntityIds[] = $item;
        }
        if(count($idList)>count($existingEntityIds)){
            $newsfeedService = NEWSFEED_BOL_Service::getInstance();
            $deletedEntityIds = array();
            foreach($idList as $key=>$id){
                if(!in_array($id, $existingEntityIds)){
                    if( $newsfeedService->findAction("photo_comments", $id) === null ) {
                        $deletedEntityIds[] = $key;
                    }
                }
            }
            IISHASHTAG_BOL_Service::getInstance()->deleteEntitiesByListIds($deletedEntityIds);
        }

        //paging
        $count = 12;
        $this->assign('isEmpty', count($existingEntityIds)==0);

        $initialCmp = new IISHASHTAG_MCMP_PhotoList($tag, $count, array());
        $this->addComponent('photos', $initialCmp);

        $this->assign('loadMore', count($idList) > $count);

        $script = '
        OWM.bind("photo.hide_load_more", function(){
            $("#btn-photo-load-more").hide();
        });

        $("#btn-photo-load-more").click(function(){
            var node = $(this);
            node.addClass("owm_preloader");
            var exclude = $("div.owm_photo_list_item").map(function(){ return $(this).data("ref"); }).get();
            OWM.loadComponent(
                "IISHASHTAG_MCMP_PhotoList",
                {tag: "' . $tag . '", count:' . $count . ', exclude: exclude},
                {
                    onReady: function(html){
                        $("#photo-list-cont").append(html);
                        node.removeClass("owm_preloader");
                    }
                }
            );
        });';

        OW::getDocument()->addOnloadScript($script);
    }
}