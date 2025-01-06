<?php

namespace Omnipay\NestPay\Message;

trait HasHashing
{
    /**
     * Generate Hash signature as seen in the NestPay-provided example.
     */
    protected function getHash(array $data): string
    {
        $values = [];

		$skip = [
			'encoding',
			'hash',
		];

		ksort($data, SORT_NATURAL | SORT_FLAG_CASE);

		foreach ($data as $key => $value) {
			if ( ! in_array(strtolower($key), $skip, true)){
				$values[] = $this->escape($value ?? '');
			}
		}

        $values[] = $this->escape($this->getStoreKey());

        return base64_encode(pack('H*', hash('sha512', implode('|', $values))));
    }

    /**
     * Escape the provided string, as seen in the NestPay provided example.
     */
    protected function escape(string $value): string
    {
        return str_replace("|", "\\|", str_replace("\\", "\\\\", $value));
    }
}