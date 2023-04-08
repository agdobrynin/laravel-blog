<?php
return [
    'cache' => [
        'enabled' => env('ROLES_CACHE_ENABLED', true),
        // Cache expire after "x" seconds
        'ttl' => env('ROLES_CACHE_TTL', 3600),
    ]
];
