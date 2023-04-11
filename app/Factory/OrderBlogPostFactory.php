<?php
declare(strict_types=1);

namespace App\Factory;

use App\Enums\OrderBlogPostEnum;
use Illuminate\Support\Str;

class OrderBlogPostFactory
{
    public static function make(string $param): ?OrderBlogPostEnum
    {
        foreach (OrderBlogPostEnum::cases() as $case) {
            if (Str::upper($case->name) === Str::upper($param)) {
                return $case;
            }
        }

        return null;
    }
}
