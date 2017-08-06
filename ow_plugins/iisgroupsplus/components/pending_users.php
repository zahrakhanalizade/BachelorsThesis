<?php

/**
 * component class.
 *
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iisgroupsplus.classes
 * @since 1.0
 */
class IISGROUPSPLUS_CMP_PendingUsers extends OW_Component
{
    /**
     * Constructor.
     */
    public function __construct($params)
    {
        parent::__construct();
        $groupId = $params;
        $users = GROUPS_BOL_Service::getInstance()->findAllInviteList($groupId);
        $usersInformation = array();
        if($users!=null){
            foreach($users as $user){
                $usersInformation[] = $user->userId;
            }
        }
        $data = array();
        if ( !empty($usersInformation) )
        {
            $data = BOL_AvatarService::getInstance()->getDataForUserAvatars($usersInformation);
        }
        $this->assign("data", $data);
        $this->assign("userIdList", $usersInformation);
    }
}