<?php


/**
 * @author Mohammad Agha Abbasloo
 * @package ow_plugins.iiseventplus.controllers
 * @since 1.0
 */
class IISEVENTPLUS_MCTRL_Base extends OW_MobileActionController
{
    /**
     * @var EVENT_BOL_EventService
     */
    private $eventPlusService;

    public function __construct()
    {
        parent::__construct();
        $this->eventPlusService = IISEVENTPLUS_BOL_Service::getInstance();
    }

    /***
     * leave event controller
     * @param $params
     * @throws Redirect403Exception
     * @throws Redirect404Exception
     */
    public function leave( $params )
    {
        $event = $this->getEventForParams($params);

        if ( !OW::getUser()->isAuthenticated() || ( OW::getUser()->getId() == $event->getUserId() && !OW::getUser()->isAuthorized('event') ) )
        {
            throw new Redirect403Exception();
        }

        $eventService = EVENT_BOL_EventService::getInstance();
        $eventUser = $eventService->findEventUser($event->getId(),OW::getUser()->getId());
        $this->eventPlusService->leaveEvent($event->getId(),OW::getUser()->getId());

        OW::getEventManager()->call("feed.delete_activity", array(
            'activityType' => 'event-join',
            'activityId' => $eventUser->getId(),
            'entityId' => $event->getId(),
            'userId' => OW::getUser()->getId(),
            'entityType' => 'event'
        ));

        OW::getEventManager()->call("feed.delete_activity", array(
            'activityType' => 'subscribe',
            'activityId' => $eventUser->getId(),
            'entityId' => $event->getId(),
            'userId' => OW::getUser()->getId(),
            'entityType' => 'event'
        ));

        OW::getFeedback()->info(OW::getLanguage()->text('iiseventplus', 'leave_success_message'));
        $this->redirect(OW::getRouter()->urlForRoute('event.main_menu_route'));
    }

    /***
     * Get event by params(eventId)
     * @param $params
     * @return EVENT_BOL_Event
     * @throws Redirect404Exception
     */
    private function getEventForParams( $params )
    {
        if ( empty($params['eventId']) )
        {
            throw new Redirect404Exception();
        }

        $event = EVENT_BOL_EventService::getInstance()->findEvent($params['eventId']);

        if ( $event === null )
        {
            throw new Redirect404Exception();
        }

        return $event;
    }

}
