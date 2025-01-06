<?php

namespace Omnipay\NestPay\Message;

class CaptureRequest extends AbstractRequest {

	/**
	 * @inheritDoc
	 */
	public function getData()
	{
		$xml = parent::getData();

		$this->validate(
			'orderId',
		);

		$xml->addChild('Type', 'PostAuth');
		$xml->addChild('OrderId', $this->getOrderId());

		return $xml;
	}
}