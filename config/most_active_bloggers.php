<?php
/*
 * Configuration for block "Most active bloggers"
 * This config use in View/Composers/MostActiveBloggersComposer.php
 */
return [
    'take' => env('MOST_ACTIVE_BLOGGER_TAKE_USERS', 5),
    'cache_ttl' => env('MOST_ACTIVE_BLOGGER_CACHE_TTL', 1800),
    'last_month' => env('MOST_ACTIVE_BLOGGER_LAST_MONTH'),
    'min_count_post' => env('MOST_ACTIVE_BLOGGER_MIN_POSTS', 8),
];
