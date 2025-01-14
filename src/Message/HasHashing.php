<?php

namespace Omnipay\NestPay\Message;

trait HasHashing
{
	/**
	 * Generate Hash signature as seen in the NestPay-provided example.
	 *
	 * Hashing algorithm defaults to v2, since it appears v3 is not yet fully supported.
	 *
	 * @param array $data Data to be hashed
	 * @param int $version Hashing algorithm (default: 2)
	 * @return string
	 */
    protected function getHash(array $data, int $version = 2): string
    {
        $values = [];

		switch ($version) {
			case 2:
				foreach ($data as $value) {
					$values[] = $this->escape($value);
				}
				break;

			case 3:
			default:
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
		}

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