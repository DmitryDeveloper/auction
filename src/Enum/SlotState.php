<?php

namespace App\Enum;

enum SlotState: string
{
    case PREPARATION = 'preparation';
    case PRE_AUCTION = 'pre-auction';
    case STARTED = 'started';
    case RESERVED = 'reserved';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
    case REJECTED = 'rejected';
}
