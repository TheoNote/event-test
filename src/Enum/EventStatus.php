<?php

namespace App\Enum;

enum EventStatus: string
{
    case Draft = 'Draft';
    case Published = 'Published';
    case Cancelled = 'Cancelled';
}
