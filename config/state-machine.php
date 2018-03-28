<?php

return [
    'simple' => [
        // class of your domain object
        'class' => App\Order::class,

        // name of the graph (default is "default")
        'graph' => 'simple',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'state',

        // list of all possible states
        'states' => [
            'new',
            'pending_review',
            'awaiting_changes',
            'accepted',
            'rejected',
            'published',
        ],

        // list of all possible transitions
        'transitions' => [
            'create' => [
                'from' => ['new'],
                'to' => ['pending_review'],
            ],
            'ask_for_changes' => [
                'from' => ['pending_review', 'accepted'],
                'to' => ['awaiting_changes'],
                // to make the from states dependent on each other
                // (cant proceed unless if all from states are in the current states)
                // 'dependent' =>  true,
            ],
            'cancel_changes' => [
                'from' => ['awaiting_changes'],
                'to' => ['pending_review'],
            ],
            'submit_changes' => [
                'from' => ['awaiting_changes'],
                'to' => ['pending_review'],
            ],
            'approve' => [
                'from' => ['pending_review', 'rejected'],
                'to' => ['accepted'],
            ],
            'publish' => [
                'from' => ['accepted'],
                'to' => ['published'],
            ],
        ],

        'callbacks' => [

            'guard' => [
                'guard_on_creating' => [
                    'on' => ['create'],
                    'do' => 'App\your-directory\CreateCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_ask_for_changes' => [
                    'on' => ['ask_for_changes'],
                    'do' => 'App\your-directory\AskForChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_cancel_changes' => [
                    'on' => ['cancel_changes'],
                    'do' => 'App\your-directory\CancelChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_submitting' => [
                    'on' => ['submit_changes'],
                    'do' => 'App\your-directory\SubmitChangesCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_approving' => [
                    'on' => ['approve'],
                    'do' => 'App\your-directory\ApproveCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'guard_on_publishing' => [
                    'on' => ['publish'],
                    'do' => 'App\your-directory\PublishCallback@fire',
                    'args' => ['object', 'event', '"simple"'],
                ],
            ],

            // will be called before applying a transition
            'before' => [],
            // will be called after applying a transition
            'after' => [
                'after_creating' => [
                    'on' => ['create'],
                    'do' => 'App\your-directory\CreateCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_ask_for_changes' => [
                    'on' => ['ask_for_changes'],
                    'do' => 'App\your-directory\AskForChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_cancel_changes' => [
                    'on' => ['cancel_changes'],
                    'do' => 'App\your-directory\CancelChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_submitting' => [
                    'on' => ['submit_changes'],
                    'do' => 'App\your-directory\SubmitChangesCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_approving' => [
                    'on' => ['approve'],
                    'do' => 'App\your-directory\ApproveCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
                'after_publishing' => [
                    'on' => ['publish'],
                    'do' => 'App\your-directory\PublishCallback@addHistory',
                    'args' => ['object', 'event', '"simple"'],
                ],
            ],
        ],
    ],

];
