<?php

namespace nouka\Backlog;

use nouka\Backlog;

/**
 * Backlog Issue
 */
class Issue extends Backlog
{
    const MAX_COUNT = 100;
    const STATUS_COMPLETE = 4;

    /**
     * 実績時間を取得
     *
     * @param int $projectId
     * @param int $userId
     * @param string $start
     * @param string $end
     *
     * @return float
     */
    public function getEstimetedHours($projectId, $userId, $start, $end)
    {
        $issues = $this->client->issues->get([
            'projectId[]' => $projectId,
            'assigneeId[]' => $userId,
            'statusId[]' => self::STATUS_COMPLETE,
            'dueDateSince' => $start,
            'dueDateUntil' => $end,
            'count' => self::MAX_COUNT
        ]);

        $estimatedHours = 0;
        foreach ($issues as $issue) {
            $estimatedHours += $issue['estimatedHours'];
        }

        return $estimatedHours;
    }

    /**
     * マイルストーンを指定してIssueを取得する
     *
     * @param int $id
     * @return array
     */
    public function getByMilestoneId($id)
    {
        return $this->client->issues->get([
            'milestoneId[]' => $id,
            'count' => self::MAX_COUNT
        ]);
    }
}
