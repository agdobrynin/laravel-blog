<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Contracts\DtoFromRequest;
use App\Enums\LocaleEnums;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class UserProfileDto implements DtoFromRequest
{
    public function __construct(
        public LocaleEnums $locale,
        public string $name,
        public ?UploadedFile $uploadedFile
    )
    {
    }

    public static function fromRequest(Request $request): UserProfileDto
    {
        $foundIndex = array_search(
            $request->input('locale'),
            array_column(LocaleEnums::cases(), 'value')
        );

        if ($foundIndex === false) {
            $foundIndex = 0;
        }

        return new self(
            LocaleEnums::cases()[$foundIndex],
            $request->input('name'),
            $request->file('avatar')
        );
    }
}
