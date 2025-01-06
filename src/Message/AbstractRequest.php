<?php
namespace Omnipay\NestPay\Message;

use SimpleXMLElement;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const LIVE_HOST = 'https://activa.eway2pay.com';
    const TEST_HOST = 'https://testsecurepay.eway2pay.com';

    public function setClientId($username)
    {
        return $this->setParameter('clientId', $username);
    }

    public function getClientId()
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

	public function setOrderId(string $id): self
	{
		return $this->setParameter('orderId', $id);
	}

	public function getOrderId(): string
	{
		return $this->getParameter('orderId');
	}

	/**
	 * Get common request data
	 *
	 * @return SimpleXMLElement
	 */
	public function getData()
	{
		$this->validate(
			'clientId',
			'username',
			'password',
		);

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><CC5Request></CC5Request>');

		$xml->addChild('Name', $this->getUsername());
		$xml->addChild('Password', $this->getPassword());
		$xml->addChild('ClientId', $this->getClientId());

		return $xml;
	}

	/**
	 * @inheritDoc
	 */
	public function sendData($data)
	{
		$url = $this->getEndpointUrl('fim/api');

		$this->httpClient->setConfig([
			'curl.options' => [
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
			],
		]);

		$httpResponse = $this->httpClient->post($url, null, [
			'DATA' => $this->getData()->asXML(),
		])->send();

		return $this->response = new Response($this, $httpResponse->getBody());
	}

    protected function getEndpointUrl(?string $endpoint = NULL): string
    {
        return ($this->getTestMode() ? self::TEST_HOST : self::LIVE_HOST) . '/' . $endpoint;
    }
}
