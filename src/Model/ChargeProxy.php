<?php

namespace Mapado\Stripe\Model;

use Stripe_Charge;

use Mapado\Stripe\StripeApi;

class ChargeProxy extends StripeObject
{
    /**
     * api
     *
     * @var StripeApi
     * @access private
     */
    private $api;

    /**
     * __construct
     *
     * @param Stripe_Charge $charge
     * @param StripeApi $api
     * @access public
     * @return void
     */
    public function __construct(Stripe_Charge $charge, StripeApi $api)
    {
        parent::__construct($charge);
        $this->api = $api;
    }

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
