<?php

namespace SanderVanHooft\PayableRedirect\IsPayable;

use SanderVanHooft\PayableRedirect\Payment;

trait IsPayableTrait
{
    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
