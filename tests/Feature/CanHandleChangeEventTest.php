<?php

namespace SanderVanHooft\PayableRedirect\Feature;

use Illuminate\Support\Facades\Event;
use SanderVanHooft\PayableRedirect\AbstractTestCase;
use SanderVanHooft\PayableRedirect\Events\PaymentCancelled;
use SanderVanHooft\PayableRedirect\Events\PaymentChargedBack;
use SanderVanHooft\PayableRedirect\Events\PaymentExpired;
use SanderVanHooft\PayableRedirect\Events\PaymentFailed;
use SanderVanHooft\PayableRedirect\Events\PaymentOpened;
use SanderVanHooft\PayableRedirect\Events\PaymentPaid;
use SanderVanHooft\PayableRedirect\Events\PaymentPaidOut;
use SanderVanHooft\PayableRedirect\Events\PaymentPending;
use SanderVanHooft\PayableRedirect\Events\PaymentRefunded;
use SanderVanHooft\PayableRedirect\Events\PaymentUpdated;
use SanderVanHooft\PayableRedirect\Listeners\DispatchPaymentStatusChangeEvent;
use SanderVanHooft\PayableRedirect\Payment;

class CanHandleChangeEventTest extends AbstractTestCase
{
    /**
     * @dataProvider eventProvider
     * @test
     */
    public function canHandleFailedEvent($status, $eventName)
    {
        Event::fake();

        $listener = new DispatchPaymentStatusChangeEvent();

        $payment = new Payment();

        $payment->status = $status;

        $event = new PaymentUpdated($payment);

        $listener->handle($event);

        Event::assertDispatched($eventName);
    }

    public function eventProvider()
    {
        return [
            ['open', PaymentOpened::class],
            ['cancelled', PaymentCancelled::class],
            ['pending', PaymentPending::class],
            ['expired', PaymentExpired::class],
            ['failed', PaymentFailed::class],
            ['paid', PaymentPaid::class],
            ['paidout', PaymentPaidOut::class],
            ['refunded', PaymentRefunded::class],
            ['charged_back', PaymentChargedBack::class]
        ];
    }
}
