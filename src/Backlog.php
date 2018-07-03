<?php

namespace nouka;

abstract class Backlog
{
    /** @var \atomita\Backlog */
    protected $client;

    /**
     * set client
     *
     * @param \atomita\Backlog $client
     * @return void
     */
    public function setClient(\atomita\Backlog $client)
    {
        $this->client = $client;
    }
}
