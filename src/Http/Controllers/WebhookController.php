<?php

namespace SanderVanHooft\PayableRedirect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SanderVanHooft\PayableRedirect\Payment;

class WebhookController extends Controller
{

    public function handle(Request $request)
    {
        $payment = Payment::where('gateway_payment_reference', $request->id)->firstOrFail();
        $paymentGateway = new $payment->gateway_name;
        $paymentGateway->fetchUpdateFor($payment);
        return response()->json(null, 200);
    }
}
