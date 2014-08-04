<?php

namespace Mapado\Stripe\Tests\Units\Model;


use Mapado\Stripe\Model\StripeObject as BaseStripeObject;

use atoum;

class StripeObject extends atoum
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
     * testCallGet
     *
     * @access public
     * @return void
     */
    public function testCallGet()
    {
        $this
            ->if($stripeObject = new \mock\Stripe_Charge)
            ->and($stripeObject['total'] = 10)
            ->and($stripeObject['percent_off'] = 80)
            ->and($object = new BaseStripeObject($stripeObject, $this->stripeApi))
            ->then
                ->integer($object->getTotal())
                ->isEqualTo(10)
                ->integer($object->total())
                ->isEqualTo(10)
            ->then
                ->variable($object->getUnexistentValue())
                ->isNull()
            ->then
                ->integer($object->getPercentOff())
                ->integer($object->percentOff())
                ->isEqualTo(80)
        ;
    }

    /**
     * testCallSet
     *
     * @access public
     * @return void
     */
    public function testCallSet()
    {
        $this
            ->if($stripeObject = new \mock\Stripe_Charge)
            ->and($object = new BaseStripeObject($stripeObject, $this->stripeApi))
            ->and($object->setTotal(15))
            ->then
            ->then
                ->integer($object->getTotal())
                ->isEqualTo(15)
            ->then
                ->variable($object->getUnexistentValue())
                ->isNull()
        ;
    }

    public function testClone()
    {
        $this
            ->if($stripeObject = new \mock\Stripe_Charge)
            ->and($object = new BaseStripeObject($stripeObject, $this->stripeApi))
            ->and($clone = clone $object)
            ->then
                ->object($clone)
                ->isCloneOf($object)
            ->if($object->setTotal(5))
            ->and($clone->setTotal(10))
            ->then
                ->integer($object->getTotal())
                ->isNotEqualTo($clone->getTotal())
        ;
    }
}
