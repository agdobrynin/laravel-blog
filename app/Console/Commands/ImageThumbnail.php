<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImageThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:image-thumbnail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear thumbnails for images (avatars, blog post)';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // TODO implement this functional.
        throw new \LogicException('Not implemented yet.');
    }
}
