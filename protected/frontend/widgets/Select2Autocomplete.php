<?php

namespace frontend\widgets;

use kartik\select2\Select2;
use yii\base\Widget;
use yii\web\JsExpression;
use yii\helpers\Url;

class Select2Autocomplete extends Widget
{
    public $model;
    public $attribute;
    public $data = [];
    public $url;
    public $placeholder;
    public $prompt;
    public $disabled;
    public $toUppercase;
    public $inputMinLength = 2;
    public $options = [];
    public $pluginOptions = [];
    public $waitingResultsMessage;
    public $multiple = false;
    
    public function run()
    {
        $options = [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'data' => $this->data,
            'options' => []
        ];
        
        /*if ($this->prompt) {
            $options['prompt'] = $this->prompt;
        }*/
        
        if ($this->disabled) {
            $options['disabled'] = $this->disabled;
        }
        
        if ($this->placeholder) {
            $options['placeholder'] = $this->placeholder;
        }
        
        $options['options']['multiple'] = $this->multiple;
        
        if ($this->toUppercase) {
            $options['oninput'] = 'this.value = this.value.toUpperCase()';
        }
        
        $waitingResultsMessage = $this->waitingResultsMessage ?? 'Waiting for results...';
        
        $options['pluginOptions'] = array_merge(
            $this->pluginOptions,
            [
                'allowClear' => true,
                'minimumInputLength' => $this->inputMinLength,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return '$waitingResultsMessage'; }"),
                ],
                'ajax' => [
                    'url' => $this->url ?? Url::to(['search']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(number) { return number.text; }'),
                'templateSelection' => new JsExpression('function (number) { return number.text; }'),
            ]);
        
        return Select2::widget($options);
    }
}
