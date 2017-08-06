<?php

/**
 * @author Issa Annamoradnejad <i.moradnejad@gmail.com>
 * @package ow_plugins.iishashtag
 * @since 1.0
 */
class IISHASHTAG_CLASS_EventHandler
{
    private static $classInstance;

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

    public function genericInit()
    {
        $service = IISHASHTAG_BOL_Service::getInstance();
        OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_DOCUMENT_RENDER, array($this, 'onBeforeDocumentRender'));
        //OW::getEventManager()->bind( OW_EventManager::ON_AFTER_ROUTE, array($this, 'onBeforeDocumentRender') );

        //new content added
        OW::getEventManager()->bind('feed.after_comment_add', array($service, 'onAddComment'));
        OW::getEventManager()->bind('feed.action', array($service, 'onAddFeedAction'));

        //rendering content
        OW::getEventManager()->bind('base.comment_item_process', array($service, 'renderComments'));
        OW::getEventManager()->bind(IISEventManager::ON_FEED_ITEM_RENDERER, array($service,'renderNewsfeed') );
        OW::getEventManager()->bind(IISEventManager::ON_AFTER_NEWSFEED_STATUS_STRING_READ, array($service,'renderString')); //iisnews
        OW::getEventManager()->bind('hashtag.before_render_string', array($service,'renderString')); //groups, event, video

//        if(OW::getConfig()->getValue('iishashtag', 'should_be_repopulated')=='true'){
//            $service->repopulate_tags_table();
//            OW::getConfig()->saveConfig('iishashtag', 'should_be_repopulated','false');
//        }
    }

    public function onBeforeDocumentRender( OW_Event $event )
    {
        //  if (!startsWith(OW::getRouter()->getUri(), "forum/"))
        {
            OW::getDocument()->addStyleSheet( OW::getPluginManager()->getPlugin('iishashtag')->getStaticCssUrl() . 'iishashtag.css' );

            $js = ";var iishashtagLoadTagsUrl='". OW::getRouter()->urlForRoute('iishashtag.load_tags')."/';";
            $js = $js.";var iishashtagMaxCount=". OW::getConfig()->getValue('iishashtag', 'max_count').";";
            $friends = "var iishashtag_friends = [{tag: 'i.moradnejad', count: '5'}];";
            $js = $js.";".$friends.";";
            OW::getDocument()->addScriptDeclarationBeforeIncludes($js);
            OW::getDocument()->addScript( OW::getPluginManager()->getPlugin('iishashtag')->getStaticJsUrl() . 'suggest.js' );
            OW::getDocument()->addScript( OW::getPluginManager()->getPlugin('iishashtag')->getStaticJsUrl() . 'iishashtag.js' );
        }
    }

}
