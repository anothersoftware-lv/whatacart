<?php

namespace common\modules\lm;

use usni\library\components\SecuredModule;

class Module extends SecuredModule
{
    public function init()
    {
        $modulePath = $this->getBasePath();
        
        \Yii::setAlias('@LmModule', $modulePath);
    
        parent::init();
    }
}
