<?php

namespace nouka\DTO;

use nouka\Backlog\Issue;

class BufferObject
{
    /** @var string */
    private $milestoneName = '';

    /** @var float */
    private $digestibility = 0;

    /** @var float */
    private $progressRate = 0;

    /**
     * constructor
     *
     * @param float $bufferRetio
     * @param string $name
     * @param array $issues
     */
    public function __construct($bufferRetio, $name, $issues)
    {
        $this->milestoneName = $name;

        $estimatedHours = 0;
        $actualHours = 0;
        $sum = 0;
        foreach ($issues as $issue) {
            $estimatedHours += $issue['estimatedHours'];
            if ($issue['status']['id'] === Issue::STATUS_COMPLETE) {
                $actualHours += $issue['estimatedHours'];
                $sum += $issue['actualHours'];
            } else {
                $sum += $issue['estimatedHours'];
            }
        }
        $buffer = $estimatedHours * $bufferRetio;
        $diff = $sum - $estimatedHours;
        $diff = $diff > 0 ? $diff : 0;
        $this->digestibility = $diff / $buffer * 100;
        $this->progressRate = ($actualHours / $estimatedHours) * 100;
    }

    /**
     * get milestone name
     *
     * @return string
     */
    public function getMilestoneName()
    {
        return $this->milestoneName;
    }

    /**
     * get digestibility
     *
     * @return float
     */
    public function getDigestibility()
    {
        return $this->digestibility;
    }

    /**
     * get progress rate
     *
     * @return float
     */
    public function getProgressRate()
    {
        return $this->progressRate;
    }
}
