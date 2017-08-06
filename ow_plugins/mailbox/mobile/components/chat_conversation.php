<?php

class MAILBOX_MCMP_ChatConversation extends OW_MobileComponent
{
    public function __construct($data)
    {
        $script = UTIL_JsGenerator::composeJsString('
        OWM.conversation = new MAILBOX_Conversation({$params});
        OWM.conversationView = new MAILBOX_ConversationView({model: OWM.conversation});
        ', array('params' => $data));

        OW::getDocument()->addOnloadScript($script);

        OW::getLanguage()->addKeyForJs('mailbox', 'text_message_invitation');

        $form = new MAILBOX_MCLASS_NewMessageForm($data['conversationId'], $data['opponentId']);
        $this->addForm($form);
        $messages = MAILBOX_BOL_MessageDao::getInstance()->findUnreadMessagesForConversation($data['conversationId'],OW::getUser()->getId());
        foreach($messages as $message){
            $message->recipientRead = 1;
            MAILBOX_BOL_MessageDao::getInstance()->save($message);
        }

        $this->assign('data', $data);
        $this->assign('defaultAvatarUrl', BOL_AvatarService::getInstance()->getDefaultAvatarUrl());
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=null){
            $this->assign('backReffererUrl',$_SERVER['HTTP_REFERER']);
        }
        $firstMessage = MAILBOX_BOL_ConversationService::getInstance()->getFirstMessage($data['conversationId']);

        if (empty($firstMessage))
        {
            $actionName = 'send_chat_message';
        }
        else
        {
            $actionName = 'reply_to_chat_message';
        }

        $isAuthorized = OW::getUser()->isAuthorized('mailbox', $actionName);
        
        if ( !$isAuthorized )
        {
            $status = BOL_AuthorizationService::getInstance()->getActionStatus('mailbox', $actionName);

            if ( $status['status'] == BOL_AuthorizationService::STATUS_PROMOTED )
            {
                $this->assign('sendAuthMessage', $status['msg']);
            }
            else if ( $status['status'] != BOL_AuthorizationService::STATUS_AVAILABLE )
            {
                $this->assign('sendAuthMessage', OW::getLanguage()->text('mailbox', $actionName.'_permission_denied'));
            }
        }
    }
}