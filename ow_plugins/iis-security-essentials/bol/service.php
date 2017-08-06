<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 *
 *
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iissecurityessentials.bol
 * @since 1.0
 */
class IISSECURITYESSENTIALS_BOL_Service
{
    const ON_AFTER_READ_URL_EMBED= 'iissecurityessentials.on.after.read.url.embed';
    const ON_CHECK_URL_EMBED= 'iissecurityessentials.on.check.url.embed';
    const TEST_HOME_PAGE_ACTIVITY= 'iissecurityessentials.test.home.page.activity';
    const ON_CHECK_OBJECT_BEFORE_SAVE_OR_UPDATE= 'iissecurityessentials.on.check.object.before.save.or.update';
    private static $classInstance;
    public static $PRIVACY_EVERYBODY = 'everybody';
    public static $PRIVACY_ONLY_FOR_ME = 'only_for_me';
    public static $PRIVACY_FRIENDS_ONLY = 'friends_only';

    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private $questionPrivacy;

    private function __construct()
    {
        $this->questionPrivacy = IISSECURITYESSENTIALS_BOL_QuestionPrivacyDao::getInstance();
    }

    /***
     * @param $userId
     * @param $questionId
     * @return mixed
     */
    public function getQuestionPrivacy($userId, $questionId){
        return $this->questionPrivacy->getQuestionPrivacy($userId, $questionId);
    }

    /***
     * @param $questionId
     * @param $privacy
     * @return IISSECURITYESSENTIALS_BOL_QuestionPrivacy
     */
    public function setQuestionPrivacy($questionId, $privacy){
        $userId = OW::getUser()->getId();
        return $this->questionPrivacy->setQuestionPrivacy($userId, $questionId,$privacy);
    }

    /***
     * @param $userIds
     * @param $privacy
     * @param $questionId
     * @return array
     */
    public function getQuestionsPrivacyByExceptPrivacy($userIds, $privacy, $questionId){
        return $this->questionPrivacy->getQuestionsPrivacyByExceptPrivacy($userIds, $privacy, $questionId);
    }

    public function getSections($currentSection = null){
        if($currentSection==null){
            $currentSection = 1;
        }

        $sectionsInformation = array();

        for ($i = 1; $i <= 3; $i++) {
            $sections[] = array(
                'sectionId' => $i,
                'active' => $currentSection == $i ? true : false,
                'url' => OW::getRouter()->urlForRoute('iissecurityessentials.admin.currentSection', array('currentSection' => $i)),
                'label' => $this->getPageHeaderLabel($i)
            );
        }

        $sectionsInformation['sections'] = $sections;
        $sectionsInformation['currentSection'] = $currentSection;
        return $sectionsInformation;
    }

    public function getPageHeaderLabel($sectionId)
    {
        if ($sectionId == 1) {
            return OW::getLanguage()->text('iissecurityessentials', 'general_setting');
        } else if ($sectionId == 2) {
            return OW::getLanguage()->text('iissecurityessentials', 'privacy_setting');
        }else if ($sectionId ==3) {
            return OW::getLanguage()->text('iissecurityessentials', 'newsfeed_homepage_setting');
        }
    }


    public function onBeforeUsersInformationRender(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['userIdList']) && isset($params['questionList'])){
            $questionList = $params['questionList'];
            $userIdList = $params['userIdList'];
            $notGrantUsersWithPublicSexType = array();
            $qSex = BOL_QuestionService::getInstance()->findQuestionByName('sex');
            $usersWithoutPublicSexType = IISSECURITYESSENTIALS_BOL_Service::getInstance()->getQuestionsPrivacyByExceptPrivacy($userIdList, self::$PRIVACY_EVERYBODY, $qSex->id);
            foreach($usersWithoutPublicSexType as $userWithoutPublicSexType){
                $notGrantUsersWithPublicSexType[] = $userWithoutPublicSexType->userId;
            }

            $notGrantUsersWithPublicBirthdateType = array();
            $qBdate = BOL_QuestionService::getInstance()->findQuestionByName('birthdate');
            $usersWithoutPublicBirthdateType = IISSECURITYESSENTIALS_BOL_Service::getInstance()->getQuestionsPrivacyByExceptPrivacy($userIdList, self::$PRIVACY_EVERYBODY, $qBdate->id);
            foreach($usersWithoutPublicBirthdateType as $userWithoutPublicBirthdateType){
                $notGrantUsersWithPublicBirthdateType[] = $userWithoutPublicBirthdateType->userId;
            }

            $newQuestionList = array();
            foreach ( $questionList as $uid => $question )
            {
                if(in_array($uid, $notGrantUsersWithPublicSexType)){
                    unset($question['sex']);
                }

                if(in_array($uid, $notGrantUsersWithPublicBirthdateType)){
                    unset($question['birthdate']);
                }

                $newQuestionList[$uid] = $question;
            }
            $event->setData(array('questionList' => $newQuestionList));
        }
    }


    public function onBeforePrivacyItemAdd(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['key'])){
            $value = $this->getAdminDefaultValueOfPrivacy($params['key']);
            if($value!=null){
                $event->setData(array('value' => $value));
            }
        }
    }

    public function onBeforeEmailVerifyFormRender( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['this'])){
            if(isset($params['page']) && $params['page']=='verifyForm'){
                $params['this']->assign('verifyLater', '</br><p class="ow_center"><a class="ow_lbutton" href="' . OW::getRouter()->urlForRoute('base_email_verify') . '">' . OW::getLanguage()->text('iissecurityessentials', 'verify_using_resend_email') . '</a></p>');
            }else {
                $params['this']->assign('verifyLater', '</br><p class="ow_center"><a class="ow_lbutton" href="' . OW::getRouter()->urlForRoute('base_email_verify_code_form') . '">' . OW::getLanguage()->text('iissecurityessentials', 'verify_using_code') . '</a></p></br><p class="ow_center"><a class="ow_lbutton" href="' . OW::getRouter()->urlForRoute('base_sign_out') . '">' . OW::getLanguage()->text('iissecurityessentials', 'verify_later') . '</a></p>');
            }
        }
    }

    public function onBeforeQuestionsDataProfileRender( OW_Event $event )
    {
        $params = $event->getParams();
        $ownerId = $params['userId'];
        $questions = $params['questions'];
        if(isset($params['questions']) && isset($params['userId']) && isset($params['component'])){
            $service = IISSECURITYESSENTIALS_BOL_Service::getInstance();
            $questionsPrivacyButton = array();
            $questionsPrivacyIgnoreList = array();
            $actionType = 'questionsPrivacy';
            $change_privacy_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label');
            foreach($questions as $question){
                $privacy = $service->getQuestionPrivacy($ownerId, $question['id']);
                if($privacy == null){
                    $privacy = self::$PRIVACY_EVERYBODY;
                }

                $privacyButton = array('label' => $this->getPrivacyLabelByFeedId($privacy, $ownerId),
                    'imgSrc' => OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticUrl() . 'images/' . $privacy . '.png');
                if ($ownerId == OW::getUser()->getId()) {
                    $privacyButton['onClick'] = 'javascript:showAjaxFloatBoxForChangePrivacy(\'' . $question['id'] . '\', \'' . $change_privacy_label . '\',\''. $actionType .'\',\''.$ownerId.'\')';
                    $privacyButton['id'] = 'sec-' . $question['id'] . '-' . $ownerId;
                }

                if(!$this->checkPrivacyOfObject($privacy, $ownerId, null, false)){
                    $questionsPrivacyIgnoreList[$question['id']] = false;
                }else if(OW::getUser()->isAuthenticated() && $ownerId==OW::getUser()->getId()){
                    $questionsPrivacyButton[$question['id']] = $privacyButton;
                }
            }
            $params['component']->assign('questionsPrivacyIgnoreList',$questionsPrivacyIgnoreList);
            $params['component']->assign('questionsPrivacyButton',$questionsPrivacyButton);
            $params['component']->assign('isOwner',OW::getUser()->isAuthenticated() && $ownerId==OW::getUser()->getId());

        }
    }


    public function onBeforeAlbumCreateForStatusUpdate( OW_Event $event )
    {
        $params = $event->getParams();
        if (isset($params['albumName'])) {
            $count = 0;
            while($count<20){
                $randomName = $params['albumName'] . ' ' . rand(0,9999999999);
                $albumName = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumByName($randomName, OW::getUser()->getId());
                if($albumName==null){
                    $event->setData(array('albumName' => $randomName));
                    break;
                }
                $count++;
            }
        }
    }

    public function onAfterLastPhotoRemoved( OW_Event $event )
    {
        $params = $event->getParams();
        if (isset($params['photoIdList']) && isset($params['fromAlbumLastPhoto'])) {
            if(in_array($params['fromAlbumLastPhoto']->id, $params['photoIdList'])){
                $fromAlbumLastPhoto = PHOTO_BOL_PhotoDao::getInstance()->getLastPhoto($params['fromAlbumLastPhoto']->albumId, $params['photoIdList']);
                $event->setData(array('fromAlbumLastPhoto' => $fromAlbumLastPhoto));
            }
        }
    }

    public function onBeforePhotoInit(OW_Event $event){
        $params = $event->getParams();
        $error = false;
        if(isset($params['username']) && isset($params['action']) && $params['action'] == 'userPhotos'){
            $user = BOL_UserService::getInstance()->findByUsername($params['username']);
            if($user!=null){
                $eventParams = array(
                    'action' => 'photo_view_album',
                    'ownerId' => $user->getId()
                );
                $privacy = OW::getEventManager()->getInstance()->call('plugin.privacy.get_privacy', $eventParams);
                if(!OW::getUser()->isAuthenticated() && $privacy != self::$PRIVACY_EVERYBODY){
                    $this->throwPrivacyExecption($user->getUsername(), $user->getId(), $privacy);
                }
            }
        }else if(isset($params['photoId']) && isset($params['ownerId'])){
            $user = BOL_UserService::getInstance()->findUserById($params['ownerId']);
            $photo = PHOTO_BOL_PhotoService::getInstance()->findPhotoById($params['photoId']);
            if($user!=null && $photo!=null){
                $eventParams = array(
                    'action' => 'photo_view_album',
                    'ownerId' => $user->getId()
                );
                $photoPrivacy = $photo->privacy;
                $modulePrivacy = OW::getEventManager()->getInstance()->call('plugin.privacy.get_privacy', $eventParams);
                if(!OW::getUser()->isAuthenticated() && ($modulePrivacy != self::$PRIVACY_EVERYBODY || $photoPrivacy != self::$PRIVACY_EVERYBODY)){
                    $error = true;
                }else if(OW::getUser()->isAuthenticated() && ($modulePrivacy == self::$PRIVACY_FRIENDS_ONLY || $photoPrivacy == self::$PRIVACY_FRIENDS_ONLY)){
                    $userFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $user->getId()));
                    if(false !== array_search(OW::getUser()->getId(), $userFriendsId)){
                        $error = false;
                    }else if(!OW::getUser()->isAuthenticated() || OW::getUser()->getId() != $user->getId()){
                        $error = true;
                    }
                }else if(OW::getUser()->isAuthenticated() && ($modulePrivacy == self::$PRIVACY_ONLY_FOR_ME || $photoPrivacy == self::$PRIVACY_ONLY_FOR_ME) && OW::getUser()->getId() != $user->getId()){
                    $error = true;
                }

                if($error){
                    $this->throwPrivacyExecption($user->getUsername(), $user->getId(), $modulePrivacy);
                }
            }
        }else if(isset($params['albumId']) && isset($params['action']) && $params['action']=='check_album_privacy'){
            $album = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumById($params['albumId']);
            $owner = BOL_UserService::getInstance()->findUserById($album->userId);
            if($owner!=null && $album!=null){
                $eventParams = array(
                    'action' => 'photo_view_album',
                    'ownerId' => $owner->getId()
                );
                $photoPrivacy = $this->getPrivacyOfAlbum($album->getId());
                $modulePrivacy = OW::getEventManager()->getInstance()->call('plugin.privacy.get_privacy', $eventParams);
                if(!OW::getUser()->isAuthenticated() && ($modulePrivacy != self::$PRIVACY_EVERYBODY || $photoPrivacy != self::$PRIVACY_EVERYBODY)){
                    $error = true;
                }else if(OW::getUser()->isAuthenticated() && ($modulePrivacy == self::$PRIVACY_ONLY_FOR_ME || $photoPrivacy == self::$PRIVACY_ONLY_FOR_ME) && OW::getUser()->getId() != $owner->getId()){
                    $error = true;
                }else if(OW::getUser()->isAuthenticated() && ($modulePrivacy == self::$PRIVACY_FRIENDS_ONLY || $photoPrivacy == self::$PRIVACY_FRIENDS_ONLY)){
                    $userFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $owner->getId()));
                    if(false !== array_search(OW::getUser()->getId(), $userFriendsId)){
                        $error = false;
                    }else if(!OW::getUser()->isAuthenticated() || OW::getUser()->getId() != $album->userId){
                        $error = true;
                    }
                }

                if($error){
                    $this->throwPrivacyExecption($owner->getUsername(), $owner->getId(), $modulePrivacy);
                }
            }
        }
    }

    public function throwPrivacyExecption($username, $userId, $privacy){
        $exception = new RedirectException(OW::getRouter()->urlForRoute('privacy_no_permission', array('username' => $username)));
        $langParams = array(
            'username' => $username,
            'display_name' => BOL_UserService::getInstance()->getDisplayName($userId)
        );
        $error['message'] = OW::getLanguage()->getInstance()->text('privacy', 'privacy_no_permission_message', $langParams);
        $error['privacy'] = $privacy;
        OW::getSession()->set('privacyRedirectExceptionMessage', $error['message']);
        $exception->setData($error);
        throw $exception;
    }

    public function eventAfterPhotoMove( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['toAlbum']) && isset($params['fromAlbum']) && isset($params['photoIdList'])){
            $privacyOfToAlbum = $this->getPrivacyOfAlbum($params['toAlbum'], $params['photoIdList']);
            $privacyOfFromAlbum = $this->getPrivacyOfAlbum($params['fromAlbum']);
            foreach($params['photoIdList'] as $photoId) {
                $photo = PHOTO_BOL_PhotoService::getInstance()->findPhotoById($photoId);
                if($privacyOfToAlbum==null){
                    if(isset($_REQUEST['statusPrivacy'])){
                        $privacyOfToAlbum = $this->validatePrivacy($_REQUEST['statusPrivacy']);
                    }else{
                        $privacyOfToAlbum = $photo->privacy;
                    }
                }
                $this->updatePrivacyOfPhoto($photo->id, $privacyOfToAlbum);
            }

            $actionIds = $this->findActionOfDependenciesPhoto($params['toAlbum']);
            $this->updateNewsFeedActivitiesByActionIds($actionIds, $privacyOfToAlbum);

            if($privacyOfFromAlbum!=null){
                $actionIds = $this->findActionOfDependenciesPhoto($params['fromAlbum']);
                $this->updateNewsFeedActivitiesByActionIds($actionIds, $privacyOfFromAlbum);
            }
        }
    }

    public function findActionOfDependenciesPhoto($albumId){
        $actionIds = array();

        $count = PHOTO_BOL_PhotoService::getInstance()->countAlbumPhotos($albumId, array());
        $photosOfAlbum = PHOTO_BOL_PhotoService::getInstance()->findPhotoListByAlbumId($albumId,1,$count);
        foreach($photosOfAlbum as $photoItem){
            $action = NEWSFEED_BOL_Service::getInstance()->findAction('multiple_photo_upload', $photoItem['uploadKey']);
            if($action!=null) {
                $actionIds[] = $action->id;
            }

            $action = NEWSFEED_BOL_Service::getInstance()->findAction('multiple_photo_upload', $photoItem['id']);
            if($action!=null) {
                $actionIds[] = $action->id;
            }

            $action = NEWSFEED_BOL_Service::getInstance()->findAction('photo_comments', $photoItem['uploadKey']);
            if($action!=null) {
                $actionIds[] = $action->id;
            }

            $action = NEWSFEED_BOL_Service::getInstance()->findAction('photo_comments', $photoItem['id']);
            if($action!=null){
                $actionIds[] = $action->id;
                return $actionIds;
            }

        }
        return $actionIds;
    }

    public function check_permission( BASE_CLASS_EventCollector $event )
    {
        $params = $event->getParams();
        if(isset($params['action']) && $params['action']=='view_my_feed'){
            $privacies = array(self::$PRIVACY_EVERYBODY, self::$PRIVACY_FRIENDS_ONLY, self::$PRIVACY_ONLY_FOR_ME, null);
            foreach($privacies as $privacy){
                $data = array($privacy => array('blocked' => false));
                $event->add($data);
            }

        }
    }

    public function onBeforeFeedActivity( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['activityType'])){
            $activityType = $params['activityType'];
            if(in_array($activityType,array('like','comment'))){
                $event->setData(array('createFeed' => false));
            }else{
                if(isset($params['actionId'])){
                    $action = NEWSFEED_BOL_Service::getInstance()->findActionById($params['actionId']);
                    if($action!=null && $action->entityType=='friend_add'){
                        $event->setData(array('createFeed' => false));
                    }
                }
            }
        }

    }

    public function getActionPrivacy( OW_Event $event )
    {
        $params = $event->getParams();

        if (isset($params['ownerId']) && isset($params['action']) && isset($_REQUEST['statusPrivacy']) && ($params['action']=='photo_view_album' || $params['action']=='video_view_video'))
        {
            if(isset($_REQUEST['album-name']) && isset($_REQUEST['album']) && $_REQUEST['album-name']==$_REQUEST['album']){
                $album = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumByName($_REQUEST['album-name'],$params['ownerId']);
                $privacy= $this->getPrivacyOfAlbum($album->id);
                if($privacy!=null){
                    $event->setData(array('privacy' => $privacy));
                }else{
                    $event->setData(array('privacy' => $this->validatePrivacy($_REQUEST['statusPrivacy'])));
                }
            }else{
                $event->setData(array('privacy' => $this->validatePrivacy($_REQUEST['statusPrivacy'])));
            }
        }
    }

    public function onBeforeVideoUploadFormRenderer(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form'])){
            $form = $params['form'];
            $form->addElement($this->createStatusPrivacyElement('video_default_privacy', $params));
        }
    }

    public function onBeforeVideoUploadComponentRenderer(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form']) && isset($params['component'])){
            $form = $params['form'];
            if($form->getElement('statusPrivacy')!=null){
                $params['component']->assign('statusPrivacyField',true);
            }
        }
    }

    public function getActionValueOfPrivacy($privacyKey, $userId){
        if(OW::getUser()->isAuthenticated() && class_exists('PRIVACY_BOL_ActionService')) {
            $userPrivacy = PRIVACY_BOL_ActionService::getInstance()->getActionValue($privacyKey, $userId);
            if($userPrivacy!=null){
                return $userPrivacy;
            }
        }
        $adminValue = OW::getConfig()->getValue('iissecurityessentials', $privacyKey);
        if($adminValue!=null){
            return $adminValue;
        }
        return self::$PRIVACY_FRIENDS_ONLY;
    }


    public function onBeforePhotoUploadFormRenderer(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form'])){
            $form = $params['form'];
            $form->addElement($this->createStatusPrivacyElement('photo_default_privacy', $params));
            if(isset($params['this'])){
                $params['this']->assign('statusPrivacy',true);
            }
        }
    }

    public function onBeforeCreateFormUsingFieldPrivacy(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['privacyKey'])){
            $event->setData(array('privacyElement' => $this->createStatusPrivacyElement($params['privacyKey'])));
        }
    }

    public function onBeforeContentListQueryExecute(OW_Event $event){
        $params = $event->getParams();
        $privacyKey = '';
        $pluginKey = '';
        $whereCondition = '';
        if(isset($params['entityType']) || isset($params['objectTableName'])) {
            if ((isset($params['entityType']) && ($params['entityType'] == 'video_rates' || $params['entityType'] == 'video')) || (isset($params['objectType']) && $params['objectType'] == 'video')) {
                $privacyKey = 'video_view_video';
                $pluginKey = 'video';
            } else if ((isset($params['entityType']) && ($params['entityType'] == 'photo_rates' || $params['entityType'] == 'photo_comments')) || (isset($params['objectType']) && $params['objectType'] == 'photo')) {
                $privacyKey = 'photo_view_album';
                $pluginKey = 'photo';
            }else if (isset($params['objectType']) && $params['objectType'] == 'question') {
                $privacyKey = 'view_my_questions';
                $pluginKey = 'questions';
            }

            if(isset($params['objectTableName']) && class_exists('PRIVACY_BOL_ActionDataDao') && isset($params['listType']) && in_array($params['listType'], array('latest', 'featured'))){
                if(!isset($params['privacyTableNameExist']) || $params['privacyTableNameExist']) {
                    if (isset($params['privacyTableName'])) {
                        $justFriends = false;
                        if(isset($params['just_friends']) && $params['just_friends']){
                            $justFriends = true;
                        }
                        $whereCondition = $this->buildUserPrivacyConditionQuery($params['objectTableName'], $params['privacyTableName'], $privacyKey, $justFriends);
                    } else {
                        $whereCondition = $this->buildUserPrivacyConditionQuery($params['objectTableName'], $params['objectTableName'], $privacyKey);
                    }
                }else if(isset($params['privacyTableNameExist']) && !$params['privacyTableNameExist'] && $params['object_list'] == 'album'){
                    $whereCondition = $this->buildUserAlbumPrivacyConditionQuery($params['objectTableName'], $privacyKey);
                }
                $event->setData(array('where' => $whereCondition, 'params' => array('pluginKey' => $pluginKey, 'privacyKey' => $privacyKey)));
            }else if(isset($params['commentEntityTableName']) &&
                class_exists('BOL_CommentEntityDao') &&
                class_exists('PRIVACY_BOL_ActionDataDao') &&
                $params['entityType'] == 'photo_comments' &&
                isset($params['listType']) &&
                in_array($params['listType'], array('commentDao')))
            {
                //put privacy condition in most discussed photo
                $whereCondition = $this->buildQueryForPhotoWithEntityIdPrivacyCondition($params['commentEntityTableName']);
                $privacyCondition = $this->buildUserPrivacyConditionQuery('album', 'pho', $privacyKey);
                $whereCondition .= $privacyCondition;
                $whereCondition .= ') >0';
                $event->setData(array('where' => $whereCondition, 'params' => array('pluginKey' => $pluginKey, 'privacyKey' => $privacyKey)));
            }else if(isset($params['tagEntityTableName']) && $params['entityType'] == 'video' && class_exists('PRIVACY_BOL_ActionDataDao')){
                //put privacy condition in video tag search
                $whereCondition = $this->buildQueryForVideoWithEntityIdPrivacyCondition($params['tagEntityTableName']);
                $whereCondition .= $this->buildUserPrivacyConditionQuery('video', 'video', $privacyKey);
                $whereCondition .= ') >0';
                $event->setData(array('where' => $whereCondition, 'params' => array('pluginKey' => $pluginKey, 'privacyKey' => $privacyKey)));
            }else if(isset($params['rateTableName']) && class_exists('BOL_RateDao') && class_exists('PRIVACY_BOL_ActionDataDao') && isset($params['listType']) && in_array($params['listType'], array('rateDao'))){
                if($params['entityType'] == 'photo_rates') {
                    //put privacy condition in top rated photo
                    $whereCondition = $this->buildQueryForPhotoWithEntityIdPrivacyCondition($params['rateTableName']);
                    $whereCondition .= $this->buildUserPrivacyConditionQuery('album', 'pho', $privacyKey);
                    $whereCondition .= ') >0';
                }else if($params['entityType'] == 'video_rates') {
                    //put privacy condition in top rated video
                    $whereCondition = $this->buildQueryForVideoWithEntityIdPrivacyCondition($params['rateTableName']);
                    $whereCondition .= $this->buildUserPrivacyConditionQuery('video', 'video', $privacyKey);
                    $whereCondition .= ') >0';
                }
                if($whereCondition!='') {
                    $event->setData(array('where' => $whereCondition, 'params' => array('pluginKey' => $pluginKey, 'privacyKey' => $privacyKey)));
                }
            }
        }else if(isset($params['example']) && isset($params['ownerId']) && isset($params['objectType']) && $params['objectType'] == 'video'){
            $example = $params['example'];
            $ownerId = $params['ownerId'];
            if(!OW::getUser()->isAuthenticated()){
                $example->andFieldInArray('privacy', array('everybody'));
            }else if(OW::getUser()->getId() != $ownerId){
                $userFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $ownerId));
                if(false !== array_search(OW::getUser()->getId(), $userFriendsId)){
                    $example->andFieldInArray('privacy', array('everybody', 'friends_only'));
                }else{
                    $example->andFieldInArray('privacy', array('everybody'));
                }
            }
            $event->setData(array('example' => $example));
        }else{
            return;
        }
    }

    /***
     * build query for privacy condition of comment and tag photo list
     * @param $tableName
     * @return string
     */
    public function buildQueryForPhotoWithEntityIdPrivacyCondition($tableName){
        $whereCondition = ' and (select count(*) from ' . PHOTO_BOL_PhotoAlbumDao::getInstance()->getTableName() . ' as album, ' . PHOTO_BOL_PhotoDao::getInstance()->getTableName() . ' as pho where album.id = pho.albumId and pho.id = ' . $tableName . '.`entityId`';
        return $whereCondition;
    }

    /***
     * build query for privacy condition of comment and tag video list
     * @param $tableName
     * @return string
     */
    public function buildQueryForVideoWithEntityIdPrivacyCondition($tableName){
        $whereCondition = ' and (select count(*) from ' . VIDEO_BOL_ClipDao::getInstance()->getTableName() . ' as video where video.id = ' . $tableName . '.`entityId`';
        return $whereCondition;
    }

    /***
     * build privacy condition query for fetching content list as video and photo
     * @param null $objectTableName
     * @param null $privacyOfObjectTableName
     * @param null $privacyKey
     * @param bool $justFriends
     * @return string
     */
    public function buildUserPrivacyConditionQuery($objectTableName = null, $privacyOfObjectTableName = null, $privacyKey = null, $justFriends = false){
        if($objectTableName == null){
            return "";
        }
        $adminPrivacy = "false";
        $config = OW::getConfig();
        if($privacyKey!=null && $privacyKey!='' && $config->configExists('iissecurityessentials', $privacyKey) && $config->getValue('iissecurityessentials', $privacyKey) == self::$PRIVACY_EVERYBODY){
            $adminPrivacy = "true";
        }
        $queryForPublicContent = $privacyOfObjectTableName . '.`privacy` = \''.self::$PRIVACY_EVERYBODY.'\' and ( ' . $objectTableName.'.`userId` in (select pad.userId from '.PRIVACY_BOL_ActionDataDao::getInstance()->getTableName().' AS pad where pad.key = :privacyKey and pad.pluginKey = :pluginKey and  value = \'' . self::$PRIVACY_EVERYBODY . '\'  ) or '.$adminPrivacy.' ) ';
        $whereCondition = "";
        if(!$justFriends){
            $whereCondition = ' and ( (' . $queryForPublicContent . ') ';
        }else{
            $whereCondition = ' and ( 0 ';
        }

        if(OW::getUser()->isAuthenticated()){
            $currentUserId = OW::getUser()->getId();

            $queryForOwner = $objectTableName.'.`userId` = '.$currentUserId;
            $queryForFriends = '';
            if(class_exists('FRIENDS_BOL_FriendshipDao')) {
                $adminPrivacy = "false";
                if($privacyKey!=null && $privacyKey!='' && $config->configExists('iissecurityessentials', $privacyKey) && $config->getValue('iissecurityessentials', $privacyKey) != self::$PRIVACY_ONLY_FOR_ME){
                    $adminPrivacy = "true";
                }
                $queryForFriends = $privacyOfObjectTableName . '.`privacy` != \''.self::$PRIVACY_ONLY_FOR_ME.'\' and '.$objectTableName.'.`userId` in (SELECT ff.`userId` FROM ' . FRIENDS_BOL_FriendshipDao::getInstance()->getTableName() . ' AS ff WHERE ff.friendId = ' . $currentUserId . ' AND ff.`status` = \'active\' union SELECT ff.`friendId` as userId FROM ' . FRIENDS_BOL_FriendshipDao::getInstance()->getTableName() . ' AS ff WHERE ff.userId = ' . $currentUserId . ' AND ff.`status` = \'active\') and ( ' . $objectTableName . '.`userId` in (select pad.userId from ' . PRIVACY_BOL_ActionDataDao::getInstance()->getTableName() . ' AS pad where pad.key = :privacyKey and pad.pluginKey = :pluginKey and  value in (\'' . self::$PRIVACY_EVERYBODY . '\', \'' . self::$PRIVACY_FRIENDS_ONLY . '\')) or '.$adminPrivacy.'  )';
            }
            if(!$justFriends){
                $whereCondition .= ' or (' . $queryForOwner . ')';
            }

            $whereCondition .= ' or (' . $queryForFriends . ')';
        }

        $whereCondition .= ')';

        return $whereCondition;
    }

    /***
     * build privacy condition query for fetching content list as photo album list
     * @param null $objectTableName
     * @param null $privacyKey
     * @return string
     */
    public function buildUserAlbumPrivacyConditionQuery($objectTableName = null, $privacyKey = null){
        if($objectTableName == null){
            return "";
        }
        $adminPrivacy = "false";
        $config = OW::getConfig();
        if($privacyKey!=null && $privacyKey!='' && $config->configExists('iissecurityessentials', $privacyKey) && $config->getValue('iissecurityessentials', $privacyKey) == self::$PRIVACY_EVERYBODY){
            $adminPrivacy = "true";
        }
        $queryForPublicContent = '(select count(*) from ow_photo as pho where pho.albumId = '. $objectTableName.'.`id` and privacy = \''.self::$PRIVACY_EVERYBODY.'\' >0 ) and ( ' . $objectTableName.'.`userId` in (select pad.userId from '.PRIVACY_BOL_ActionDataDao::getInstance()->getTableName().' AS pad where pad.key = :privacyKey and pad.pluginKey = :pluginKey and  value = \'' . self::$PRIVACY_EVERYBODY . '\' ) or '.$adminPrivacy.' ) ';
        $whereCondition = ' and ( (' . $queryForPublicContent . ') ';
        if(OW::getUser()->isAuthenticated()){
            $currentUserId = OW::getUser()->getId();

            $queryForOwner = $objectTableName.'.`userId` = '.$currentUserId;
            $queryForFriends = '';
            if(class_exists('FRIENDS_BOL_FriendshipDao')) {
                $adminPrivacy = "false";
                if($privacyKey!=null && $privacyKey!='' && $config->configExists('iissecurityessentials', $privacyKey) && $config->getValue('iissecurityessentials', $privacyKey) != self::$PRIVACY_ONLY_FOR_ME){
                    $adminPrivacy = "true";
                }
                $queryForFriends ='(select count(*) from ow_photo as pho where pho.albumId = '. $objectTableName.'.`id` and privacy != \''.self::$PRIVACY_EVERYBODY.'\' >0 ) and '.$objectTableName.'.`userId` in (SELECT ff.`userId` FROM ' . FRIENDS_BOL_FriendshipDao::getInstance()->getTableName() . ' AS ff WHERE ff.friendId = ' . $currentUserId . ' AND ff.`status` = \'active\' union SELECT ff.`friendId` as userId FROM ' . FRIENDS_BOL_FriendshipDao::getInstance()->getTableName() . ' AS ff WHERE ff.userId = ' . $currentUserId . ' AND ff.`status` = \'active\') and ( ' . $objectTableName . '.`userId` in (select pad.userId from ' . PRIVACY_BOL_ActionDataDao::getInstance()->getTableName() . ' AS pad where pad.key = :privacyKey and pad.pluginKey = :pluginKey and  value in (\'' . self::$PRIVACY_EVERYBODY . '\', \'' . self::$PRIVACY_FRIENDS_ONLY . '\')) or '.$adminPrivacy.'  )';
            }
            $whereCondition .= ' or (' . $queryForOwner . ')';
            $whereCondition .= ' or (' . $queryForFriends . ')';
        }

        $whereCondition .= ')';

        return $whereCondition;
    }

    public function getPrivacyOfAlbum($albumId, $excludeIds = array()){
        if(class_exists('PHOTO_BOL_PhotoDao')) {
            $photosOfAlbum = PHOTO_BOL_PhotoDao::getInstance()->getAlbumPhotos($albumId, 1, 1, $excludeIds);
            if (sizeof($photosOfAlbum) > 0) {
                return $photosOfAlbum[0]->privacy;
            }
        }

        return null;
    }

    public function onReadyResponseOfPhoto(OW_Event $event){
        $data = $event->getData();
        if(isset($data['data']['photoList'])){
            $change_privacy_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label');
            $photos = array();
            foreach($data['data']['photoList'] as $photo){
                $objectId = $photo['id'];
                $feedId = $photo['userId'];
                $privacy = null;
                if(isset($photo['privacy'])) {
                    $privacy = $photo['privacy'];
                    $actionType = 'photo_comments';
                }else if(!isset($photo['albumId']) && isset($photo['albumUrl'])){
                    $albumPrivacy = $this->getPrivacyOfAlbum($photo['id']);
                    if($albumPrivacy!=null){
                        $privacy = $albumPrivacy;
                        $actionType = 'album';
                    }
                }
                $privacyButton = array('label' => $this->getPrivacyLabelByFeedId($privacy, $feedId),
                    'imgSrc' => OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticUrl() . 'images/' . $privacy . '.png');
                if ($feedId == OW::getUser()->getId()) {
                    $privacyButton['onClick'] = 'javascript:showAjaxFloatBoxForChangePrivacy(\'' . $objectId . '\', \'' . $change_privacy_label . '\',\''. $actionType .'\',\''.$feedId.'\')';
                    $privacyButton['id'] = 'sec-' . $objectId . '-' . $feedId;
                }
                $photo['privacy_label'] = $privacyButton;
                $photos[] = $photo;
            }
            $data['data']['photoList'] = $photos;
            $event->setData($data);
        }
    }

    public function createStatusPrivacyElement($privacyKey, $params = null){
        $statusPrivacy = new Selectbox('statusPrivacy');
        $statusPrivacy->setLabel(OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label'));
        $options = array();
        $options[self::$PRIVACY_EVERYBODY] = OW::getLanguage()->text("privacy", "privacy_everybody");
        $options[self::$PRIVACY_ONLY_FOR_ME] = OW::getLanguage()->text("privacy", "privacy_only_for_me");
        $options[self::$PRIVACY_FRIENDS_ONLY] = OW::getLanguage()->text("friends", "privacy_friends_only");
        $statusPrivacy->setHasInvitation(false);
        $statusPrivacy->setOptions($options);
        $statusPrivacy->addAttribute('class', 'statusPrivacy');
        $statusPrivacy->setRequired();
        $defaultPrivacy = $this->getActionValueOfPrivacy($privacyKey,OW::getUser()->getId());
        if(isset($params['albumId'])){
            $albumPrivacy = $this->getPrivacyOfAlbum($params['albumId']);
            if($albumPrivacy!=null){
                $defaultPrivacy = $albumPrivacy;
            }
        }
        if(isset($params['clipId'])){
            $videoPrivacy = $this->getPrivacyOfVideo($params['clipId']);
            if($videoPrivacy!=null){
                $defaultPrivacy = $videoPrivacy;
            }
        }
        if($params!=null && array_key_exists('albumId',$params)){
            $statusPrivacy->setLabel(OW::getLanguage()->text('iissecurityessentials', 'change_privacy_of_album_label'));
        }
        if(isset($params['data']) && isset($params['data']['statusPrivacy'])){
            $defaultPrivacy = $params['data']['statusPrivacy'];
        }
        $statusPrivacy->setValue($defaultPrivacy);
        return $statusPrivacy;
    }

    public function getPrivacyOfVideo($clipId){

        if(class_exists('VIDEO_BOL_ClipService')) {
            $clip = VIDEO_BOL_ClipService::getInstance()->findClipById($clipId);
            if ($clip!=null) {
                return $clip->privacy;
            }
        }

        return null;
    }

    public function privacyOnChangeActionPrivacy(OW_Event $event)
    {
        $params = $event->getParams();
        $userId = $params['userId'];
        $actionList = $params['actionList'];
        if(isset($actionList) && isset($userId) && isset($actionList['last_post_of_others_newsfeed'])){
            $privacy = $actionList['last_post_of_others_newsfeed'];
            $getActivityQuery = 'select a.id from ow_newsfeed_activity a, ow_newsfeed_action_feed ff where a.id = ff.activityId and ff.feedId = '.$userId.' and a.userId!='.$userId;
            $activityIds = OW::getDbo()->queryForList($getActivityQuery);
            $activityIdsImplodes = array();
            foreach($activityIds as $activityId){
                $activityIdsImplodes[] = $activityId['id'];
            }
            if(count($activityIdsImplodes)>0) { //issa added. don't remove
                $updateQuery = 'update ow_newsfeed_activity activity set activity.privacy = \'' . $privacy . '\' where activity.id in(' . implode(",", $activityIdsImplodes) . ')';
                OW::getDbo()->query($updateQuery);
            }
        }

        if(isset($actionList) && isset($userId) && isset($actionList['last_post_of_myself_newsfeed'])){
            $privacy = $actionList['last_post_of_myself_newsfeed'];
            $getActivityQuery = 'select a.id from ow_newsfeed_activity a, ow_newsfeed_action_feed ff where a.id = ff.activityId and ff.feedId = '.$userId.' and a.userId='.$userId;
            $activityIds = OW::getDbo()->queryForList($getActivityQuery);
            $activityIdsImplodes = array();
            foreach($activityIds as $activityId){
                $activityIdsImplodes[] = $activityId['id'];
            }
            if(count($activityIdsImplodes)>0) { //issa added. don't remove
                $updateQuery = 'update ow_newsfeed_activity activity set activity.privacy = \'' . $privacy . '\' where activity.id in(' . implode(",", $activityIdsImplodes) . ')';
                OW::getDbo()->query($updateQuery);
            }
        }
    }

    public function onQueryFeedCreate(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['feedId'])){
            $feedId = $params['feedId'];
            if($feedId==OW::getUser()->getId()){
                $event->setData(array('privacy' => '\''.self::$PRIVACY_EVERYBODY.'\',\''.self::$PRIVACY_FRIENDS_ONLY.'\',\''.self::$PRIVACY_ONLY_FOR_ME.'\''));
            }else{
                $ownerFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $feedId));
                if(!in_array(OW::getUser()->getId(),$ownerFriendsId)){
                    $event->setData(array('privacy' => '\''.self::$PRIVACY_EVERYBODY.'\''));
                }else{
                    $event->setData(array('privacy' =>'\''.self::$PRIVACY_EVERYBODY.'\',\''.self::$PRIVACY_FRIENDS_ONLY.'\''));
                }
            }
        }

    }

    public function onBeforeUpdateStatusFormCreateInProfile(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['userId'])){
            $userId = $params['userId'];
            if($userId != OW::getUser()->getId()) {
                $whoCanPostPrivacy = $this->getActionValueOfPrivacy('who_post_on_newsfeed', $userId);
                if ($whoCanPostPrivacy == self::$PRIVACY_FRIENDS_ONLY) {
                    $ownerFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $userId));
                    if(!in_array(OW::getUser()->getId(),$ownerFriendsId)){
                        $event->setData(array('showUpdateStatusForm' => false));
                    }
                } else if ($whoCanPostPrivacy == self::$PRIVACY_ONLY_FOR_ME) {
                    $event->setData(array('showUpdateStatusForm' => false));
                }
            }
        }
    }

    public function onBeforeUpdateStatusFormCreate(OW_Event $event){
        //Descide to show update status form in public page (false=hide)
        $event->setData(array('showUpdateStatusForm' => false));
    }

    public function privacyAddAction( BASE_CLASS_EventCollector $event )
    {
        $language = OW::getLanguage();

        $actions = array('my_post_on_feed_newsfeed','other_post_on_feed_newsfeed','last_post_of_others_newsfeed','who_post_on_newsfeed','video_default_privacy','last_post_of_myself_newsfeed');
        foreach ($actions as $action) {
            $information = $this->getInformationOfPrivacyField($action);
            $description = '';
            if(isset($information['description'])){
                $description = $information['description'];
            }

            $defaultValue = self::$PRIVACY_FRIENDS_ONLY;
            if(isset($information['defaultValue'])){
                $defaultValue = $information['defaultValue'];
            }

            $action = array(
                'key' => $action,
                'pluginKey' => 'iissecurityessentials',
                'label' => $language->text('iissecurityessentials', $action),
                'description' => $description,
                'defaultValue' => $defaultValue
            );

            $event->add($action);
        }
    }

    public function getInformationOfPrivacyField($privacyKey){
        $information = array();
        if($privacyKey == 'last_post_of_myself_newsfeed'){
            $information['description'] = OW::getLanguage()->text('iissecurityessentials','last_post_of_myself_newsfeed_description');
        }else if($privacyKey == 'last_post_of_others_newsfeed'){
            $information['description'] = OW::getLanguage()->text('iissecurityessentials','last_post_of_others_newsfeed_description');
        }

        $adminDefaultValue = $this->getAdminDefaultValueOfPrivacy($privacyKey);
        if($adminDefaultValue != null){
            $information['defaultValue'] = $adminDefaultValue;
        }

        return $information;
    }

    public function getAdminDefaultValueOfPrivacy($privacyKey){
        return OW::getConfig()->getValue('iissecurityessentials', $privacyKey);
    }

    public function updatePrivacyOfVideo($objectId, $privacy){
        $videoService = VIDEO_BOL_ClipService::getInstance();
        $video = $videoService->findClipById($objectId);
        $this->checkUserOwnerId($video->userId);
        $video->privacy = $privacy;
        $videoService->updateClip($video);
        return $video->userId;
    }

    public function getActionOwner($actionId){
        $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds(array($actionId));
        foreach($activities as $activityId){
            $activity = NEWSFEED_BOL_Service::getInstance()->findActivity($activityId)[0];
            if($activity->activityType='create'){
                $feedObject = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids(array($activity->id));
                if(isset($feedObject[$activity->id]) && isset($feedObject[$activity->id][0])) {
                    $feedId = $feedObject[$activity->id][0]->feedId;
                    if ($feedId != null) {
                        return $feedId;
                    }
                }
            }
        }
        return null;
    }

    public function updatePrivacyOfPhoto($objectId, $privacy){
        $photoService = PHOTO_BOL_PhotoService::getInstance();
        $photo = $photoService->findPhotoById($objectId);
        $photoOwner = $photoService->findPhotoOwner($photo->id);
        $this->checkUserOwnerId($photoOwner);
        $photo->privacy = $privacy;
        $photoService->updatePhoto($photo);
        return $photoOwner;
    }

    public function getPhotoOwner($objectId){
        $photoService = PHOTO_BOL_PhotoService::getInstance();
        $photo = $photoService->findPhotoById($objectId);
        $photoOwner = $photoService->findPhotoOwner($photo->id);
        return $photoOwner;
    }

    public function updatePrivacyOfMultiplePhoto($photoIds, $privacy){
        $photoOwner = '';
        $photoSampleId = null;
        foreach($photoIds as $photoId){
            $photoSampleId = $photoId;
            $photoOwner = $this->updatePrivacyOfPhoto($photoId, $privacy);
        }
        if($photoSampleId!=null){
            $albumId = PHOTO_BOL_PhotoService::getInstance()->findPhotoById($photoSampleId)->albumId;
            $this->updatePrivacyOfPhotosByAlbumId($albumId, $privacy);
        }
        return $photoOwner;
    }

    public function updatePrivacyOfPhotosByAlbumId($objectId, $privacy){
        $actionId = array();
        $album = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumById($objectId);
        $count = PHOTO_BOL_PhotoService::getInstance()->countAlbumPhotos($album->id, array());
        $photosOfAlbum = PHOTO_BOL_PhotoService::getInstance()->findPhotoListByAlbumId($album->id,1,$count);
        foreach($photosOfAlbum as $photo){
            $photoOwner = $this->updatePrivacyOfPhoto($photo['id'], $privacy);
            $action = NEWSFEED_BOL_Service::getInstance()->findAction('photo_comments', $photo['id']);
            if($action!=null){
                if($this->getActionOwner($action->id)==$photoOwner) {
                    $actionId[] = $action->id;
                }
            }else{
                $action = NEWSFEED_BOL_Service::getInstance()->findAction('multiple_photo_upload', $photo['uploadKey']);
                if($action!=null){
                    if($this->getActionOwner($action->id)==$photoOwner) {
                        $actionId[] = $action->id;
                    }
                }
            }
        }
        return array('userId' => $album->userId, 'actionId' => $actionId);
    }

    public function updateNewsFeedActivitiesByActionId($activities, $privacy){
        $privacy = $this->validatePrivacy($privacy);
        foreach($activities as $activityId){
            $activity = NEWSFEED_BOL_Service::getInstance()->findActivity($activityId)[0];
            if($privacy == self::$PRIVACY_ONLY_FOR_ME && $activity->activityType == 'subscribe' && OW::getUser()->isAuthenticated() && $activity->userId != OW::getUser()->getId()){
                NEWSFEED_BOL_Service::getInstance()->removeActivity("subscribe.{$activity->userId}:$activity->actionId");
            }else {
                $this->checkUserOwnerId($activity->userId);
                $activity->privacy = $privacy;
                NEWSFEED_BOL_Service::getInstance()->saveActivity($activity);
            }
        }
    }

    public function onBeforeUsedFeedListQueryExecuted(OW_Event $event){
        $where = array();
        $where['followerPrivacyWhereCondition'] = ' and (activity.privacy != \''.self::$PRIVACY_ONLY_FOR_ME.'\' || activity.userId=:u) ';
        $where['viewerActivityPrivacyWhereCondition'] = ' and action.id not in(select activityPrivacy.actionId from ow_newsfeed_activity activityPrivacy where activityPrivacy.activityType = :ac and activityPrivacy.privacy = \''.self::$PRIVACY_ONLY_FOR_ME.'\' and activityPrivacy.userId != :u) ';
        $event->setData(array('whereConditionPrivacy' => $where));
    }

    public function onBeforeUserDisapproveAfterEditProfile(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['params'])){
            $paramsData = $params['params'];
            if(isset($paramsData['forEditProfile']) && $paramsData['forEditProfile']){
                $disableUserDisapprove = OW::getConfig()->getValue('iissecurityessentials', 'approveUserAfterEditProfile');
                $event->setData(array('disapprove' => !$disableUserDisapprove));
            }
        }

        if(isset($params['checkApproveEnabled']) && $params['checkApproveEnabled']){
            $disableUserDisapprove = OW::getConfig()->getValue('iissecurityessentials', 'approveUserAfterEditProfile');
            $event->setData(array('approveEnabled' => !$disableUserDisapprove));
        }

    }

    public function checkUserOwnerId($ownerId,$feedId = null){
        if($feedId!=null && $feedId!='' && $feedId==OW::getUser()->getId()){
            return;
        }else if(!OW::getUser()->isAuthenticated() || OW::getUser()->getId()!=$ownerId){
            exit(json_encode(array('result' => false)));
        }
    }

    public function updateNewsFeedActivitiesByActionIds($actionIds, $privacy){
        $activities = array();
        if(is_array($actionIds)){
            $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds($actionIds);
        }else{
            $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds(array($actionIds));
        }
        $this->updateNewsFeedActivitiesByActionId($activities, $privacy);
    }

    public function onAfterActivity(OW_Event $event){
        $params = $event->getParams();
        $feedId = null;
        if(isset($params['feedId'])){
            $feedId = $params['feedId'];
        }
        $feedType = null;
        if(isset($params['feedType'])){
            $feedType = $params['feedType'];
        }
        $entityType = null;
        if(isset($params['entityType'])){
            $entityType = $params['entityType'];
        }
        $entityId = null;
        if(isset($params['entityId'])){
            $entityId = $params['entityId'];
        }
        $actionId = null;
        if(isset($params['actionId'])){
            $actionId = $params['actionId'];
        }
        $privacy = null;
        $findActivity = true;
        if($entityType == 'friend_add'){
            $privacy = self::$PRIVACY_FRIENDS_ONLY;
        }else if($feedType == 'user') {
            $privacy = $this->setPrivacy($feedId);
        }else if(($entityType == 'photo_comments' || $entityType == 'multiple_photo_upload') && isset($_REQUEST['statusPrivacy'])){
            if($entityType=='photo_comments'){
                $albumId = PHOTO_BOL_PhotoService::getInstance()->findPhotoById($entityId)->albumId;
                $privacyOfAlbum = $this->getPrivacyOfAlbum($albumId);
                if($privacyOfAlbum!=null){
                    $privacy = $privacyOfAlbum;
                }
                $results = $this->updatePrivacyOfPhotosByAlbumId($albumId, $privacy);
                $this->updateNewsFeedActivitiesByActionIds($results['actionId'], $privacy);
                $findActivity = false;
            }else if($entityType=='multiple_photo_upload'){
                $photoSampleId = null;
                $photoIdList = $event->getData()['photoIdList'];
                $privacy = $this->validatePrivacy($_REQUEST['statusPrivacy']);
                if($photoIdList!=null && !isEmpty($photoIdList)) {
                    $photoSampleId = $photoIdList[0];
                }else{
                    $actionObj = NEWSFEED_BOL_Service::getInstance()->findAction('multiple_photo_upload', $entityId);
                    if($actionObj!=null){
                        $data = $actionObj->data;
                        if($data!=null && isset(json_decode($data)->photoIdList[0])) {
                            $photoSampleId = json_decode($data)->photoIdList[0];
                        }
                    }
                }

                if($photoSampleId!=null) {
                    $albumId = PHOTO_BOL_PhotoService::getInstance()->findPhotoById($photoSampleId)->albumId;
                    $privacyOfAlbum = $this->getPrivacyOfAlbum($albumId);
                    if ($privacyOfAlbum != null) {
                        $privacy = $privacyOfAlbum;
                    }
                    $results = $this->updatePrivacyOfPhotosByAlbumId($albumId, $privacy);
                    $this->updateNewsFeedActivitiesByActionIds($results['actionId'], $privacy);
                    $findActivity = false;
                }
            }
        }else if($entityType == 'video_comments' && isset($_REQUEST['statusPrivacy'])){
            $privacy = $this->validatePrivacy($_REQUEST['statusPrivacy']);
            $this->changePrivacyOfVideo($entityId, $privacy);
        }else if($entityType == 'add_audio'){
            $privacy = $this->validatePrivacy($_REQUEST['statusPrivacy']);
        }

        if ($actionId!=null && $privacy!=null && $findActivity) {
            $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds(array($actionId));
            foreach ($activities as $activityId) {
                $activity = NEWSFEED_BOL_Service::getInstance()->findActivity($activityId)[0];
                $privacy = $this->validatePrivacy($privacy);
                $activity->privacy = $privacy;
                NEWSFEED_BOL_Service::getInstance()->saveActivity($activity);
            }
        }
    }

    public function changePrivacyOfVideo($clipId, $privacy){
        if(class_exists('VIDEO_BOL_ClipService')){
            $clip = VIDEO_BOL_ClipService::getInstance()->findClipById($clipId);
            $clip->privacy = $privacy;
            VIDEO_BOL_ClipService::getInstance()->saveClip($clip);
        }
    }

    public function validatePrivacy($privacy){
        if($privacy == self::$PRIVACY_EVERYBODY || $privacy == self::$PRIVACY_ONLY_FOR_ME || $privacy == self::$PRIVACY_FRIENDS_ONLY){
            return $privacy;
        }
        return self::$PRIVACY_ONLY_FOR_ME;
    }

    public function onAfterUpdateStatusFormRenderer(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['form']) && isset($params['component'])){
            $form = $params['form'];
            if($form->getElement('statusPrivacy')!=null){
                $params['component']->assign('statusPrivacyField',true);
            }else{
                $profileOwner = $this->findUserByProfile();
                if($profileOwner!=null && $profileOwner->getId() != OW::getUser()->getId()){
                    $profileOwnerPrivacy = $this->getActionValueOfPrivacy('other_post_on_feed_newsfeed',$profileOwner->getId());
                    $text = '';
                    if($profileOwnerPrivacy == self::$PRIVACY_ONLY_FOR_ME){
                        $text = OW::getLanguage()->text('iissecurityessentials', 'show_to_user',array('username' => $profileOwner->username));
                    }else if($profileOwnerPrivacy == self::$PRIVACY_FRIENDS_ONLY){
                        $text = OW::getLanguage()->text('iissecurityessentials', 'show_to_friends',array('username' => $profileOwner->username));
                    }else if($profileOwnerPrivacy == self::$PRIVACY_EVERYBODY){
                        $text = OW::getLanguage()->text('iissecurityessentials', 'show_to_everybody');
                    }
                    $params['component']->assign('statusPrivacyLabel',$text);
                }
            }
        }
    }

    public function onBeforeUpdateStatusFormRenderer(OW_Event $event){
        $params = $event->getParams();
        $user = $this->findUserByProfile();
        if(isset($params['form']) && ($user==null || ($user->getId()==OW::getUser()->getId())) && $params['form']->getElement('feedType')->getValue()=='user'){
            $form = $params['form'];
            $form->addElement($this->createStatusPrivacyElement('my_post_on_feed_newsfeed'));
        }
    }

    public function onBeforeObjectRenderer(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['privacy']) && isset($params['ownerId'])){
            $this->checkPrivacyOfObject($params['privacy'], $params['ownerId']);
        }
    }


    public function onCheckObjectBeforeSaveOrUpdate(OW_Event $event){
        $params = $event->getParams();
        $isValid = true;
        if(isset($params['entity']) && isset($params['entityClass'])){
            $entity = $params['entity'];
            if($entity instanceof NEWSFEED_BOL_Status || $entity instanceof NEWSFEED_BOL_ActionFeed){
                if(strcmp('groups',$entity->feedType)==0){
                    $isValid =$this->groupsNewsFeedCheckObjectBeforeSaveOrUpdate($entity->feedId);
                }
                else if(strcmp('user',$entity->feedType)==0){
                    $isValid =$this->userNewsFeedCheckObjectBeforeSaveOrUpdate($entity->feedId);
                }
            }
        }

        if(!$isValid){
            exit();
        }
    }

    public function groupsNewsFeedCheckObjectBeforeSaveOrUpdate($groupId){

        if(!OW::getUser()->isAuthenticated()){
            return false;
        }
        $groupDto = GROUPS_BOL_Service::getInstance()->findGroupById($groupId);
        if ( empty($groupDto) )
        {
            return false;
        }

        $isUserInGroup = GROUPS_BOL_Service::getInstance()->findUser($groupId, OW::getUser()->getId());
        $creatorId = $groupDto->userId;
        if(!$isUserInGroup && $creatorId!=OW::getUser()->getId()){
            return false;
        }
        return true;
    }

    public function userNewsFeedCheckObjectBeforeSaveOrUpdate($userId){

        if(!OW::getUser()->isAuthenticated()){
            return false;
        }
        if($userId!=OW::getUser()->getId()){

            $isBloacked = BOL_UserService::getInstance()->isBlocked(OW::getUser()->getId(), $userId);

            if ( OW::getUser()->isAuthorized('base', 'add_comment') )
            {
                if ( $isBloacked )
                {
                    return false;
                }
                else
                {
                    $event = OW::getEventManager()->trigger(new OW_Event(IISEventManager::ON_BEFORE_UPDATE_STATUS_FORM_CREATE_IN_PROFILE, array('userId' => $userId)));
                    if(isset($event->getData()['showUpdateStatusForm'])) {
                        if(!$event->getData()['showUpdateStatusForm']){
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    public function onBeforeFeedItemRenderer(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['actionId']) && isset($params['feedId'])){
            $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds(array($params['actionId']));
            foreach($activities as $activityId){
                $activity = NEWSFEED_BOL_Service::getInstance()->findActivity($activityId)[0];
                if($activity->activityType=='create'){
                    $this->checkPrivacyOfObject($activity->privacy, $params['feedId'], $activity->userId);
                }
            }
        }else if(isset($params['actionId']) && !isset($params['feedId'])){
            //view feed page
            $activities = NEWSFEED_BOL_ActivityDao::getInstance()->findIdListByActionIds(array($params['actionId']));
            foreach($activities as $activityId){
                $activity = NEWSFEED_BOL_Service::getInstance()->findActivity($activityId)[0];
                if($activity->activityType=='create'){
                    $this->checkPrivacyOfObjectForViewer($activity->privacy, $activity->userId, true);
                }
            }
        }
    }

    public function checkPrivacyOfObject($privacy, $ownerId, $activityOwner = null, $throwEx = true){
        if(OW::getUser()->isAuthenticated() && $ownerId==OW::getUser()->getId()){
            return true;
        }else if($privacy==self::$PRIVACY_EVERYBODY || ($activityOwner!=null && OW::getUser()->isAuthenticated() && $activityOwner==OW::getUser()->getId())){
            return true;
        }else if($privacy==self::$PRIVACY_ONLY_FOR_ME && $ownerId!=OW::getUser()->getId()){
            if($throwEx){
                throw new Redirect404Exception();
            }else{
                return false;
            }
        }else if($privacy == self::$PRIVACY_FRIENDS_ONLY && $ownerId!=OW::getUser()->getId()){
            $ownerFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $ownerId));
            if(!in_array(OW::getUser()->getId(),$ownerFriendsId)){
                if($throwEx){
                    throw new Redirect404Exception();
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }
    }

    public function checkPrivacyOfObjectForViewer($privacy, $ownerId, $throwEx){
        if((OW::getUser()->isAuthenticated() && $ownerId==OW::getUser()->getId()) || $privacy==self::$PRIVACY_EVERYBODY){
            return true;
        }else if(OW::getUser()->isAuthenticated() && $privacy==self::$PRIVACY_ONLY_FOR_ME){
            if($throwEx){
                throw new Redirect404Exception();
            }else{
                return false;
            }
        }else if(OW::getUser()->isAuthenticated() && $privacy == self::$PRIVACY_FRIENDS_ONLY && class_exists('FRIENDS_BOL_Service')){
            $ownerFriendsId = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => $ownerId));
            if(!in_array(OW::getUser()->getId(),$ownerFriendsId)){
                if($throwEx){
                    throw new Redirect404Exception();
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else if(!OW::getUser()->isAuthenticated() && $privacy!=self::$PRIVACY_EVERYBODY) {
            if($throwEx){
                throw new Redirect404Exception();
            }else{
                return false;
            }
        }
        return true;
    }

    public function onCollectPhotoContextActions( BASE_CLASS_EventCollector $event ){
        $params = $event->getParams();
        $photoId = $params['photoId'];

        if(OW::getUser()->isAuthenticated() && PHOTO_BOL_PhotoService::getInstance()->findPhotoOwner($photoId) == OW::getUser()->getId()) {
            $change_privacy_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label');
            $change_privacy_of_album_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_of_album_label');

            $changePrivacyData = array(
                'url' => 'javascript:showAjaxFloatBoxForChangePrivacy(\'' . $photoId . '\', \'' . $change_privacy_label . '\',\'photo_comments\',\'\');',
                'id' => 'btn-video-change-privacy',
                'label' => $change_privacy_of_album_label,
                'order' => 4
            );

            $event->add($changePrivacyData);
        }
    }

    public function onCollectVideoToolbarItems( BASE_CLASS_EventCollector $event ){
        $params = $event->getParams();
        $clipId = $params['clipId'];
        $clipDto = $params['clipDto'];
        $change_privacy_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label');
        $iconUrl = OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticUrl() . 'images/'.$clipDto->privacy.'.png';
        $changePrivacyData = array(
            'label' => '<img title="'. $this->getPrivacyLabelByFeedId($clipDto->privacy, $clipDto->userId) .'" class="feed_image_privacy" src="' . $iconUrl . '" />'
        );

        if(OW::getUser()->isAuthenticated() && $clipDto->userId == OW::getUser()->getId()) {
            $changePrivacyData['href'] = 'javascript:showAjaxFloatBoxForChangePrivacy(\'' . $clipId . '\', \'' . $change_privacy_label . '\',\'video_comments\',\'\');';
            $changePrivacyData['id'] = 'sec-'.$clipId.'-'.$clipDto->userId;
        }
        $event->add($changePrivacyData);
    }

    public function questionItemPrivacy( OW_Event $event ){
        $params = $event->getParams();
        $questionTpl = $event->getData();
        $questionId = $params['questionId'];
        $question = QUESTIONS_BOL_ActivityDao::getInstance()->findActivity($questionId, 'create', $questionId);
        $privacyButtonString = $this->getPrivacyButtonInformation($questionId,$question->userId,$question->privacy,'question');
        $questionTpl['privacy_label'] = $privacyButtonString;
        $event->setData($questionTpl);
    }

    public function getPrivacyButtonInformation($objectId, $userId, $privacy, $objectType, $linkable = true){
        $change_privacy_label = OW::getLanguage()->text('iissecurityessentials', 'change_privacy_label');
        $privacyButton = array('label' => $this->getPrivacyLabelByFeedId($privacy, $userId),
            'imgSrc' => OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticUrl() . 'images/' . $privacy . '.png');
        if (OW::getUser()->isAuthenticated() && $userId == OW::getUser()->getId() && $linkable) {
            $privacyButton['onClick'] = 'javascript:showAjaxFloatBoxForChangePrivacy(\'' . $objectId . '\', \'' . $change_privacy_label . '\',\''.$objectType.'\',\''.$userId.'\')';
            $privacyButton['id'] = 'sec-'.$objectId.'-'.$userId;
        }

        return $privacyButton;
    }

    public function onBeforeDocumentRenderer( OW_Event $event )
    {
        $jsFile = OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticJsUrl() . 'iissecurityessentials.js';
        OW::getDocument()->addScript($jsFile);

        $cssFile = OW::getPluginManager()->getPlugin('iissecurityessentials')->getStaticCssUrl() . 'iissecurityessentials.css';
        OW::getDocument()->addStyleSheet($cssFile);
    }

    public function onFeedItemRenderer( OW_Event $event )
    {
        $data = $event->getData();
        $params = $event->getParams();
        if(isset($params['data']) && isset($params['data']['privacy_label'])){
            $data['privacy_label'] = $params['data']['privacy_label'];
            $event->setData($data);
        }
    }

    public function onBeforeAlbumInfoRenderer(OW_Event $event)
    {
        $params = $event->getParams();
        if (isset($params['this']) && isset($params['album'])) {
            $album = $params['album'];
            $userId = $album->userId;
            $privacy = $this->getPrivacyOfAlbum($album->id);
            if($privacy!=null) {
                $params['this']->assign('privacy_label', $this->getPrivacyButtonInformation('', $userId, $privacy, '', false));
            }
        }
    }

    public function onBeforeAlbumsRenderer(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['this']) && isset($params['album'])){
            $album = $params['album'];
            $privacy = $this->getPrivacyOfAlbum($album->id);
            if($privacy!=null){
                $params['this']->assign('privacy_label',$this->getPrivacyButtonInformation($album->id, $album->userId, $privacy, 'album'));
            }
        }
    }

    public function onFeedItemRender( OW_Event $event )
    {
        $data = $event->getData();
        $params = $event->getParams();
        $feedType = $params['feedType'];
        $ignoreByEntityTypes = false;
        $entityTypeBlackList = array('friend_add', 'groups-status', 'group', 'group-join', 'event');
        if(isset($params['action']['entityType']) && in_array($params['action']['entityType'], $entityTypeBlackList)){
            $ignoreByEntityTypes = true;
        }
        if ( in_array($feedType , array('user', 'my', 'site')) && !$ignoreByEntityTypes)
        {
            $activities = $params['activity'];
            foreach($activities as $activity){
                if($activity['activityType'] == 'create') {
                    $feedObject = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids(array($activity['id']));
                    $feedId = $feedObject[$activity['id']][sizeof($feedObject[$activity['id']])-1]->feedId;
                    $data['privacy_label'] = $this->getPrivacyButtonInformation($params['createActivity']->actionId, $feedId, $activity['privacy'], 'user_status');
                }
            }
        }


        if (OW::getUser()->isAuthenticated()) {
            $activityIds = array();
            if (isset($params['action']) && in_array($params['action']['entityType'], array('group')) && class_exists('GROUPS_BOL_Service')) {
                foreach ($params['activity'] as $activity) {
                    if (in_array($activity['activityType'],array('groups-join', 'subscribe')) && $activity['userId'] == OW::getUser()->getId()) {
                        $activityIds[] = $activity['id'];
                    }
                }
            }

            if (isset($params['action']) && in_array($params['action']['entityType'], array('event')) && class_exists('EVENT_BOL_EventService')) {
                foreach ($params['activity'] as $activity) {
                    if (in_array($activity['activityType'],array('event-join', 'subscribe')) && $activity['userId'] == OW::getUser()->getId()) {
                        $activityIds[] = $activity['id'];
                    }
                }
            }

            if (sizeof($activityIds)>0) {
                $acceptedActivityIds = array();
                foreach($activityIds as $activityId) {
                    $feedList = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids(array($activityId));
                    $feedId = null;
                    if(!empty($feedList)) {
                        foreach ($feedList[$activityId] as $feed) {
                            if ($feed->feedType == 'user' && $feed->feedId == OW::getUser()->getId()) {
                                $acceptedActivityIds[] = $activityId;
                            }
                        }
                    }
                }
                if (sizeof($acceptedActivityIds)>0) {
                    $activiIdsString = implode($acceptedActivityIds, '-');
                    $data['contextMenu'] = empty($data['contextMenu']) ? array() : $data['contextMenu'];
                    $callbackUri = OW::getRequest()->getRequestUri();
                    $deleteUrl = OW::getRequest()->buildUrlQueryString(OW::getRouter()->urlForRoute('iissecurityessentials.delete_activity', array(
                        'activityId' => $activiIdsString
                    )), array(
                        'redirectUri' => urlencode($callbackUri)
                    ));

                    array_unshift($data['contextMenu'], array(
                        'label' => OW::getLanguage()->text('iissecurityessentials', 'delete_feed_item_label'),
                        'url' => $deleteUrl,
                        'attributes' => array(
                            'data-message' => OW::getLanguage()->text('iissecurityessentials', 'delete_feed_item_confirmation'),
                            'onclick' => 'return confirm($(this).data().message);'
                        )
                    ));
                }
            }
        }

        $event->setData($data);
    }

    public function deleteFeedItemByActivityId($activityIds = null){
        if($activityIds == null || !OW::getUser()->isAuthenticated() || !class_exists('NEWSFEED_BOL_Service') || !class_exists('NEWSFEED_BOL_ActionFeedDao')){
            throw new Redirect404Exception();
        }

        $activityIdsArray = explode('-', $activityIds);
        $feedList = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids($activityIdsArray);
        foreach($activityIdsArray as $activityId) {
            foreach ($feedList[$activityId] as $feed) {
                if ($feed->feedType == 'user' && $feed->feedId == OW::getUser()->getId()) {
                    NEWSFEED_BOL_ActionFeedDao::getInstance()->deleteByFeedAndActivityId('user', $feed->feedId, $activityId);
                }
            }
        }

        $redirectUri = urldecode($_GET['redirectUri']);
        OW_Application::getInstance()->redirect(OW_URL_HOME . $redirectUri);
    }

    public function onBeforeVideoRender( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['objectId']) && isset($params['this']) && isset($params['privacy']) && isset($params['userId'])){
            $item = array();
            $item['privacy_label'] = $this->getPrivacyButtonInformation($params['objectId'], $params['userId'], $params['privacy'], 'video_comments');
            $params['this']->assign('item', $item);
        }
    }

    public function onBeforePhotoRender( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['objectId']) && isset($params['this']) && isset($params['privacy']) && isset($params['userId'])){
            $item = array();
            $item['privacy_label'] = $this->getPrivacyButtonInformation($params['objectId'], $params['userId'], $params['privacy'], 'album');
            $params['this']->assign('item', $item);
        }
    }

    public function getPrivacyLabelByFeedId($privacy, $feedId){
        $user = BOL_UserService::getInstance()->findUserById($feedId);
        if($user == null){
            return null;
        }
        return $this->getPrivacyLabel($privacy, $user->username);
    }

    public function getPrivacyLabel($privacy, $username){
        if(self::$PRIVACY_FRIENDS_ONLY == $privacy){
            return OW::getLanguage()->text('iissecurityessentials', 'show_to_friends', array('username' => $username));
        }else if(self::$PRIVACY_ONLY_FOR_ME == $privacy){
            return OW::getLanguage()->text('iissecurityessentials', 'show_to_user', array('username' => $username));
        }else if(self::$PRIVACY_EVERYBODY == $privacy){
            return OW::getLanguage()->text('iissecurityessentials', 'show_to_everybody');
        }
    }

    public function onFeedCollectPrivacy( BASE_CLASS_EventCollector $event )
    {
        $event->add(array('*:*', 'view_my_feed'));
    }

    public function setPrivacy($ownerId){
        $privacy = self::$PRIVACY_FRIENDS_ONLY;
        if($ownerId!=null && $ownerId==OW::getUser()->getId()){
            if(isset($_REQUEST['statusPrivacy'])){
                $privacy = $this->validatePrivacy($_REQUEST['statusPrivacy']);
            }else{
                $my_post_on_feed_newsfeed = $this->getActionValueOfPrivacy('my_post_on_feed_newsfeed',$ownerId);
                if($my_post_on_feed_newsfeed!=null){
                    $privacy = $my_post_on_feed_newsfeed;
                }
            }
        }else if($ownerId!=null && $ownerId!=OW::getUser()->getId()){
            $other_post_on_feed_newsfeed = $this->getActionValueOfPrivacy('other_post_on_feed_newsfeed',$ownerId);
            if($other_post_on_feed_newsfeed!=null){
                $privacy = $other_post_on_feed_newsfeed;
            }
        }
        return $privacy;
    }

    public function findUserByProfile(){
        $user  = null;
        if(strpos($_SERVER['REQUEST_URI'],'/user/')!==false){
            $username = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'/user/')+6);
            if(strpos($username,'/')!==false){
                $username = substr($username,0,strpos($username,'/'));
            }
            $user = BOL_UserService::getInstance()->findByUsername($username);
        }
        return $user;
    }

    public function catchAllRequestsExceptions( BASE_CLASS_EventCollector $event )
    {
        $event->add(array(
            OW_RequestHandler::ATTRS_KEY_CTRL => 'BASE_CTRL_EmailVerify',
            OW_RequestHandler::ATTRS_KEY_ACTION => 'verify'
        ));

        $event->add(array(
            OW_RequestHandler::ATTRS_KEY_CTRL => 'BASE_CTRL_EmailVerify',
            OW_RequestHandler::ATTRS_KEY_ACTION => 'verifyForm'
        ));
    }

    public function onBeforeIndexStatusEnabled(OW_Event $event){
        $params = $event->getParams();
        $config =  OW::getConfig();
        $indexStatus = null;
        if($config->configExists('newsfeed', 'index_status_enabled')) {
            $config->saveConfig('newsfeed', 'index_status_enabled',null);
        }
        else{
            $config->addConfig('newsfeed', 'index_status_enabled',null);
        }
        if(isset($params['checkBoxField'])){
            $field = $params['checkBoxField'];
            $field->removeAttribute("checked");
            $field->addAttribute('disabled', 'disabled');
        }
    }

    /*
    * return the correct invitation feed status
    * @param OW_Event $event
    */
    public static function onBeforeFeedRendered(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['userId'])) {
            if($params['userId'] == ow::getUser()->getId())
            {
                IISSecurityProvider::setStatusMessage(OW::getLanguage()->text('iissecurityessentials', 'status_field_ownUser'));
            }
            else
            {
                $user = BOL_UserService::getInstance()->findUserById($params['userId']);
                $username = $user->getUsername();
                IISSecurityProvider::setStatusMessage(OW::getLanguage()->text('iissecurityessentials', 'status_field_otherUser',array('username' => $username)));
            }
        }
        else
        {
            IISSecurityProvider::setStatusMessage(OW::getLanguage()->text('iissecurityessentials', 'status_field_invintation'));
        }
    }

    public function regenerateSessionID(OW_Event $event){
        $userContext = null;
        if(OW::getSession()->isKeySet(OW_Application::CONTEXT_NAME)){
            $userContext = OW::getSession()->get(OW_Application::CONTEXT_NAME);
        }
        OW::getSession()->regenerate();
        if($userContext!=null){
            OW::getSession()->set(OW_Application::CONTEXT_NAME, $userContext);
        }
    }

    public function logoutIfIdle(OW_Event $event){
        $user = OW::getUser();
        if ( !$user->isAuthenticated() || $user->getUserObject()==null)
        {
            return;
        }
        $timestamp = $user->getUserObject()->getActivityStamp();
        $now = time();
        if (isset($_COOKIE['ow_login']) && !$_COOKIE['ow_login'] && $now - $timestamp > OW::getConfig()->getValue('iissecurityessentials', 'idleTime')*60){
            OW::getUser()->logout();
            if ( isset($_COOKIE['ow_login']) )
            {
                setcookie('ow_login', '', time() - 3600, '/');
            }
            OW::getSession()->set('no_autologin', true);
            OW::getApplication()->redirect(OW_URL_HOME);
        }
    }

    public function onAfterReadUrlEmbed(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['stringToFix'])){
            $oneStepFixed = html_entity_decode($params['stringToFix'], ENT_NOQUOTES, 'UTF-8');
            $finalStepFixed = html_entity_decode($oneStepFixed, ENT_NOQUOTES, 'UTF-8');
            $finalStepFixed = htmlspecialchars_decode($finalStepFixed);
            $finalStepFixed = str_replace('&#x202B'," ",$finalStepFixed);
            $event->setData(array('fixedString' => $finalStepFixed));
        }
    }

    public function onCheckUrlEmbed(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['oembed']) && $params['oembed']['type']=='link') {
            if( $params['oembed']['title']==null) {
                $event->setData(array('noContent' => true));
            }
        }
    }

    public function testHomePageActivityKey( $key, $testKey, $all = false )
    {
        $key = $this->parseActivityKey($key);
        $testKey= $this->processActivityKey($testKey);

        $result = true;
        foreach ( $testKey as $tk )
        {
            $result = true;
            foreach ( $tk as $type => $f )
            {
                foreach ( $f as $k => $v )
                {
                    $r = empty($key[$type][$k]) ? true : empty($v) || $key[$type][$k] == $v;
                    if ( !$r )
                    {
                        $result = false;

                        break 2;
                    }
                }
            }

            if ( $result && !$all || !$result && $all)
            {
                break;
            }
        }

        return $result;
    }

    private function parseActivityKey( $key, $context = null )
    {
        $key = str_replace('*', '', $key);

        $temp = explode(':', $key);

        $userId = empty($temp[2]) ? null : $temp[2];
        $actionKey = empty($temp[1]) ? null : $temp[1];
        $activityKey = empty($temp[0]) ? null : $temp[0];

        $out = array(
            'action' => array( 'entityType' => null, 'entityId' => null, 'id' => null ),
            'activity' => array( 'activityType' => null, 'activityId' => null, 'id' => null, 'userId' => $userId)
        );

        if ( is_numeric($actionKey) && strpos($actionKey, '.') === false )
        {
            $out['action']['id'] = $actionKey;
        }
        else
        {
            $temp = explode('.', $actionKey);

            $out['action']['entityType'] = $temp[0];
            $out['action']['entityId'] = empty($temp[1]) ? null : $temp[1];

        }

        if ( is_numeric($activityKey) && strpos($activityKey, '.') === false )
        {
            $out['activity']['id'] = $activityKey;
        }
        else
        {
            $temp = explode('.', $activityKey);
            $out['activity']['activityType'] = empty($temp[0]) ? null : $temp[0];
            $out['activity']['activityId'] = empty($temp[1]) ? null : $temp[1];
        }

        if ( !empty($context) )
        {
            $context = $this->parseActivityKey( $context );
            foreach ( $context as $k => $c )
            {
                $out[$k] = array_merge($c, array_filter($out[$k]));
            }
        }

        return $out;
    }
    private function processActivityKey( $activityKey, $context = null )
    {
        $params = array();
        $keys = array();

        $_keys = is_array($activityKey) ? $activityKey : explode(',', $activityKey);
        foreach ( $_keys as $key )
        {
            $_key = is_array($key) ? $key : explode(',', $key);
            $keys = array_merge($keys, $_key);
        }

        foreach ( $keys as $key )
        {
            $params[] = $this->parseActivityKey($key, $context);
        }

        return $params;
    }

    public function getActionTypes()
    {
        $event = new BASE_CLASS_EventCollector('feed.collect_configurable_activity');
        OW::getEventManager()->trigger($event);
        $actions = array();
        $eventData = $event->getData();

        $configTypes = json_decode(OW::getConfig()->getValue('iissecurityessentials', 'disabled_home_page_action_types'), true);

        foreach ( $eventData as $item )
        {
            $item['activity'] = is_array($item['activity']) ? implode(',', $item['activity']) : $item['activity'];

            $item['active'] = !isset($configTypes[$item['activity']]) ? empty($item['active']) || $item['active'] : $configTypes[$item['activity']];
            $actions[] = $item;
        }

        return $actions;
    }

    public function getHomePageDisabledEntityTypes()
    {
        $allTypes = $this->getActionTypes();
        $out = array();
        foreach ( $allTypes as $type )
        {
            if ( !$type['active'] )
            {
                $out[] = $type['activity'];
            }
        }

        return $out;
    }

    public function testHomePageActivity(OW_Event $event)
    {
        $params = $event->getParams();
        if (isset($params['activity'])) {
            $activity = $params['activity'];
            $dto = NEWSFEED_BOL_Service::getInstance()->findActionById($activity->actionId);
            if(!isset($activity->id)){
                //$activityKey = "create.{$params['entityId']}:{$params['entityType']}.{$params['entityId']}:{$uid}";
                $activityKey ="create.".$dto->entityId.":".$dto->entityType.".".$dto->entityId.":". $activity->userId;
            }else {
                $activityKey = $activity->activityType . "." . $activity->id . ":" . $dto->entityType . "." . $dto->entityId . ":" . $activity->userId;
            }
            $disbledActivity = $this->getHomePageDisabledEntityTypes();
            if (empty($disbledActivity)) {
                return;
            }else {
                $status = !$this->testHomePageActivityKey($activityKey, $disbledActivity);
                if(!$status){
                    $visibility = decbin($activity->visibility);
                    $bitToChange = strlen($visibility)-1;
                    $visibility[$bitToChange]=0;
                    $visibility = bindec($visibility);
                    $event->setData(array('visibilityChanged' =>$visibility));
                }
            }
        }
    }

}
