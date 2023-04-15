<?php
return [
    'cache' => [
        'enabled' => env('TAGS_DICTIONARY_CACHE_ENABLED', true),
        // Cache expire after "x" seconds, if not defined ttl cache remember forever.
        'ttl' => env('TAGS_DICTIONARY_TTL'),
    ]
];
