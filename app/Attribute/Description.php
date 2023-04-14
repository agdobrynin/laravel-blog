<?php
declare(strict_types=1);

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
class Description
{
    public function __construct(public string $description)
    {
    }
}
