<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_CTRL_Load extends OW_ActionController
{

    public function __construct()
    {
    }

    /***
     * @param $params
     * @throws Redirect404Exception
     */
    public function index($params){
        $service = IISHASHTAG_BOL_Service::getInstance();

        $tag = empty($params['tag'])? false:urldecode($params['tag']);
        $this->assign('tag',$tag);
        $contentMenu = new BASE_CMP_ContentMenu(null);
        $isEmptyList = false;

        //search form
        $this->addForm($service->getSearchForm());

        if(!$tag) {
            //no tag specified
            $page_title = OW::getLanguage()->text('iishashtag', 'list_page_title_default');
            $top_tags_tmp = $service->findTags("",50);
            $top_tags = array();
            $count_max = $top_tags_tmp[0]['count'];
            foreach($top_tags_tmp as $item){
                $size = 13 + intval(intval($item['count'])*16/$count_max);
                $top_tags[] = array(
                    'label' => $item['tag'],
                    'size' => $size,
                    'lineHeight' => $size+4,
                    'url' => OW::getRouter()->urlForRoute('iishashtag.tag', array('tag'=>$item['tag']))
                );
            }
            $this->assign('top_tags',$top_tags);
            $isEmptyList = (count($top_tags)==0);
            $this->assign('no_newsfeed',false);
        }else if(!OW::getPluginManager()->isPluginActive('newsfeed')) {
            $page_title = OW::getLanguage()->text('iishashtag', 'list_page_title_default');
            $this->assign('no_newsfeed',true);
        }else{
            $page_title = OW::getLanguage()->text('iishashtag', 'list_page_title') . " \"" .$tag. "\"";

            //menu
            $contentMenuArray = $service->getContentMenu($tag);
            $contentMenu = $contentMenuArray['menu'];
            $contentMenu = new BASE_CMP_ContentMenu($contentMenu);

            $selectedTab = empty($params['tab'])?$contentMenuArray['default']:$params['tab'];
            $selectedPage = (!empty($_GET['page']) && intval($_GET['page']) > 0 ) ? $_GET['page'] : 1;
            $contentMenu->getElement($selectedTab)->setActive(true);

            $this->assign('no_newsfeed',false);
            $this->assign('selected_tab',$selectedTab);


            if ($selectedTab == "newsfeed") {
                $entityIds = $service->findEntitiesByTag($tag, "user-status");
                if (count($entityIds) > 0) {
                    $this->addComponent('newsfeedComponent', new IISHASHTAG_CMP_Newsfeed($entityIds));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "news" && OW::getPluginManager()->isPluginActive('iisnews')) {
                $entityIds = $service->findEntitiesByTag($tag, "news-entry");
                if (count($entityIds) > 0) {
                    $this->addComponent('newsComponent', new IISHASHTAG_CMP_News($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "groups" && OW::getPluginManager()->isPluginActive('groups')) {
                $entityIds = $service->findEntitiesByTag($tag, "groups-status");
                if (count($entityIds) > 0) {
                    $this->addComponent('groupsComponent', new IISHASHTAG_CMP_Groups($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "event" && OW::getPluginManager()->isPluginActive('event')) {
                $entityIds = $service->findEntitiesByTag($tag, "event");
                if (count($entityIds) > 0) {
                    $this->addComponent('eventComponent', new IISHASHTAG_CMP_Event($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "video" && OW::getPluginManager()->isPluginActive('video')) {
                $entityIds = $service->findEntitiesByTag($tag, "video_comments");
                if (count($entityIds) > 0) {
                    $this->addComponent('videoComponent', new IISHASHTAG_CMP_Video($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "photo" && OW::getPluginManager()->isPluginActive('photo')) {
                $this->addComponent('photoComponent', new IISHASHTAG_CMP_Photo($tag));
            } else {
                throw new Redirect404Exception();
            }
        }

        $this->assign('isEmpty',$isEmptyList);
        $this->addComponent('menu', $contentMenu);
        OW::getNavigation()->activateMenuItem(OW_Navigation::MAIN, 'iishashtag', 'main_menu_item');

        $this->setPageHeading($page_title);
        $this->setPageTitle($page_title);
        $this->setPageHeadingIconClass('ow_ic_write');
    }

    /***
     * @param $params
     * @throws AuthenticateException
     */
    public function loadTags($params){
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }

        $tag = false;
        if(isset($params['tag']))
            $tag = urldecode($params['tag']);

        try {
            //sample
            $data[] = array('tag'=>'moradnejad', 'count'=>'4');

            //actual
            $max_count = OW::getConfig()->getValue('iishashtag','max_count');
            $data = IISHASHTAG_BOL_Service::getInstance()->findTags($tag,$max_count);

            exit(json_encode($data));
        }catch(Exception $e){
            exit(json_encode(array('status'=>'error','error_msg'=>OW::getLanguage()->text('base','comment_add_post_error'))));
        }
    }

    public function ajaxResponder($params)
    {
        $photoService = PHOTO_BOL_PhotoService::getInstance();
        if ($_POST['ajaxFunc'] == 'ajaxDeletePhoto') {
            $photoId = (int)$_POST['entityId'];
            $ownerId = $photoService->findPhotoOwner($photoId);
            $ownerMode = $ownerId !== null && $ownerId == OW::getUser()->getId();

            if($ownerId == null || $ownerMode) {
                $photoService->deletePhoto($photoId);
                exit( json_encode(array(
                    'result' => true,
                    'msg' => OW::getLanguage()->text('admin', 'theme_graphics_delete_success_message'),
                    'imageId' => $photoId
                )));
            }
            return;
        }

        $offset = 1;
        if(isset($_POST['offset'])){
            $offset = $_POST['offset'];
        }
        $tag = '';
        if(isset($params['tag'])){
            $tag = $params['tag'];
            $tag = urldecode($tag);
        }
        IISHASHTAG_BOL_Service::getInstance()->getPhotoList($offset, $tag);
    }
}

