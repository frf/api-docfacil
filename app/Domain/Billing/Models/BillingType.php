<?php

namespace Domain\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BillingType extends Collection
{
    const TYPE_NORMAL = 'normal';
    const TYPE_RECURRENCE = 'recurrence';
}
