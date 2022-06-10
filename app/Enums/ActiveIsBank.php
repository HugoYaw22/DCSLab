<?php

namespace App\Enums;

use App\Traits\EnumHelper;

enum ActiveIsBank: int
{
    use EnumHelper;

    case ACTIVE = 1;
    case INACTIVE = 0;
}