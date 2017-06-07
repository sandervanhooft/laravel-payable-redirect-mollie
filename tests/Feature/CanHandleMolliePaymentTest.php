<?php

namespace SanderVanHooft\PayableRedirect\Feature\CanHandleMolliePaymentTest;

use SanderVanHooft\PayableRedirect\AbstractTestCase;
use SanderVanHooft\PayableRedirect\MolliePaymentGateway;
use SanderVanHooft\PayableRedirect\Payment;
use SanderVanHooft\PayableRedirect\TestModel;

class CanHandleMolliePaymentTest extends AbstractTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->paymentGateway = new MolliePaymentGateway;
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);
        $this->order = new TestModel(['amount' => 12345]);
        $this->order->save();
        $this->payment = $this->paymentGateway->chargeAmountForPayable(
            $this->order->amount,
            $this->order,
            'Some description',
            [
                'return_url' => 'http://www.sandervanhooft.nl',
                'webhook_url' => 'http://www.fake-webhook-url.com',
            ]
        );
    }

    /**
     * @test
     * @group integration
     * @group mollie
     */
    public function canCreatePaymentRequest()
    {
        $this->assertEquals(12345, $this->payment->amount);
        $this->assertEquals($this->paymentGateway->getGatewayName(), $this->payment->gateway_name);
        $this->assertEquals('open', $this->payment->status);
        $this->assertNotNull($this->payment->redirect_url);
        $this->assertNotNull($this->payment->gateway_payment_reference);
    }

    /**
     * @test
     * @group integration
     * @group mollie
     */
    public function canFetchPaymentUpdate()
    {
        $this->payment = $this->paymentGateway->fetchUpdateFor($this->payment);
        $this->assertEquals('open', $this->payment->status);
        $this->assertNotNull($this->payment->redirect_url);
    }

    /**
     * @test
     * @group integration
     * @group mollie
     */
    public function canHandleWebhookCallForExistingPayment()
    {
        $response = $this->json('POST', route('webhooks.payments.mollie'), [
            'id' => $this->payment->gateway_payment_reference,
        ]);
        
        $response->assertStatus(200);
    }

    /**
     * @test
     * @group mollie
     */
    public function canHandleWebhookCallForNonexistingPayment()
    {
        $response = $this->json('POST', route('webhooks.payments.mollie'), [
            'id' => 'non-existing',
        ]);
        
        $response->assertStatus(404);
    }
}
