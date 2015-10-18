<?php

namespace DGreene\GitVersion\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Class Version
 * @package DGreene\GitVersion\Facades
 */
class Version extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Version';
    }
}