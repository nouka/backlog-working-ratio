<?php

namespace nouka;

use atomita\Backlog as BL;
use atomita\BacklogException;
use nouka\Backlog\Version;
use nouka\Backlog\Issue;
use nouka\DTO\BufferObject;

/**
 * Backlog APIからバッファの消化率を計算する
 */
class BufferDigestibility
{
    /** @var BL */
    private $backlog;

    /** @var Version */
    private $version;

    /** @var Issue */
    private $issue;

    /** @var float */
    private $bufferRetio;

    /**
     * Undocumented function
     *
     * @param string $spaceName
     * @param string $apiKey
     * @param string $projectKey
     * @param float $bufferRetio
     */
    public function __construct($spaceName, $apiKey, $projectKey, $bufferRetio)
    {
        $this->backlog = new BL($spaceName, $apiKey);
        $version = new Version($projectKey);
        $version->setClient($this->backlog);
        $this->version = $version;

        $issue = new Issue();
        $issue->setClient($this->backlog);
        $this->issue = $issue;

        $this->bufferRetio = $bufferRetio;
    }

    /**
     * バッファ消化率を取得
     *
     * @return BufferObject[]
     */
    public function get()
    {
        $activeVersions = $this->version->getActiveVersions();
        $digestibility = 0;
        $buffers = [];
        foreach ($activeVersions as $version) {
            $buffers[] = new BufferObject(
                $this->bufferRetio,
                $version['name'],
                $this->issue->getByMilestoneId($version['id'])
            );
        }

        return $buffers;
    }
}
