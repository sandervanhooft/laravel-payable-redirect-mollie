# LaravelPayableRedirectMollie

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Implementing Mollie payments in your Laravel app does not have to be difficult. This package helps you by creating payment records and keeping the status in sync with Mollie. It is built on top of the very solid Omnipay/Mollie package. It supports one-off payments only; recurring payments are not supported.

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
``` env
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

### Basic usage
In your code, create a Payment record using the MolliePaymentGateway:

``` php
// Using some App\Order model (provided by you)

$order = new App\Order(['amount' => 12345]);
$order->save();

$paymentGateway = new SanderVanHooft\PayableRedirect\MolliePaymentGateway;

$payment = $paymentGateway->chargeAmountForPayable(
    $order->amount, // AMOUNT IN CENTS!!
    $order,
    'Some description',
    [ 'return_url' => 'http://some-return-url.com' ]
);
```

__The payment amount is in eurocents!__

The payment status will be kept in sync with Mollie: Mollie will call the webhook whenever the payment status changes. This will trigger your app to fetch the latest payment status from Mollie. Mollie has designed this process in this way for security reasons.

### IsPayableTrait
For convenience you can use the `isPayableTrait` on your payable Eloquent model (the `App\Order` model in the example above). This enables you to call `$order->payments`.

``` php
use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\Payment;
use SanderVanHooft\PayableRedirect\IsPayable\IsPayableTrait;

class Order extends Model
{
    use IsPayableTrait;
}
```

### Events
PaymentEvents are dispatched for easy integration with your own custom listeners (see [Laravel events and listeners](https://laravel.com/docs/5.4/events)). The following events are available:

- PaymentUpdated: this event is dispatched when Mollie calls the webhook. It checks whether the payment status really has changed. Depending on the new status, it dispatches one of the [Mollie based](https://www.mollie.com/nl/docs/status) events below.
- PaymentCancelled
- PaymentChargedBack
- PaymentExpired
- PaymentFailed
- PaymentOpened
- PaymentPaid
- PaymentPaidOut
- PaymentPending
- PaymentRefunded

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

Please mind that for testing the payment status synchronisation your app needs to be reachable on a public url by Mollie. Therefore, under normal circumstances, you cannot fully test this functionality on a local Laravel installation.

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
