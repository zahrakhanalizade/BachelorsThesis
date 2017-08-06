<?php


/**
 * iisvideoplus admin action controller
 *
 */
class IISVIDEOPLUS_CTRL_Admin extends ADMIN_CTRL_Abstract
{
    /**
     * @param array $params
     */
    public function index(array $params = array())
    {
        $this->setPageHeading(OW::getLanguage()->text('iisvideoplus', 'admin_settings_heading'));
        $this->setPageTitle(OW::getLanguage()->text('iisvideoplus', 'admin_settings_heading'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
        $config =  OW::getConfig();
        $language = OW::getLanguage();

        $form = new Form('form');
        $maxUploadMaxFilesize = BOL_FileService::getInstance()->getUploadMaxFilesize();

        $this->assign('maxUploadMaxFilesize', $maxUploadMaxFilesize);

        $maxUploadMaxFilesizeValidator = new FloatValidator(0, $maxUploadMaxFilesize);
        $maxUploadMaxFilesizeValidator->setErrorMessage($language->text('iisvideoplus', 'settings_max_upload_size_error'));

        $maxUploadSize = new TextField('max_video_uploadfile_size');
        $maxUploadSize->setLabel($language->text('iisvideoplus', 'input_settings_max_upload_size_label'));
        $maxUploadSize->addValidator($maxUploadMaxFilesizeValidator);
        $form->addElement($maxUploadSize);

        $submit = new Submit('save');
        $form->addElement($submit);
        $this->addForm($form);

        if ( OW::getRequest()->isPost() && $form->isValid($_POST) )
        {
            $data = $form->getValues();
            if ( !$config->configExists('iisvideoplus', 'maximum_video_file_upload') )
            {
                $config->addConfig('iisvideoplus', 'maximum_video_file_upload', round((float)$data['max_video_uploadfile_size'], 2));
            }else {
                $config->saveConfig('iisvideoplus', 'maximum_video_file_upload', round((float)$data['max_video_uploadfile_size'], 2));
            }
            OW::getFeedback()->info(OW::getLanguage()->text('iisvideoplus', 'modified_successfully'));
            $this->redirect();
        }
        if($config->configExists('iisvideoplus', 'maximum_video_file_upload'))
        {
            $maxUploadSize->setValue($config->getValue('iisvideoplus', 'maximum_video_file_upload'));
        }
    }

    public function uninstall()
    {
        if ( isset($_POST['action']) && $_POST['action'] == 'delete_content' )
        {
            OW::getConfig()->saveConfig('iisvideoplus', 'uninstall_inprogress', 1);

            IISVIDEOPLUS_BOL_Service::getInstance()->setMaintenanceMode(true);

            OW::getFeedback()->info(OW::getLanguage()->text('iisvideoplus', 'plugin_set_for_uninstall'));
            $this->redirect();
        }

        $this->setPageHeading(OW::getLanguage()->text('iisvideoplus', 'page_title_uninstall'));
        $this->setPageHeadingIconClass('ow_ic_delete');

        $this->assign('inprogress', (bool) OW::getConfig()->getValue('iisvideoplus', 'uninstall_inprogress'));

        $js = new UTIL_JsGenerator();
        $js->jQueryEvent('#btn-delete-content', 'click', 'if ( !confirm("'.OW::getLanguage()->text('iisvideoplus', 'confirm_delete_video_file').'") ) return false;');

        OW::getDocument()->addOnloadScript($js);
    }
}