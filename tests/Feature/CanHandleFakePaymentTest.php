<?php

namespace SanderVanHooft\PayableRedirect\Feature;

use Illuminate\Support\Facades\Event;
use SanderVanHooft\PayableRedirect\AbstractTestCase;
use SanderVanHooft\PayableRedirect\Events\PaymentOpened;
use SanderVanHooft\PayableRedirect\Events\PaymentUpdated;
use SanderVanHooft\PayableRedirect\FakePaymentGateway;
use SanderVanHooft\PayableRedirect\Payment;
use SanderVanHooft\PayableRedirect\TestModel;

class CanHandleFakePaymentTest extends AbstractTestCase
{
    protected function setUp()
    {
        parent::setUp();
        Event::fake();
        $this->paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);

        $this->order = new TestModel(['amount' => 12345]);
        $this->order->save();
    }

    /** @test */
    public function canCreatePaymentRequest()
    {
        $payment = $this->paymentGateway->chargeAmountForPayable(
            $this->order->amount,
            $this->order,
            'Some description',
            [ 'return_url' => 'http://fake-return-url.com' ]
        );
        
        $this->assertEquals(12345, $payment->amount);
        $this->assertEquals($this->paymentGateway->getGatewayName(), $payment->gateway_name);
        $this->assertEquals('open', $payment->status);
        $this->assertEquals('Some description', $payment->description);
        $this->assertNotNull($payment->redirect_url);
        $this->assertNotNull($payment->return_url);
        $this->assertNotNull($payment->gateway_payment_reference);
        Event::assertDispatched(PaymentOpened::class, function ($e) use ($payment) {
            return $e->payment->id === $payment->id;
        });
    }

    /** @test */
    public function canFetchPaymentUpdate()
    {
        $payment = $this->order->payments()->save(new Payment([
            'amount' => 12345,
            'status' => 'open',
            'description' => 'Some fake description',
            'return_url' => 'http://www.return-to-here.com',
            'redirect_url' => 'http://www.redirect-to-here.com',
            'gateway_name' => 'FakePaymentGateway',
            'gateway_payment_reference' => 'fake-external-id-1',
        ]));
        
        $payment = $this->paymentGateway->fetchUpdateFor($payment);
        $this->assertEquals('paid', $payment->status);
        $this->assertNull($payment->redirect_url);
    }

    /** @test */
    public function dispatchesPaymentUpdatedEventIfChanged()
    {
        $payment = $this->order->payments()->save(new Payment([
            'amount' => 12345,
            'status' => 'open',
            'description' => 'Some fake description',
            'return_url' => 'http://www.return-to-here.com',
            'redirect_url' => 'http://www.redirect-to-here.com',
            'gateway_name' => 'FakePaymentGateway',
            'gateway_payment_reference' => 'fake-external-id-1',
        ]));
        
        $payment = $this->paymentGateway->fetchUpdateFor($payment);
        Event::assertDispatched(PaymentUpdated::class, function ($e) use ($payment) {
            return $e->payment->id === $payment->id;
        });
    }

    /** @test */
    public function doesNotdispatchPaymentUpdatedEventIfNotChanged()
    {
        $payment = $this->order->payments()->save(new Payment([
            'amount' => 12345,
            'status' => 'paid',
            'description' => 'Some fake description',
            'return_url' => 'http://www.return-to-here.com',
            'redirect_url' => null,
            'gateway_name' => 'FakePaymentGateway',
            'gateway_payment_reference' => 'fake-external-id-1',
        ]));
        
        $payment = $this->paymentGateway->fetchUpdateFor($payment);
        Event::assertNotDispatched(PaymentUpdated::class, function ($e) use ($payment) {
            return $e->payment->id === $payment->id;
        });
    }
}
