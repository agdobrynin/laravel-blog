<?php
declare(strict_types=1);

namespace App\Attribute;

#[\Attribute]
class Description
{
    public function __construct(public string $description)
    {
    }
}
