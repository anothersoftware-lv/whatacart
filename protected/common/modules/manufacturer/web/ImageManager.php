<?php
/**
 */
namespace common\modules\manufacturer\web;

use usni\UsniAdaptor;
/**
 * ImageManager class file
 * 
 * @package usni\library\web
 */
class ImageManager extends \usni\library\web\ImageManager
{
    /**
     * @inheritdoc
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->uploadPath = UsniAdaptor::app()->assetManager->manufacturerImageUploadPath;
        $this->thumbnailUploadPath = UsniAdaptor::app()->assetManager->manufacturerThumbUploadPath;
        $this->thumbnailUploadUrl = UsniAdaptor::app()->assetManager->getManufacturerThumbnailUploadUrl();
        $this->uploadUrl = UsniAdaptor::app()->assetManager->getManufacturerImageUploadUrl();
    }
}