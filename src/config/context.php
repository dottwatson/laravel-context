<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Context storage Type
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the context storage below you wish
    | to use as your default context storage. Of course
    | you may use many storages at once.
    |
    */
    'storage' => 'session',

    /*
    |--------------------------------------------------------------------------
    | Default Context Initialization
    |--------------------------------------------------------------------------
    |
    | Here you may specify the context you wish to be automatically registered on startup,
    | and its relative storage type.
    |
    */
    'default' => [
        'application' => 'session'
    ],

    /*
    |--------------------------------------------------------------------------
    | Context Storages
    |--------------------------------------------------------------------------
    |
    | Here are each of the context storages setup for your application.
    | Of course, examples of configuring each context storage that is
    | supported is shown below to make development simple.
    | 
    | You can write your own handler, using the \Dottwaton\Context\Storages\Storage interface
    | 
    */
    'storages'=>[
        'memory' => [
            'handler'=>\Dottwatson\Context\Storages\StorageArray::class
        ],
        /*
        |--------------------------------------------------------------------------
        | Using session storeage, you have advantage of the fact that the context
        | will not be lose at the end of the script execution.
        | it will be resumed automatically.
        */ 
        'session'=> [
            /*
            |--------------------------------------------------------------------------
            | This is the main session key under witch the contexts are stored.
            | Each context will be stored using its name under this defined name
            */
            'name' => 'context',
            'handler'=>\Dottwatson\Context\Storages\StorageSession::class
        ],
    ]

];
