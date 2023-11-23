<?php

namespace App\Enum;

enum BidState: string
{
    case PROCESSING = 'processing';
    case FAILED = 'failed';
    case SUCCESS = 'success';
}
