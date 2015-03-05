<?php

namespace Mapado\Stripe;

use Mapado\Stripe\Model\CardProxy;
use Mapado\Stripe\Model\ChargeProxy;
use Mapado\Stripe\Model\CouponProxy;
use Mapado\Stripe\Model\CustomerProxy;
use Mapado\Stripe\Model\InvoiceProxy;
use Mapado\Stripe\Model\SubscriptionProxy;
use Mapado\Stripe\Model\EventProxy;

class StripeApi
{
    /**
     * subscriptionList
     *
     * @var array
     * @access private
     */
    private $subscriptionList;

    /**
     * customerList
     *
     * @var array
     * @access private
     */
    private $customerList;

    /**
     * chargeList
     *
     * @var array
     * @access private
     */
    private $chargeList;

    /**
     * couponList
     *
     * @var array
     * @access private
     */
    private $couponList;

    /**
     * __construct
     *
     * @param string stripePrivateKey
     * @access public
     * @return void
     */
    public function __construct($stripePrivateKey)
    {
        \Stripe::setApiKey($stripePrivateKey);
        $this->subscriptionList = [];
    }

    /**
     * getInvoiceListForClient
     *
     * @param string $clientId
     * @param array $params
     * @access public
     * @return array
     */
    public function getInvoiceListForClient($clientId, array $params = array())
    {
        $stripeInvoiceList = \Stripe_Invoice::all(['customer' => $clientId] + $params);
        $invoiceList = [];
        if (!empty($stripeInvoiceList)) {
            foreach ($stripeInvoiceList['data'] as $stripeInvoice) {
                $invoiceList[] = new InvoiceProxy($stripeInvoice, $this);
            }
        }
        return $invoiceList;
    }

    /**
     * getChargeListForClient
     *
     * @param string $clientId
     * @param array $params
     * @access public
     * @return array
     */
    public function getChargeListForClient($clientId, array $params = array())
    {
        $stripeChargeList = \Stripe_Charge::all(['customer' => $clientId] + $params);
        $chargeList = [];
        if (!empty($stripeChargeList)) {
            foreach ($stripeChargeList['data'] as $stripeCharge) {
                $chargeList[] = new ChargeProxy($stripeCharge, $this);
            }
        }
        return $chargeList;
    }

    /**
     * getCouponList
     *
     * @param array $params
     * @access public
     * @return array
     */
    public function getCouponList(array $params = array())
    {
        $stripeCouponList = \Stripe_Coupon::all($params);
        $couponList = [];
        if (!empty($stripeCouponList)) {
            foreach ($stripeCouponList['data'] as $stripeCoupon) {
                $couponList[] = new CouponProxy($stripeCoupon, $this);
            }
        }
        return $couponList;
    }

    /**
     * getCoupon
     *
     * @param string $couponId
     * @access public
     * @return CouponProxy
     */
    public function getCoupon($couponId)
    {
        if (!isset($this->couponList[$couponId])) {
            $stripeCoupon = \Stripe_Coupon::retrieve($couponId);
            $couponProxy = new CouponProxy($stripeCoupon, $this);
            $this->couponList[$couponId] = $couponProxy;
        }
        return $this->couponList[$couponId];
    }

    /**
     * getInvoice
     *
     * @param string $invoiceId
     * @access public
     * @return \Stripe_Invoice
     */
    public function getInvoice($invoiceId)
    {
        $invoice = \Stripe_Invoice::retrieve($invoiceId);
        return new InvoiceProxy($invoice, $this);
    }

    /**
     * getCharge
     *
     * @param string $chargeId
     * @access public
     * @return ChargeProxy
     */
    public function getCharge($chargeId)
    {
        if (!isset($this->chargeList[$chargeId])) {
            $stripeCharge = \Stripe_Charge::retrieve($chargeId);
            $chargeProxy = new ChargeProxy($stripeCharge, $this);
            $this->chargeList[$chargeId] = $chargeProxy;
        }
        return $this->chargeList[$chargeId];
    }

    /**
     * createCharge
     *
     * @param array $chargeInfo
     * @access public
     * @return ChargeProxy
     */
    public function createCharge(array $chargeInfo)
    {
        $stripeCharge = \Stripe_Charge::create($chargeInfo);
        $chargeProxy = new ChargeProxy($stripeCharge, $this);
        $chargeId = $stripeCharge['id'];
        $this->chargeList[$chargeId] = $chargeProxy;

        return $this->chargeList[$chargeId] = $chargeProxy;
    }

    /**
     * getCustomer
     *
     * @param mixed $customerId
     * @access public
     * @return CustomerProxy
     */
    public function getCustomer($customerId)
    {
        if (!isset($this->customerList[$customerId])) {
            $stripeCustomer = \Stripe_Customer::retrieve($customerId);
            $customerProxy = new CustomerProxy($stripeCustomer, $this);
            $this->customerList[$customerId] = $customerProxy;
        }
        return $this->customerList[$customerId];
    }

    /**
     * createCustomer
     *
     * @param array $customerInfo
     * @access public
     * @return CustomerProxy
     */
    public function createCustomer(array $customerInfo)
    {
        $stripeCustomer = \Stripe_Customer::create($customerInfo);
        $customerProxy = new CustomerProxy($stripeCustomer, $this);
        $this->customerList[$customerProxy->id] = $customerProxy;

        return $this->customerList[$customerProxy->id];
    }

    /**
     * getEvent
     *
     * @param string $invoiceId
     * @access public
     * @return EventProxy
     */
    public function getEvent($eventId)
    {
        $event = \Stripe_Event::retrieve($eventId);

        return new EventProxy($event, $this);
    }

    /**
     * getSubscription
     *
     * @param string $customerId
     * @param string $subscriptionId
     * @access public
     * @return \Stripe_Subscription
     */
    public function getSubscription($customerId, $subscriptionId)
    {
        if (!isset($this->subscriptionList[$subscriptionId])) {
            $customer = $this->getCustomer($customerId);
            $stripeSubscription = $customer->subscriptions->retrieve($subscriptionId);
            $subProxy = new SubscriptionProxy($stripeSubscription, $this);
            $this->subscriptionList[$subscriptionId] = $subProxy;
        }
        return $this->subscriptionList[$subscriptionId];
    }

    /**
     * getCards
     * @param string $customerId
     *
     * @return CardProxy
     */
    public function getDefaultCard($customerId)
    {
        $customer      = $this->getCustomer($customerId);
        $defaultCardId = $customer->getDefaultCard();

        if (!$defaultCardId) {
            return;
        }

        return new CardProxy($customer->cards->retrieve($defaultCardId), $this);
    }
}
