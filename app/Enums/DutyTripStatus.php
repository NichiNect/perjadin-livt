<?php

namespace App\Enums;

enum DutyTripStatus: string
{
    case Proposed = 'proposed';
    case Approved = 'approved';
    case Rejected = 'rejected';
}