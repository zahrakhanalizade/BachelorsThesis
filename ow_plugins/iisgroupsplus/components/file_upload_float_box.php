<?php

class IISGROUPSPLUS_CMP_FileUploadFloatBox extends OW_Component
{
    public function __construct($iconClass, $groupId)
    {
        $isUserInGroup = GROUPS_BOL_Service::getInstance()->findUser($groupId, OW::getUser()->getId());
        if (!$isUserInGroup )
        {
            throw new Redirect404Exception();
        }
        parent::__construct();
        $form = IISGROUPSPLUS_BOL_Service::getInstance()->getUploadFileForm($groupId);
        $this->assign('loaderIcon',$this->getIconUrl('LoaderIcon'));
        $this->addForm($form);
    }

    public function getIconUrl($name){
        return OW::getPluginManager()->getPlugin('iisgroupsplus')->getStaticUrl(). 'images/'.$name.'.gif';
    }
}


