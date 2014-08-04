<?php

namespace Mapado\Stripe\Model;

use Stripe_Refund;

use Mapado\Stripe\StripeApi;

class RefundProxy extends StripeObject
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
     * @param Stripe_Refund $charge
     * @param StripeApi $api
     * @access public
     * @return void
     */
    public function __construct(Stripe_Refund $charge, StripeApi $api)
    {
        parent::__construct($charge);
        $this->api = $api;
    }
}
