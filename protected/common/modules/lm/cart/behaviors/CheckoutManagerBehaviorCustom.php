<?php
namespace cart\behaviors;

use common\modules\order\business\AdminCheckoutManagerCustom as AdminCheckoutManager;
use common\modules\order\events\ConfirmOrderEvent;
/**
 *Implement extended functions related to to checkout manager.
 *
 * @package cart\behaviors
 */
class CheckoutManagerBehaviorCustom extends CheckoutManagerBehavior
{
    /**
     * Attach events with this behavior.
     * @return array
     */
    public function events()
    {
        return [AdminCheckoutManager::EVENT_AFTER_CONFIRM => [$this, 'handleAfterConfirm']];
    }
}
