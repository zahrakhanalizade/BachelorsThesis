<?php

/**
 * Copyright (c) 2016, Yaser Alimardany
 * All rights reserved.
 */

/**
 *
 * @author Yaser Alimardany <yaser.alimardany@gmail.com>
 * @package ow_plugins.iissecurityessentials.controllers
 * @since 1.0
 */
class IISSECURITYESSENTIALS_CTRL_Admin extends ADMIN_CTRL_Abstract
{
    public function index( array $params = array() )
    {
        $language = OW::getLanguage();
        $this->setPageHeading($language->text('iissecurityessentials', 'admin_page_heading'));
        $this->setPageTitle($language->text('iissecurityessentials', 'admin_page_title'));
        $sectionsInformation = IISSECURITYESSENTIALS_BOL_Service::getInstance()->getSections($params['currentSection']);
        $sections = $sectionsInformation['sections'];
        $currentSection = $sectionsInformation['currentSection'];
        $this->assign('sections',$sections);
        $this->assign('currentSection',$currentSection);
        $config = OW::getConfig();
        $configs = $config->getValues('iissecurityessentials');

        if($currentSection==1) {

            $form = new Form('settings');
            $form->setAjax();
            $form->setAjaxResetOnSuccess(false);
            $form->setAction(OW::getRouter()->urlForRoute('iissecurityessentials.admin'));
            $form->bindJsFunction(Form::BIND_SUCCESS, 'function(data){if(data.result){OW.info("' . OW::getLanguage()->text("iissecurityessentials", "settings_successfuly_saved") . '");}else{OW.error("Parser error");}}');

            $idleTime = new TextField('idleTime');
            $idleTime->setLabel($language->text('iissecurityessentials','idle_time_label'));
            $idleTime->setRequired();
            $idleTime->addValidator(new IntValidator(1));
            $idleTime->setValue($configs['idleTime']);
            $form->addElement($idleTime);

            $viewUserCommentWidget = new CheckboxField('viewUserCommentWidget');
            $viewUserCommentWidget->setLabel(OW::getLanguage()->text("iissecurityessentials", "view_user_comment_widget"));
            $viewUserCommentWidget->setValue($configs['viewUserCommentWidget']);
            $form->addElement($viewUserCommentWidget);

            $approveUserAfterEditProfile = new CheckboxField('approveUserAfterEditProfile');
            $approveUserAfterEditProfile->setLabel(OW::getLanguage()->text("iissecurityessentials", "approve_user_after_edit_profile_label"));
            $approveUserAfterEditProfile->setValue($configs['approveUserAfterEditProfile']);
            $form->addElement($approveUserAfterEditProfile);

            $submit = new Submit('save');
            $form->addElement($submit);

            $this->addForm($form);

            if (OW::getRequest()->isAjax()) {
                if ($form->isValid($_POST)) {
                    $viewUserCommentWidgetValue = $form->getElement('viewUserCommentWidget')->getValue();
                    $config->saveConfig('iissecurityessentials', 'viewUserCommentWidget', $viewUserCommentWidgetValue);
                    $this->updateUserCommentWidget($viewUserCommentWidgetValue);
                    $config->saveConfig('iissecurityessentials', 'idleTime', $form->getElement('idleTime')->getValue());
                    $config->saveConfig('iissecurityessentials', 'approveUserAfterEditProfile', $form->getElement('approveUserAfterEditProfile')->getValue());
                    exit(json_encode(array('result' => true)));
                }
            }
        }else if($currentSection==2){
            if(class_exists('PRIVACY_BOL_ActionService')) {

                $privacyForm = new Form('privacyForm');
                $privacyForm->setAjax(false);
                $privacyForm->setAction(OW::getRouter()->urlForRoute('iissecurityessentials.admin.currentSection', array('currentSection' => $currentSection)));
                $actionSubmit = new Submit('submit');
                $actionSubmit->addAttribute('class', 'ow_button ow_ic_save');
                $privacyForm->addElement($actionSubmit);

                $actionValuesEvent= new BASE_CLASS_EventCollector( PRIVACY_BOL_ActionService::EVENT_GET_PRIVACY_LIST );
                OW::getEventManager()->trigger($actionValuesEvent);
                $data = $actionValuesEvent->getData();

                $actionValuesInfo = empty($data) ? array() : $data;
                usort($actionValuesInfo, array($this, "sortPrivacyOptions"));

                $optionsList = array();
                // -- sort action values
                foreach( $actionValuesInfo as $value )
                {
                    $optionsList[$value['key']] = $value['label'];
                }

                $resultList = array();
                $actionList = PRIVACY_BOL_ActionService::getInstance()->findAllAction();

                foreach ($actionList as $action) {

                    /* @var $action PRIVACY_CLASS_Action */
                    if ( !empty( $action->label ) )
                    {
                        $formElement = new Selectbox($action->key);
                        $formElement->setLabel($action->label);
                        $formElement->setOptions($optionsList);
                        $formElement->setRequired(true);

                        $formElement->setDescription('');
                        $privacyValue = OW::getConfig()->getValue('iissecurityessentials',$action->key);
                        if(!isset($privacyValue)){
                            $formElement->setDescription(OW::getLanguage()->text("iissecurityessentials", "privacy_value_empty"));
                            $formElement->setValue(null);
                            $formElement->setHasInvitation(true);
                        }else{
                            $formElement->setValue($privacyValue);
                            $formElement->setHasInvitation(false);
                        }

                        $privacyForm->addElement($formElement);

                        $resultList[$action->key] = $action->key;
                    }
                }

                $this->addForm($privacyForm);
                $this->assign('actionList', $resultList);

                if (OW::getRequest()->isPost()) {
                    if ($privacyForm->isValid($_POST)) {
                        $values = $privacyForm->getValues();
                        foreach ($actionList as $action) {
                            $value = $values[$action->key];
                            if ($value != null) {
                                $oldValue = OW::getConfig()->getValue('iissecurityessentials', $action->key);
                                if ($oldValue == null) {
                                    OW::getConfig()->addConfig('iissecurityessentials', $action->key, $value);
                                } else {
                                    OW::getConfig()->saveConfig('iissecurityessentials', $action->key, $value);
                                }
                            }
                        }
                        OW::getFeedback()->info(OW::getLanguage()->text("iissecurityessentials", "settings_successfuly_saved"));
                        $this->redirect();
                    }
                }
            }else{
                $this->assign('plugin_privacy_not_exist_description', OW::getLanguage()->text("iissecurityessentials", "plugin_privacy_not_exist_description"));
            }
        }else if($currentSection==3){
            $language = OW::getLanguage();

            $this->setPageHeading($language->text('iissecurityessentials', 'admin_page_heading'));
            $this->setPageTitle($language->text('iissecurityessentials', 'admin_page_title'));
            $this->setPageHeadingIconClass('ow_ic_comment');

            $types = IISSECURITYESSENTIALS_BOL_Service::getInstance()->getActionTypes();

            $form = new IISSECURITYESSENTIALS_CustomizationForm();
            $this->addForm($form);

            $processTypes = array();

            foreach ( $types as $type )
            {
                $field = new CheckboxField($type['activity']);
                $field->setValue($type['active']);
                $form->addElement($field);

                $processTypes[] = $type['activity'];
            }

            if ( OW::getRequest()->isPost() )
            {
                $result = $form->process($_POST, $processTypes);
                if ( $result )
                {
                    OW::getFeedback()->info($language->text('iissecurityessentials', 'customization_changed'));
                }
                else
                {
                    OW::getFeedback()->warning($language->text('iissecurityessentials', 'customization_not_changed'));
                }

                $this->redirect();
            }

            $this->assign('types', $types);
        }
    }

    public function updateUserCommentWidget($enable){
        $widgetService = BOL_ComponentAdminService::getInstance();
        if($enable){
            $widget = $widgetService->addWidget('BASE_CMP_ProfileWallWidget');
            $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_PROFILE);
        }else{
            BOL_ComponentAdminService::getInstance()->deleteWidget('BASE_CMP_ProfileWallWidget');
        }
    }
}

class IISSECURITYESSENTIALS_CustomizationForm extends Form
{

    public function __construct(  )
    {
        parent::__construct('homePageCustomizationForm');

        $language = OW::getLanguage();

        $btn = new Submit('save');
        $btn->setValue($language->text('iissecurityessentials', 'save_customization_btn_label'));
        $this->addElement($btn);
    }

    public function process( $data, $types )
    {
        $config = OW::getConfig();
        $changed = false;
        if ( !$config->configExists('iissecurityessentials', 'disabled_home_page_action_types') )
        {
            $config->addConfig('iissecurityessentials', 'disabled_home_page_action_types', '');
        }
        $configValue = json_decode(OW::getConfig()->getValue('iissecurityessentials', 'disabled_home_page_action_types'), true);
        $typesToSave = array();

        foreach ( $types as $type )
        {
            $typesToSave[$type] = isset($data[$type]);
            if ( !isset($configValue[$type]) || $configValue[$type] !== $typesToSave[$type] )
            {
                $changed = true;
            }
        }

        $jsonValue = json_encode($typesToSave);
        OW::getConfig()->saveConfig('iissecurityessentials', 'disabled_home_page_action_types', $jsonValue);

        return $changed;
    }
}
