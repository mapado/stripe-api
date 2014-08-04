<?php

namespace Mapado\Stripe\Tests\Units\Model;

use Mapado\Stripe\Model\InvoiceProxy as BaseInvoiceProxy;
use Mapado\Stripe\Model\SubscriptionProxy;
use Mapado\Stripe\Model\InvoiceLineProxy;

use atoum;

class InvoiceProxy extends atoum
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
     * testGetSubscription
     *
     * @access public
     * @return void
     */
    public function testGetSubscription()
    {
        // empty implementation of getSubscription for api
        $this->stripeApi->getMockController()->getSubscription = new SubscriptionProxy(new \Stripe_Subscription, $this->stripeApi);

        $this
            ->if($stripeInvoice = new \mock\Stripe_Invoice)
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->object($invoice->getSubscription())
                ->isInstanceOf('Mapado\Stripe\Model\SubscriptionProxy')
        ;
    }

    public function testIsRefunded()
    {
        $this
            ->if($stripeInvoice = new \mock\Stripe_Invoice)
            ->and($stripeInvoice['id'] = 'plop')
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->boolean($invoice->isRefunded())
                ->isFalse()
            ->if($stripeInvoice['id'] = 'ref_plop')
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->boolean($invoice->isRefunded())
                ->isTrue()
            ;
    }

    /**
     * testGetLines
     *
     * @access public
     * @return void
     */
    public function testGetLines()
    {
        $subscription = new \Stripe_Object;
        $subscription['type'] = 'subscription';
        $stripeInvoice = new \mock\Stripe_Invoice;
        $stripeInvoice['lines'] = [
            'data' => [$subscription]
        ];

        $this->stripeApi->getMockController()->getSubscription = new SubscriptionProxy(new \Stripe_Subscription, $this->stripeApi);


        $this
            ->if($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->array($invoice->getLines())
                ->size->isEqualTo(1)
        ;
    }

    public function testNoDiscount()
    {
        $this
            ->if($stripeInvoice = new \mock\Stripe_Invoice)
            ->and($stripeInvoice['discount'] = null)
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->variable($invoice->getDiscount())
                ->isNull()
        ;
    }

    /**
     * testDiscount
     *
     * @access public
     * @return void
     */
    public function testDiscount()
    {
        $coupon = new \Stripe_Object;
        $coupon['percent_off'] = 80;
        $discount['coupon'] = $coupon;


        $this
            ->if($stripeInvoice = new \mock\Stripe_Invoice)
            ->and($stripeInvoice['discount'] = $discount)
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->object($discount = $invoice->getDiscount())
                ->isInstanceOf('Mapado\Stripe\Model\DiscountProxy')
            ->then
                ->object($coupon = $discount->getCoupon())
                ->isInstanceOf('Mapado\Stripe\Model\CouponProxy')
            ->then
                ->integer($coupon->getPercentOff())
                ->isEqualTo(80)
        ;
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
            ->if($stripeInvoice = new \mock\Stripe_Invoice)
            ->and($stripeInvoice['total'] = 10)
            ->and($invoice = new BaseInvoiceProxy($stripeInvoice, $this->stripeApi))
            ->then
                ->integer($invoice->getTotal())
                ->isEqualTo(10)
            ->then
                ->variable($invoice->getUnexistentValue())
                ->isNull()
        ;
    }
}
