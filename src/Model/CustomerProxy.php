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
        return $this->api->getChargeListForClient($this->getId(), $params);
    }

    /**
     * getInvoiceList
     *
     * @param array $params
     * @access public
     * @return array
     */
    public function getInvoiceList(array $params = array())
    {
        return $this->api->getInvoiceListForClient($this->getId(), $params);
    }
}
