<?php

namespace App\Console\Commands;

use App\Attribute\Description;
use App\Enums\CacheTagsEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class CacheTagsFlush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:tags-flush {tags?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache by site tags.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $menu = [];

        foreach ((new ReflectionClass(CacheTagsEnum::class))->getReflectionConstants() as $constant) {
            $description = new Description('Without attribute '.Description::class);

            if ($attributes = $constant->getAttributes(Description::class)) {
                $description = $attributes[0]->newInstance();
            }

            $menu[$constant->getValue()->value] = $description->description;
        }

        if ($tags = $this->argument('tags')) {
            $existTags = array_keys($menu);
            $intersectTags = array_intersect($tags, $existTags);

            if (!$intersectTags) {
                $this->error('Wrong input tags: '.implode(', ', $tags));

                return;
            }

            if (!$this->confirm('Flash cache for tags: '.implode(', ', $intersectTags).'?', true)) {
                return;
            }

            $tags = $intersectTags;
        } else {
            $tags = $this->choice('Choose tags for flushing', $menu, multiple: true);
        }

        if (Cache::tags($tags)->flush()) {
            $choices = array_filter($menu, fn($k) => \in_array($k, $tags), \ARRAY_FILTER_USE_KEY);
            $this->info('Cache was flushing by tags');

            $rows = array_reduce($tags, function ($carry, $tag) use ($menu) {;
                $carry[] = [$tag, $menu[$tag]];

                return $carry;
                }, []);

            $this->table(['tag', 'description'], $rows);
        } else {
            $this->warn('Something wrong. Flush was return false.');
        }
    }
}
