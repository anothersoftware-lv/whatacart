<?php
use usni\UsniAdaptor;

$stateCities = [];

if (!empty($model->state) && !empty($model->city)) {
    $stateCities = \common\utils\AddressUtil::getStateCitiesDropdownArray($model->state);
}

$stateDropdownClass = isset($stateClass) ? $stateClass : 'address-dropdown-state';
$cityDropdownClass = isset($cityClass) ? $cityClass : 'address-dropdown-city';
?>
<div class="col-sm-6">
    <?= $form->field($model, 'state')->select2input(
        $states,
        true,
        [
            'class' => 'custom-select ' . $stateDropdownClass,
            'prompt' => '--' . UsniAdaptor::t('state', 'Select a state') . '--'
        ]
    )?>
</div>

<div class="col-sm-6">
    <?= $form->field($model, 'city')->select2input(
        $stateCities,
        true,
        [
            'class' => 'custom-select ' . $cityDropdownClass,
            'prompt' => '--' . UsniAdaptor::t('city', 'Select a city') . '--'
        ]
    )?>
</div> 