# Omnipay: NestPay

**NestPay driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements NestPay support for Omnipay. It's implemented for Slovenia, but maybe it works with some other countries too.

## Installation

**Warning**: this library was made for internal DataLinx usage in a legacy project, so it's not published on Packagist. 
If you need a new, modern version, you can [hire us](mailto:info@datalinx.si) and we can implement it for you.

## Basic Usage

The following gateways are provided by this package:

* NestPay

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Supported operations
* Authorization
* Capture
* Purchase (authorization + capture)
* Void
* Refund

## Available languages
To select a language for the Hosted Payment Page, pass the "language" parameter to the Authorization requests:

* sl (Slovenian)
* en (English)

## Test cards

* `5450420130166055`, expiration: `12/2026`, CVV code: `000` - Mastercard  
Transaction result (success, failure...) is selected on 3DSecure page. 

## Reference
* [NestPay test backoffice](https://testsecurepay.eway2pay.com/activa/report/user.login)
* [NestPay Gateway documentation](https://testsecurepay.eway2pay.com/fim/resource/NestPay_Document_Set.zip)

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/datalinx/omnipay-nestpay/issues),
or better yet, fork the library and submit a pull request.
