<?php
namespace Omnipay\NestPay\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\NestPay\Address;

class AuthorizeRequest extends AbstractRequest
{
	use HasHashing;

	public function setStoreKey(string $store_key): self
	{
		return $this->setParameter('storeKey', $store_key);
	}

	public function getStoreKey(): string
	{
		return $this->getParameter('storeKey');
	}

	/**
	 * Set payment page language. See Readme.md for available options.
	 *
     * @return $this
	 */
	public function setLanguage(string $value): self
	{
		return $this->setParameter('language', $value);
	}

	/**
	 * Get payment page language
     */
	public function getLanguage(): string
	{
		return $this->getParameter('language');
	}

	/**
	 * Set URL for failed transactions
	 *
     * @return $this
	 */
	public function setErrorUrl(string $value): self
	{
		return $this->setParameter('errorUrl', $value);
	}

	/**
	 * Get Error URL
	 */
	public function getErrorUrl(): string
	{
		return $this->getParameter('errorUrl');
	}

    /**
     * URL to send callback for this transaction
     *
     * @param string $url A valid URL
     */
    public function setCallbackUrl(string $url): self
    {
        return $this->setParameter('callbackUrl', $url);
    }

    public function getCallbackUrl(): ?string
    {
        return $this->getParameter('callbackUrl');
    }

    /**
     * The return URL which NestPay redirects customers when the customer clicks the button “back to order” displayed in HPP.
     *
     * @param string $value A valid URL. It is expected from the merchant to send the URL of its website.
     */
    public function setShopUrl(string $value): self
    {
        return $this->setParameter('shopUrl', $value);
    }

    public function getShopUrl(): ?string
    {
        return $this->getParameter('shopUrl');
    }

    public function setBillingAddress(Address $address): self
    {
        return $this->setParameter('billingAddress', $address);
    }

    public function getBillingAddress(): ?Address
    {
        return $this->getParameter('billingAddress');
    }

    public function setShippingAddress(Address $address): self
    {
        return $this->setParameter('shippingAddress', $address);
    }

    public function getShippingAddress(): ?Address
    {
        return $this->getParameter('shippingAddress');
    }

	/**
	 * Get request data
	 */
    public function getData(): array
    {
		// Validate required parameters
		$this->validate(
			'clientId',
            'storeKey',
			'amount',
			'currency',
            'orderId',
            'returnUrl',
			'errorUrl',
		);

        $data = [
            // Preset parameters
			'clientid' => $this->getClientId(), // Note: this parameter name be passed with all lowercase characters!
            'storetype' => '3d_pay_hosting',
			'trantype' => 'PreAuth',
            'hashAlgorithm' => 'ver3',
            'rnd' => $this->getRandomString(),
			'refreshtime' => 3,

            // Mandatory input parameters
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'oid' => $this->getOrderId(),
            'okUrl' => $this->getReturnUrl(),
            'failUrl' => $this->getErrorUrl(),
            'lang' => $this->getLanguage() ?: 'en',

            // Optional input parameters
            'description' => $this->getDescription(),
            'shopUrl' => $this->getShopUrl(),
        ];

        $billing = $this->getBillingAddress();

        if ($billing) {
            $data += $billing->toArray();
        }

        $shipping = $this->getShippingAddress();

        if ($shipping) {
            $data += $shipping->toArray(false);
        }

        $data['hash'] = $this->getHash($data);

        return $data;
    }

    public function sendData($data): void
	{
        // Empty method, since unfortunately NestPay is dumb and needs a form submit to the endpoint
        throw new RuntimeException('NestPay does not support sending data directly. You must use the getSubmitUrl() method to get the URL to submit the data to.');
	}

    private function getRandomString(int $length = 20): string
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }

    public function getSubmitUrl(): string
    {
        return $this->getEndpointUrl('fim/est3Dgate');
    }
}
