<?php

namespace App\Enum;

enum RoleUser : string
{
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';
    case Organizer = 'ROLE_ORGANIZER';
}
