<?php

namespace Omnipay\NestPay\Message;

class PurchaseRequest extends AuthorizeRequest {

	public function getData(): array
	{
		$data = parent::getData();

		$data['trantype'] = 'Auth';

		// Hash has to be recalculated
		$data['hash'] = $this->getHash($data);

		return $data;
	}

}