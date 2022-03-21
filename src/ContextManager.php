<?php

namespace Dottwatson\Context;

use Dottwatson\Context\Exception\UnregisteredContextException;
use Dottwatson\Context\Exception\AlreadRegisteredContextException;

class ContextManager
{
    /**
     * The collection of Contexts
     * @var array
     */
    protected static $contexts = [];

    protected static $contextsInitialized = [];


    /**
     * the current context name
     *
     * @var string|null
     */
    protected static $currentContext;
    
    /**
     * get current or specific context
     *
     * @param string|null $name
     * @return \Dottwatson\Context\Storages\Storage|Dottwatson\Context\Exception\UnregisteredContextException
     */
    public function getContext(string $name = null)
    {
        if(is_null($name) && is_null(static::$currentContext) && !static::$contexts){
            throw new UnregisteredContextException("No contexts are registered.");
        }
        //returns first available context
        elseif(is_null($name) && is_null(static::$currentContext)){
            $keys                   = array_keys(static::$contexts);
            $firstAvailableContext  = array_shift($keys);
            return static::getInitializedContext($firstAvailableContext);
        }
        //returns this current context
        elseif(is_null($name)){
            return static::getInitializedContext(static::$currentContext);
        }
        else{
            if(!$this->isContext($name)){
                throw new UnregisteredContextException("{$name} is not a context.");
            }

            return static::getInitializedContext($name);
        }
    }

    /**
     * returns the context in lazyloading
     *
     * @param string $name
     * @return \Dottwatson\Context\Storages\Storage
     */
    protected static function getInitializedContext(string $name)
    {
        if(!in_array($name,static::$contextsInitialized)){
            $handler = static::$contexts[$name];
            static::$contexts[$name] = new $handler($name);
            static::$contextsInitialized[] = $name;
        }

        return static::$contexts[$name];
    }
    
    /**
     * register a new context
     *
     * @param string $name
     * @param string $storageType
     * @return static
     */
    public function register(string $name,$storageType = null)
    {
        if($this->isRegistered($name)){
            throw new AlreadRegisteredContextException("{$name} is not a context.");
        }

        if(is_null($storageType)){
            $storageType = config('context.storage');
        }
        $storage = config("context.storages.{$storageType}.handler");

        static::$contexts[$name] = $storage;

        return $this;
    }    
    
    /**
     * removes a context and all its content
     *
     * @param string $name
     * @return static
     */
    public function unregister(string $name)
    {
        if(static::$currentContext == $name){
            static::$currentContext = null;
        }

        unset(static::$contexts[$name]);
        $contextIndex = array_search($name,static::$contextsInitialized);
        if($contextIndex !== false){
            unset(static::$contextsInitialized[$contextIndex]);
            static::$contextsInitialized = array_values(static::$contextsInitialized);
        }

        return $this;
    }

    /**
     * set current context
     *
     * @param string $name
     * @return static|Dottwatson\Context\Exception\UnregisteredContextException
     */
    public function setCurrentContext(string $name)
    {
        if(!$this->isRegistered($name)){
            throw new UnregisteredContextException("{$name} is not a registered context");
        }
        
        static::$currentContext = $name;

        return $this;
    }

    public function getCurrentContext()
    {
        return static::$currentContext;
    }


    /**
     * check if context is registered
     *
     * @param string $name
     * @return bool
     */
    public function isRegistered(string $name)
    {
        return isset(static::$contexts[$name]);
    }


    /**
     * returns all registered context names
     *
     * @return array
     */
    public function getRegisteredContext()
    {
        return array_keys(static::$contexts);
    }

    /**
     * returns all available contexts
     *
     * @return array
     */
    public function getAllContexts()
    {
        return static::$contexts;
    }

    /**
     * returns if a context is registered
     *
     * @param string $name
     * @return boolean
     */
    public function isContext(string $name)
    {
        return isset(static::$contexts[$name]);
    }
    
    /**
     * set current context
     *
     * @param string $name
     * @return \Dottwatson\Context\Storages\Storage
     */
    public function setContext(string $name)
    {
        if(!static::isContext($name)){
            throw new UnregisteredContextException("{$name} is not a context.");
        }

        static::$currentContext = $name;

        return $this->getContext($name);
    }
}
