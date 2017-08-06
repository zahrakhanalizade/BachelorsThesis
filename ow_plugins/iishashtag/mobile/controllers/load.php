<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_MCTRL_Load extends OW_MobileActionController
{
    /***
     * @param $params
     * @throws Redirect404Exception
     */
    public function index($params){
        OW::getNavigation()->activateMenuItem(OW_Navigation::MAIN, 'iishashtag', 'mobile_main_menu_item');
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
                $size = 12 + intval(intval($item['count'])*8/$count_max);
                $top_tags[] = array(
                    'label' => $item['tag'],
                    'size' => $size,
                    'lineHeight' => $size+5,
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
            $contentMenu = new BASE_MCMP_ContentMenu($contentMenu);

            $selectedTab = empty($params['tab'])?$contentMenuArray['default']:$params['tab'];
            $selectedPage = (!empty($_GET['page']) && intval($_GET['page']) > 0 ) ? $_GET['page'] : 1;
            $contentMenu->getElement($selectedTab)->setActive(true);

            $this->assign('no_newsfeed',false);
            $this->assign('selected_tab',$selectedTab);


            if ($selectedTab == "newsfeed") {
                $entityIds = $service->findEntitiesByTag($tag, "user-status");
                if (count($entityIds) > 0) {
                    $this->addComponent('newsfeedComponent', new IISHASHTAG_MCMP_Newsfeed($entityIds));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "news" && OW::getPluginManager()->isPluginActive('iisnews')) {
                $entityIds = $service->findEntitiesByTag($tag, "news-entry");
                if (count($entityIds) > 0) {
                    $this->addComponent('newsComponent', new IISHASHTAG_MCMP_News($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "groups" && OW::getPluginManager()->isPluginActive('groups')) {
                $entityIds = $service->findEntitiesByTag($tag, "groups-status");
                if (count($entityIds) > 0) {
                    $this->addComponent('groupsComponent', new IISHASHTAG_MCMP_Groups($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "event" && OW::getPluginManager()->isPluginActive('event')) {
                $entityIds = $service->findEntitiesByTag($tag, "event");
                if (count($entityIds) > 0) {
                    $this->addComponent('eventComponent', new IISHASHTAG_MCMP_Event($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "video" && OW::getPluginManager()->isPluginActive('video')) {
                $entityIds = $service->findEntitiesByTag($tag, "video_comments");
                if (count($entityIds) > 0) {
                    $this->addComponent('videoComponent', new IISHASHTAG_MCMP_Video($entityIds, $selectedPage));
                }
                $isEmptyList = (count($entityIds) == 0);
            } else if ($selectedTab == "photo" && OW::getPluginManager()->isPluginActive('photo')) {
                $this->addComponent('photoComponent', new IISHASHTAG_MCMP_Photo($tag));
            } else {
                throw new Redirect404Exception();
            }
        }

        $this->assign('isEmpty',$isEmptyList);
        $this->addComponent('menu', $contentMenu);
        OW::getNavigation()->activateMenuItem(OW_Navigation::MAIN, 'iishashtag', 'mobile_main_menu_item');

        $this->setPageHeading($page_title);
        $this->setPageTitle($page_title);
        $this->setPageHeadingIconClass('ow_ic_write');
    }
}