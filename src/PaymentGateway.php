<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\Payment;

interface PaymentGateway
{
    /**
     * @param  Int
     * @param  Illuminate\Database\Eloquent\Model
     * @return App\Payment
     */
    public function chargeAmountForPayable(
        Int $amount,
        Model $payable,
        String $description,
        array $params = []
    ) : Payment;

    /**
     * @return String
     */
    public function getGatewayName() : String;

    /**
     * @param  Payment
     * @return Payment
     */
    public function fetchUpdateFor(Payment $payment) : Payment;
}
