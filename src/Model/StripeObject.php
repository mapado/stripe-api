<?php

namespace Mapado\Stripe\Model;

use Mapado\Stripe\StripeApi;

class StripeObject
{
    /**
     * stripeObject
     *
     * @var mixed
     * @access protected
     */
    protected $stripeObject;

    /**
     * api
     *
     * @var StripeApi
     * @access protected
     */
    protected $api;

    /**
     * __construct
     *
     * @param mixed $stripeObject
     * @access public
     * @return void
     */
    public function __construct($stripeObject, StripeApi $api)
    {
        $this->stripeObject = $stripeObject;
        $this->api = $api;
    }

    /**
     * __clone
     *
     * @access public
     * @return void
     */
    public function __clone()
    {
        $this->stripeObject = clone $this->stripeObject;
    }

    /**
     * __call
     *
     * @param string $name
     * @param array $arguments
     * @access public
     * @return mixed
     */
    public function __call($name, array $arguments = [])
    {
        if (method_exists($this->stripeObject, $name)) {
            return call_user_func_array(array($this->stripeObject, $name), $arguments);
        }

        if (substr($name, 0, 3) === 'get') {
            $argName = $this->getArgName($name);

            return $this->stripeObject[$argName];
        } elseif (substr($name, 0, 3) === 'set') {
            $argName = $this->getArgName($name);

            $this->stripeObject[$argName] = $arguments[0];
        } elseif (method_exists($this, 'get' . ucfirst($name))) {
            return call_user_func_array(array($this, 'get' . ucfirst($name)), $arguments);
        } else {
            return $this->stripeObject[$this->getArgName($name)];
        }
    }

    /**
     * getArgName
     *
     * @param string $name
     * @access private
     * @return string
     */
    private function getArgName($name)
    {
        $tmp = substr($name, 0, 3);
        if ($tmp === 'set' || $tmp === 'get') {
            $argName = substr($name, 3);
        } else {
            $argName = $name;
        }

        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $argName)), '_');
    }
}
