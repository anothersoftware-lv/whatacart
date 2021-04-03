<?php
namespace common\modules\extension\controllers;

use common\modules\extension\business\UploadManager;
use common\modules\extension\models\UploadForm;
use yii\web\UploadedFile;
use ZipArchive;
use usni\UsniAdaptor;
use usni\library\utils\FlashUtil;
use usni\library\utils\FileUtil;
use usni\library\utils\ArrayUtil;
use common\modules\extension\models\Extension;
use yii\filters\AccessControl;
use usni\library\utils\CacheUtil;
/**
 * UploadController class file
 *
 * @package common\modules\extension\controllers
 */
class UploadController extends \usni\library\web\Controller
{
    /**
     * File containing the installation instructions
     * @var string 
     */
    public $installFile = 'install.php';
    
    /**
     * inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'summary', 'complete'],
                        'roles' => ['extension.manage'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Complete the extension upload process
     * @return string
     */
    public function actionComplete()
    {
        $folder     = UsniAdaptor::app()->getSession()->get('installerFolder');
        $tempfolder = UsniAdaptor::app()->getSession()->get('installerTempFolder');
        if($folder == null || $tempfolder == null)
        {
            return $this->redirect(UsniAdaptor::createUrl('extension/upload/create'));
        }
        $configData     = require $folder . '/install.php';
        $extensionData  = $configData['data'];
        $uploadManager  = new UploadManager(['extensionConfig' => $configData, 
                                             'extensionData'   => $extensionData,
                                             'installFolder' => $folder, 
                                             'installTempFolder' => $tempfolder]);
        if($uploadManager->run())
        {
            FileUtil::removeDirectory(FileUtil::normalizePath($tempfolder));
            sleep(3);//So that directory could be deleted
            
            //Save extension data
            $extension              = new Extension(['scenario' => 'create']);
            $extension->name        = ArrayUtil::getValue($configData, 'name');
            $extension->version     = ArrayUtil::getValue($configData, 'version');
            $extension->author      = ArrayUtil::getValue($configData, 'author');
            $extension->category    = ArrayUtil::getValue($configData, 'category');
            $compatibleVersions     = ArrayUtil::getValue($configData, 'product_version');
            if ($compatibleVersions != null)
            {
                $compatibleVersions = implode(',', $compatibleVersions);
            }
            $extension->product_version = $compatibleVersions;
            $extension->data = serialize(ArrayUtil::getValue($configData, 'data'));
            $extension->save();
            
            FlashUtil::setMessage('success', UsniAdaptor::t('extensionflash', 'Extension Uploaded Successfully'));
            return $this->redirect(UsniAdaptor::createUrl('extension/upload/create', ['clearCache' => 'true']));
        }
    }
    
    /**
     * Renders summary for upload
     * @return string
     */
    public function actionSummary()
    {
        $folder     = UsniAdaptor::app()->getSession()->get('installerFolder');
        $tempfolder = UsniAdaptor::app()->getSession()->get('installerTempFolder');
        if($folder == null || $tempfolder == null)
        {
            return $this->redirect(UsniAdaptor::createUrl('extension/upload/create'));
        }
        return $this->render('/summary');
    }
    
    /**
     * Upload extension.
     * @return string
     */
    public function actionCreate()
    {
        $model      = new UploadForm();
        if(isset($_POST['UploadForm']))
        {
            $runtimePath            = UsniAdaptor::app()->getRuntimePath();
            $model->uploadInstance  = UploadedFile::getInstance($model, 'uploadInstance');
            if($model->validate())
            {
                //Create a temp folder if not exists
                $tempFolder = 'temp-' . md5(mt_rand());
                if(!is_dir($runtimePath . DS . $tempFolder))
                {
                    mkdir($runtimePath . DS . $tempFolder);
                }
                $filePath = $runtimePath . DS . $tempFolder . DS . $model->uploadInstance->name;
                if(file_exists($filePath))
                {
                    unlink($filePath);
                }
                $model->uploadInstance->saveAs($filePath);
                //Unzip the file
                $zip    = new ZipArchive();
                $res    = $zip->open($filePath, ZipArchive::CREATE);
                if($res === true)
                {
                    $zip->extractTo($runtimePath . DS . $tempFolder);
                    @$zip->close();
                    unlink($filePath);
                }
                //Search for install.php in the folder
                $subfolderName  = basename($model->uploadInstance->name, '.zip');
                $installFile    = FileUtil::normalizePath($runtimePath . '/' . $tempFolder . '/' . $subfolderName . '/' . $this->installFile);
                if(!file_exists($installFile))
                {
                    $model->addError('uploadInstance', UsniAdaptor::t('extension', 'install.php file is missing in the zip folder'));
                }
                else
                {
                    $folderName = $runtimePath . '/' . $tempFolder . '/' . $subfolderName;
                    $configData = require $folderName . '/' . $this->installFile;
                    //Check for name
                    $name = ArrayUtil::getValue($configData, 'name');
                    $data = ArrayUtil::getValue($configData, 'data');
                    if(!empty($name) && !empty($data))
                    {
                        UsniAdaptor::app()->getSession()->set('installerFolder', $folderName);
                        UsniAdaptor::app()->getSession()->set('installerTempFolder', $runtimePath . '/' . $tempFolder);
                        return $this->redirect(UsniAdaptor::createUrl('extension/upload/summary'));
                    }
                    else
                    {
                        $model->addError('uploadInstance', UsniAdaptor::t('extension', 'install.php has invalid configuration'));
                        FileUtil::removeDirectory(FileUtil::normalizePath($runtimePath . '/' . $tempFolder));
                        sleep(2);//So that directory could be deleted
                    }
                }
            }
        }
        UsniAdaptor::app()->getSession()->remove('installerFolder');
        UsniAdaptor::app()->getSession()->remove('installerTempFolder');
        return $this->render('/upload', ['model' => $model]);
    }
}