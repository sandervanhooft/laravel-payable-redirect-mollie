<?php

namespace SanderVanHooft\PayableRedirect;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mollie_API_Client;
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
        $this->gateway = new Mollie_API_Client;
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

        $this->response = $this->gateway->payments->create(array(
            "amount" => number_format($amount / 100, 2),
            "description" => $description,
            "redirectUrl" => $params['return_url'],
            "webhookUrl"  => $params['webhook_url'],
        ));

        // create payment record for payable model
        $payment = $payable->payments()->create([
            'amount' => $amount,
            'status' => $this->response->status,
            'description' => $description,
            'redirect_url' => $this->response->links->paymentUrl,
            'return_url' => $this->response->links->redirectUrl,
            'gateway_name' => $this->getGatewayName(),
            'gateway_payment_reference' => $this->response->id,
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
        $this->response = $this->gateway->payments->get($payment->gateway_payment_reference);
        
        $status = $this->response->status;
        $update = ['status' => $status];

        if ($status !== 'open') {
            $update['redirect_url'] = null;
        }

        $payment->update($update);

        return $payment;
    }
}
