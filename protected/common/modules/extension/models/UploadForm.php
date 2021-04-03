<?php
namespace common\modules\extension\models;

use usni\UsniAdaptor;
/**
 * UploadForm class file.
 *
 * @package common\modules\extension\models
 */
class UploadForm extends \yii\base\Model
{
    /**
     * Upload File Instance.
     * @var string
     */
    public $uploadInstance;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                   ['uploadInstance', 'required'],
                   [['uploadInstance'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip']
               ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                    'uploadInstance' => UsniAdaptor::t('application', 'Extension'),
               ];
    }
}