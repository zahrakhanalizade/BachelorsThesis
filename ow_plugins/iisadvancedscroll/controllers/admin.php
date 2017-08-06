<?php

class IISADVANCEDSCROLL_CTRL_Admin extends ADMIN_CTRL_Abstract
{

    public function __construct()
    {
        parent::__construct();

        if ( OW::getRequest()->isAjax() )
        {
            return;
        }

        $lang = OW::getLanguage();

        $this->setPageHeading($lang->text('iisadvancedscroll', 'admin_settings_title'));
        $this->setPageTitle($lang->text('iisadvancedscroll', 'admin_settings_title'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
    }

    public function settings()
    {
        $adminForm = new Form('adminForm');      

        $lang = OW::getLanguage();
        $config = OW::getConfig();
        
        
        $selectField = new Selectbox("Easing");
        $selectField->setLabel($lang->text("iisadvancedscroll", "admin_selectbox_field_label"));
        $selectField->setRequired(true);
        $selectField->setInvitation($lang->text('iisadvancedscroll', 'admin_selectbox_value'));
        $selectField->setValue($config->getValue('iisadvancedscroll', 'Easing'));
        $selectField->setOptions(array(
            "linear" => "linear",
            "swing" => "swing",
            "easeInQuad" => "easeInQuad",
            "easeOutQuad" => "easeOutQuad",
            "easeInOutQuad" => "easeInOutQuad",
            "easeInCubic" => "easeInCubic",
            "easeOutCubic" => "easeOutCubic",
            "easeInOutCubic" => "easeInOutCubic",
            "easeInQuart" => "easeInQuart",
            "easeOutQuart" => "easeOutQuart",
            "easeInOutQuart" => "easeInOutQuart",
            "easeInQuint" => "easeInQuint",
            "easeOutQuint" => "easeOutQuint",
            "easeInOutQuint" => "easeInOutQuint",
            "easeInExpo" => "easeInExpo",
            "easeOutExpo" => "easeOutExpo",
            "easeInOutExpo" => "easeInOutExpo",
            "easeInSine" => "easeInSine",
            "easeOutSine" => "easeOutSine",
            "easeInOutSine" => "easeInOutSine",
            "easeInCirc" => "easeInCirc",
            "easeOutCirc" => "easeOutCirc",
            "easeInOutCirc" => "easeInOutCirc",
            "easeInElastic" => "easeInElastic",
            "easeOutElastic" => "easeOutElastic",
            "easeInOutElastic" => "easeInOutElastic",
            "easeInBack" => "easeInBack",
            "easeOutBack" => "easeOutBack",
            "easeInOutBack" => "easeInOutBack",
            "easeInBounce" => "easeInBounce",
            "easeOutBounce" => "easeOutBounce",
            "easeInOutBounce" => "easeInOutBounce"
            
        ));
        
        $adminForm->addElement($selectField);
        
        $formElements = array('EaseSpeed','InDelay','OutDelay','bottom','right','left');
        foreach ($formElements as $formElement)
        {
			$element = new TextField($formElement);
            $element->setRequired(true);
            if ($formElement == 'left' || $formElement == 'right'){
                $element->setRequired(false);
            }
            if ($formElement != 'left')
            {
                $validator = new IntValidator(1);
                $validator->setErrorMessage($lang->text('iisadvancedscroll', 'admin_invalid_number_error'));
                $element->addValidator($validator);
			}
			if ($formElement !='EaseSpeed' and $formElement != 'InDelay' and $formElement != 'OutDelay')
			{
				$element->setDescription($lang->text('iisadvancedscroll', "admin_desc_".strtolower($formElement)));
			}
            $element->setLabel($lang->text('iisadvancedscroll', "admin_".strtolower($formElement)));
            $element->setValue($config->getValue('iisadvancedscroll', $formElement));
            $adminForm->addElement($element);
		}
        
        
        $field = new RadioField('adminarea');
        $field->setLabel($lang->text('iisadvancedscroll','admin_adminarea'));
        $field->setRequired(true);
        $field->setColumnCount(1);
        $field->setOptions(array(
        'enable'=> $lang->text('iisadvancedscroll','admin_adminarea_enable'),
        'disable'=>$lang->text('iisadvancedscroll','admin_adminarea_disable')
                 ));
        $field->setValue($config->getValue('iisadvancedscroll', 'adminarea'));
        $adminForm->addElement($field);
        


        $element = new Submit('saveEaseSettings');
        $element->setValue($lang->text('iisadvancedscroll', 'admin_save_settings'));
        $adminForm->addElement($element);

        if ( OW::getRequest()->isPost() )
        {
           if ( $adminForm->isValid($_POST) )
           {
              $values = $adminForm->getValues();
              if (!is_numeric($values['left']) && !is_numeric($values['right']))
              {
				  OW::getFeedback()->error($lang->text('iisadvancedscroll', 'admin_invalid_number_error'));
			  }
			  else
			  {
                  if(!is_numeric($values['left'])){
                      $values['left'] = 0;
                  }
                  if(!is_numeric($values['right'])){
                      $values['right'] = 0;
                  }
                  $config = OW::getConfig();
                  $config->saveConfig('iisadvancedscroll', 'EaseSpeed', $values['EaseSpeed']);
                  $config->saveConfig('iisadvancedscroll', 'Easing', $values['Easing']);
                  $config->saveConfig('iisadvancedscroll', 'InDelay', $values['InDelay']);
                  $config->saveConfig('iisadvancedscroll', 'OutDelay', $values['OutDelay']);
                  $config->saveConfig('iisadvancedscroll', 'bottom', $values['bottom']);
                  $config->saveConfig('iisadvancedscroll', 'right', $values['right']);
                  $config->saveConfig('iisadvancedscroll', 'left', $values['left']);
                  $config->saveConfig('iisadvancedscroll', 'adminarea', $values['adminarea']);


                  OW::getFeedback()->info($lang->text('iisadvancedscroll', 'user_save_success'));
		     }
           }
        }

       $this->addForm($adminForm);
   } 
}
