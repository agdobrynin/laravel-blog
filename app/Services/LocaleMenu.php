<?php
declare(strict_types=1);

namespace App\Services;

use App\Attribute\Description;
use App\Dto\LocaleMenuItemDto;
use App\Enums\LocaleEnums;
use Illuminate\Routing\Route;
use ReflectionEnumUnitCase;

class LocaleMenu
{
    /**
     * @var array
     */
    protected array $locales = [];

    public function __construct(LocaleEnums ...$locales)
    {
        foreach ($locales as $locale) {
            $description = (new ReflectionEnumUnitCase(LocaleEnums::class, $locale->name))
                ->getAttributes(Description::class)[0]->newInstance();
            $this->locales[$locale->value] = $description->description;
        }
    }

    /**
     * @return LocaleMenuItemDto[]
     */
    public function menu(Route $route): array
    {
        $items = [];

        foreach ($this->locales as $locale => $title) {
            $route->setParameter('locale', $locale);
            $url = route($route->getName(), $route->parameters());
            $items[] = new LocaleMenuItemDto($title, $url, $locale);
        }

        return $items;
    }

    public function titleByLocale(string $locale): ?string
    {
        return $this->locales[$locale] ?? null;
    }
}
