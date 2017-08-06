<?php



/**
 * iisgroupsplus
 *
 * @author Mohammad Agha Abbasloo
 * @package ow_plugins.iisgroupsplus.controllers
 * @since 1.0
 */
class IISGROUPSPLUS_MCTRL_Groups extends OW_MobileActionController
{
    /**
     *
     * @var IISGROUPSPLUS_BOL_Service
     */
    private $service;

    public function __construct()
    {
        $this->service = IISGROUPSPLUS_BOL_Service::getInstance();

    }

    public function deleteUserAsManager( $params )
    {
        if ( empty($params['groupId']) || empty($params['userId']) )
        {
            throw new Redirect404Exception();
        }

        if ( !OW::getUser()->isAuthenticated() )
        {
            throw new AuthenticateException();
        }

        $groupDto = GROUPS_BOL_Service::getInstance()->findGroupById($params['groupId']);

        if ( $groupDto === null )
        {
            throw new Redirect404Exception();
        }

        $groupId = (int) $groupDto->id;
        $userId = $params['userId'];

        $this->service->deleteUserManager($groupId, $userId);

        OW::getFeedback()->info(OW::getLanguage()->text('iisgroupsplus', 'delete_user_as_manager_success_message'));

        $redirectUri = urldecode($_GET['redirectUri']);
        $this->redirect(OW_URL_HOME . $redirectUri);
    }

    public function addUserAsManager( $params )
    {
        if ( empty($params['groupId']) || empty($params['userId']) )
        {
            throw new Redirect404Exception();
        }

        if ( !OW::getUser()->isAuthenticated() )
        {
            throw new AuthenticateException();
        }

        $groupDto = GROUPS_BOL_Service::getInstance()->findGroupById($params['groupId']);

        if ( $groupDto === null )
        {
            throw new Redirect404Exception();
        }

        $groupId = (int) $groupDto->id;
        $userId = $params['userId'];

        $this->service->addUserAsManager($groupId, $userId);

        OW::getFeedback()->info(OW::getLanguage()->text('iisgroupsplus', 'add_user_as_manager_success_message'));

        $redirectUri = urldecode($_GET['redirectUri']);
        $this->redirect(OW_URL_HOME . $redirectUri);
    }
    public function fileList( $params )
    {

        $groupId = (int) $params['groupId'];
        $groupDto = GROUPS_BOL_Service::getInstance()->findGroupById($groupId);

        if ( $groupDto === null )
        {
            throw new Redirect404Exception();
        }
        $language = OW::getLanguage();

        if ( !GROUPS_BOL_Service::getInstance()->isCurrentUserCanView($groupDto) )
        {
            if ( $groupDto->status != GROUPS_BOL_Group::STATUS_ACTIVE )
            {
                throw new Redirect403Exception();
            }

            $this->assign('permissionMessage', $language->text('groups', 'view_no_permission'));

            return;
        }
        if ( $groupDto->whoCanView == GROUPS_BOL_Service::WCV_INVITE && !OW::getUser()->isAuthorized('groups') )
        {
            if ( !OW::getUser()->isAuthenticated() )
            {
                $this->redirect(OW::getRouter()->urlForRoute('groups-private-group', array(
                    'groupId' => $groupDto->id
                )));
            }

            $invite = GROUPS_BOL_Service::getInstance()->findInvite($groupDto->id, OW::getUser()->getId());
            $user = GROUPS_BOL_Service::getInstance()->findUser($groupDto->id, OW::getUser()->getId());

            if ( $groupDto->whoCanView == GROUPS_BOL_Service::WCV_INVITE && $invite === null && $user === null )
            {
                $this->redirect(OW::getRouter()->urlForRoute('groups-private-group', array(
                    'groupId' => $groupDto->id
                )));
            }
        }

        $page = (!empty($_GET['page']) && intval($_GET['page']) > 0 ) ? $_GET['page'] : 1;
        $perPage = 10;
        $first = ($page - 1) * $perPage;
        $count = $perPage;

        $dtoList = $this->service->findFileList($groupId, $first, $count);
        $listCount = $this->service->findFileListCount($groupId);
        $paging = new BASE_CMP_PagingMobile($page, ceil($listCount / $perPage), 2);
        $this->addComponent('paging',$paging);
        $filelist = array();
        $attachmentIds = array();
        $deleteUrls = array();
        foreach ( $dtoList as $item ) {
            $sentenceCorrected = false;
            if (mb_strlen($item->getOrigFileName()) > 100) {
                $sentence = $item->getOrigFileName();
                $event = OW::getEventManager()->trigger(new OW_Event(IISEventManager::PARTIAL_HALF_SPACE_CODE_DISPLAY_CORRECTION, array('sentence' => $sentence, 'trimLength' => 100)));
                if (isset($event->getData()['correctedSentence'])) {
                    $sentence = $event->getData()['correctedSentence'];
                    $sentenceCorrected = true;
                }
                $event = OW::getEventManager()->trigger(new OW_Event(IISEventManager::PARTIAL_SPACE_CODE_DISPLAY_CORRECTION, array('sentence' => $sentence, 'trimLength' => 100)));
                if (isset($event->getData()['correctedSentence'])) {
                    $sentence = $event->getData()['correctedSentence'];
                    $sentenceCorrected = true;
                }
            }
            if ($sentenceCorrected) {
                $fileName = $sentence . '...';
            } else {
                $fileName = UTIL_String::truncate($item->getOrigFileName(), 100, '...');
            }
            $canEdit=false;
            if ( GROUPS_BOL_Service::getInstance()->isCurrentUserCanEdit($groupDto) )
            {
                $this->assign("canEdit", true);
                $canEdit = true;
            }
            $fileNameArr = explode('.', $item->fileName);
            $fileNameExt = end($fileNameArr);
            $filelist[$item->id]['fileUrl'] = $this->getAttachmentUrl($item->fileName);
            $filelist[$item->id]['iconUrl'] = $this->getProperIcon(strtolower($fileNameExt));
            $filelist[$item->id]['truncatedFileName'] = $fileName;
            $filelist[$item->id]['fileName'] = $item->getOrigFileName();
            $filelist[$item->id]['createdDate'] = $item->addStamp;
            $filelist[$item->id]['userName'] = BOL_UserService::getInstance()->getDisplayName($item->getUserId());
            $filelist[$item->id]['name'] = $item->id;
            if ($item->userId == OW::getUser()->getId() || $canEdit) {
                $deleteUrls[$item->id] = OW::getRouter()->urlForRoute('iisgroupsplus.deleteFile', array('attachmentId' => $item->id, 'groupId' => $groupId));
            }
        }

        $showAdd=false;
        if(OW::getUser()->isAuthenticated()){
            $isUserInGroup = GROUPS_BOL_Service::getInstance()->findUser($groupId, OW::getUser()->getId());
            if($isUserInGroup){
                $showAdd=true;
            }
        }


        $this->assign("showAdd", $showAdd);
        $this->assign("fileList", $filelist);
        $this->assign("attachmentIds", $attachmentIds);
        $this->assign('deleteUrls', $deleteUrls);
        $plugin = OW::getPluginManager()->getPlugin('iisgroupsplus');
        OW::getDocument()->addScript($plugin->getStaticJsUrl() . 'iisgroupsplus.js');

        $this->assign("groupId", $groupId);
        $this->assign('backUrl',OW::getRouter()->urlForRoute('groups-view' , array('groupId'=>$groupId)));

        $params = array(
            "sectionKey" => "iisgroupsplus",
            "entityKey" => "groupFiles",
            "title" => "iisgroupsplus+meta_title_group_files",
            "description" => "iisgroupsplus+meta_desc_group_files",
            "keywords" => "iisgroupsplus+meta_keywords_group_files",
            "vars" => array( "group_title" => $groupDto->title )
        );

        OW::getEventManager()->trigger(new OW_Event("base.provide_page_meta_info", $params));
    }
    public function getIconUrl($name){
        return OW::getPluginManager()->getPlugin('iisgroupsplus')->getStaticUrl(). 'images/'.$name.'.png';
    }

    public function getAttachmentUrl($name)
    {
        return OW::getStorage()->getFileUrl(OW::getPluginManager()->getPlugin('base')->getUserFilesDir() . 'attachments') . '/'.$name;
    }

    public function getAttachmentDir($name)
    {
        return OW::getPluginManager()->getPlugin('base')->getUserFilesDir() . 'attachments' . DS .$name ;
    }
    public function getProperIcon($ext){
        $videoFormats = array('mov','mkv','mp4','avi','flv','ogg','mpg','mpeg');

        $wordFormats = array('docx','doc','docm','dotx','dotm');

        $excelFormats = array('xlsx','xls','xlsm');

        $zipFormats = array('zip','rar');

        $imageFormats =array('jpg','jpeg','gif','tiff','png');

        if(in_array($ext,$videoFormats)){
            return $this->getIconUrl('videoIcon');
        }
        else if(in_array($ext,$wordFormats)){
            return $this->getIconUrl('wordIcon');
        }
        else if(in_array($ext,$excelFormats)){
            return $this->getIconUrl('excelIcon');
        }
        else if(in_array($ext,$zipFormats)){
            return $this->getIconUrl('zipIcon');
        }
        else if(in_array($ext,$imageFormats)){
            return $this->getIconUrl('imageIcon');
        }
        else if(strcmp($ext,'pdf')==0){
            return $this->getIconUrl('pdfIcon');
        }
        else if(strcmp($ext,'txt')==0){
            return $this->getIconUrl('txtIcon');
        }
        else{
            return $this->getIconUrl('fileIcon');
        }
    }

    public function addFile($params)
    {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }
        $groupId = (int) $params['groupId'];

        if ( $groupId<=0  )
        {
            throw new Redirect404Exception();
        }

        $form = $this->service->getUploadFileForm($groupId);
        if (OW::getRequest()->isPost() && $form->isValid($_POST)) {
            if (!empty($_FILES)) {
                $resultArr = array('result' => false, 'message' => 'General error');
                $bundle = uniqid();

                $pluginKey = 'iisgroupsplus';
                $item = $_FILES['fileUpload'];
                if(isset($_POST['name']) && $_POST['name']!=""){
                    $item['name'] = $_POST['name'].'.'.end(explode('.',$item['name'] ));
                }
                try {
                    $dtoArr = BOL_AttachmentService::getInstance()->processUploadedFile($pluginKey, $item, $bundle);
                    OW::getEventManager()->call('base.attachment_save_image', array('uid' => $bundle, 'pluginKey' => $pluginKey));
                    $resultArr['result'] = true;
                    $resultArr['url'] = $dtoArr['url'];
                    $attachmentId = $dtoArr['dto']->id;
                    $fileId = $this->service->addFileForGroup($groupId,$attachmentId);
                    $groupService = GROUPS_BOL_Service::getInstance();
                    $group = $groupService->findGroupById($groupId);
                    $url = $groupService->getGroupUrl($group);
                    $data = array(
                        'time' => time(),
                        'string' => array(
                            "key" => 'iisgroupsplus+feed_add_file_string',
                            "vars" => array(
                                'groupTitle' => $group->title,
                                'groupUrl' => $url,
                                'fileUrl' => $this->getAttachmentUrl($dtoArr['dto']->fileName),
                                'fileName' => $dtoArr['dto']->origFileName
                            )
                        ),
                        'view' => array(
                            'iconClass' => 'ow_ic_add'
                        ),
                        'data' => array(
                            'fileAddId' => $fileId
                        )
                    );

                    $event = new OW_Event('feed.action', array(
                        'feedType' => 'groups',
                        'feedId' => $group->id,
                        'entityType' => 'groups-add-file',
                        'entityId' => $fileId,
                        'pluginKey' => 'groups',
                        'userId' => OW::getUser()->getId(),
                        'postOnUserFeed' => false
                    ), $data);

                    OW::getEventManager()->trigger($event);

                    /*
                     * send notification to group members
                    */
                    $userId = OW::getUser()->getId();
                    $avatars = BOL_AvatarService::getInstance()->getDataForUserAvatars(array($userId));
                    $avatar = $avatars[$userId];
                    $userUrl = BOL_UserService::getInstance()->getUserUrl($userId);
                    $notificationParams = array(
                        'pluginKey' => 'groups',
                        'action' => 'groups-add-file',
                        'entityType' => 'groups-add-file',
                        'entityId' => $fileId,
                        'userId' => null,
                        'time' => time()
                    );

                    $notificationData = array(
                        'string' => array(
                            "key" => 'iisgroupsplus+notif_add_file_string',
                            "vars" => array(
                                'groupTitle' => $group->title,
                                'groupUrl' => $url,
                                'userName' => BOL_UserService::getInstance()->getDisplayName($userId),
                                'fileUrl' => $this->getAttachmentUrl($dtoArr['dto']->fileName),
                                'fileName' => $dtoArr['dto']->origFileName,
                                'userUrl' => $userUrl
                            )
                        ),
                        'avatar' => $avatar,
                        'content' => '',
                        'url' => $this->getAttachmentUrl($dtoArr['dto']->fileName)
                    );

                    $userIds = GROUPS_BOL_Service::getInstance()->findGroupUserIdList($group->id);

                    foreach ( $userIds as $uid )
                    {
                        if ( $uid == OW::getUser()->getId() )
                        {
                            continue;
                        }

                        $notificationParams['userId'] = $uid;

                        $event = new OW_Event('notifications.add', $notificationParams, $notificationData);
                        OW::getEventManager()->trigger($event);
                    }
                } catch (Exception $e) {
                    $resultArr['message'] = $e->getMessage();
                    OW::getFeedback()->error($resultArr['message']);
                }
            }

            exit();
        }
    }

    public function deleteFile($params){
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }
        $groupId = $params['groupId'];
        $attachmentId = $params['attachmentId'];
        if ( !isset($groupId)  || !isset($attachmentId))
        {
            throw new Redirect404Exception();
        }
        $groupDto = GROUPS_BOL_Service::getInstance()->findGroupById($groupId);
          if(!$groupDto) {
              throw new Redirect404Exception();
          }
        $canEdit=false;
        if (GROUPS_BOL_Service::getInstance()->isCurrentUserCanEdit($groupDto) )
        {
            $canEdit = true;
        }

        $attachment = BOL_AttachmentDao::getInstance()->findById($attachmentId);
        if ($attachment->userId != OW::getUser()->getId() && !$canEdit) {
            throw new Redirect404Exception();
        }
        $isUserInGroup = GROUPS_BOL_Service::getInstance()->findUser($groupId, OW::getUser()->getId());
        if(!$isUserInGroup){
            throw new Redirect404Exception();
        }
        try {
            $fileId = $this->service->findFileIdByAidAndGid($groupId, $attachmentId);
            $this->service->deleteFileForGroup($groupId, $attachmentId);
            BOL_AttachmentService::getInstance()->deleteAttachmentById($attachmentId);
            OW::getEventManager()->call("feed.delete_item", array(
                'entityType' => 'groups-add-file',
                'entityId' => $fileId
            ));
            OW::getEventManager()->call('notifications.remove', array(
                'entityType' => 'groups-add-file',
                'entityId' => $fileId
            ));
        }
        catch (exception $e){

        }
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=null){
            $this->redirect($_SERVER['HTTP_REFERER']);
        }else{
            $this->redirect(OW::getRouter()->urlForRoute('groups-view' , array('groupId'=>$groupId)));
        }

    }

}
