<?php

namespace Mapado\Stripe\Model;

use Mapado\Stripe\Model\ChargeProxy as ChargeProxy;
use Mapado\Stripe\Model\InvoiceProxy as InvoiceProxy;

class CustomerProxy extends StripeObject
{
    public function __get($key)
    {
        return $this->stripeObject->{$key};
    }

    /**
     * getChargeList
     *
     * @param array $params
     * @access public
     * @return array
     */
    public function getChargeList(array $params = array())
    {
        $stripeChargeList = \Stripe_Charge::all(['customer' => $this->getId()] + $params);
        $chargeList = [];
        if (!empty($stripeChargeList)) {
            foreach ($stripeChargeList['data'] as $stripeCharge) {
                $chargeList[] = new ChargeProxy($stripeCharge, $this);
            }
        }
        return $chargeList;
    }

    /**
     * getInvoiceList
     *
     * @param array $params
     * @access public
     * @return array
     */
    public function getInvoiceListForClient(array $params = array())
    {
        $stripeInvoiceList = \Stripe_Invoice::all(['customer' => $this->getId()] + $params);
        $invoiceList = [];
        if (!empty($stripeInvoiceList)) {
            foreach ($stripeInvoiceList['data'] as $stripeInvoice) {
                $invoiceList[] = new InvoiceProxy($stripeInvoice, $this);
            }
        }
        return $invoiceList;
    }
}
