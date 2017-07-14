<?php

namespace SanderVanHooft\PayableRedirect;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\PayableRedirect\PaymentGateway;
use SanderVanHooft\PayableRedirect\Events\PaymentOpened;
use SanderVanHooft\PayableRedirect\Events\PaymentUpdated;

/**
 * @property string $status
 */
class Payment extends Model
{
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'updated' => PaymentUpdated::class,
        'created' => PaymentOpened::class,
    ];

    /**
     * Get all of the owning payable models.
     */
    public function payable()
    {
        return $this->morphTo();
    }
}
