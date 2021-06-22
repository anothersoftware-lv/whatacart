<?php

namespace frontend\web;

use usni\library\utils\ArrayUtil;

/**
 * Class LMView
 * @package frontend\web
 * Edhancement custom View to support shortcodes, custom properties or another functionality of the Lauris Mierkalns (LM) extensions
 */
class LMView extends View
{
    public function behaviors()
    {
        return ArrayUtil::merge(parent::behaviors(), [ShortcodeBehavior::class]);
    }
}