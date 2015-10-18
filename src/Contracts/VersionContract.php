<?php

namespace DGreene\GitVersion\Contracts;

/**
 * Interface VersionContract
 * @package DGreene\GitVersion\Contracts
 */
interface VersionContract {

    /**
     *
     */
    public function __construct();


    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments);
}