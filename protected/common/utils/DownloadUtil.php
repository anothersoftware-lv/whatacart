<?php
namespace common\utils;

use usni\UsniAdaptor;

class DownloadUtil
{
    public static function downloadFile($file)
    {
        UsniAdaptor::app()->response->sendFile($file)->send();
    }
}