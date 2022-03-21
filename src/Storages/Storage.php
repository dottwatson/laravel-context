<?php
namespace Dottwatson\Context\Storages;

use \Illuminate\Config\Repository;

class Storage{
    protected $name;
    protected $repository;

    public function __construct(string $name)
    {
        $this->name = $name;
        $data = $this->init();
        $this->repository = new Repository($data);
    }

    /**
     * returns current context name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * returns data repository
     *
     * @return \Illuminate\Config\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * init the storage and returns data
     *
     * @return array
     */
    protected function init()
    {
    }

    /**
     * called on destructor, save data
     *
     * @return void
     */
    protected function save()
    {
    }

    public function __call($name,$args)
    {
        return call_user_func_array([$this->repository,$name],$args);
    }


    public function clear()
    {
    }

    public function __destruct()
    {
        $this->save();
    }
}