<?php

include_once __DIR__ . '/src/StickyPostsListener.php';
include_once __DIR__ . '/src/Type/StickyPostsQueryType.php';

return [
    'events' => [
        'source.init' => [
            StickyPostsListener::class => ['initSource', -10],
        ],
    ],
];
