<?php

class IISPROFILEMANAGEMENT_BOL_Service
{
    private static $classInstance;

    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct()
    {
    }

    public function onBeforeDocumentRender()
    {
        $jsDir = OW::getPluginManager()->getPlugin('iisprofilemanagement')->getStaticJsUrl();
        OW::getDocument()->addScript($jsDir.'iisprofilemanagement.js');
    }

}