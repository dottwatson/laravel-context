<?php
namespace Dottwatson\Context\Storages;

use \Illuminate\Config\Repository;


class StorageArray extends Storage{

    public function init()
    {
        return [];
    }

    public function clear()
    {
        $this->repository = new Repository([]);
        return $this;
    }

}