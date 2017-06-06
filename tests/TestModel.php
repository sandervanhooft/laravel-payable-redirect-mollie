<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\Payment;

class TestModel extends Model
{
    protected $guarded = [];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
