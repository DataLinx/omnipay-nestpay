<?php

namespace Omnipay\NestPay\Message;

class VoidRequest extends AbstractRequest {

	/**
	 * @inheritDoc
	 */
	public function getData()
	{
		$xml = parent::getData();

		$this->validate(
			'orderId',
		);

		$xml->addChild('Type', 'Void');
		$xml->addChild('OrderId', $this->getOrderId());

		return $xml;
	}
}