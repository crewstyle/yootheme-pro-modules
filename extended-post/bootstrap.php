<?php

include_once __DIR__ . '/src/ExtendedPostListener.php';
include_once __DIR__ . '/src/Type/ExtendedPostType.php';

return [
    'events' => [
        'source.init' => [
            ExtendedPostListener::class => 'initSource',
        ],
    ],
];
