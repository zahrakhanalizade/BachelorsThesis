<?php

/**
 * Copyright (c) 2016, Mohammad Aghaabbasloo
 * All rights reserved.
 */

/**
 * 
 *
 * @author Mohammad Aghaabbasloo
 * @package ow_plugins.iisvideoplus
 * @since 1.0
 */
class IISVIDEOPLUS_BOL_Service
{
    const EVENT_AFTER_ADD = 'videoplus.after_add';
    const ON_VIDEO_VIEW_RENDER='videplus.on.video.view.render';
    const ON_BEFORE_VIDEO_ADD = 'videoplus.on.before.video.add';
    const ON_VIDEO_LIST_VIEW_RENDER = 'videplus.on.video.list.view.render';
    const ON_USER_UNREGISTER  = 'videplus.on.user.unregister';
    private static $LATEST_FRIENDS = 'latest_friends';
    private static $LATEST_MYVIDEO = 'latest_myvideo';
    private static $classInstance;
    private $videoFileName;
    private $videoThumbnailFileName;
    private $imageFile;
    private $oldFileName;
    private $oldImageName;
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    
    private function __construct()
    {
    }

    public function setTtileHeaderListItemVideo( OW_Event $event )
    {
        $params = $event->getParams();
        if (isset($params['listType']) && $params['listType'] == IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS) {
            OW::getDocument()->setTitle(OW::getLanguage()->text('iisvideoplus', 'meta_title_video_add_latest_friends'));
            OW::getDocument()->setDescription(OW::getLanguage()->text('iisvideoplus', 'meta_description_video_latest_friends'));
        }
        if (isset($params['listType']) && $params['listType'] == IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO) {
            OW::getDocument()->setTitle(OW::getLanguage()->text('iisvideoplus', 'meta_title_video_add_latest_myvideo'));
            OW::getDocument()->setDescription(OW::getLanguage()->text('iisvideoplus', 'meta_description_video_latest_myvideo'));
        }
    }

    public function addListTypeToVideo( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['validLists'])){
            $validLists = $params['validLists'];
            if(OW::getUser()->isAuthenticated()) {
                $validLists[] = IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS;
                $validLists[] = IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO;
            }
            $event->setData(array('validLists' => $validLists));
        }
        if(isset($params['menuItems']) && OW::getUser()->isAuthenticated()){
            $menuItems = $params['menuItems'];

            //its for my friends videos
            $item = new BASE_MenuItem();
            $item->setLabel(OW::getLanguage()->text('iisvideoplus', IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS));
            $item->setUrl(OW::getRouter()->urlForRoute('view_list', array('listType' => IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS)));
            $item->setKey(IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS);
            $item->setIconClass('ow_ic_clock');
            $item->setOrder(sizeof($params['menuItems']));
            array_push($menuItems, $item);

            //its for my videos
            $item = new BASE_MenuItem();
            $item->setLabel(OW::getLanguage()->text('iisvideoplus', IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO));
            $item->setUrl(OW::getRouter()->urlForRoute('view_list', array('listType' => IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO)));
            $item->setKey(IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO);
            $item->setIconClass('ow_ic_video');
            $item->setOrder(sizeof($params['menuItems'])+1);
            array_push($menuItems, $item);
            $event->setData(array('menuItems' => $menuItems));
        }
    }

    public function getResultForListItemVideo( OW_Event $event )
    {
        $params = $event->getParams();
        if(isset($params['this']) &&
            isset($params['listtype']) &&
            isset($params['cacheLifeTime']) &&
            isset($params['cacheTags']) &&
            isset($params['first']) &&
            isset($params['limit']) &&
            $params['listtype'] == IISVIDEOPLUS_BOL_Service::$LATEST_FRIENDS){

            $friendsOfCurrentUser = array();
            if(OW::getUser()->isAuthenticated()){
                $friendsOfCurrentUser = OW::getEventManager()->call('plugin.friends.get_friend_list', array('userId' => OW::getUser()->getId()));
            }
            if(class_exists('PRIVACY_BOL_ActionService')) {
                $friendsOfCurrentUserFiltered = array();
                $videoPrivacyKey = 'video_view_video';
                $userPrivacy = PRIVACY_BOL_ActionService::getInstance()->getActionValueListByUserIdList(array($videoPrivacyKey), $friendsOfCurrentUser);
                foreach ($friendsOfCurrentUser as $userFriendId) {
                    if (key_exists($userFriendId, $userPrivacy) && $userPrivacy[$userFriendId][$videoPrivacyKey] != 'only_for_me') {
                        $friendsOfCurrentUserFiltered[] = $userFriendId;
                    }
                }
                $friendsOfCurrentUser = $friendsOfCurrentUserFiltered;
            }
            if(!empty($friendsOfCurrentUser)) {

                $example = new OW_Example();

                $example->andFieldEqual('status', 'approved');
                $example->andFieldInArray('userId', $friendsOfCurrentUser);
                $example->andFieldNotEqual('privacy', 'only_for_me');
                $example->setOrder('`addDatetime` DESC');
                $example->setLimitClause($params['first'], $params['limit']);

                $result = $params['this']->findListByExample($example, $params['cacheLifeTime'], $params['cacheTags']);
                $event->setData(array('result' => $result));
            }
        }
        /*
         * add my list video result
         */
        if(isset($params['this']) &&
            isset($params['listtype']) &&
            isset($params['cacheLifeTime']) &&
            isset($params['cacheTags']) &&
            isset($params['first']) &&
            isset($params['limit']) &&

            $params['listtype'] == IISVIDEOPLUS_BOL_Service::$LATEST_MYVIDEO){

            if(OW::getUser()->isAuthenticated()) {
                $example = new OW_Example();
                $example->andFieldEqual('status', 'approved');
                $example->andFieldEqual('userId', OW::getUser()->getId());
                $example->setOrder('`addDatetime` DESC');
                $example->setLimitClause($params['first'], $params['limit']);
                $result = $params['this']->findListByExample($example, $params['cacheLifeTime'], $params['cacheTags']);
                $event->setData(array('result' => $result));
            }
        }
    }
    /*
 * show video thumb image after video rendered in main page
 * @param OW_Event $event
 */
    public static function onAfterVideoRendered(OW_Event $event)
    {
        $js = UTIL_JsGenerator::newInstance();
        $params = $event->getParams();
        if(isset($params['uniqId'])) {
            $js->addScript('$(".ow_oembed_video_cover", "#" + {$uniqId}).trigger( "click" );', array(
                "uniqId" => $params['uniqId']
            ));
        }
        OW::getDocument()->addOnloadScript($js);
    }


    public function onBeforeVideoUploadFormRenderer(OW_Event $event)
    {
        $this->oldFileName=null;
        $params = $event->getParams();
        if(isset($params['form']) && isset($params['component']) && isset($params['code'])){
            $form = $params['form'];
            $form->addElement($this->addVideoUploader());
            $videoDir= $this->getVideoFileDir($params['code']);
            if(file_exists($videoDir)) {
                if ($form->getElement('code') != null) {
                    $this->oldFileName=$form->getElement('code')->getValue();
                    $videId=$form->getElement('id')->getValue();
                    $video = VIDEO_BOL_ClipService::getInstance()->findClipById($videId);
                    $this->oldImageName=$video->thumbUrl;
                    $form->deleteElement("code");
                    $codeField = new Textarea('code');
                    $codeField->setLabel(OW::getLanguage()->text('video', 'code'));
                    $form->addElement($codeField);
                }
            }
            else{
                $codeValue = $form->getElement('code')->getValue();
                $form->deleteElement("code");
                $codeField = new Textarea('code');
                $codeField->setLabel(OW::getLanguage()->text('video', 'code'));
                $codeField->setValue($codeValue);
                $form->addElement($codeField);
            }
            $params['component']->assign('videoUploadField', true);
            $form->setEnctype(Form::ENCTYPE_MULTYPART_FORMDATA);
        }
        else if(isset($params['form'])){
            $form = $params['form'];
            $form->addElement($this->addVideoUploader());
            if($form->getElement('code')!=null) {
                $form->deleteElement("code");
                $codeField = new Textarea('code');
                $codeField->setLabel(OW::getLanguage()->text('video', 'code'));
                $form->addElement($codeField);
            }

            $form->setEnctype(Form::ENCTYPE_MULTYPART_FORMDATA);
        }
    }
    public function onBeforeVideoUploadComponentRenderer(OW_Event $event)
    {
        $params = $event->getParams();
        if(isset($params['form']) && isset($params['component'])){
            $form = $params['form'];
            if($form->getElement('videoUpload')!=null){
                $params['component']->assign('videoUploadField',true);
            }
        }
    }

    public function addVideoUploader(){
        $videoUpload = new IISVIDEOPLUS_File('videoUpload');
        $videoUpload->setId('videoUpload');
        $videoUpload->setLabel(OW::getLanguage()->text('iisvideoplus', 'create_video_upload_label'));
        $videoUpload->addValidator(new IISVIDEOPLUS_CMP_Validserviceproviders());
        return $videoUpload;
    }

    public function getValue()
    {
        return empty($_FILES[$this->getName()]['tmp_name']) ? null : $_FILES[$this->getName()];
    }

    public function onAfterEntryAdd(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['videoId'])  && isset($params['code'])
            && isset($params['forUpdate']) && $params['forUpdate']==true){
            if(isset($this->oldFileName) && $this->oldFileName!=null && $this->oldFileName!=$params['code']) {
                $videoDir = $this->getVideoFileDir($this->oldFileName);
                if (file_exists($videoDir)) {
                    unlink($videoDir);
                }
            }
            if(isset($this->oldImageName) && $this->oldImageName!=null && $this->oldFileName!=$params['code']) {
                $imageDir = $this->getImageVideoThumbFileDir($this->oldImageName);
                if (file_exists($imageDir)) {
                    unlink($imageDir);
                }
            }
        }
        if(isset($params['videoUpload']) && isset($params['videoId'])){
            $this->saveVideoFile($params['videoUpload'], $params['videoId']);
        }
    }
    protected function saveVideoFile( $postFile )
    {
        $videoFile = $this->getVideoFileDir( $this->videoFileName);
        $tmpDir = OW::getPluginManager()->getPlugin('video')->getPluginFilesDir();
        $tmpVideoFile = $tmpDir . $this->videoFileName;
        if(move_uploaded_file($postFile["tmp_name"], $tmpVideoFile)) {
            try {
                OW::getStorage()->copyFile($tmpVideoFile, $videoFile);
            } catch (Exception $e) {
            }
        }
        unlink($tmpVideoFile);
    }

    public function getVideoFileDir($FileName)
    {
        return OW::getPluginManager()->getPlugin('video')->getUserFilesDir() . $FileName;
    }

    public function getVideoFilePath($FileName)
    {
        return OW::getPluginManager()->getPlugin('video')->getUserFilesUrl() . $FileName;
    }
    public function getImageVideoThumbFileDir($FileName)
    {
        return OW::getPluginManager()->getPlugin('video')->getUserFilesDir() . $FileName;
    }

    public function getImageVideoThumbFilepath($FileName)
    {
        return OW::getPluginManager()->getPlugin('video')->getUserFilesUrl() . $FileName;
    }

    public function onBeforeVideoAdded(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['videoUpload'])){
            $fileName = OW::getUser()->getId() . "_" . UTIL_String::getRandomString(16);
            $this->videoThumbnailFileName = $fileName.".png";
            $fileName = $fileName.'.'.UTIL_File::getExtension($params['videoUpload']['name']);
            $this->videoFileName=$fileName;
            $event->setData(array('fileName'=>$fileName,'newFile'=>true));
        }
        else if(!isset($params['code'])|| $params['code']==null){
            $event->setData(array('fileName'=>$this->oldFileName));
        }
        else if (isset($params['code']) && isset($params['oldCode'])){
            $videoDir = $this->getVideoFileDir($params['oldCode']);
            if (file_exists($videoDir)) {
                unlink($videoDir);
            }
            $videoName = explode('.', $params['oldCode']);
            $imageDir = $this->getImageVideoThumbFileDir($videoName[0].'.png');
            if (file_exists($imageDir)) {
                unlink($imageDir);
            }
        }
    }
    public function onVideoViewRender(OW_Event $event){
        $params = $event->getParams();
        if(isset($params['code']) && isset($params['videoId'])){
            $videoDir= $this->getVideoFileDir($params['code']);
            if(file_exists($videoDir)) {
                $video = VIDEO_BOL_ClipService::getInstance()->findClipById($params['videoId']);
                $videoFile = $this->getVideoFilePath($params['code']);
                $jsDir = OW::getPluginManager()->getPlugin("iisvideoplus")->getStaticJsUrl();
                OW::getDocument()->addScript($jsDir . "mediaelement-and-player.js");
                $cssDir = OW::getPluginManager()->getPlugin("iisvideoplus")->getStaticCssUrl();
                OW::getDocument()->addStyleSheet($cssDir . "mediaelementplayer.css");
                //$script = '$(\'video,audio\').mediaelementplayer(/* Options */);';
                //OW::getDocument()->addOnloadScript($script);
                $this->videoPlayerRenderScript();
                if(!isset($video->thumbUrl) ||  $video->thumbUrl== "") {
                    $this->addCheckThumbnailAndRenderScript($params['videoId']);
                }else{
                    $videoThumbDir= $this->getImageVideoThumbFileDir($video->thumbUrl);
                    if(!file_exists($videoThumbDir)) {
                        $this->addCheckThumbnailAndRenderScript($params['videoId']);
                    }
                }
                $event->setData(array('source' => $videoFile));
            }
        }
    }

    public function videoPlayerRenderScript(){
        $script = "$('video,audio').mediaelementplayer(/* Options */);";
        OW::getDocument()->addOnloadScript($script);
    }

    public function addCheckThumbnailAndRenderScript($videoId){
        $script =
            "
            $(window).ready(function(){
                    checkThumb();
                });
            function checkThumb(){
                    var scale = 0.25;
                    var canvas = document.getElementById('canvas');
                    if(canvas == null){
                        return;
                    }
                    var video = document.getElementById('videoUpload_html5');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    canvasData = canvas.toDataURL(\"image/png\");
                    if(canvasData!='data:,' && canvasData!=null)
                    {
                        canvas.style.display=\"none\";
                        var data = {'videoId': " . json_encode($videoId).", 'canvasData': canvasData};
                       $.ajax({
                            type: 'POST',
                            url: '" . OW::getRouter()->urlFor('IISVIDEOPLUS_CTRL_Videoplus', 'index') . "',
                            data: data,
                            dataType: 'json',
                            success : function(data){
                                if( data.messageType == 'error' ){

                                }
                                else{

                                }
                            },
                            error : function( XMLHttpRequest, textStatus, errorThrown ){

                            }
                        });
                    }
                     else {
                        setTimeout(checkThumb, 2000); //wait 2000ms, then try again
                    }
                }
                ";
        OW::getDocument()->addOnloadScript($script);
    }

    public function onVideoListViewRender(OW_Event $event)
    {
        $params = $event->getParams();
        if (isset($params['clips'])) {
            $clips=$params['clips'];
            $newClips = array();
            foreach ( $clips as $clip ) {
                $video = VIDEO_BOL_ClipService::getInstance()->findClipById($clip['id']);
                $videoThumbDir= $this->getImageVideoThumbFileDir($video->thumbUrl);
                if(file_exists($videoThumbDir)) {
                    $clip['thumb']=$this->getImageVideoThumbFilepath($video->thumbUrl);
                }
                $newClips[]=$clip;
            }
            $event->setData(array('clips' => $newClips));
        }else if(isset($params['clip'])){
            $clip = $params['clip'];
            $video = VIDEO_BOL_ClipService::getInstance()->findClipById($clip->id);
            $videoThumbDir= $this->getImageVideoThumbFileDir($this->videoThumbnailFileName);
            if(file_exists($videoThumbDir)) {
                $clip->thumbUrl=$this->getImageVideoThumbFilepath($video->thumbUrl);
            }
            $event->setData(array('clip' => $clip));
        }else if(isset($params['getThumb']) && $params['getThumb']==true && isset($params['clipId'])){
            $video = VIDEO_BOL_ClipService::getInstance()->findClipById($params['clipId']);
            $videoThumbDir= $this->getImageVideoThumbFileDir($video->thumbUrl);
            if(file_exists($videoThumbDir)) {
                $thumbUrl=$this->getImageVideoThumbFilepath($video->thumbUrl);
                $event->setData(array('thumbUrl' => $thumbUrl));
            }
        }else if(isset($params['forNewsFeed']) && $params['forNewsFeed']==true && isset($params['videoId'])){
            $video = VIDEO_BOL_ClipService::getInstance()->findClipById($params['videoId']);
            $eventVideo = new OW_Event('videplus.on.video.view.render', array('code'=>$video->code,'videoId'=>$video->id));
            OW::getEventManager()->trigger($eventVideo);
            if(isset($eventVideo->getData()['source'])) {
                $config = OW::getConfig();
                $playerWidth = $config->getValue('video', 'player_width');
                $playerHeight = $config->getValue('video', 'player_height');
                $event->setData(array('width' => $playerWidth,'height'=> $playerHeight,'source'=>$eventVideo->getData()['source']));
            }
        }
    }
    public function setMaintenanceMode( $mode = true )
    {
        $config = OW::getConfig();

        if ( $mode )
        {
            $state = (int) $config->getValue('base', 'maintenance');
            $config->saveConfig('iisvideoplus', 'maintenance_mode_state', $state);
            OW::getApplication()->setMaintenanceMode($mode);
        }
        else
        {
            $state = (int) $config->getValue('iisvideoplus', 'maintenance_mode_state');
            $config->saveConfig('base', 'maintenance', $state);
        }
    }

    public function deleteVideoFileByCode(OW_Event $event)
    {
        $params = $event->getParams();
        if (isset($params['code'])) {
            $videoDir = $this->getVideoFileDir($params['code']);
            if (file_exists($videoDir)) {
                unlink($videoDir);
            }
            $videoName = explode('.', $params['code']);
            $imageDir = $this->getImageVideoThumbFileDir($videoName[0].'.png');
            if (file_exists($imageDir)) {
                unlink($imageDir);
            }
        }
    }
    public function deleteAllVideoFiles($limit){
        $files = glob(OW::getPluginManager()->getPlugin('video')->getUserFilesDir().'/*');
        $videoDao = VIDEO_BOL_ClipDao::getInstance();
        $videoService = VIDEO_BOL_ClipService::getInstance();
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                $file = basename($file);
                $example = new OW_Example();
                $example->andFieldEqual('code', $file);
                $res=$videoDao->findIdByExample($example);
                if (count($res) != 0) {
                    $videoService->deleteClip($res);
                }
                unlink($this->getVideoFileDir($file)); // delete file
            }
        }
        return true;
    }
}

class IISVIDEOPLUS_File extends FileField
{

    public function getValue()
    {
        return empty($_FILES[$this->getName()]['tmp_name']) ? null : $_FILES[$this->getName()];
    }
}

