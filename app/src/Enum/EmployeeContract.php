<?php

namespace App\Enum;

enum EmployeeContract: string
{
    case CDD = 'cdd';
    case CDI = 'cdi';
    case FREELANCE = 'freelance';
}