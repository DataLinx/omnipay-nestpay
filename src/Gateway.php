<?php

namespace Omnipay\NestPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\NestPay\Message\AuthorizeRequest;
use Omnipay\NestPay\Message\CaptureRequest;
use Omnipay\NestPay\Message\PurchaseRequest;
use Omnipay\NestPay\Message\RefundRequest;
use Omnipay\NestPay\Message\VoidRequest;

class Gateway extends AbstractGateway
{
	/**
	 * Transaction type = Sale (no pre-auth)
	 */
	const TRAN_TYPE_SALE = 'Auth';

	/**
	 * Transaction type = Authorization + Capture
	 */
	const TRAN_TYPE_PREAUTH = 'PreAuth';

    public function getName(): string
    {
        return 'NestPay';
    }

    public function getDefaultParameters()
    {
        return [
            'clientId' => '',
			'username' => '',
			'password' => '',
            'storeKey' => '',
            'testMode' => true,
        ];
    }

    public function setClientId(string $client_id): self
    {
        return $this->setParameter('clientId', $client_id);
    }

    public function getClientId(): string
    {
        return $this->getParameter('clientId');
    }

	public function setUsername(string $username): self
	{
		return $this->setParameter('username', $username);
	}

	public function getUsername(): string
	{
		return $this->getParameter('username');
	}

	public function setPassword(string $password): self
	{
		return $this->setParameter('password', $password);
	}

	public function getPassword(): string
	{
		return $this->getParameter('password');
	}

    public function setStoreKey(string $store_key): self
    {
        return $this->setParameter('storeKey', $store_key);
    }

    public function getStoreKey(): string
    {
        return $this->getParameter('storeKey');
    }

    /**
     * Create an authorize request.
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

	/**
	 * Create a capture request.
	 *
	 * @param array $parameters Parameters
	 * @return \Omnipay\Common\Message\AbstractRequest|CaptureRequest
	 */
	public function capture(array $parameters = array())
	{
		return $this->createRequest(CaptureRequest::class, $parameters);
	}

	/**
	 * @param array $parameters
	 * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
	 */
	public function purchase(array $parameters = array())
	{
		return $this->createRequest(PurchaseRequest::class, $parameters);
	}

	/**
	 * Create a void request.
	 *
	 * @param array $parameters Parameters
	 * @return \Omnipay\Common\Message\AbstractRequest|VoidRequest
	 */
	public function void(array $parameters = array())
	{
		return $this->createRequest(VoidRequest::class, $parameters);
	}

    /**
     * Create a refund request.
     *
     * @param array $parameters Parameters
     * @return \Omnipay\Common\Message\AbstractRequest|RefundRequest
     */
	public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
}
