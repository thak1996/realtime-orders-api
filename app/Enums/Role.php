<?php

namespace App\Enums;

use Ramsey\Uuid\Type\Integer;

enum Role: int
{
    case Admin = 1;
    case Store = 2;
    case Customer = 3;
}
