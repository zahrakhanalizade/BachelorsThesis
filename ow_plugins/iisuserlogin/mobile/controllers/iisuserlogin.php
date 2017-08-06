<?php

class IISUSERLOGIN_MCTRL_Iisuserlogin extends OW_MobileActionController
{

    public function index($params)
    {
        OW::getDocument()->setHeading(OW::getLanguage()->text('iisuserlogin','bottom_menu_item'));
        if(!OW::getUser()->isAuthenticated()){
            throw new Redirect404Exception();
        }
        $service = IISUSERLOGIN_BOL_Service::getInstance();
        $items = array();
        $details = $service->getUserLoginDetails(OW::getUser()->getId());
        if($details != null) {
            foreach ($details as $detail) {
                $items[] = array(
                    'time' => UTIL_DateTime::formatSimpleDate($detail->time),
                    'browser' => $detail->browser,
                    'ip' => $detail->ip
                );
            }
        }
        $this->assign("items", $items);
    }
}