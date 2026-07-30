<?php

return [
    // The resources to use, think of anything!? (We use precious metal now, but the sky is the limit)
    'resources' => [
        'gold' => [
            'type' => 'precious metal',
            'symbol' => 'Au',
        ],
        'palladium' => [
            'type' => 'precious metal',
            'symbol' => 'Pd',
        ],
        'silver' => [
            'type' => 'precious metal',
            'symbol' => 'Ag',
        ],
        'platinum' => [
            'type' => 'precious metal',
            'symbol' => 'Pt',
        ],
    ],
    // Switch for switching between the named convention of their respective symbol
    'use_symbol_as_column_header' => true,
    // These are the statuses that can be assigned to a mutation, you can add more if you want to
    'status' => [
        'Withdrawal requested',
        'Deposit requested',
        'Transfer requested',
        'Completed',
        'Processing',
        'Refunded',
        'Cancelled',
    ],
    // DO NOT EDIT! These are the basic permissions that will be installed with the package, you can add more if you want to
    'permissions' => [
        'Resource Balance' => [
            'View resource balance' => 'web',
        ],
    ],
];
