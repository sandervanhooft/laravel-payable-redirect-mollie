<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\Payment;
use SanderVanHooft\PayableRedirect\IsPayable\IsPayableTrait;

class TestModel extends Model
{
    use IsPayableTrait;

    protected $guarded = [];
}
