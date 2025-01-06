<?php

namespace Omnipay\NestPay;

class Address
{
    protected ?string $companyName = null;

    protected ?string $fullName = null;

    protected ?string $addressLine1 = null;

    protected ?string $addressLine2 = null;

    protected ?string $city = null;

    protected ?string $state = null;

    protected ?string $postalCode = null;

    protected ?string $countryCode = null;

    /**
     * Get the company name.
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Set the company name.
     *
     * @param string|null $companyName
     * @return self
     */
    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * Get the full name.
     *
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * Set the full name.
     *
     * @param string|null $fullName
     * @return self
     */
    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Get the first address line.
     *
     * @return string|null
     */
    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    /**
     * Set the first address line.
     *
     * @param string|null $addressLine1
     * @return self
     */
    public function setAddressLine1(?string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * Get the second address line.
     *
     * @return string|null
     */
    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    /**
     * Set the second address line.
     *
     * @param string|null $addressLine2
     * @return self
     */
    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * Get the city.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set the city.
     *
     * @param string|null $city
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get the state.
     *
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set the state.
     *
     * @param string|null $state
     * @return self
     */
    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get the postal code.
     *
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Set the postal code.
     *
     * @param string|null $postalCode
     * @return self
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * Get the country alpha-3 code.
     *
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Set the country alpha-3 code.
     *
     * @param string|null $countryCode
     * @return self
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function toArray(bool $asBilling = TRUE): array
    {
        $result = [];

        // Iterate over object properties
        $properties = [
            'companyName' => 'Company',
            'fullName' => 'Name',
            'addressLine1' => 'Street1',
            'addressLine2' => 'Street2',
            'city' => 'City',
            'state' => 'StateProv',
            'postalCode' => 'PostalCode',
            'countryCode' => 'Country',
        ];

        foreach ($properties as $src => $target) {
            $value = $this->$src;

            // Add to array if not null
            if ($value !== null) {
                $result[($asBilling ? "BillTo" : 'ShipTo') . $target] = $value;
            }
        }

        return $result;
    }
}