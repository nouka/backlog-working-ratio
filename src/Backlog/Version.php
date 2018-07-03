<?php

namespace nouka\Backlog;

use nouka\Backlog;

/**
 * Backlog Version
 */
class Version extends Backlog
{
    /** @var string */
    private $projectKey;

    /**
     * constructor
     *
     * @param string $projectKey
     */
    public function __construct($projectKey)
    {
        $this->projectKey = $projectKey;
    }

    /**
     * 現在進捗中のマイルストーンを取得
     *
     * @return array
     */
    public function getActiveVersions()
    {
        $versions = $this->client->projects->param($this->projectKey)->versions->get();
        $now = date('Y-m-d');

        return array_filter($versions, function ($version) use ($now) {
            $start = date('Y-m-d', strtotime($version['startDate']));
            $end = date('Y-m-d', strtotime($version['releaseDueDate']));
            return $start <= $now && $now <= $end && strpos($version['name'], '施策') !== false;
        });
    }
}
