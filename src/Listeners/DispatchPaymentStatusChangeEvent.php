<?php

namespace SanderVanHooft\PayableRedirect\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
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

class DispatchPaymentStatusChangeEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaymentUpdated  $event
     * @return void
     */
    public function handle(PaymentUpdated $event)
    {
        $payment = $event->payment;
        switch ($payment->status) {
            case 'open':
                event(new PaymentOpened($payment));
                break;
            case 'cancelled':
                event(new PaymentCancelled($payment));
                break;
            case 'pending':
                event(new PaymentPending($payment));
                break;
            case 'expired':
                event(new PaymentExpired($payment));
                break;
            case 'failed':
                event(new PaymentFailed($payment));
                break;
            case 'paid':
                event(new PaymentPaid($payment));
                break;
            case 'paidout':
                event(new PaymentPaidOut($payment));
                break;
            case 'refunded':
                event(new PaymentRefunded($payment));
                break;
            case 'charged_back':
                event(new PaymentChargedBack($payment));
                break;
            default:
                break;
        }
    }
}
