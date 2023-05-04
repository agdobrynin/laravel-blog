<?php

namespace App\Listeners;

use App\Enums\CacheTagsEnum;
use App\Enums\QueueNamesEnum;
use App\Models\CacheStat;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class CacheStatSubscriber implements ShouldQueue
{
    /**
     * @var string[]
     */
    protected array $appCacheTags;

    public string $queue = QueueNamesEnum::LOW->value;

    public function __construct()
    {
        $this->appCacheTags = array_column(CacheTagsEnum::cases(), 'value');
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(CacheHit|CacheMissed $event): bool
    {
        return !empty(array_intersect($this->appCacheTags, $event->tags));
    }

    public function handleCacheHit(CacheHit $event): void
    {
        if (array_intersect($this->appCacheTags, $event->tags)) {
            $this->updateStat($event);
        }
    }

    public function handleCacheMissed(CacheMissed $event): void
    {
        if (array_intersect($this->appCacheTags, $event->tags)) {
            $this->updateStat($event);
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CacheHit::class => 'handleCacheHit',
            CacheMissed::class => 'handleCacheMissed',
        ];
    }

    protected function updateStat(CacheMissed|CacheHit $event): void
    {
        $fieldStat = $event instanceof CacheHit ? 'hit' : 'mis';
        $tags = implode(', ', $event->tags);

        if ($stat = CacheStat::find($event->key)) {
            $stat->tags = $tags;
            $stat->{$fieldStat} = $stat->{$fieldStat} + 1;
            $stat->save();
        } else {
            CacheStat::create([
                'key' => $event->key,
                'tags' => $tags,
                $fieldStat => 1,
            ]);
        }
    }
}
