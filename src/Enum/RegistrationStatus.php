<?php

namespace App\Enum;

enum RegistrationStatus: string
{

    case Confirmed = 'Confirmed';
    case Waitlist = 'Waitlist';
    case Cancelled = 'Cancelled';
}
