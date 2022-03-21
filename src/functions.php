<?php
use Dottwatson\Context\ContextManager;

/**
 * returns an instance of ContextManager
 *
 * @return Dottwatson\Context\ContextManager
 */
function context()
{
    return (new ContextManager);
}