<?php

/**
 * iisvideoplus
 */

$config = OW::getConfig();
if($config->configExists('iisvideoplus', 'maximum_video_file_upload'))
{
    $config->deleteConfig('iisvideoplus', 'maximum_video_file_upload');
}

