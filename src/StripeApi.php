<?php

namespace Mapado\Stripe;

use Mapado\Stripe\Model\ChargeProxy;
use Mapado\Stripe\Model\CustomerProxy;
use Mapado\Stripe\Model\InvoiceProxy;
use Mapado\Stripe\Model\SubscriptionProxy;

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
     * @access public
     * @return array
     */
    public function getInvoiceListForClient($clientId)
    {
        $stripeInvoiceList = \Stripe_Invoice::all(['customer' => $clientId]);

        $invoiceList = [];
        if (!empty($stripeInvoiceList)) {
            foreach ($stripeInvoiceList['data'] as $stripeInvoice) {
                $invoiceList[] = new InvoiceProxy($stripeInvoice, $this);
            }
        }

        return $invoiceList;
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
        try {
            $charge = \Stripe_Charge::retrieve($chargeId);
        } catch (\Stripe_InvalidRequestError $e) {
            return null;
        }
        return new ChargeProxy($charge, $this);
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
}
