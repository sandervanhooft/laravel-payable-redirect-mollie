<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\Payment;

class FakePaymentGateway implements PaymentGateway
{
    /**
     * @param  Int
     * @param  Illuminate\Database\Eloquent\Model
     * @return Payment
     */
    public function chargeAmountForPayable(
        Int $amount,
        Model $payable,
        String $description,
        array $params = []
    ) : Payment {
        $payment = $payable->payments()->create([
            'amount' => $amount,
            'status' => 'open',
            'description' => $description,
            'redirect_url' => 'https://www.fake-payment-gateway-url.com/?ref=123456',
            'return_url' => $params['return_url'],
            'gateway_name' => $this->getGatewayName(),
            'gateway_payment_reference' => 'fake-reference',
        ]);

        return $payment;
    }

    /**
     * @return String
     */
    public function getGatewayName() : String
    {
        return self::class;
    }

    /**
     * @param  Payment
     * @return Payment
     */
    public function fetchUpdateFor(Payment $payment) : Payment
    {
        $payment->status = 'paid';
        $payment->redirect_url = null;
        $payment->save();
        return $payment;
    }
}
