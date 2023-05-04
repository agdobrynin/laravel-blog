<?php

namespace App\Console\Commands;

use App\Attribute\Description;
use App\Enums\CacheTagsEnum;
use App\Models\CacheStat;
use Illuminate\Console\Command;
use ReflectionClass;

class ShowCacheStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-cache-stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show current statistic for cache missed or hit';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $legend = [];

        foreach ((new ReflectionClass(CacheTagsEnum::class))->getReflectionConstants() as $constant) {
            $description = new Description('Without attribute ' . Description::class);

            if ($attributes = $constant->getAttributes(Description::class)) {
                $description = $attributes[0]->newInstance();
            }

            $legend[] = [$constant->getValue()->value, $description->description];
        }

        $this->info('Legend of cache tags');

        $this->table(
            ['Cache tag', 'Description'],
            $legend
        );

        // TODO my be set pagination or filter for this output 😏
        $this->table(
            ['Key value', 'With cache tags', 'hit', 'missed'],
            CacheStat::all(['key', 'tags', 'hit', 'mis'])->toArray()
        );
    }
}
