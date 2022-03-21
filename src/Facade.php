<?php

namespace Dottwaton\Context;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Dottwaton\Context\ContextManager;

class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return ContextManager::class;
    }
}
