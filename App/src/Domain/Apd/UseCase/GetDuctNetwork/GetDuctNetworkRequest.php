<?php

namespace App\Domain\Apd\UseCase\GetDuctNetwork;

class GetDuctNetworkRequest
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}