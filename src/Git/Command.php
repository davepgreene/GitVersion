<?php

namespace DGreene\GitVersion\Git;

use DGreene\GitVersion\Exceptions\NonZeroException;

class Command {

    /**
     * @param $command
     *
     * @return string
     * @throws NonZeroException
     */
    public static function execCommand($command)
    {
        exec($command, $output, $code);
        if($code !== 0)
        {
            throw new NonZeroException("{$command} returned a non-zero response");
        }

        return $output;
    }
}