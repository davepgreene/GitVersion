<?php

use DGreene\GitVersion\Git\Remote;

/**
 * Class RemoteTest
 */
class RemoteTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Remote
     */
    private $remote;


    /**
     *
     */
    protected function setUp()
    {
        $this->remote = new Remote();
    }

    /**
     *
     */
    public function testIsValidRemote()
    {
        $this->assertTrue($this->remote->validRemote);
    }

    /**
     *
     */
    public function testIsInvalidRemote()
    {
        $failingRemote = new Remote('foobar');
        $this->assertFalse($failingRemote->validRemote);
    }

    /**
     *
     */
    public function testParsesRemoteUrl()
    {
        $remoteUrl = $this->remote->getRemoteUrl();
        $this->assertTrue(is_string($remoteUrl), "\$remoteUrl is not a string");
    }

    /**
     *
     */
    public function testRemoteIsValidUrl()
    {
        $remoteUrl = $this->remote->getRemoteUrl();
        $this->assertEquals('github.com/davepgreene/GitVersion.git', $remoteUrl);
    }
}