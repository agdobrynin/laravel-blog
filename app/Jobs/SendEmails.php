<?php

namespace App\Jobs;

use App\Enums\QueueNamesEnum;
use App\Models\User;
use App\Services\SendEmailsJobConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Mailable $mailable, public readonly User $user)
    {
        $this->onQueue(QueueNamesEnum::EMAIL->value);
    }

    /**
     * Execute the job.
     * @throws LimiterTimeoutException
     */
    public function handle(SendEmailsJobConfig $config): void
    {
        Redis::throttle(self::class)
            ->allow($config->maxLocks)
            ->every($config->timeLock)
            ->then(
                fn() => Mail::to($this->user)->send($this->mailable),
                fn() => $this->release($config->releaseDelay)
            );
    }
}
