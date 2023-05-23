<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Enums\LocaleEnums;
use Illuminate\Http\UploadedFile;

readonly class UserProfileDto
{
    public LocaleEnums $locale;

    public function __construct(
        string               $locale,
        public string        $name,
        public ?UploadedFile $avatar = null
    )
    {
        $foundIndex = array_search(
            $locale,
            array_column(LocaleEnums::cases(), 'value')
        );

        if ($foundIndex === false) {
            $foundIndex = 0;
        }

        $this->locale = LocaleEnums::cases()[$foundIndex];
    }
}
