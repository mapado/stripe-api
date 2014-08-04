<?php

namespace Mapado\Stripe\Model;

class DiscountProxy extends StripeObject
{
    public function getCoupon()
    {
        return new CouponProxy($this->stripeObject['coupon']);
    }
}
