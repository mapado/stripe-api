<?php

namespace Mapado\Stripe\Model;

use Stripe_Object;

use Mapado\Stripe\StripeApi;

class SubscriptionProxy extends StripeObject
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
     * @param Stripe_Subscription $subscription
     * @param StripeApi $api
     * @access public
     * @return void
     */
    public function __construct(Stripe_Object $subscription, StripeApi $api)
    {
        parent::__construct($subscription);
        $this->api = $api;
    }
}
