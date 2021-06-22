<?php
/**
 * @copyright Copyright (c) 2017 Usha Singhai Neo Informatique Pvt. Ltd
 * @license https://github.com/ushainformatique/yiichimp/blob/master/LICENSE.md
 */
namespace common\web;

use usni\UsniAdaptor;
use usni\library\utils\FileUtil;
use usni\library\utils\StringUtil;
/**
 * AssetManager class file. It would add properties related to uploading files, images and video
 * assets in the application.
 * 
 * @package
 */
class AssetManager extends \usni\library\web\AssetManager
{
    public $productImageUploadPath;
    public $productThumbUploadPath;
    public $productCategoryImageUploadPath;
    public $productCategoryThumbUploadPath;
    public $manufacturerImageUploadPath;
    public $manufacturerThumbUploadPath;

    public $productImageManagerClass;
    public $productCategoryImageManagerClass;
    public $manufacturerImageManagerClass;

    public function init()
    {
        FileUtil::createDirectory($this->productImageUploadPath);
        FileUtil::createDirectory($this->productThumbUploadPath);
        FileUtil::createDirectory($this->productCategoryImageUploadPath);
        FileUtil::createDirectory($this->productCategoryThumbUploadPath);
        FileUtil::createDirectory($this->manufacturerImageUploadPath);
        FileUtil::createDirectory($this->manufacturerThumbUploadPath);

        parent::init();
    }

    /**
     * Get resource manager instance
     * @param string $type
     * @param array $config
     * @return \usni\library\components\BaseFileManager
     */
    public function getResourceManager($type, $config = [])
    {
        if($type == 'product_image') {
            $imageManagerClass = $this->productImageManagerClass;
            return new $imageManagerClass($config);
        } elseif($type == 'product_category_image') {
            $imageManagerClass = $this->productCategoryImageManagerClass;
            return new $imageManagerClass($config);
        } elseif($type == 'manufacturer_image') {
            $imageManagerClass = $this->manufacturerImageManagerClass;
            return new $imageManagerClass($config);
        } else {
             return parent::getResourceManager($type, $config);
        }
    }

    /**
     * Gets image upload url.
     * @return string
     */
    public function getProductImageUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->productImageUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }

    /**
     * Gets thumbnail upload url.
     * @return string
     */
    public function getProductThumbnailUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->productThumbUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }

    /**
     * Gets image upload url.
     * @return string
     */
    public function getProductCategoryImageUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->productCategoryImageUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }

    /**
     * Gets thumbnail upload url.
     * @return string
     */
    public function getProductCategoryThumbnailUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->productCategoryThumbUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }

    /**
     * Gets image upload url.
     * @return string
     */
    public function getManufacturerImageUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->manufacturerImageUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }

    /**
     * Gets thumbnail upload url.
     * @return string
     */
    public function getManufacturerThumbnailUploadUrl()
    {
        $frontUrl = UsniAdaptor::app()->getFrontUrl();
        $route    = str_replace(APPLICATION_PATH, '', $this->manufacturerThumbUploadPath);
        return StringUtil::replaceBackSlashByForwardSlash($frontUrl . $route);
    }
}