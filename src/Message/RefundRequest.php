<?php

namespace Omnipay\NestPay\Message;

class RefundRequest extends AbstractRequest {

	/**
	 * @inheritDoc
	 */
	public function getData()
	{
		$xml = parent::getData();

		$this->validate(
			'orderId',
		);

		$xml->addChild('Type', 'Credit');
		$xml->addChild('OrderId', $this->getOrderId());

		return $xml;
	}
}