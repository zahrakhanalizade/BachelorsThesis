<?php

class IISGROUPSPLUS_CMP_EditItemFloatBox extends OW_Component
{
    public function __construct($id)
    {
        parent::__construct();
        $form = IISGROUPSPLUS_BOL_Service::getInstance()->getItemForm($id);
        $this->addForm($form);
    }
}
