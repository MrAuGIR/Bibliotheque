<?php

namespace App\Service\Api\Model\Access;

class AbstractAccess
{
    protected bool $isAvailable = false;

    protected string $acsTokenLink;
}