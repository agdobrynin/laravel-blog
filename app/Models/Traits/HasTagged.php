<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Tag;
use App\Services\Contracts\TagsDictionaryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait HasTagged
{
    protected static string $tagPattern = '/#([^\#]+)#/m';
    protected static ?string $syncTagsFromField = 'content';
    protected static ?TagsDictionaryInterface $tagsDictionary = null;

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps()->as('tagged');
    }

    public function contentWithoutTags(): ?string
    {
        throw_if(
            !isset($this->{static::$syncTagsFromField}),
            message: 'Model "'.static::class.'" do not has attribute "'.static::$syncTagsFromField.'"'
        );

        if(static::$tagPattern) {
            return preg_replace(static::$tagPattern, '', $this->{static::$syncTagsFromField});
        }

        return $this->{static::$syncTagsFromField};
    }

    protected static function bootHasTagged(): void
    {
        if (App::has(TagsDictionaryInterface::class)) {
            static::$tagsDictionary = App::get(TagsDictionaryInterface::class);
        }

        if (static::$tagPattern && !empty(static::$syncTagsFromField)) {
            static::updating(fn($model) => static::syncTags($model));
            static::created(fn($model) => static::syncTags($model));
        }
    }

    /**
     * @param string $content
     * @return Collection|null
     */
    protected static function findTags(string $content): ?Collection
    {
        \preg_match_all(static::$tagPattern, $content, $foundSourceTags);

        if (!isset($foundSourceTags[1])) {
            return null;
        }

        $tags = self::$tagsDictionary ? self::$tagsDictionary->tags() : Tag::all();
        array_walk($foundSourceTags[1], fn(&$item) => $item = Str::upper($item));

        return $tags->filter(fn(Tag $item) => in_array(Str::upper($item->name), $foundSourceTags[1]));
    }

    protected static function syncTags($model): void
    {
        if (isset($model->{static::$syncTagsFromField})) {
            $foundTags = static::findTags($model->{static::$syncTagsFromField});

            if ($foundTags?->count()) {
                $model->tags()->sync($foundTags->pluck('id')->toArray());
            }
        }
    }
}
