<?php
namespace Dottwatson\Context\Storages;

use \Illuminate\Config\Repository;


class StorageSession extends Storage{

    protected function init()
    {
        $sessionName = $this->sessionKey();
        $sessionData = session($sessionName,null);

        if(is_null($sessionData)){
            $sessionData = [];
            session([$sessionName => $sessionData]);
        }

        return $sessionData;
    }

    protected function save()
    {
        $sessionName = $this->sessionKey();
        $sessionData = $this->getRepository()->all();
        session([$sessionName => $sessionData]);
        session()->save();
    }

    public function clear()
    {
        $this->repository = new Repository([]);
        return $this;
    }

    protected function sessionKey()
    {
        $path = config('context.session.name','context');

        return "{$path}.".$this->getName();
    }

}