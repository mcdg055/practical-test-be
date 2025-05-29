<?php

namespace App\Enum;

enum LogAction: string
{
    case CREATED = 'CREATE';
    case UPDATED = 'UPDATE';
    case DELETED = 'DELETE';
    case LOGGED_IN = 'LOGIN';
    case LOGGED_OUT = 'LOGOUT';
}
