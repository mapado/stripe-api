<?php

namespace Mapado\Stripe\Tests\Units\Model;


use Mapado\Stripe\Model\SubscriptionProxy as BaseSubscriptionProxy;

use atoum;

class SubscriptionProxy extends atoum
{
    /**
     * stripeApi
     *
     * @var StripeApi
     * @access private
     */
    private $stripeApi;

    /**
     * beforeTestMethod
     *
     * @param mixed $methodName
     * @access public
     * @return void
     */
    public function beforeTestMethod($methodName)
    {
        $this->stripeApi = new \mock\Mapado\Stripe\StripeApi('privatekey');
    }

    /**
     * testCall
     *
     * @access public
     * @return void
     */
    public function testCall()
    {
        $this
            ->if($stripeObject = new \mock\Stripe_Subscription)
            ->and($stripeObject['total'] = 10)
            ->and($object = new BaseSubscriptionProxy($stripeObject, $this->stripeApi))
            ->then
                ->integer($object->getTotal())
                ->isEqualTo(10)
            ->then
                ->variable($object->getUnexistentValue())
                ->isNull()
        ;
    }
}
