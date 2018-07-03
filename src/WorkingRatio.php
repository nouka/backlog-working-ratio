<?php

namespace nouka;

use atomita\Backlog;
use atomita\BacklogException;
use nouka\Backlog\Project;
use nouka\Backlog\User;
use nouka\Backlog\Issue;

/**
 * Backlogの実績時間から稼働率を取得する
 */
class WorkingRatio
{
    /** @var Backlog */
    private $backlog;

    /** @var int */
    private $projectId;

    /** @var int[] */
    private $userIds;

    /** @var Issue */
    private $issue;

    /** @var int */
    private $workingHours;

    /**
     * constructor
     *
     * @param string $spaceName
     * @param string $apiKey
     * @param string $projectKey
     * @param string[] $users
     * @param int $workingHours
     */
    public function __construct($spaceName, $apiKey, $projectKey, $users, $workingHours)
    {
        $this->backlog = new Backlog($spaceName, $apiKey);
        $project = new Project($projectKey);
        $project->setClient($this->backlog);
        $this->projectId = $project->getId();

        $user = new User($users);
        $user->setClient($this->backlog);
        $this->userIds = $user->getIds();

        $issue = new Issue();
        $issue->setClient($this->backlog);
        $this->issue = $issue;

        $this->workingHours = $workingHours;
    }

    /**
     * 昨日の稼働率を取得
     *
     * @return float
     */
    public function getYesterDayRatio()
    {
        if (date('w') == 1) {
            $yesterDay = new \DateTime(date('Y-m-d', strtotime('Friday previous week')));
        } else {
            $yesterDay = new \DateTime(date('Y-m-d', strtotime('yesterday')));
        }

        return $this->get($yesterDay, $yesterDay);
    }

    /**
     * 先週の稼働率を取得
     *
     * @return float
     */
    public function getPreviousWeekRatio()
    {
        $start = new \DateTime('Monday previous week');
        $end = new \DateTime('Friday previous week');

        return $this->get($start, $end);
    }

    /**
     * 稼働率を取得する
     *
     * @param \DateTime $start
     * @param \DateTime $end
     * @return float
     */
    private function get(\DateTime $start, \DateTime $end)
    {
        $estimatedHours = 0;
        foreach ($this->userIds as $userId) {
            $estimatedHours += $this->issue->getEstimetedHours(
                $this->projectId,
                $userId,
                $start->format('Y-m-d'),
                $end->format('Y-m-d')
            );
        }

        if ($estimatedHours === 0) {
            return 0;
        }

        $dateDiff = date_diff($start, $end);
        $diff = $dateDiff->format('%d') + 1;

        return ($estimatedHours / ($this->workingHours * count($this->userIds) * $diff)) * 100;
    }
}
