<?php

class IISVIDEOPLUS_CTRL_Videoplus extends OW_ActionController
{

    public function index()
    {
        $respondArray = array();

        if ( empty($_POST['videoId']) || empty($_POST['canvasData'])){
            $respondArray['messageType'] = 'error';
            $respondArray['message'] = '_ERROR_';
            echo json_encode($respondArray);
            exit;
        }
        if(class_exists('IISVIDEOPLUS_BOL_Service')) {
            $videoPlusService = IISVIDEOPLUS_BOL_Service::getInstance();
            $videoService = VIDEO_BOL_ClipService::getInstance();
            $video = $videoService->findClipById($_POST['videoId']);
            if ($video === null) {
                $respondArray['messageType'] = 'error';
                $respondArray['message'] = '_EMPTY_EVENT_';
                echo json_encode($respondArray);
                exit;
            }

            $videoNameParts = explode('.', $video->code);
            $imageName = "";
            foreach ($videoNameParts as $videoNamePart) {
                if ($videoNamePart != end($videoNameParts)) {
                    $imageName = $imageName . $videoNamePart;
                }
            }
            $imageName = $imageName . '.png';
            $tmpDir = OW::getPluginManager()->getPlugin('video')->getPluginFilesDir();
            $tmpVideoImageFile = $tmpDir . $imageName;
            $rawData = $_POST['canvasData'];
            $filteredData = explode(',', $rawData);
            $decodedData = base64_decode($filteredData[1]);
            $fp = fopen($tmpVideoImageFile, 'w');
            fwrite($fp, $decodedData);
            fclose($fp);

            $imageFile = $videoPlusService->getVideoFileDir($imageName);

            try {
                OW::getStorage()->copyFile($tmpVideoImageFile, $imageFile);
                $video->thumbUrl = $imageName;
                $videoService->saveClip($video);
            } catch (Exception $e) {
            }
            unlink($tmpVideoImageFile);

            $respondArray['messageType'] = 'info';
            $respondArray['message'] = OW::getLanguage()->text('iisvideoplus', 'users_invite_success_message');

            exit(json_encode($respondArray));
        }
    }

}