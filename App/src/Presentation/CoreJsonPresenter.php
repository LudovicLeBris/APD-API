<?php

namespace App\Presentation;

class CoreJsonPresenter
{
    protected JsonModel $jsonModel;

    public function getJson(): array
    {
        return $this->jsonModel->getJsonResponse();
    }
}