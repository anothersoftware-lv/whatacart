<?php
/* @var $this \usni\library\web\AdminView */
/* @var $formDTO \usni\library\dto\FormDTO */

use usni\UsniAdaptor;
use usni\library\bootstrap\ActiveForm;
use usni\library\bootstrap\FormButtons;

$this->params['breadcrumbs'] = [
        [
        'label' => UsniAdaptor::t('application', 'Manage') . ' ' .
        UsniAdaptor::t('extension', 'Extensions'),
        'url' => ['/extension/default/index']
    ],
        [
        'label' => UsniAdaptor::t('application', 'Upload Extension')
    ]
];
$this->title = UsniAdaptor::t('extension', 'Upload Extension');
$form = ActiveForm::begin([
        'id' => 'uploadextensionview',
        'layout' => 'horizontal',
        'caption' => $this->title
    ]);
?>
<?= $form->field($model, 'uploadInstance')->fileInput(); ?>
<?= FormButtons::widget(['cancelUrl' => UsniAdaptor::createUrl('extension/default/index'),
                         'submitButtonLabel' => UsniAdaptor::t('application', 'Continue')]);?>
<?php ActiveForm::end(); ?>
