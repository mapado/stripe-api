<?php

namespace Mapado\Stripe\Model;

class CustomerProxy extends StripeObject
{
    public function __get($key)
    {
        return $this->stripeObject->{$key};
    }
}
