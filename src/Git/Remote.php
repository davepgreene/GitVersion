<?php

namespace DGreene\GitVersion\Git;

use DGreene\GitVersion\Exceptions\NonZeroException;
use Illuminate\Support\Collection;

class Remote {

    private $remoteUrl;

    private $output;

    public $validRemote = true;


    public function __construct($branch = 'origin')
    {
        try
        {
            $this->output = Command::execCommand("git remote show {$branch}");
        }
        catch (NonZeroException $e)
        {
            $this->validRemote = false;
        }
    }

    private function parseRemoteOutput($commandOutput)
    {
        $repoUrl = (new Collection($commandOutput))->filter(function($k)
        {
            return strtolower(substr(trim($k), 0, 5)) === 'fetch';
        })->map(function($k)
        {
            return trim(str_replace("Fetch URL:", '', $k));
        })->first();

        if(substr($repoUrl, 0, 5) === 'https')
        {
            // HTTPS
            return $repoUrl;
        }

        // SSL
        return str_replace(':', '/', substr($repoUrl, 4, strlen($repoUrl) - 4));
    }

    public function getRemoteUrl()
    {
        if(!$this->validRemote)
        {
            return null;
        }

        $this->remoteUrl = $this->parseRemoteOutput($this->output);
        return $this->remoteUrl;
    }
}