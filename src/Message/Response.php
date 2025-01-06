<?php

namespace Omnipay\NestPay\Message;

use Exception;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use RuntimeException;

class Response extends AbstractResponse {

	/**
	 * @noinspection PhpMissingParentConstructorInspection
	 */
	public function __construct(RequestInterface $request, $data)
	{
		$this->request = $request;
		$this->data = $data;

		libxml_use_internal_errors(true);

		try {
			$xml = simplexml_load_string($data);

			if ($xml) {
				$this->data = (array)$xml;
			} else {
				throw new RuntimeException('Failed to parse XML response!');
			}
		} catch (Exception $ex) {
			throw new InvalidResponseException('Could not load response data: ' . $ex->getMessage(), $ex->getCode(), $ex);
		}
	}

	public function isSuccessful(): bool
	{
		return ($this->data['Response'] ?? null) === 'Approved';
	}

	public function getCode(): ?string
	{
		return $this->data['ProcReturnCode'] ?? null;
	}

	public function getMessage(): ?string
	{
		if ($this->isSuccessful()) {
			return $this->data['Response'];
		} elseif (isset($this->data['ErrMsg'])) {
			return (string)$this->data['ErrMsg'];
		}

		return null;
	}

}