<?php

include_once __DIR__ . '/src/ExtendedUserListener.php';
include_once __DIR__ . '/src/Type/ExtendedUserType.php';

return [
    'events' => [
        'source.init' => [
            ExtendedUserListener::class => 'initSource',
        ],
    ],
];
