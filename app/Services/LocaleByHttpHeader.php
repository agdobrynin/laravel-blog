<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LocaleEnums;
use Illuminate\Http\Request;

final readonly class LocaleByHttpHeader
{
    public string $locale;

    public function __construct(Request $request, LocaleEnums ...$locales)
    {
        $availableLocalesValue = array_column($locales, 'value');
        $acceptLocalesValue = $request->getLanguages();
        $intersectLocales = array_values(array_intersect($acceptLocalesValue, $availableLocalesValue));

        $this->locale = $intersectLocales ? $intersectLocales[0] : LocaleEnums::cases()[0]->value;
    }
}
