<?php

namespace Mapado\Stripe\Model;

use Stripe_Invoice;

use Mapado\Stripe\StripeApi;

class InvoiceProxy extends StripeObject
{
    /**
     * api
     *
     * @var StripeApi
     * @access private
     */
    private $api;

    /**
     * discount
     *
     * @var discount
     * @access private
     */
    private $discount;

    /**
     * __construct
     *
     * @param Stripe_Invoice $invoice
     * @param StripeApi $api
     * @access public
     * @return void
     */
    public function __construct(Stripe_Invoice $invoice, StripeApi $api)
    {
        parent::__construct($invoice);
        $this->api = $api;
    }

    /**
     * isRefunded
     *
     * @access public
     * @return boolean
     */
    public function isRefunded()
    {
        if (substr($this->stripeObject['id'], 0, 4) === 'ref_') {
            return true;
        }
        return false;
    }

    /**
     * getSubscription
     *
     * @access public
     * @return SubscriptionProxy
     */
    public function getSubscription()
    {
        $subId = $this->stripeObject['subscription'];
        $customerId = $this->stripeObject['customer'];
        return $this->api->getSubscription($customerId, $subId);
    }

    /**
     * Return subscription id
     *
     * @return integer
     */
    public function getSubscriptionId()
    {
        return $this->stripeObject['subscription'];
    }

    /**
     * getCharge
     *
     * @access public
     * @return ChargeProxy
     */
    public function getCharge()
    {
        $chargeId = $this->stripeObject['charge'];
        if ($chargeId) {
            return $this->api->getCharge($chargeId);
        } else {
            return null;
        }
    }

    /**
     * getLines
     *
     * @access public
     * @return void
     */
    public function getLines()
    {
        $stripeLines = $this->stripeObject['lines'];
        $lines = [];

        if (!empty($stripeLines['data'])) {
            foreach ($stripeLines['data'] as $stripeLine) {
                if ($stripeLine['type'] == 'subscription') {
                    $subProxy = new SubscriptionProxy($stripeLine, $this->api);

                    $lines[] = $subProxy;
                }
            }
        }

        return $lines;
    }

    /**
     * getDiscount
     *
     * @access public
     * @return DiscountProxy
     */
    public function getDiscount()
    {
        if (!$this->stripeObject['discount']) {
            return null;
        }

        if (!isset($this->discount)) {
            $this->discount = new DiscountProxy($this->stripeObject['discount'], $this->api);
        }
        return $this->discount;
    }
}
