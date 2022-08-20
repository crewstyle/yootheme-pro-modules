<?php

include_once __DIR__ . '/src/MenuItemsListener.php';
include_once __DIR__ . '/src/Type/MenuItemsQueryType.php';
include_once __DIR__ . '/src/Type/MenuItemsType.php';

return [
    'events' => [
        'source.init' => [
            MenuItemsListener::class => ['initSource', -10],
        ],
    ],
];
