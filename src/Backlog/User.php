<?php

namespace nouka\Backlog;

use nouka\Backlog;

/**
 * Backlog User
 */
class User extends Backlog
{
    /** @var string[] */
    private $names = [];

    /**
     * constructor
     *
     * @param string[] $names
     */
    public function __construct($names)
    {
        $this->names = $names;
    }

    /**
     * get id
     *
     * @return int[]
     */
    public function getIds()
    {
        $users = $this->client->users->get();
        $ids = [];
        foreach ($users as $user) {
            if (in_array($user['name'], $this->names)) {
                $ids[] = $user['id'];
            }
        }
        return $ids;
    }
}
