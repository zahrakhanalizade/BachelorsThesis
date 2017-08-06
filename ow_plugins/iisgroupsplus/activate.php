<?php

/**
 * iisgroupsplus
 */
/**
 * @author Mohammad Agha Abbasloo <a.mohammad85@gmail.com>
 * @package ow_plugins.iisgroupsplus
 * @since 1.0
 */
try {
    $widgetService = BOL_ComponentAdminService::getInstance();
    $widget = $widgetService->addWidget('IISGROUPSPLUS_CMP_FileListWidget', false);
    $placeWidget = $widgetService->addWidgetToPlace($widget, 'group');
    $widgetService->addWidgetToPosition($placeWidget, BOL_ComponentAdminService::SECTION_LEFT);
} catch(Exception $e){}