<?php

namespace App\Enums;

enum Throttle
{
    const LOW = 'custom.throttle:30,1';
    const MEDIUM = 'custom.throttle:50,1';
    const MEDIUM_HIGH = 'custom.throttle:80,1';
    const HIGH = 'custom.throttle:100,1';
    const VERY_HIGH = 'custom.throttle:150,1';
    
}
