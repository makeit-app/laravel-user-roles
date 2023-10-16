<?php

return [
    'default_role' => 'user',
    'observersToRegister' => [
        \App\Models\User::class => \App\Observers\UserObserver::class,
    ],
    'policiesToRegister' => [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ],
];
