# LaravelPayableRedirectMollie

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Easily implement Mollie payments in your Laravel app. This package provides a Mollie webhook handler and an Eloquent Payment model. It is built on top of the Omnipay/Mollie package.

## Structure

```        
config/
database/
docs/
routes/
src/
tests/
```


## Install

Via Composer

``` bash
$ composer require sander-van-hooft/laravel-payable-redirect-mollie
```

Next, you must install the service provider:

``` php
// config/app.php
'providers' => [
    ...
    SanderVanHooft\PayableRedirect\PayableRedirectServiceProvider::class,
];
```

And add the Mollie API key to the `.env` file in your project root.
This is also where you can override the webhook route which Mollie calls when a payment status is updated:
```
# /.env:
...
MOLLIE_KEY=YOUR_MOLLIE_API_KEY_HERE
# MOLLIE_WEBHOOK_URL=your_url_relative/to_your_app_url
```

You can publish the migration with:

``` bash
$ php artisan vendor:publish --provider="SanderVanHooft\PayableRedirect\PayableRedirectServiceProvider" --tag="migrations"
```

After the migration has been published you can create the payments-table by running the migrations:

``` bash
$ php artisan migrate
```

Laravel automatically loads the routes from this package.

If you prefer this over configuring using the `.env` file (not required!) you can also publish the `payable.php` config file with:

``` bash
$ php artisan vendor:publish --provider="SanderVanHooft\PayableRedirect\PayableRedirectServiceProvider" --tag="config"
```

In the config file, you can set the MOLLIE api key and override the default mollie payment webhook route. This is what the default config file looks like:

``` php
return [
    'mollie' => [
        'key' => env('MOLLIE_KEY', 'test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
        'webhook_url' => env('MOLLIE_WEBHOOK_URL', '/webhooks/payments/mollie'),
    ],
];
```

## Usage

In your code, create a Payment record using the MolliePaymentGateway:
``` php
// Using some App\Order model (provided by you)

$order = new App\Order(['amount' => 12345]);
$order->save();

$payment = $this->paymentGateway->chargeAmountForPayable(
    $order->amount,
    $order,
    'Some description',
    [ 'return_url' => 'http://some-return-url.com' ]
);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email info@sandervanhooft.nl instead of using the issue tracker.

## Credits

- [Sander van Hooft][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sander-van-hooft/laravel-payable-redirect-mollie.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/sander-van-hooft/laravel-payable-redirect-mollie/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/sander-van-hooft/laravel-payable-redirect-mollie.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/sander-van-hooft/laravel-payable-redirect-mollie.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sander-van-hooft/laravel-payable-redirect-mollie.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/sander-van-hooft/laravel-payable-redirect-mollie
[link-travis]: https://travis-ci.org/sander-van-hooft/laravel-payable-redirect-mollie
[link-scrutinizer]: https://scrutinizer-ci.com/g/sander-van-hooft/laravel-payable-redirect-mollie/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/sander-van-hooft/laravel-payable-redirect-mollie
[link-downloads]: https://packagist.org/packages/sander-van-hooft/laravel-payable-redirect-mollie
[link-author]: https://github.com/sandervanhooft
[link-contributors]: ../../contributors
