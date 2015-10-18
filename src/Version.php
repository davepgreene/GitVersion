<?php

namespace DGreene\GitVersion;

use DGreene\GitVersion\Contracts\VersionContract;
use DGreene\GitVersion\Git\Command;
use DGreene\GitVersion\Git\Remote;

class Version implements VersionContract {

    private $gitExists = false;

    /**
     *
     */
    public function __construct()
    {
        // We can test for the existence of a git binary on the system
        // when the class is instantiated at initial service container boot.
        exec('git', $output, $code);
        if($code === 1)
        {
            $this->gitExists = true;
        }
    }


    /**
     * __call is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name         string
     * @param $arguments    array
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // Because we're doing dynamic method invocation we can shortcut calls to
        // valid private methods based on the existence of the git binary we tested
        // for at singleton instantiation.
        if(!$this->gitExists)
        {
            return '';
        }

        $var = substr($name, 3);
        if(strncasecmp($name, 'get', 3) === 0)
        {
            return call_user_func([$this, camel_case($var)], $arguments);
        }
    }

    private function repo()
    {
        // We're assuming that origin is the canonical remote that the version url should link to
        $remote = new Remote();
        exec('git remote show origin', $output, $code);
        if($code !== 0)
        {
            return '';
        }

        $remotes = [];
        foreach($output as $remote)
        {
            $split = explode("\t", $remote);
            $remotes[$split[0]] = $split[1];
        }
    }

    private function version()
    {
        if($this->branch() === 'HEAD')
        {
            $display = $this->tag();
        }
    }

    private function tag()
    {
        $output = Command::execCommand('git describe');
        return (empty($output)) ? '' : $output[0];
    }

    private function branch()
    {
        $output = Command::execCommand('git rev-parse --abbrev-ref HEAD');
        if(empty($output))
        {
            return '';
        }

        $branch = $output[0];

        if(substr($branch, 0, 7) === 'release')
        {
            return substr($branch, 8) . ' (RC)';
        }

        return $branch;
    }

    private function shortHash()
    {
        $hash = $this->getLongHash();
        return substr($hash, 0, 7);
    }

    private function longHash()
    {
        $output = Command::execCommand('git rev-parse HEAD');
        return empty($output) ? '' : $output[0];
    }
}