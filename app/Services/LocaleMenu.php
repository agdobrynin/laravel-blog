<?php
declare(strict_types=1);

namespace App\Services;

use App\Attribute\Description;
use App\Dto\LocaleMenuItemDto;
use App\Enums\LocaleEnums;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use ReflectionEnumUnitCase;

readonly class LocaleMenu
{
    public array $localeWithDescription;

    /**
     * @var Collection<LocaleEnums>
     */
    protected Collection $localeCollection;

    public function __construct(LocaleEnums ...$locales)
    {
        $this->localeCollection = collect($locales);

        $this->localeWithDescription = $this->localeCollection
            ->reduce(function ($acc, LocaleEnums $enums){
                $acc[$enums->value] = $this->getLocaleWithDescription($enums);

                return $acc;
        }, []);
    }

    /**
     * @return LocaleMenuItemDto[]
     */
    public function menu(Route $route): array
    {
        $items = [];

        foreach ($this->localeCollection as $locale) {
            $route->setParameter('locale', $locale->value);
            $url = route($route->getName(), $route->parameters());
            $items[] = new LocaleMenuItemDto($this->localeWithDescription[$locale->value], $url);
        }

        return $items;
    }

    public function titleByLocale(string $locale): ?string
    {
        if ($enum = $this->localeCollection->firstWhere('value', $locale)) {
            return $this->localeWithDescription[$enum->value];
        }

        return null;
    }

    protected function getLocaleWithDescription(LocaleEnums $enum): string
    {
        return (new ReflectionEnumUnitCase(LocaleEnums::class, $enum->name))
            ->getAttributes(Description::class)[0]->newInstance()->description;
    }
}
