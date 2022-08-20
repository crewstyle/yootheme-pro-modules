<?php

include_once __DIR__ . '/src/PopularPostsListener.php';
include_once __DIR__ . '/src/Type/PopularPostsQueryType.php';

return [
    'events' => [
        'source.init' => [
            PopularPostsListener::class => ['initSource', -10],
        ],
    ],
];
