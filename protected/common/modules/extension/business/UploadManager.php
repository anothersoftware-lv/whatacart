<?php
namespace common\modules\extension\business;

use usni\UsniAdaptor;
use usni\library\utils\FileUtil;
use usni\library\utils\ArrayUtil;
use common\modules\extension\models\Extension;
use yii\web\View;
/**
 * UploadManager class file. This file process the actions defined in manifest.php.
 *
 * @package common\modules\extension\business
 */
class UploadManager extends \yii\base\Component
{
    /**
     * Extension configuration
     * @var array 
     */
    public $extensionConfig;
    /**
     * Configuration data
     * @var array 
     */
    public $extensionData;
    
    /**
     * The path for the folder having all the files 
     * @var String 
     */
    public $installFolder;
    
    /**
     * The path for the temp folder under which install folder is copied 
     * @var String 
     */
    public $installTempFolder;
    
    /**
     * Database extension record
     * @var Extension 
     */
    public $dbExtension;
    
    /**
     * View associated with the upload
     * @var View 
     */
    public $view;
    
    /**
     * Run the process
     * @return boolean
     */
    public function run()
    {
        $this->processCopyFolders();
        $this->processCopyFiles();
        $this->processDeleteFiles();
        $this->processModifiedFiles();
        $this->processSql();
        $this->saveExtension();
        return true;
    }
    
    /**
     * Process copy folder task
     * @return void
     */
    protected function processCopyFolders()
    {
        $copyFolders    = ArrayUtil::getValue($this->extensionData, 'copyFolders');
        if(!empty($copyFolders))
        {
            foreach($copyFolders as $folder)
            {
                $path = FileUtil::normalizePath($this->installFolder . DS . 'copyFolders' . DS . $folder['sourceFolder']);
                if(is_dir($path))
                {
                    $targetPath = FileUtil::normalizePath(UsniAdaptor::getAlias($folder['targetFolder']));
                    if(!is_dir($targetPath))
                    {
                        FileUtil::createDirectory($targetPath);
                    }
                    FileUtil::copyDirectory($path, $targetPath);
                }
            }
        }
    }
    
    /**
     * Process copy files task
     * @return void
     */
    protected function processCopyFiles()
    {
        $copyFiles    = ArrayUtil::getValue($this->extensionData, 'copyFiles');
        if(!empty($copyFiles))
        {
            foreach($copyFiles as $file)
            {
                $path = FileUtil::normalizePath($this->installFolder . DS . 'copyFiles' . DS . $file['sourceFile']);
                if(file_exists($path))
                {
                    $fileName = basename($file['sourceFile']);
                    $targetPath = FileUtil::normalizePath(UsniAdaptor::getAlias($file['targetFolder']) . DS . $fileName);
                    copy($path, $targetPath);
                }
            }
        }
    }
    
    /**
     * Process delete files task
     * @return void
     */
    protected function processDeleteFiles()
    {
        $deleteFiles    = ArrayUtil::getValue($this->extensionData, 'deleteFiles');
        if(!empty($deleteFiles))
        {
            foreach($deleteFiles as $file)
            {
                $targetPath = FileUtil::normalizePath(UsniAdaptor::getAlias($file['targetFolder']) . DS . $file['sourceFile']);
                if(file_exists($targetPath))
                {
                    unlink($targetPath);
                }
            }
        }
    }
    
    /**
     * Process modified files task
     * @return void
     */
    public function processModifiedFiles()
    {
        $modifiedFiles    = ArrayUtil::getValue($this->extensionData, 'modifiedFiles');
        if(!empty($modifiedFiles))
        {
            foreach($modifiedFiles as $file)
            {
                $targetPath = FileUtil::normalizePath(UsniAdaptor::getAlias($file['targetFolder']) . DS . $file['sourceFile']);
                if(file_exists($targetPath))
                {
                    $operations = $file['operations'];
                    $lines      = file($targetPath, FILE_IGNORE_NEW_LINES);
                    $fileHash   = sha1_file($targetPath);
                    foreach($operations as $operation)
                    {
                        $this->processOperation($operation, $lines);
                    }
                    //@see http://stackoverflow.com/questions/3576248/reading-php-code-using-file-get-contents
                    $content = implode(PHP_EOL, $lines);
                    if(sha1($content) != $fileHash)
                    {
                        file_put_contents($targetPath, $content, LOCK_EX);
                    }
                }
            }
        }
    }
    
    /**
     * Process the operation
     * @param string $operation
     * @param string $lines
     */
    public function processOperation($operation, & $lines)
    {
        if(strlen($operation['content']) == 0) 
        {
            \Yii::warning(UsniAdaptor::t('extension', 'The content is empty'));
            return;
        }
        $type           = $operation['type'];
        $isRegex        = false;
        if(isset($operation['regex']) && $operation['regex'] === true)
        {
            $isRegex = true;
        }
        if($type == 'search')
        {
            $indexCount = 0;
            foreach($lines as $lineNumber => $line)
            {
                if($isRegex === true)
                {
                    $pos = preg_match($operation['content'], $line);
                    if($pos === false) 
                    {
                        \Yii::log(UsniAdaptor::t('extension', 'The expression is not found in line {line}', ['line' => $line]));
                        continue;
                    }
                }
                else
                {
                    $pos = strpos($line, $operation['content']);
                }
                if($pos !== false)
                {
                    $action = $operation['action'];
                    if($action == 'replace')
                    {
                        $indexCount++;
                        $indexes = [];
                        if(isset($operation['index']))
                        {
                            $indexes = explode(',', $operation['index']);
                        }
                        if(empty($indexes) || in_array($indexCount, $indexes))
                        {
                            if($isRegex === true)
                            {
                                $tempContent[$lineNumber] = preg_replace($operation['content'], $operation['data'], $line);
                            }
                            else
                            {
                                $tempContent[$lineNumber] = str_replace($operation['content'], $operation['data'], $line);
                            }
                        }
                        else
                        {
                            $tempContent[$lineNumber] = $line;
                        }
                    }
                    elseif($action == 'add_before')
                    {
                        if(isset($operation['data']))
                        {
                            $tempContent[$lineNumber] = $operation['data'] . $line;
                        }
                        else
                        {
                            \Yii::warning(UsniAdaptor::t('extension', 'The data is missing'));
                        }
                    }
                    elseif($action == 'add_after')
                    {
                        if(isset($operation['data']))
                        {
                            $tempContent[$lineNumber] = $line . $operation['data'];
                        }
                        else
                        {
                            \Yii::warning(UsniAdaptor::t('extension', 'The data is missing'));
                        }
                    }
                }
                else
                {
                    $tempContent[$lineNumber] = $line;
                }
            }
        }
        ksort($tempContent);
        $lines = $tempContent;
    }
    
    /**
     * Process sql files
     * @return void
     */
    protected function processSql()
    {
        $sqls    = ArrayUtil::getValue($this->extensionData, 'sqls');
        if(!empty($sqls))
        {
            foreach($sqls as $sql)
            {
                UsniAdaptor::app()->db->createCommand($sql)->execute();
            }
        }
    }
    
    /**
     * Save extension
     * @return boolean
     */
    protected function saveExtension()
    {
        $this->dbExtension  = new Extension(['scenario' => 'create']);
        $extData            = $this->extensionConfig;
        $data               = $this->extensionData;
        foreach($extData as $key => $value)
        {
            if($key != 'data')
            {
                $this->dbExtension->$key = $value;
            }
            else
            {
                $this->dbExtension->data = serialize($data);
            }
        }
        if($this->dbExtension->save())
        {
            return true;
        }
        return false;
    }
}