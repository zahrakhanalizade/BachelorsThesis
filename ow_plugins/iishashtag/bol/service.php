<?php
/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_BOL_Service
{
    private static $classInstance;

    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }
    private $hashtagTagDao;
    private $hashtagEntityDao;

    private function __construct()
    {
        $this->hashtagTagDao = IISHASHTAG_BOL_TagDao::getInstance();
        $this->hashtagEntityDao = IISHASHTAG_BOL_EntityDao::getInstance();
    }

    /***
     * @param $hashtag
     * @param $entityId
     * @param $entityType
     */
    public function add_hashtag($hashtag, $entityId, $entityType){
        $hashtag = UTIL_HtmlTag::stripTags($hashtag);
        $tags = $this->hashtagTagDao->getItemByTagText($hashtag);
        if(count($tags)>0) {
            $tag = $tags[0];
            $tag->count = $tag->count + 1;
            $this->hashtagTagDao->save($tag);
        }
        else{
            $tag = new IISHASHTAG_BOL_Tag();
            $tag->tag = $hashtag;
            $tag->count = 1;
            $this->hashtagTagDao->save($tag);
        }

        //add to entity
        $tagItem = $this->hashtagTagDao->getItemByTagText($hashtag)[0];
        if(!$this->hashtagEntityDao->itemExists($tagItem->id, $entityId, $entityType)) {
            $entity = new IISHASHTAG_BOL_Entity();
            $entity->tagId = $tagItem->id;
            $entity->entityId = $entityId;
            $entity->entityType = $entityType;
            $this->hashtagEntityDao->save($entity);
        }
    }

    /***
     * @param $idList
     */
    public function deleteEntitiesByListIds($idList)
    {
        $this->hashtagEntityDao->deleteByIdList($idList);
    }
    /**
     *
     * @param $entityType
     * @param $entityIds
     * @return NEWSFEED_BOL_Action
     */
    public function findActionsByEntityIds( $entityType, $entityIds )
    {
        $example = new OW_Example();
        $example->andFieldEqual('entityType', $entityType);
        $example->andFieldInArray('entityId', $entityIds);

        return NEWSFEED_BOL_ActionDao::getInstance()->findListByExample($example);
    }

    /***
     * @param $hashtag
     * @param $count
     * @return array
     */
    public function findTags($hashtag,$count){
        $items = $this->hashtagTagDao->findTagList($hashtag,$count);
        $result = array ();
        foreach($items as $key => $item){
            $result[] = array (
                'tag'=>$item->tag,
                'count'=>$item->count);
        }
        return $result;
    }

    /***
     * @param $tag
     * @param $entityType
     * @return array
     */
    public function findEntitiesByTag($tag, $entityType){
        $hashtag = UTIL_HtmlTag::stripTags($tag);
        $tags = $this->hashtagTagDao->getItemByTagText($hashtag);
        $result = array();
        if(count($tags)>0) {
            $items = $this->hashtagEntityDao->findEntityList($tags[0]->id, $entityType);
            foreach ($items as $key => $item) {
                $result[$item->id] = $item->entityId;
            }
        }
        return $result;
    }

    /***
     * @param $hashtag
     * @return array
     */
    public function findEntityCountByTag($hashtag){
        $hashtag = UTIL_HtmlTag::stripTags($hashtag);
        $tags = $this->hashtagTagDao->getItemByTagText($hashtag);
        if(count($tags)>0) {
            $tagId = $tags[0]->id;
            $q = "SELECT `entityType`,count(*) FROM `" . OW_DB_PREFIX . "iishashtag_entity` WHERE `tagId`="
                .$tagId." GROUP BY `entityType` ";
            $res = OW::getDbo()->queryForList($q);
            $res_array = array();
            foreach($res as $key=>$item){
                $res_array[$item["entityType"]]=$item["count(*)"];
            }
            return $res_array;
        }
        else{
            return array();
        }

    }


    /***
     * @param $tag
     * @return array
     */
    public function getContentMenu($tag)
    {
        $validLists = array('newsfeed');
        if(OW::getPluginManager()->isPluginActive('iisnews')) $validLists[] = 'news';
        if(OW::getPluginManager()->isPluginActive('groups')) $validLists[] = 'groups';
        if(OW::getPluginManager()->isPluginActive('event')) $validLists[] = 'event';
        if(OW::getPluginManager()->isPluginActive('video')) $validLists[] = 'video';
        if(OW::getPluginManager()->isPluginActive('photo')) $validLists[] = 'photo';

        //$classes = array('ow_ic_push_pin', 'ow_ic_clock', 'ow_ic_star', 'ow_ic_tag');

        $countArray = $this->findEntityCountByTag($tag);
        $countArray['newsfeed'] = array_key_exists('user-status', $countArray)?intval($countArray['user-status']):0;
        $countArray['news'] = array_key_exists('news-entry', $countArray)?intval($countArray['news-entry']):0;
        $countArray['groups'] = array_key_exists('groups', $countArray)?intval($countArray['groups']):0;
        $countArray['groups'] += array_key_exists('groups-status', $countArray)?intval($countArray['groups-status']):0;
        $countArray['video'] = array_key_exists('video_comments', $countArray)?intval($countArray['video_comments']):0;
        $countArray['photo'] = array_key_exists('photo_comments', $countArray)?intval($countArray['photo_comments']):0;
        $countArray['event'] = array_key_exists('event', $countArray)?intval($countArray['event']):0;

        $language = OW::getLanguage();
        $menuItems = array();
        $order = 0;
        $defaultTab = -1;
        foreach ( $validLists as $key => $type )
        {
            if($defaultTab == -1 && $countArray[$type]>0)
                $defaultTab = $type;
            $item = new BASE_MenuItem();
            $item->setLabel($language->text('iishashtag', 'at').' '.$language->text('iishashtag', 'menu_' . $type).' ('.$countArray[$type].')');
            $item->setUrl(OW::getRouter()->urlForRoute('iishashtag.tag.tab', array('tag'=>$tag, 'tab' => $type)));
            $item->setKey($type);
            //$item->setIconClass($classes[$order]);
            $item->setOrder($order);
            array_push($menuItems, $item);
            $order++;
        }
        if($defaultTab==-1)
            $defaultTab = "newsfeed";

        return array("menu"=>$menuItems, "default"=>$defaultTab);
    }

    /***
     * @return Form
     */
    public function getSearchForm(){
        $form = new Form("form");

        $textField = new TextField('txt');
        $textField->setLabel(OW::getLanguage()->text('iishashtag', 'tag'))
            ->setRequired(true);
        $textField->addAttribute('placeholder', OW::getLanguage()->text('iishashtag', 'tag'));
        $form->addElement($textField);

        $submit = new Submit('submit');
        $submit->setValue(OW::getLanguage()->text('iishashtag', 'search'));
        $form->addElement($submit);

        if ( OW::getRequest()->isPost() && $form->isValid($_POST) )
        {
            $data = $form->getValues();
            $tag = $data['txt'];
            OW::getApplication()->redirect(OW::getRouter()->urlForRoute('iishashtag.tag', array('tag' => $tag)));
        }
        return $form;
    }

    /***
     * @param $page
     * @param $tag
     */
    public function getPhotoList($page, $tag){
        $limit = OW::getConfig()->getValue('photo', 'photos_per_page');
        $first = ($page - 1) * $limit;
        $idList = $this->findEntitiesByTag($tag,"photo_comments");
        $photoObjects = array();
        if(is_array($idList) && sizeof($idList)>0){
            $photoObjects = PHOTO_BOL_PhotoDao::getInstance()->getPhotoList('latest', $first, $limit, null, false, $idList);

            $existingEntityIds = array();
            foreach($photoObjects as $item){
                $existingEntityIds[] = $item['id'];
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
        }
        $type = PHOTO_BOL_PhotoService::TYPE_PREVIEW;
        if ( $photoObjects )
        {
            if ( !in_array($type, PHOTO_BOL_PhotoService::getInstance()->getPhotoTypes()) )
            {
                $type = self::TYPE_PREVIEW;
            }

            foreach ( $photoObjects as $key => $photo )
            {
                $photoObjects[$key]['url'] = PHOTO_BOL_PhotoService::getInstance()->getPhotoUrlByPhotoInfo($photo['id'], $type, $photo['hash'], !empty($photo['dimension']) ? $photo['dimension'] : FALSE);
            }
        }

        $photoList = $this->generatePhotoList($photoObjects);
        if ( !OW_DEBUG_MODE )
        {
            ob_end_clean();
        }

        $event = new OW_Event('photo.onReadyResponse', $_POST, $photoList);
        OW::getEventManager()->trigger($event);
        $result = $event->getData();

        $document = OW::getDocument();

        $result['scripts'] = array(
            'beforeIncludes' => $document->getScriptBeforeIncludes(),
            'scriptFiles' => $document->getScripts(),
            'onloadScript' => $document->getOnloadScript(),
            'styleDeclarations' => $document->getStyleDeclarations(),
            'styleSheets' => $document->getStyleSheets()
        );

        header('Content-Type: application/json');
        exit(json_encode($result));
    }

    /***
     * @param $photos
     * @return array
     */
    public function generatePhotoList( $photos )
    {
        $userIds = $userUrlList = $albumIdList = $albumUrlList = $displayNameList = $albumNameList = $entityIdList = array();

        $unique = uniqid(time(), true);

        if ( $photos )
        {
            foreach ( $photos as $key => $photo )
            {
                $userIds[] = $photo['userId'];
                $albumIdList[] = $photo['albumId'];
                $entityIdList[] = $photo['id'];

                $photos[$key]['description'] = UTIL_HtmlTag::autoLink($photos[$key]['description']);
                $photos[$key]['unique'] = $unique;
            }

            $displayNameList = BOL_UserService::getInstance()->getDisplayNamesForList($userIds);

            foreach ( ($usernameList = BOL_UserService::getInstance()->getUserNamesForList($userIds)) as $id => $username )
            {
                $userUrlList[$id] = BOL_UserService::getInstance()->getUserUrlForUsername($username);
            }

            foreach ( ($albumNameList = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumNameListByIdList($albumIdList)) as $id => $album )
            {
                $albumUrlList[$id] = OW::getRouter()->urlForRoute('photo_user_album', array('user' => $usernameList[$album['userId']], 'album' => $id));
            }
        }

        return array('status' => 'success', 'data' => array(
            'photoList' => $photos,
            'displayNameList' => $displayNameList,
            'userUrlList' => $userUrlList,
            'albumNameList' => $albumNameList,
            'albumUrlList' => $albumUrlList,
            'rateInfo' => BOL_RateService::getInstance()->findRateInfoForEntityList('photo_rates', $entityIdList),
            'userScore' => BOL_RateService::getInstance()->findUserSocre(OW::getUser()->getId(), 'photo_rates', $entityIdList),
            'commentCount' => BOL_CommentService::getInstance()->findCommentCountForEntityList('photo_comments', $entityIdList),
            'unique' => $unique
        ));
    }




    /***
     * @param $content
     * @param $entityId
     * @param $entityType
     */
    private function findAndAddTagsFromContent($content, $entityId, $entityType){
        preg_match_all('/(#(\w{2,64}|([\x{0600}-\x{06FF}\x]{2,64})))/u', $content, $matches);
        if($matches[0]){
            foreach($matches[0] as $key=>$match){
                $match1 = substr($match, 1);
                $this->add_hashtag($match1, $entityId, $entityType);
            }
        }
    }

    /***
     * @param OW_Event $e
     */
    public function onAddComment( OW_Event $e )
    {
        $params = $e->getParams();
        $comment = BOL_CommentService::getInstance()->findComment($params['commentId']);
        $content = $comment->getMessage();
        $entityId = $params['entityId'];
        $entityType = $params['entityType'];

        if(isset($params['pluginKey']) && $params['pluginKey'] == 'groups') {
            if (isset($params['entityType']) && $params['entityType'] == 'groups-join') {
                $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction('groups-join', $entityId);
                if($action == null) {
                    $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction('user-status', $entityId);
                }

                if($action == null) {
                    $groupId = $this->findGroupIdByEntityId($params['entityId']);
                    if ($groupId == null) {
                        return;
                    } else {
                        $entityId = $groupId;
                    }
                }else{
                    $entityId = $this->findGroupIdByActionId($action->id, $params['entityId'], 'groups');
                    if($entityId == null){
                        return;
                    }
                }
            }
            $entityType = 'groups-status';
        }
        else if(isset($params['pluginKey']) && $params['pluginKey'] == 'newsfeed') {
            if (isset($params['entityType']) && $params['entityType'] == 'groups-status') {
                $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($params['entityType'], $entityId);
                if($action == null){
                    $groupId = $this->findGroupIdByEntityId($params['entityId']);
                    if($groupId == null){
                        return;
                    }else{
                        $entityId = $groupId;
                    }
                }else {
                    $entityId = $this->findGroupIdByActionId($action->id, $params['entityId'], 'groups');
                    if($entityId == null){
                        return;
                    }
                }
            }
        }

        $this->findAndAddTagsFromContent($content, $entityId, $entityType);
    }

    public function findGroupIdByActionId($actionId, $entityId, $type){
        $activityId = null;
        $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findByActionIds(array($actionId));
        foreach($activities as $activity){
            if($activity->activityType=='create'){
                $activityId = $activity->id;
            }
        }
        if($activityId!=null){
            $feedList = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids(array($activityId));
            $feedList = $feedList[$activityId];
            foreach ($feedList as $feed) {
                if ($feed->feedType == $type) {
                    return $feed->feedId;
                }
            }
        }else {
            $groupId = $this->findGroupIdByEntityId($entityId);
            if($groupId == null){
                return null;
            }else{
                return $groupId;
            }
        }

        return null;
    }

    /***
     * @param $entityId
     * @return null
     */
    public function findGroupIdByEntityId($entityId){
        if($entityId == null){
            return null;
        }
        $groupStatus = NEWSFEED_BOL_StatusDao::getInstance()->findById($entityId);
        if($groupStatus == null || $groupStatus->feedType != 'groups'){
            return null;
        }else if($groupStatus != null && $groupStatus->feedType == 'groups'){
            return $groupStatus->feedId;
        }
    }


    /***
     * @param OW_Event $e
     */
    public function onAddFeedAction( OW_Event $e )
    {
        $params = $e->getParams();
        $entityId = $params['entityId'];
        $entityType = $params['entityType'];
        if($params['pluginKey']=='iisnews') {
            $entry = EntryService::getInstance()->findById($entityId);
            $content = UTIL_HtmlTag::stripTags($entry->entry);
        }else if($entityType==GROUPS_BOL_Service::FEED_ENTITY_TYPE) {
            $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($entityType, $entityId);
            $jsonTmp = json_decode($action->data, true);
            $content = $jsonTmp["content"]["vars"]["description"];
            $content = UTIL_HtmlTag::stripTags($content);
            $entityType = "groups-status";
        }else if($entityType=='groups-status') {
            $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($entityType, $entityId);
            $jsonTmp = json_decode($action->data, true);
            $content = $jsonTmp["content"]["vars"]["status"];
            $content = UTIL_HtmlTag::stripTags($content);
            $entityId = $params['feedId'];
            $entityType = "groups-status";
        }else if($entityType=='event') {
            $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($entityType, $entityId);
            $jsonTmp = json_decode($action->data, true);
            $content = $jsonTmp["content"]["vars"]["description"];
            $content = UTIL_HtmlTag::stripTags($content);
        }else if($entityType=='video_comments') {
            $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($entityType, $entityId);
            $jsonTmp = json_decode($action->data, true);
            $content = $jsonTmp["content"]["vars"]["description"];
            $content = UTIL_HtmlTag::stripTags($content);
        }else if($entityType=='user-status') {
            $action = NEWSFEED_BOL_ActionDao::getInstance()->findAction($entityType, $entityId);
            $jsonTmp = json_decode($action->data, true);
            $content = $jsonTmp["content"]["vars"]["status"];
            $content = UTIL_HtmlTag::stripTags($content);
        }else{
            return;
        }

        $this->findAndAddTagsFromContent($content, $entityId, $entityType);
    }

    /***
     * @param $content
     * @return mixed
     */
    private function findAndReplaceTagsFromView($content){

        $replace1 = preg_replace_callback('/(#(\w{2,64}|([\x{0600}-\x{06FF}\x]{2,64})))(?=[^>]*(<|$))/u', function($matches) {
            $matches1 = substr($matches[1], 1);
            $url = OW::getRouter()->urlForRoute('iishashtag.tag', array('tag'=>$matches1));
            return '<a class="iishashtag_tag" href="'.$url.'">'.$matches[1].'</a>';
        },  $content);

        return $replace1;
    }

    /***
     * @param OW_Event $event
     * @return mixed
     */
    public function renderNewsfeed( OW_Event $event )
    {
        $data = ($event->getData());
        if($data['content']) {
            $data['content'] = $this->findAndReplaceTagsFromView($data['content']);
        }

        $event->setData($data);
        return $data;
    }

    /***
     * @param BASE_CLASS_EventProcessCommentItem $e
     */
    public function renderComments( BASE_CLASS_EventProcessCommentItem $e )
    {
        $content = $e->getDataProp('content');
        $content2 = $this->findAndReplaceTagsFromView($content);

        $e->setDataProp('content', $content2);
    }

    /***
     * @param OW_Event $event
     * @return mixed
     */
    public function renderString( OW_Event $event )
    {
        $data = ($event->getParams());
        if($data['string']) {
            $data['string'] = $this->correctHomeUrlVariable($data['string']);
            $data['string'] = $this->findAndReplaceTagsFromView($data['string']);
        }

        $event->setData($data);
        return $data;
    }

    public function correctHomeUrlVariable($string)
    {
        return preg_replace('/\$\$BASE_URL\$\$/', OW_URL_HOME, $string);
    }


    /***
     * @deprecated update per case
     */
    public function repopulate_tags_table()
    {
        //this is to repopulate in case of activating the plugin
        //should repopulate both tables
        // POSTPONED
        return;
        //clear table
        $example = new OW_Example();
        $example->andFieldNotEqual('id','-1');
        $this->hashtagTagDao->deleteByExample($example);

        //repopulate comments
        $commentDao = BOL_CommentDao::getInstance();
        $example = new OW_Example();
        $example->andFieldLike("message", '%#%');
        $all_comments = $commentDao->findListByExample($example);
        foreach ($all_comments as $key => $item) {
            $content = $item->message;
            preg_match_all('/(#(\w{2,64}|([\x{0600}-\x{06FF}\x]{2,64})))/u', $content, $matches);
            if($matches[0]){
                foreach($matches[0] as $key=>$match){
                    $match1 = substr($match, 1);
                    $this->add_hashtag($match1);
                }
            }
        }

        //repopulate newsfeed
        $statusDao = NEWSFEED_BOL_StatusDao::getInstance();
        $example = new OW_Example();
        $example->andFieldLike("status", '%#%');
        $statusDao->findListByExample($example);
        $all_status = $statusDao->findListByExample($example);
        foreach ($all_status as $key => $item) {
            $content = $item->status;
            preg_match_all('/(#(\w{2,64}|([\x{0600}-\x{06FF}\x]{2,64})))/u', $content, $matches);
            if($matches[0]){
                foreach($matches[0] as $key=>$match){
                    $match1 = substr($match, 1);
                    $this->add_hashtag($match1);
                }
            }
        }
    }

    /***
     * @deprecated
     * @param $tag
     * @param bool $feedType
     * @return array
     */
    public function findStatusByTag($tag, $feedType = false)
    {
        $statusDao = NEWSFEED_BOL_StatusDao::getInstance();
        $example = new OW_Example();
        $example->andFieldLike("status", '%#%');
        if($feedType!=false){
            $example->andFieldEqual("feedType", $feedType);
        }
        $statusDao->findListByExample($example);
        $all_status = $statusDao->findListByExample($example);

        $results = array();
        foreach ($all_status as $key => $item) {
            $content = $item->status;
            preg_match_all('/(#(\w{2,64}|([\x{0600}-\x{06FF}\x]{2,64})))/u', $content, $matches);
            if($matches[0]){
                foreach($matches[0] as $key=>$match){
                    $match1 = substr($match, 1);
                    if($match1==$tag) {
                        $results[] = $item;
                        break;
                    }
                }
            }
        }
        return $results;
    }

}
