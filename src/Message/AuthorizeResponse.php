<?php
namespace Omnipay\NestPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\NestPay\Gateway;

class AuthorizeResponse extends AbstractResponse {

    use HasHashing;

    protected Gateway $gateway;

    public function __construct(array $data, Gateway $gateway)
    {
		// Call parent constructor with empty request, since with NestPay the flow is different
		parent::__construct($gateway->authorize(), $data);

        $this->gateway = $gateway;

		$this->validate();
    }

    /**
	 * Was the auth. process successful?
	 */
	public function isSuccessful(): bool
	{
        return ($this->data['Response'] ?? null) === 'Approved';
	}

    /**
	 * Get transaction reference
	 */
	public function getTransactionReference(): string
    {
        return '';
    }

    public function getMessage(): ?string
    {
		if (! empty($this->data['ErrMsg'])) {
			return $this->data['ErrMsg'];
		}

        $parts = [];

		if (isset($this->data['mdStatus'])) {
			switch ($this->data['mdStatus']) {
				case 0:
					$str = 'Authentication failed';
					break;
				case 1:
					$str = 'Authentication successful';
					break;
				case 2:
				case 3:
				case 4:
					$str = 'Card not participating or attempt';
					break;
				default:
					$str = 'Authentication not available or system error';
			}

			$parts[] = '3DS status: '. $str .' (code: '. $this->data['mdStatus'] .')';
		}

		if (isset($this->data['ErrCode'])) {
			$parts[] = 'ErrCode = '. $this->data['ErrCode'];
		}

		if (isset($this->data['Response'])) {
			$parts[] = 'Response = '. $this->data['Response'];
		}

		return implode(', ', $parts);
    }

    public function getCode(): ?string
    {
        return $this->data['ProcReturnCode'] ?? null;
    }

    public function validate(): void
    {
		$required = ['clientid', 'oid', 'HASH'];

		foreach ($required as $key) {
			if ( ! isset($this->data[$key])) {
				throw new InvalidResponseException('Missing required parameter: '. $key);
			}
		}

		/*
		 * TODO Fix hash validation
		$hash = $this->getHash($this->data);

		if ($hash !== $this->data['HASH']) {
			throw new InvalidResponseException('Invalid hash received in response from NestPay! Expected: '. $hash .', got: '. $this->data['HASH']);
		}
		*/

		if ($this->data['clientid'] !== $this->gateway->getClientId()) {
			throw new InvalidResponseException('Invalid client ID received in response from NestPay! Expected: '. $this->gateway->getClientId() .', got: '. $this->data['clientid']);
		}
    }

    protected function getStoreKey(): string
    {
        return $this->gateway->getStoreKey();
    }

	public function is3DSecure(): bool
	{
		return ($this->data['Response'] ?? null) === 'Approved' && ($this->data['mdStatus'] ?? null) === '1';
	}
}
