<?php

namespace App\Listeners;

use App\Enums\CacheTagsEnum;
use App\Enums\QueueNamesEnum;
use App\Models\CacheStat;
use App\Services\CacheStatQueueConfig;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class CacheStatSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string[]
     */
    protected array $appCacheTags;

    public string $queue = QueueNamesEnum::LOW->value;

    public function __construct(public readonly CacheStatQueueConfig $config)
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
            $this->throttle($event);
        }
    }

    public function handleCacheMissed(CacheMissed $event): void
    {
        if (array_intersect($this->appCacheTags, $event->tags)) {
            $this->throttle($event);
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CacheHit::class => 'handleCacheHit',
            CacheMissed::class => 'handleCacheMissed',
        ];
    }

    protected function throttle(CacheMissed|CacheHit $event):void
    {
        Redis::throttle(self::class.get_class($event))
            ->allow($this->config->maxLocks)
            ->every($this->config->timeLock)
            ->then(
                fn() => $this->updateStat($event),
                fn() => $this->release($this->config->releaseDelay)
            );
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
