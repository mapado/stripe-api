<?php

namespace Mapado\Stripe\Model;

use Stripe_Charge;

use Mapado\Stripe\StripeApi;

class ChargeProxy extends StripeObject
{
    /**
     * getRefunds
     *
     * @access public
     * @return array
     */
    public function getRefunds()
    {
        $refunds = [];
        if (!empty($this->stripeObject['refunds'])) {
            foreach ($this->stripeObject['refunds'] as $refund) {
                $refunds[] = new RefundProxy($refund, $this->api);
            }
        }

        return $refunds;
    }
}
