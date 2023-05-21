<?php
declare(strict_types=1);

namespace App\Dto\Request;

use App\Contracts\DtoFromRequest;
use App\Enums\LocaleEnums;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

readonly class UserProfileDto implements DtoFromRequest
{
    public function __construct(
        public LocaleEnums   $locale,
        public string        $name,
        public ?UploadedFile $uploadedFile
    )
    {
    }

    public static function fromRequest(Request $request): static
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'avatar' => [
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:3500',
                'dimensions:min_width=50,min_height=50,max_width=4000,max_height=4000'
            ],
            'locale' => [
                'required',
                Rule::in(array_column(LocaleEnums::cases(), 'value')),
            ],
        ]);

        $foundIndex = array_search(
            $data['locale'],
            array_column(LocaleEnums::cases(), 'value')
        );

        if ($foundIndex === false) {
            $foundIndex = 0;
        }

        return new static(LocaleEnums::cases()[$foundIndex], $data['name'], $data['avatar'] ?? null);
    }
}
