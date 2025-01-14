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
		$required = ['clientid', 'oid', 'HASH', 'HASHPARAMS', 'HASHPARAMSVAL'];

		foreach ($required as $key) {
			if ( ! isset($this->data[$key])) {
				throw new InvalidResponseException('Missing required parameter: '. $key);
			}
		}

		$values = [];
		$escaped_values = [];

		$rx_hash_params = explode('|', $this->data['HASHPARAMS']); // Received list of parameters that are used for calculating hash

		foreach ($rx_hash_params as $param) {
			$vl = $this->data[$param] ?? '';

			$values[] = $vl;
			$escaped_values[] = $this->escape($vl);
		}

		$escaped_values[] = $this->escape($this->getStoreKey());

		$our_hash_value_str = implode('|', $escaped_values);
		$rx_hash_value_str = $this->data['HASHPARAMSVAL'] . '|' . $this->escape($this->getStoreKey()); // Received hash value string

		if ($our_hash_value_str != $rx_hash_value_str) {
			throw new InvalidResponseException('Invalid hash parameter values received in response from NestPay! Expected: '. $our_hash_value_str .', got: '. $rx_hash_value_str);
		}

		$values[] = $this->getStoreKey();

		$hash = $this->getHash($values);

		if ($hash !== $this->data['HASH']) {
			throw new InvalidResponseException('Invalid hash received in response from NestPay! Expected: '. $hash .', got: '. $this->data['HASH']);
		}

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
