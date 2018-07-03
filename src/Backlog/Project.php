<?php

namespace nouka\Backlog;

use nouka\Backlog;

/**
 * Backlog Project
 */
class Project extends Backlog
{
    /** @var string */
    private $key;

    /**
     * constructor
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * get id
     *
     * @return int
     */
    public function getId()
    {
        $projects = $this->client->projects->get();
        foreach ($projects as $project) {
            if ($project['projectKey'] === $this->key) {
                return $project['id'];
            }
        }
    }
}
