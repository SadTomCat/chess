<?php

namespace App\Jobs\Managers;

use DB;
use App\Game\GameTimings;
use App\Models\GameJob;
use Illuminate\Support\Facades\Queue;
use Exception;
use Throwable;

final class MoveTimeEndJobManager
{
    /**
     * @var array ['queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at']
     */
    private static array $softDeletedJob = [];

    /**
     * @param int $gameId
     * @param $serializedJob
     * @param mixed $data
     *
     * @return bool
     * @throws Throwable
     */
    public static function push(int $gameId, $serializedJob, mixed $data = ''): bool
    {
        $delay = $serializedJob->delay ?? GameTimings::JOB_GAME_DELAY;
        $newJobId = Queue::later($delay, $serializedJob, $data);

        if ($newJobId === null) {
            return false;
        }

        GameJob::create([
            'job_id' => $newJobId,
            'game_id' => $gameId,
            'job_type' => 'move_time_end',
        ]);

        self::$softDeletedJob = [];
        return true;
    }

    /**
     * @param int $gameId
     * @throws Exception
     */
    public static function deleteLast(int $gameId): void
    {
        $lastGameJob = GameJob::where([
            ['job_type', 'move_time_end'],
            ['game_id', $gameId],
        ])->latest()?->first(['id', 'job_id']);

        if ($lastGameJob !== null) {
            $lastJobId = $lastGameJob->job_id;
            $lastGameJob->delete();
            DB::table('jobs')->delete($lastJobId);
        }
    }

    /**
     * @param int $gameId
     * @param $job
     * @return bool
     * @throws Throwable
     */
    public static function deleteLastAndAddNew(int $gameId, $job): bool
    {
        DB::beginTransaction();

        try {
            self::deleteLast($gameId);
            $pushStatus = self::push($gameId, $job);

            if ($pushStatus === false) {
                throw new Exception();
            }

        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @param int $gameId
     * @throws Exception
     */
    public static function softDeleteLast(int $gameId): void
    {
        $lastGameJob = GameJob::where([
            ['job_type', 'move_time_end'],
            ['game_id', $gameId],
        ])->latest()->first(['id', 'job_id']);

        if ($lastGameJob === null) {
            return;
        }

        $lastJob = DB::table('jobs')
                     ->where(['id' => $lastGameJob->job_id])
                     ->first(['queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at']);

        self::$softDeletedJob = get_object_vars($lastJob);
        self::deleteLast($gameId);
    }

    /**
     * @param int $gameId
     */
    public static function recoveryLast(int $gameId): void
    {
        if (empty(self::$softDeletedJob)) {
            return;
        }

        $jobId = DB::table('jobs')->insertGetId(self::$softDeletedJob);

        GameJob::create([
            'game_id' => $gameId,
            'job_id' => $jobId,
            'job_type' => 'move_time_end',
        ]);

        self::$softDeletedJob = [];
    }
}
