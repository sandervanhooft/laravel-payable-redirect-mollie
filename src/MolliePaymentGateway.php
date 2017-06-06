<?php

namespace SanderVanHooft\PayableRedirect;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Omnipay\Omnipay;
use SanderVanHooft\PayableRedirect\PaymentGateway;
use SanderVanHooft\PayableRedirect\Payment;

class MolliePaymentGateway implements PaymentGateway
{
    private $gateway;
    private $response;

    public function __construct()
    {
        $this->response = false;
        $this->gateway = Omnipay::create('Mollie');
        $this->gateway->setApiKey(config('payable.mollie.key'));
    }

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
    ): Payment {
        $this->response = $this->gateway->purchase([
            "amount" => number_format($amount / 100, 2),
            "description" => $description,
            "return_url" => $params['return_url'],
            "notifyUrl" => $params['webhook_url'],
        ])->send();

        // create payment record for payable model
        $payment = $payable->payments()->create([
            'amount' => $amount,
            'status' => $this->response->getStatus(),
            'description' => $description,
            'redirect_url' => $this->response->getRedirectUrl(),
            'return_url' => $params['return_url'],
            'gateway_name' => $this->getGatewayName(),
            'gateway_payment_reference' => $this->response->getTransactionReference(),
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
        $this->response = $this->gateway->completePurchase([
            'transactionReference'   => $payment->gateway_payment_reference,
        ])->send();

        $status = $this->response->getStatus();
        $update = ['status' => $this->response->getStatus()];
        
        if ($status !== 'open') {
            $update['redirect_url'] = null;
        }

        $payment->update($update);

        return $payment;
    }
}
